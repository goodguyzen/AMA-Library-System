<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: /capstoneproject/index.html");
    exit();
}

// Retrieve the logged-in user's details
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);

// Database connection
$conn = new mysqli("localhost", "root", "", "library_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch rented books for the logged-in user
$rented_books_query = $conn->prepare("
    SELECT 
        b.title AS book_name, 
        b.genre AS book_genre, 
        b.image_path AS book_cover, 
        r.date_rented, 
        DATE_ADD(r.date_rented, INTERVAL 14 DAY) AS return_date, 
        r.status 
    FROM rentals r
    JOIN books b ON r.book_id = b.id
    WHERE r.rented_by = ?
");
$rented_books_query->bind_param("s", $username);
$rented_books_query->execute();
$rented_books_result = $rented_books_query->get_result();

// Handle book rental
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rent_book'])) {
    $book_id = $_POST['book_id'];
    $student = $_SESSION['username'];

    // Check if the book is still available
    $check_query = $conn->prepare("SELECT availability FROM books WHERE id = ? AND availability = 'Available'");
    $check_query->bind_param("i", $book_id);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows > 0) {
        // Mark the book as rented
        $rent_query = $conn->prepare("INSERT INTO rentals (book_id, rented_by, status) VALUES (?, ?, 'Rented')");
        $rent_query->bind_param("is", $book_id, $student);
        $rent_query->execute();

        // Update book availability
        $update_query = $conn->prepare("UPDATE books SET availability = 'Unavailable' WHERE id = ?");
        $update_query->bind_param("i", $book_id);
        $update_query->execute();

        $message = "You have successfully rented the book!";
    } else {
        $message = "Sorry, the book is no longer available.";
    }
}


$search_query = "SELECT * FROM books WHERE availability = 'Available'";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = "%" . trim($_GET['search']) . "%";
    $search_query .= " AND (title LIKE ? OR author LIKE ? OR genre LIKE ?)";
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $books = $stmt->get_result();
} else {
    $books = $conn->query($search_query);
}

