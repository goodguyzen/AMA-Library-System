<?php
// Start a new session or resume the existing one
session_start();

// Database connection parameters
$servername = "localhost"; // The server hosting the database (usually localhost for local development)
$username = "root"; // The database username, typically 'root' for local setups
$password = ""; // The database password; empty by default for many local installations
$dbname = "library_system"; // The name of the database we are connecting to

// Create a new connection to the MySQL database using the provided parameters
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If there was a connection error, display an error message and stop the script
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST, which indicates that a form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the username and password from the form submission
    $inputUsername = $_POST['username']; // The username input from the user
    $inputPassword = $_POST['password']; // The password input from the user

    // Prepare a SQL statement to select user data based on the provided username
    $sql = "SELECT * FROM users WHERE username = ?"; // Using a placeholder (?) to prevent SQL injection
    $stmt = $conn->prepare($sql); // Prepare the SQL statement
    $stmt->bind_param("s", $inputUsername); // Bind the input username to the placeholder in the SQL statement
    $stmt->execute(); // Execute the prepared statement

    // Get the result of the executed statement
    $result = $stmt->get_result();

    // Check if any users were found with the provided username
    if ($result->num_rows > 0) {
        
        // Fetch the user data from the result as an associative array
        $user = $result->fetch_assoc();

        // Check if the input password matches the stored password for the user
        if (password_verify($inputPassword, $user['password'])) {

            // If the password matches, store the username and role in the session
            $_SESSION['username'] = $user['username']; // Save the username in the session
            $_SESSION['role'] = $user['role']; // Save the user's role in the session

            
            header("Location: " . ($user['role'] == 'employee' ? "employee/employee_dashboard.php" : "student/student-home.php")); // Redirects the user to the appropriate dashboard based on their role
            exit(); // Stop further script execution after redirection

        } else {

            // If the password does not match, display an error message
            echo "Invalid username or password.";
        }
    } else {

        // If no user was found with the provided username, display an error message
        echo "Invalid username or password.";
    }
}
?>
