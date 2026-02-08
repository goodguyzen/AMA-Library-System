<?php
session_start();

// Check if the user is logged in and has the role of 'employee'
// If not, redirect them to the login page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'employee') {
    header("Location: /capstoneproject/index.html"); // Redirect to the login page
    exit(); // Stop further script execution after redirection
}

// Retrieves the logged-in user's details and sanitize the output to prevent XSS
$username = htmlspecialchars($_SESSION['username']); // Get the username from the session
$role = htmlspecialchars($_SESSION['role']); // Get the role from the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMA University</title>
    <link rel="stylesheet" href="/capstoneproject/assets/css/employee-stylesheet.css">
</head>
<body>
    
    <div class="container"> <!-- Main container for the page content -->

        <!-- Top navigation bar -->
        <div class="topnav">
            <img src="/capstoneproject/assets/images/amau_logo_basic2a.png" alt="Logo" class="logo"> <!-- University logo -->
            <a href="#home">Home</a> <!-- Link to home section -->
            <a href="#contact">Contact</a> <!-- Link to contact section -->
            <a href="#about">About</a> <!-- Link to about section -->
            <a href="/capstoneproject/profiles/employee/dashboard/dashboard.php">Dashboard</a> <!-- Link to employee dashboard -->

            <!-- Profile Dropdown -->
            <div class="profile-container" id="profileContainer">
                <div class="profile-circle" id="profileCircle">
                    <?php echo strtoupper(substr($username, 0, 1)); ?> <!-- Display the first letter of the username in uppercase -->
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <p>Name: <?php echo $username; ?></p> <!-- Display the username -->
                    <p>Role: <?php echo $role; ?></p> <!-- Display the user role -->
                    <a href="/capstoneproject/profiles/logout.php">Logout</a> <!-- Link to logout -->
                </div>
            </div>
        </div>

        <!-- Main content section -->
        <section id="home"> 
            <div class="content">
                <div class="intro">
                    <img src="/capstoneproject/assets/images/AMAschool.jpg" alt="AMA"> <!-- Introductory image -->
                    <h2>AMA University: Employee Dashboard</h2> <!-- Dashboard title -->
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias, officiis dolorem odit sapiente ipsum aliquam atque nihil? Sapiente culpa vero sint, architecto eveniet, illum animi aut possimus deserunt excepturi reiciendis?</p> <!-- Introductory text -->
                </div>
            </div>
        </section>

        <div class="middle"> <!-- Additional content section -->
            <h1>Title Placeholder</h1> <!-- Placeholder for a title -->
            <p>
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Omnis soluta eaque modi alias cumque iste ea illum deserunt eligendi pariatur possimus quibusdam facere nesciunt adipisci explicabo, ex numquam quo repellat! Lorem ipsum dolor sit amet consectetur, adipisicing elit. Pariatur doloribus voluptatibus error, quos labore cum. Reprehenderit autem, perspiciatis vero minus omnis architecto, quae, soluta laboriosam error dolorum iusto nihil eveniet.
            </p> <!-- Placeholder for additional text -->
            <hr class="visible-line-break"> <!-- Horizontal line for visual separation -->
        </div>
    </div>

    <!-- JavaScript files for interactivity -->
    <script src="/capstoneproject/assets/js/topnavscript.js"></script> <!-- Script for top navigation functionality -->
    <script src="/capstoneproject/assets/js/profilescript.js"></script> <!-- Script for profile dropdown functionality -->

</body>
</html>
