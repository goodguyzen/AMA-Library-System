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

// Handle book search
$search_query = "SELECT * FROM books WHERE availability = 'Available'";
if (isset($_GET['search'])) {
    $search_term = "%" . $_GET['search'] . "%";
    $search_query .= " AND (title LIKE ? OR author LIKE ? OR genre LIKE ?)";
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $books = $stmt->get_result();
} else {
    $books = $conn->query($search_query);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMA University</title>
    <link rel="stylesheet" href="/capstoneproject/assets/css/student-stylesheet.css">
</head>
<body>
    
    <div class="container">

        <!-- topnav -->
        <div class="topnav">
            <img src="/capstoneproject/assets/images/amau_logo_basic2a.png" alt="Logo" class="logo">
            <a href="#home">Home</a>
            <a href="#contact">Contact</a>
            <a href="#about">About</a>
            <a href="#library">Library</a>

            <!-- Profile Dropdown -->
            <div class="profile-container" id="profileContainer">
                <div class="profile-circle" id="profileCircle">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <p>Name: <?php echo $username; ?></p>
                    <p>Role: <?php echo $role; ?></p>
                    <a href="/capstoneproject/profiles/logout.php">Logout</a>
                </div>
            </div>
        </div>

        <!-- content -->
        <section  id="home">
            <div class="content">
                <div class="intro">
                    <img src="/capstoneproject/assets/images/AMAschool.jpg" alt="tnts">
                    <h2>AMA University: Library</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias, officiis dolorem odit sapiente ipsum aliquam atque nihil? Sapiente culpa vero sint, architecto eveniet, illum animi aut possimus deserunt excepturi reiciendis?</p>
                </div>
            </div>
        </section>


        <h1 style="margin-left: -800px; margin-top: 80px;">Search a book</h1>
        <section id="library">
            
            <div class="middle">
                <!-- Display Message -->
                <?php if (isset($message)): ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <!-- Search Form -->
                <form id="searchForm" method="GET">
                    <input type="text" name="search" placeholder="Search for books by title, author, or genre">
                    <button type="submit">Search</button>
                </form>

                <!-- Books List -->
                <h2>Available Books</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Number of Copies</th> <!-- New column -->
                            <th>Published Year</th> <!-- New column -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($book = $books->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($book['image_path']); ?>" alt="Book Image" style="width: 50px; height: 75px;">
                                </td>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['genre']); ?></td>
                                <td><?php echo htmlspecialchars($book['number_of_copies']); ?></td> <!-- Display number of copies -->
                                <td><?php echo htmlspecialchars($book['published_in']); ?></td> <!-- Display published year -->
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                        <button type="submit" name="rent_book">Rent</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>


    <script src="/capstoneproject/assets/js/topnavscript.js"></script>
    <script src="/capstoneproject/assets/js/profilescript.js"></script>

</body>
</html>
