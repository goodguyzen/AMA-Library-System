<?php
session_start();

// Check if the user is logged in and has the 'employee' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'employee') {
    // If not logged in or not an employee, redirect to the login page
    header("Location: index.html");
    exit(); // Stop further execution
}

// Store the username and role in variables for later use
// Use htmlspecialchars() to prevent cross-site scripting (XSS) attacks
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);

// Connect to the MySQL database
$conn = new mysqli("localhost", "root", "", "library_system");

// Check if the connection was successful
if ($conn->connect_error) {
    // Terminate script execution and display the error if the connection fails
    die("Connection failed: " . $conn->connect_error);
}

// Fetches all books from the "books" table
$books = $conn->query("SELECT * FROM books");

// Fetch recent rental transactions from the "rentals" table, along with book titles
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
    <!-- Link to the CSS file for styling the dashboard -->
    <link rel="stylesheet" href="/capstoneproject/assets/css/dashboard-stylesheet.css">
</head>
<body>

    <!-- Profile Section -->
    <div class="profile-container" id="profileContainer">
        <div class="profile-circle" id="profileCircle">
            <!-- Display the first letter of the username in uppercase -->
            <?php echo strtoupper(substr($username, 0, 1)); ?>
        </div>
        <div class="profile-dropdown" id="profileDropdown">
            <!-- Show the logged-in user's name and role -->
            <p>Name: <?php echo $username; ?></p>
            <p>Role: <?php echo $role; ?></p>
            <!-- Provide a logout option -->
            <a href="/capstoneproject/profiles/logout.php">Logout</a>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
    <h2>Dashboard</h2>
        <!-- Links to various pages in the dashboard -->
        <!-- Highlight the active page using PHP's basename() function -->
        <a href="/capstoneproject/profiles/employee/dashboard/dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/Books.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Books.php' ? 'active' : '' ?>">Books</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/Students.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Students.php' ? 'active' : '' ?>">Students</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/CreateAccount.php" class="<?= basename($_SERVER['PHP_SELF']) == 'CreateAccount.php' ? 'active' : '' ?>">Create Account</a>
        <a href="/capstoneproject/profiles/employee/employee_dashboard.php">Home</a>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        
        <!-- Books Section -->
        <div id="books" class="table-container">
            <h2>Books Status</h2>
            <!-- Display all books in a table -->
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through the "books" result set and display each book -->
                    <?php while ($book = $books->fetch_assoc()): ?>
                        <tr>
                            <!-- Escape HTML characters to prevent XSS -->
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['genre']); ?></td>
                            <td><?php echo htmlspecialchars($book['availability']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Students Section -->
        <div id="students" class="table-container">
            <h2>Recent Rental Transactions</h2>
            <!-- Display recent rental transactions in a table -->
            <table>
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Rented By</th>
                        <th>Rental Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through the "rentals" result set and display each transaction -->
                    <?php while ($rental = $rentals->fetch_assoc()): ?>
                    <tr>
                        <!-- Escape HTML characters to prevent XSS -->
                        <td><?php echo htmlspecialchars($rental['title']); ?></td>
                        <td><?php echo htmlspecialchars($rental['rented_by']); ?></td>
                        <td><?php echo htmlspecialchars($rental['rental_date']); ?></td>
                        <td><?php echo htmlspecialchars($rental['status']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!--The JavaScript for the profile dropdwon -->
    <script src="/capstoneproject/assets/js/profilescript.js"></script>
</body>
</html>
