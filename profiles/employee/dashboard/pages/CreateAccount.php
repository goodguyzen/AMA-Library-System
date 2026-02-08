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

// Handle form submission for creating a new account
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = htmlspecialchars($_POST['username']);
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $new_role = htmlspecialchars($_POST['role']);

    // This inserts the new account into the database
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $new_username, $new_password, $new_role);

    if ($stmt->execute()) {
        $_SESSION['account_created'] = true;
        header("Location: CreateAccount.php");
        exit();
    } else {
        $error_message = "Error creating account: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="/capstoneproject/assets/css/acreation-stylesheet.css">
</head>
<body>

    <!-- Profile Section -->
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

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="/capstoneproject/profiles/employee/dashboard/dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/Books.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Books.php' ? 'active' : '' ?>">Books</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/Students.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Students.php' ? 'active' : '' ?>">Students</a>
        <a href="/capstoneproject/profiles/employee/dashboard/pages/CreateAccount.php" class="<?= basename($_SERVER['PHP_SELF']) == 'CreateAccount.php' ? 'active' : '' ?>">Create Account</a>
        <a href="/capstoneproject/profiles/employee/employee_dashboard.php">Home</a>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="create-account-container">
            <h2>Create a New Account</h2>
            <form method="POST" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                <option value="student">student</option>
                <option value="employee">employee</option>
            </select>

            <button type="submit">Create Account</button>
        </form>

        <!-- Display error message if any -->
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        </div>
    </div>

    <script>
        <?php if (isset($_SESSION['account_created']) && $_SESSION['account_created']): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = document.getElementById('successModal');
                modal.style.display = 'flex';
                
                // Close modal when clicking outside
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                }
            });
            <?php unset($_SESSION['account_created']); ?>
        <?php endif; ?>
    </script>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <h3>✅ Success!</h3>
            <p>Account created successfully!</p>
            <button onclick="document.getElementById('successModal').style.display='none'">OK</button>
        </div>
    </div>

    <script src="/capstoneproject/assets/js/profilescript.js"></script>
</body>
</html>