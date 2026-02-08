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
                            <li><a href="student-home.php" class="active">Home</a></li>
                            <li><a href="library.php">Library</a></li>
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
          <!-- ***** Banner Start ***** -->
            <div class="main-banner">
              <div class="row">
                <div class="col-lg-7">
                  <div class="header-text">
                    <h6>Welcome!</h6>
                    <h4>
                      <em>Browse</em> the AMA Cavite Campus Online Library Now!
                    </h4>
                    <div class="main-button">
                      <a href="library.php">Browse Now</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <!-- ***** Banner End ***** -->

          <!-- ***** Banner Start ***** -->
            <div class="announcement-banner">
              <div class="row">
                <div class="col-lg-7">
                  <div class="header-text">
                    
                    <h4><em>ANNOUNCEMENTS</em></h4>

                    <h6>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum doloremque quis iusto, aspernatur enim hic ex minus fugiat praesentium consectetur ea corrupti animi iste id itaque aliquid nostrum nesciunt alias!</h6>
                  </div>
                </div>
              </div>
            </div>
          <!-- ***** Banner End ***** -->
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


  <!-- ***** Scripts ***** -->
  <!-- ***** Bootstrap core JavaScript ***** -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

  <script src="/capstoneproject/assets/js/isotope.min.js"></script>
  <script src="/capstoneproject/assets/js/owl-carousel.js"></script>
  <script src="/capstoneproject/assets/js/tabs.js"></script>
  <script src="/capstoneproject/assets/js/popup.js"></script>
  <script src="/capstoneproject/assets/js/custom.js"></script>

</body>

</html>