$profile_query = $conn->prepare("SELECT profile_picture FROM users WHERE username = ?");
$profile_query->bind_param("s", $username);
$profile_query->execute();
$profile_result = $profile_query->get_result();
$profile_data = $profile_result->fetch_assoc();
$profile_picture = $profile_data['profile_picture'] ?? 'assets/images/default-profile.jpg';

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        $message = "File is not an image.";
        $upload_ok = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["profile_picture"]["size"] > 2000000) {
        $message = "Sorry, your file is too large.";
        $upload_ok = 0;
    }

    // Allow certain file formats
    if (!in_array($image_file_type, ["jpg", "jpeg", "png", "gif"])) {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = 0;
    }

    // Check if $upload_ok is set to 0 by an error
    if ($upload_ok === 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update the profile picture path in the database
            $update_query = $conn->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
            $update_query->bind_param("ss", $target_file, $username);
            $update_query->execute();

            $message = "Profile picture updated successfully!";
            $profile_picture = $target_file; // Update the displayed profile picture

            // Redirect to the same page to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch total books rented
$total_rented_query = $conn->prepare("SELECT COUNT(*) AS total_rented FROM rentals WHERE rented_by = ?");
$total_rented_query->bind_param("s", $username);
$total_rented_query->execute();
$total_rented_result = $total_rented_query->get_result();
$total_rented = $total_rented_result->fetch_assoc()['total_rented'] ?? 0;

// Fetch books returned
$books_returned_query = $conn->prepare("SELECT COUNT(*) AS books_returned FROM rentals WHERE rented_by = ? AND status = 'Returned'");
$books_returned_query->bind_param("s", $username);
$books_returned_query->execute();
$books_returned_result = $books_returned_query->get_result();
$books_returned = $books_returned_result->fetch_assoc()['books_returned'] ?? 0;

// Fetch books to be returned
$books_to_return_query = $conn->prepare("SELECT COUNT(*) AS books_to_return FROM rentals WHERE rented_by = ? AND status = 'Rented'");
$books_to_return_query->bind_param("s", $username);
$books_to_return_query->execute();
$books_to_return_result = $books_to_return_query->get_result();
$books_to_return = $books_to_return_result->fetch_assoc()['books_to_return'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>AMA Online Library</title>

  <!-- Bootstrap core CSS -->
  <link href="/capstoneproject/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


  <!-- Additional CSS Files -->
  <link rel="stylesheet" href="/capstoneproject/assets/css/fontawesome.css">
  <link rel="stylesheet" href="/capstoneproject/assets/css/student-main-stylesheet.css">
  <link rel="stylesheet" href="/capstoneproject/assets/css/owl.css">
  <link rel="stylesheet" href="/capstoneproject/assets/css/animate.css">
</head>
<body>

<!-- ***** Preloader Start ***** -->
<div id="js-preloader" class="js-preloader">
  <div class="preloader-inner">
    <span class="dot"></span>
    <div class="dots">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</div>
<!-- ***** Preloader End ***** -->

<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <nav class="main-nav">
          <!-- ***** Logo Start ***** -->
          <a href="student-home.php" class="logo">
            <img src="/capstoneproject/assets/images/amau_logo_basic2a.png">
          </a>
          <!-- ***** Logo End ***** -->
          <!-- ***** Menu Start ***** -->
          <ul class="nav">
            <li><a href="student-home.php">Home</a></li>
            <li><a href="library.php">Library</a></li>
            <li><a href="/capstoneproject/profiles/logout.php">Logout</a></li>
            <li>
              <a href="profile.php" class="active">
                Profile <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
              </a>
            </li>
          </ul>
          <a class='menu-trigger'>
            <span>Menu</span>
          </a>
          <!-- ***** Menu End ***** -->
        </nav>
      </div>
    </div>
  </div>
</header>
<!-- ***** Header Area End ***** -->

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-content">

          <!-- ***** Banner Start ***** -->
            <div class="row">
              <div class="col-lg-12">
                <div class="main-profile">
                  <div class="row">

                    <!-- ***** Profile Picture Start ***** -->
                    <div class="col-lg-4" style="text-align: center;">
                      <div class="profile-wrapper">
                        <div class="profile-container">
                          <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture">
                          <div class="profile-hover-overlay">
                            <span>See Profile</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Profile Popup -->
                    <div id="profilePopup" class="popup">
                      <div class="popup-content">
                        <span class="close-btn" onclick="closePopup()">&times;</span>
                        <div class="popup-header">
                          <h4>Profile</h4>
                        </div>
                        <div class="popup-body">
                          <!-- Profile Picture Preview -->
                          <div class="profile-preview-container">
                            <label for="profile_picture">
                              <img id="previewImage" src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="popup-profile-picture">
                              <div class="hover-overlay">
                                <span class="plus-sign">+</span>
                              </div>
                            </label>
                          </div>

                          <!-- Hidden File Input -->
                          <form method="POST" enctype="multipart/form-data" id="profileForm">
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewFile()" style="display: none;" required>
                            <button type="submit" id="saveButton">Upload</button>
                          </form>
                        </div>
                      </div>
                    </div>
                    <!-- ***** Profile Popup End ***** -->
                    <!-- ***** Profile Picture End ***** -->
                  

                    
                    
                    <div class="col-lg-4 align-self-center">
                      <div class="main-info header-text">
                        <span><?php echo htmlspecialchars($role); ?></span> <!-- Dynamic role -->
                        <h4><?php echo htmlspecialchars($username); ?></h4> <!-- Dynamic username -->
                        <p>Bio</p> <!-- Keep this static for now -->
                      </div>
                    </div>
                    <div class="col-lg-4 align-self-center">
                      <ul>
                        <li>Total Books Rented <span><?php echo $total_rented; ?></span></li>
                        <li>Books Returned <span><?php echo $books_returned; ?></span></li>
                        <li>Books To Be Returned <span><?php echo $books_to_return > 0 ? $books_to_return : 'None'; ?></span></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <!-- ***** Banner End ***** -->

          <!-- ***** Rented Books Start ***** -->
            <div class="rented-books profile-library">
              <div class="col-lg-12">
                <div class="heading-section">
                  <h4><em>Your Rented</em> Books</h4>
                </div>
                <?php while ($book = $rented_books_result->fetch_assoc()): ?>
                  <div class="item">
                    <ul>
                      <li>
                        <img src="<?php echo htmlspecialchars($book['book_cover'] ?: 'assets/images/default-book.jpg'); ?>" alt="Book Cover" class="templatemo-item">
                      </li>
                      <li>
                        <h4><?php echo htmlspecialchars($book['book_name']); ?></h4>
                        <span><?php echo htmlspecialchars($book['book_genre']); ?></span>
                      </li>
                      <li>
                        <h4>Date Rented</h4>
                        <span><?php echo htmlspecialchars($book['date_rented']); ?></span>
                      </li>
                      <li>
                        <h4>To Be Returned At</h4>
                        <span><?php echo htmlspecialchars($book['return_date']); ?></span>
                      </li>
                      <li>
                        <h4>Status</h4>
                        <span><?php echo htmlspecialchars($book['status']); ?></span>
                      </li>
                    </ul>
                  </div>
                <?php endwhile; ?>
              </div>
            </div>
          <!-- ***** Rented Books End ***** -->
        </div>
      </div>
    </div>
  </div>
  
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright © 2025 <a href="#">DevCodeSigma</a>. All rights reserved.
        </div>
      </div>
    </div>
  </footer>


  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
  <script src="/capstoneproject/vendor/jquery/jquery.min.js"></script>
  <script src="/capstoneproject/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="/capstoneproject/assets/js/isotope.min.js"></script>
  <script src="/capstoneproject/assets/js/owl-carousel.js"></script>
  <script src="/capstoneproject/assets/js/tabs.js"></script>
  <script src="/capstoneproject/assets/js/popup.js"></script>
  <script src="/capstoneproject/assets/js/custom.js"></script>
  <script src="/capstoneproject/assets/js/profilepopup.js"></script>

</body>

</html>
