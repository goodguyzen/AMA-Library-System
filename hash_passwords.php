<?php
$servername = "localhost";
$username = "root"; // your DB username
$password = ""; // your DB password
$dbname = "library_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users with plain passwords
$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $hashed = password_hash($row['password'], PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET password = '$hashed' WHERE id = " . $row['id'];
        $conn->query($update_sql);
    }
    echo "Passwords hashed successfully.";
} else {
    echo "No users found.";
}

$conn->close();
?>