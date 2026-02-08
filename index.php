<?php
// Placeholder for future database connection and dynamic content.
// Example: require_once __DIR__ . '/config/database.php';

// Connect to the database (consistent with existing project usage)
$conn = new mysqli("localhost", "root", "", "library_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available books (adjust fields if your schema differs)
$books_sql = "SELECT id, title, author, image_path, availability FROM books WHERE availability = 'Available' ORDER BY title ASC LIMIT 12";
$books_result = $conn->query($books_sql);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>The AMA Library</title>
        <!-- CSS FILES -->     
            
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/css/index-style.css" rel="stylesheet">
    </head>
    <body>
        <main>
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="index.php">
                        <img src="assets/images/amau_logo_basic2a.png" alt="AMAU Logo" class="navbar-brand-icon" style="height: 40px;">
                    </a>
    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-lg-auto me-lg-4">
                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="#section_1">Home</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="#section_2">How to use</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="#section_books">Available Books</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="pages/about.php">About</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="pages/contact.php">Contact</a>
                            </li>

                            <li class="nav-item login-btn" id="loginBtn">
                                <a class="nav-link click-scroll" href="javascript:void(0)">Login</a>
                            </li>
                        </ul>

                        
                    </div>
                </div>
            </nav>
            

            <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-12 mb-5 pb-5 pb-lg-0 mb-lg-0">

                            <h6>Introducing</h6>

                            <h1 class="text-white mb-4">The AMA Cavite Campus Online Library</h1>

                            
                        </div>

                        <div class="hero-image-wrap col-lg-6 col-12 mt-3 mt-lg-0">
                            <img src="assets/images/Digital Gear (2).png" class="hero-image img-fluid" alt="education online books">
                        </div>

                    </div>
                </div>
            </section>


            <section class="featured-section">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-8 col-12">
                            <div class="avatar-group d-flex flex-wrap align-items-center">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php /* Available Books Section (showcase) */ ?>
            <section id="section_books" class="py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h2 class="mb-4">Available Books</h2>
                            <p class="text-muted mb-5">Currently showing available books from the database.</p>
                        </div>
                    </div>
                    <div class="row g-4">
                        <?php if ($books_result && $books_result->num_rows > 0): ?>
                            <?php while ($book = $books_result->fetch_assoc()): ?>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="card h-100 shadow-sm">
                                        <img src="<?php echo htmlspecialchars($book['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book['title']); ?>">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title mb-1"><?php echo htmlspecialchars($book['title']); ?></h5>
                                            <p class="card-text text-muted mb-3"><?php echo htmlspecialchars($book['author']); ?></p>
                                            <span class="badge bg-success align-self-start mb-3">Available</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-center text-muted">No books are currently available.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="py-lg-5"></section>

            <section id="section_2">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-12 col-12 text-center">
                            <h2 class="mb-5">How to use AMA Cavite Library?</h2>
                        </div>

                        <div class="col-lg-4 col-12">
                            <nav id="navbar-example3" class="h-100 flex-column align-items-stretch">
                                <nav class="nav nav-pills flex-column">
                                    <a class="nav-link smoothscroll" href="#item-1"><strong>Login to your Given Account</strong></a>

                                    <a class="nav-link smoothscroll" href="#item-2"><strong>Browse in Library</strong></a>

                                    <a class="nav-link smoothscroll" href="#item-3"><strong>Search for a Book</strong></a>

                                    <a class="nav-link smoothscroll" href="#item-4"><strong>Click Rent</strong></a>

                                    <a class="nav-link smoothscroll" href="#item-5"><strong>Wait for the Admin's Approval</strong></a>

                                    <a class="nav-link smoothscroll" href="#item-6"><strong>Claim your book</strong></a>

                                    <a class="nav-link smoothscroll" href="#item-7"><strong>Returning the Book</strong></a>
                                </nav>
                            </nav>
                        </div>

                        <div class="col-lg-8 col-12">
                            <div data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-smooth-scroll="true" class="scrollspy-example-2" tabindex="0">
                                <div class="scrollspy-example-item" id="item-1">
                                    <h5>Login to your AMA Account</h5>

                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

                                    <p>Sed leo nisl, posuere at molestie ac, suscipit auctor mauris. Etiam quis metus elementum, tempor risus vel, condimentum orci.</p>

                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-3">
                                            <img src="assets/images/click-login.png" class="scrollspy-example-item-image img-fluid" alt="">
                                        </div>

                                        <div class="col-lg-6 col-12 mb-3">
                                            <img src="assets/images/credentials.png" class="scrollspy-example-item-image img-fluid" alt="">
                                        </div>
                                    </div>
                                </div>

                                <div class="scrollspy-example-item" id="item-2">
                                    <h5>Browse in Library</h5>

                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus incidunt iste omnis culpa quo dolorem itaque! Aspernatur, quisquam! Explicabo corrupti nihil vel enim assumenda ducimus nisi in consectetur autem natus.</p>
                                    <p>This is a second paragraph. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>

                                    <p>Lorem ipsum dolor sit amet, consive adipisicing elit, sed do eiusmod. tempor incididunt ut labore.</p>

                                    <div class="row align-items-center">
                                        <div class="col-lg-6 col-12">
                                            <img src="assets/images/browse-book.png" class="img-fluid" alt="">
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <p><strong>You could browse for the available books in the library.</strong></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="scrollspy-example-item" id="item-3">
                                    <h5>Search for a Book</h5>

                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

                                    <p>Lorem ipsum dolor sit amet, consive adipisicing elit, sed do eiusmod. tempor incididunt ut labore.</p>

                                    <img src="assets/images/search-book.png" class="scrollspy-example-item-image img-fluid mb-3" alt="">
                                </div>

                                <div class="scrollspy-example-item" id="item-4">
                                    <h5>Click Rent</h5>

                                    <p>Click the rent button</p>

                                    <p>Sed leo nisl, posuere at molestie ac, suscipit auctor mauris. Etiam quis metus elementum, tempor risus vel, condimentum orci.</p>

                                    <img src="assets/images/click-rent.png" class="scrollspy-example-item-image img-fluid" alt="">
                                      
                                </div>

                                <div class="scrollspy-example-item" id="item-5">
                                    <h5>Wait for the admin's approval</h5>

                                    <p>Click the Rent button and then and wait for the admin's approval.</p>

                                    <p>Sed leo nisl, posuere at molestie ac, suscipit auctor mauris. Etiam quis metus elementum, tempor risus vel, condimentum orci.</p>

                                    <img src="assets/images/approval.png" class="scrollspy-example-item-image img-fluid" alt="">
                                     
                                </div>

                                <div class="scrollspy-example-item" id="item-6">
                                    <h5>Claim your book</h5>

                                    <p>Go to AMA Cavite Campus, and claim your rented book.</p>

                                    <p>Lorem ipsum dolor sit amet, consive adipisicing elit, sed do eiusmod. tempor incididunt ut labore.</p>

                                    <img src="assets/images/book-receive.png" class="scrollspy-example-item-image img-fluid mb-3" alt="">
                                </div>

                                <div class="scrollspy-example-item" id="item-7">
                                    <h5>Return the book</h5>

                                    <p>Make sure to return the book you rented on the alloted date.</p>

                                    <p>Lorem ipsum dolor sit amet, consive adipisicing elit, sed do eiusmod. tempor incididunt ut labore.</p>

                                    <img src="assets/images/deadline.png" class="scrollspy-example-item-image img-fluid mb-3" alt="">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </main>

        
        <div id="loginForm" class="login-form">
            <form action="profiles/authenticate.php" method="POST">
                <h3 id="loginTitle">Login</h3>
                <label for="username">Student ID/Email:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <a href="forgot-password.html" class="forgot-password">Forgot Password? Contact an Admin</a>
                <button type="submit">Login</button>
                <span id="closeForm" class="close-btn">X</span>
            </form>
        </div>

    </body>

    
<!-- JAVASCRIPT FILES -->
<script src="assets/js/index-js/jquery.min.js"></script>
<script src="assets/js/index-js/bootstrap.bundle.min.js"></script>
<script src="assets/js/index-js/jquery.sticky.js"></script>
<script src="assets/js/index-js/click-scroll.js"></script>
<script src="assets/js/index-js/custom.js"></script>
<script src="assets/js/index-js/loginscript.js"></script>
</html>
