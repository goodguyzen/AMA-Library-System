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
     <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="/capstoneproject/assets/css/fontawesome-free-6.7.2-web/css/all.min.css">
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
                            <img src="/capstoneproject/assets/images/amau_logo_basic2a.png" alt="">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="student-home.php">Home</a></li>
                            <li><a href="library.php"  class="active">Library</a></li>
                            <li><a href="/capstoneproject/profiles/logout.php">Logout</a></li>
                            <li>
                                <a href="profile.php">
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

                    <!-- ***** Available Books Start ***** -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="available-books header-text">
                                    <div class="heading-section">
                                        <h4><em>Available</em> Books</h4>
                                    </div>

                                    <div class="owl-features owl-carousel">
                                        <?php
                                        // Fetch all available books from the database
                                        $available_books_query = "SELECT * FROM books WHERE availability = 'Available'";
                                        $available_books = $conn->query($available_books_query);

                                        if ($available_books->num_rows > 0):
                                        while ($book = $available_books->fetch_assoc()): 
                                            ?>
                                                <div class="item">
                                                    <div class="thumb" onclick="showBookDetails('<?php echo htmlspecialchars($book['image_path']); ?>', '<?php echo htmlspecialchars($book['title']); ?>', '<?php echo htmlspecialchars($book['author']); ?>', '<?php echo htmlspecialchars($book['genre']); ?>', '<?php echo htmlspecialchars($book['published_in']); ?>', '<?php echo htmlspecialchars($book['number_of_copies']); ?>')">
                                                        <img src="<?php echo htmlspecialchars($book['image_path']); ?>" alt="Book Image">
                                                        <div class="hover-effect">
                                                            <h6><?php echo htmlspecialchars($book['number_of_copies']); ?> Copies Available</h6>
                                                        </div>
                                                    </div>
                                                    <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                                                    <span>By <?php echo htmlspecialchars($book['author']); ?></span>
                                                </div>
                                            <?php endwhile;
                                        else: ?>
                                            <p>No books are currently available in the library.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- ***** Available Books End ***** -->

                    <!-- ***** Books Section Start ***** -->
                        <div class="books-section">
                            <div class="col-lg-12">
                                <div class="search-bar">
                                    <form method="GET" action="library.php">
                                        <input type="text" name="search" placeholder="Search for books..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                        <button type="submit"><i class="fas fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                // Fetch books from the database
                                $conn = new mysqli("localhost", "root", "", "library_system");
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
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
                                while ($book = $books->fetch_assoc()): ?>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="item">
                                                <div class="thumb" onclick="showBookDetails(
                                                '<?php echo htmlspecialchars($book['image_path']); ?>', 
                                                '<?php echo htmlspecialchars($book['title']); ?>', 
                                                '<?php echo htmlspecialchars($book['author']); ?>', 
                                                '<?php echo htmlspecialchars($book['genre']); ?>', 
                                                '<?php echo htmlspecialchars($book['published_in']); ?>', 
                                                '<?php echo htmlspecialchars($book['number_of_copies']); ?>',
                                                '<?php echo htmlspecialchars($book['id']); ?>'
                                            )">
                                                <img src="<?php echo htmlspecialchars($book['image_path']); ?>" alt="Book Image">
                                                <div class="hover-effect">
                                                    <div class="content">
                                                        <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                                                        <p><?php echo htmlspecialchars($book['genre']); ?></p>
                                                        <span>By <?php echo htmlspecialchars($book['author']); ?></span>
                                                        <span>Published: <?php echo htmlspecialchars($book['published_in']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <!-- ***** Books Section End ***** -->

                    <!-- ***** Book Details Popup Start ***** -->
                        <div id="bookDetailsPopup" class="popup">
                            <div class="popup-content">
                                <img id="popupBookImage" src="" alt="Book Image">
                                <div class="details">
                                    <div>
                                        <h4 id="popupBookTitle"></h4>
                                        <p><strong>Author:</strong> <span id="popupBookAuthor"></span></p>
                                        <p><strong>Genre:</strong> <span id="popupBookGenre"></span></p>
                                        <p><strong>Published:</strong> <span id="popupBookPublished"></span></p>
                                        <p><strong>Copies Available:</strong> <span id="popupBookCopies"></span></p>
                                    </div>
                                    <form method="POST" action="library.php">
                                        <input type="hidden" name="book_id" id="rentBookId">
                                        <button type="submit" name="rent_book" class="rent-button">
                                            Rent This Book
                                        </button>
                                    </form>
                                </div>
                                <span class="close-btn" onclick="closePopup()">&times;</span>
                            </div>
                        </div>
                    <!-- ***** Book Details Popup End ***** -->
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
    <script src="/capstoneproject/assets/js/books.js"></script>
    
</body>

</html>
