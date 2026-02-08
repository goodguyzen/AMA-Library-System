<?php
session_start();

// Check if the user is logged in and if their role is 'employee'
// If not logged in or role is incorrect, redirect to the login page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'employee') {
    header("Location: index.html"); // Redirect to login page
    exit(); // Stop executing further code
}

// Store the logged-in user's username and role in variables
// htmlspecialchars is used to prevent XSS attacks by escaping special characters
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);

// Establish a connection to the MySQL database
$conn = new mysqli("localhost", "root", "", "library_system");

// Check if the connection failed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // If failed, display error message and stop script
}

// Fetch all books from the 'books' table
$books = $conn->query("SELECT * FROM books");

// Fetch rental transactions by joining the 'rentals' and 'books' tables
// The query retrieves rental details such as the book title, who rented it, and the status
$rentals = $conn->query("
    SELECT r.id, b.title, r.rented_by, r.rental_date, r.status
    FROM rentals r
    JOIN books b ON r.book_id = b.id
    ORDER BY r.rental_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/capstoneproject/assets/css/dashboard-stylesheet.css">
</head>
<body>

    <!-- Profile Section -->
    <div class="profile-container" id="profileContainer">

        <!-- Circular icon displaying the first letter of the user's username -->

        <div class="profile-circle" id="profileCircle">
            <?php echo strtoupper(substr($username, 0, 1)); // This converts first letter of username to uppercase ?>
        </div>

        <!-- Dropdown showing user details like name and role -->
        <div class="profile-dropdown" id="profileDropdown">
            <p>Name: <?php echo $username; ?></p> <!-- Displays the user's name -->
            <p>Role: <?php echo $role; ?></p> <!-- Displays the user's role -->
            <a href="/capstoneproject/profiles/logout.php">Logout</a> <!-- Logout -->
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <!-- Links for navigating the employee dashboard pages -->
        <a href="/capstoneproject/profiles/employee/dashboard/dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/Books.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Books.php' ? 'active' : '' ?>">Books</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/Students.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Students.php' ? 'active' : '' ?>">Students</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/CreateAccount.php" class="<?= basename($_SERVER['PHP_SELF']) == 'CreateAccount.php' ? 'active' : '' ?>">Create Account</a>
        <a href="/capstoneproject/profiles/employee/employee_dashboard.php">Home</a>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Section for displaying recent rental transactions -->
        <div id="students" class="table-container">
            <h2>Recent Rental Transactions</h2>
            <!-- Table to display rental transaction details -->
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Rental Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <!-- Loop through the rental data fetched from the database -->
                <?php while ($rental = $rentals->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rental['title']); // Display book title ?></td>
                    <td><?php echo htmlspecialchars($rental['rented_by']); // Display the person who rented the book ?></td>
                    <td><?php echo htmlspecialchars($rental['rental_date']); // Display the rental date ?></td>
                    <td><?php echo htmlspecialchars($rental['status']); // Display the rental status ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- A Link for the javascript of the profile drop down -->
    <script src="/capstoneproject/assets/js/profilescript.js"></script>
</body>
</html>
