<?php
// Start a session to maintain user login state
session_start();

// Ensure the user is logged in and is an employee
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'employee') {
    // If the user is not logged in or not an employee, redirect them to the login page
    header("Location: /capstoneproject/index.html");
    exit(); // Stop further script execution after redirection
}

// Retrieve the logged-in user's details
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);

// Establish a connection to the MySQL database
$conn = new mysqli("localhost", "root", "", "library_system");
// Check for a connection error and display a message if the connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the availability update when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_availability'])) {
    // Get the book ID and new availability status from the POST request
    $book_id = $_POST['book_id'];
    $availability = $_POST['availability'];
    
    // Prepare the SQL statement to update the book's availability in the database
    $update_query = $conn->prepare("UPDATE books SET availability = ? WHERE id = ?");
    $update_query->bind_param("si", $availability, $book_id); // Bind parameters to prevent SQL injection
    $update_query->execute(); // Execute the prepared statement
}

// Handle book addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    // Sanitize and retrieve form data
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $availability = $conn->real_escape_string($_POST['availability']);
    $number_of_copies = intval($_POST['number_of_copies']);
    $published_in = intval($_POST['published_in']);

    // Handle the uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = "/capstoneproject/assets/images/books/" . $imageName;

        // Move the uploaded file to the target directory
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/capstoneproject/assets/images/books/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
        }
        move_uploaded_file($imageTmpPath, $targetDir . $imageName);
    } else {
        $imagePath = null; // Set to null if no image is uploaded
    }

    // Prepare and execute SQL query to insert the new book
    $add_book_query = $conn->prepare("INSERT INTO books (title, author, genre, availability, number_of_copies, published_in, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $add_book_query->bind_param("ssssiss", $title, $author, $genre, $availability, $number_of_copies, $published_in, $imagePath);

    if ($add_book_query->execute()) {
        // Redirect to the same page to refresh and clear the form
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<p>Error adding book: " . $conn->error . "</p>";
    }
}

// Fetches all books from the database to display in the table
$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="/capstoneproject/assets/css/dashboard-stylesheet.css"> <!-- The link for external CSS styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="/capstoneproject/assets/css/profile-stylesheet.css"> <!-- The link for external CSS styling -->
    <style>
        /* Reduce font size for the form and table */
    form, table {
        font-size: 15px; /* Adjust font size */
        width: 100%; /* Ensure the table fits within the container */
        overflow-x: auto; /* Add horizontal scrolling if needed */
    }

    /* Adjust table cells to prevent overlapping */
    table th, table td {
        padding: 5px; /* Reduce padding for better spacing */
        text-align: left; /* Align text to the left */
        word-wrap: break-word; /* Allow text to wrap within cells */
    }

    /* Ensure the container doesn't overflow */
    .table-container {
        max-width: 100%; /* Prevent the container from exceeding the page width */
        overflow-x: auto; /* Add horizontal scrolling for large tables */
    }

    /* Adjust input fields, select dropdowns, and buttons */
    input, select, button {
        font-size: 12px; /* Reduce font size */
        padding: 3px 5px; /* Reduce padding for compact fields */
        margin: 2px 0; /* Reduce margin between elements */
        box-sizing: border-box; /* Ensure padding doesn't affect width */
    }

    /* Adjust the table header for better alignment */
    table th {
        font-size: 13px; /* Slightly smaller font for headers */
        padding: 5px; /* Reduce padding */
    }
    </style>
</head>
<body>
    <!-- Profile Section: Displays the employee's profile and logout option -->
    <div class="profile-container" id="profileContainer">
        <div class="profile-circle" id="profileCircle">
            <?php echo strtoupper(substr($username, 0, 1)); // Displays the first letter of the username ?>
        </div>
        <div class="profile-dropdown" id="profileDropdown">
            <p>Name: <?php echo $username; ?></p> <!-- Displays the username -->
            <p>Role: <?php echo $role; ?></p> <!-- Displays the user's role -->
            <a href="/capstoneproject/profiles/logout.php">Logout</a> <!-- Logout -->
        </div>
    </div>

    <!-- Sidebar: Provides navigation links to different sections of the dashboard -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="/capstoneproject/profiles/employee/dashboard/dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/Books.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Books.php' ? 'active' : '' ?>">Books</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/Students.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Students.php' ? 'active' : '' ?>">Students</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/CreateAccount.php" class="<?= basename($_SERVER['PHP_SELF']) == 'CreateAccount.php' ? 'active' : '' ?>">Create Account</a>
        <a href="/capstoneproject/profiles/employee/employee_dashboard.php">Home</a>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <!-- Books Management Section -->
        <div id="books" class="table-container">
            <h2>Manage Books</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Availability</th>
                        <th>Number of Copies</th> <!-- New column -->
                        <th>Published In</th> <!-- New column -->
                        <th>Update Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($book = $books->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['genre']); ?></td>
                            <td><?php echo htmlspecialchars($book['availability']); ?></td>
                            <td><?php echo htmlspecialchars($book['number_of_copies']); ?></td> <!-- Display number of copies -->
                            <td><?php echo htmlspecialchars($book['published_in']); ?></td> <!-- Display published year -->
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <select name="availability">
                                        <option value="Available" <?= $book['availability'] === 'Available' ? 'selected' : '' ?>>Available</option>
                                        <option value="Unavailable" <?= $book['availability'] === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                                    </select>
                                    <button type="submit" name="update_availability">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div id="add-book" class="table-container">
        <h2>Add a New Book</h2>
        <div class="table-container">
            <form method="POST" action="" enctype="multipart/form-data">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Availability</th>
                            <th>Number of Copies</th>
                            <th>Published In</th>
                            <th>Image</th>
                            <th>Add</th> <!-- Change column header -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" id="title" name="title" required></td>
                            <td><input type="text" id="author" name="author" required></td>
                            <td><input type="text" id="genre" name="genre" required></td>
                            <td>
                                <select id="availability" name="availability" required>
                                    <option value="Available">Available</option>
                                    <option value="Unavailable">Unavailable</option>
                                </select>
                            </td>
                            <td><input type="number" id="number_of_copies" name="number_of_copies" min="1" required></td>
                            <td><input type="text" id="published_in" name="published_in" min="1000" max="<?php echo date('Y'); ?>" required></td>
                            <td><input type="file" id="image" name="image" accept="image/*" required></td>
                            <td>
                                <!-- Replace button text with a plus icon -->
                                <button type="submit" name="add_book" title="Add Book" style="background: none; border: none; cursor: pointer;">
                                    <i class="fas fa-plus" style="font-size: 18px; color: green;"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    </div>

    <script src="/capstoneproject/assets/js/profilescript.js"></script> <!-- Link to external JavaScript for the dropdown function -->
</body>
</html>
