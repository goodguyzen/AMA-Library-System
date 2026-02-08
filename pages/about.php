<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About AMA Cavite Library</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/index-style.css" rel="stylesheet">
    <style>
      :root{
        --maroon: #800000;
        --maroon-600: #6a0000;
        --blue: #0d6efd;
        --blue-700: #0a58ca;
      }
      .navbar-sticky { position: sticky; top: 0; z-index: 1030; }
      .navbar.bg-light { background-color: #f8f9fa !important; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
      .navbar .nav-link { color: var(--maroon) !important; font-weight: 500; border-radius: 999px; padding: .35rem .9rem; transition: all .2s ease; }
      .navbar .nav-link:hover { background-color: rgba(128,0,0,.08); color: var(--maroon) !important; }
      .navbar .nav-link.active { color: #fff !important; background-color: var(--maroon); box-shadow: 0 2px 6px rgba(128,0,0,.25); }
      .about-hero { background: linear-gradient(135deg, var(--blue) 0%, var(--maroon) 100%); }
      .about-badge { background-color: #fff; color: var(--maroon); }
      .about-title { color: var(--maroon); }
      .about-title.centered { text-align: center; }
      .about-title.smaller { font-size: 2rem; }
      @media (min-width: 992px){ .about-title.smaller { font-size: 2.25rem; } }
      .about-lead { text-align: center; }
      .about-figure { display: inline-block; }
      .about-figure img { border-radius: 1rem; border: 3px solid #fff; box-shadow: 0 6px 18px rgba(0,0,0,.15); }
      .about-figure figcaption { font-size: .9rem; color: #6b7280; margin-top: .5rem; }
      .about-section h2 { color: var(--maroon); text-align: center; font-weight: 700; font-size: 1.6rem; margin: 3rem 0 2rem; }
      @media (min-width: 992px){ .about-section h2 { font-size: 1.9rem; margin: 4rem 0 2.5rem; } }
      .about-section .source-note { font-size: .9rem; color: #6b7280; text-align: center; }
      .about-section .source-note a { color: var(--blue-700); text-decoration: none; }
      .about-section .source-note a:hover { color: var(--blue); text-decoration: underline; }
      .card .fs-5 { color: #334155; }
      .btn-outline-primary { color: var(--maroon); border-color: var(--maroon); }
      .btn-outline-primary:hover { background-color: var(--maroon); color: #fff; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-sticky">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../assets/images/amau_logo_basic2a.png" alt="AMAU Logo" class="navbar-brand-icon" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border: 1px solid rgba(0,0,0,.1)">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-lg-auto me-lg-4">
                    <li class="nav-item"><a class="nav-link" href="../index.php#section_1">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php#section_2">How to use</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php#section_books">Available Books</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                    <li class="nav-item login-btn" id="loginBtn"><a class="nav-link" href="javascript:void(0)">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="py-5">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-12">
                        <h1 class="fw-bold mb-2 about-title centered smaller">About AMA Cavite Library</h1>
                    </div>
                    <div class="col-12 text-center">
                        <figure class="about-figure">
                            <img src="../assets/images/AMAschool.jpg" class="img-fluid" style="max-height: 260px; object-fit: cover;" alt="AMA Cavite Campus">
                            <figcaption>AMA Cavite Campus</figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-9 col-lg-10">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4 p-lg-5">
                                <div class="fs-5 lh-lg text-secondary about-section">
                                    <h2>About AMA Education System</h2>
                                    <p>Founded in 1980 by Dr. Amable R. Aguiluz V, the AMA Education System (AMAES) is a pioneering and leading provider of IT-based education in the Philippines. Recognized as the first school to have a one-to-one student to computer ratio in a classroom, AMAES has consistently been at the forefront of innovation in education. In 2014, it launched the first full online education program in the Philippines, demonstrating its commitment to accessible and flexible learning.</p>
                                    <p>AMAES is composed of several member institutions, including AMA University and Colleges, ACLC College, AMA Computer Learning Center (ACLC), ABE International Business College, St. Augustine School of Nursing, Delta Air International Aviation Academy, AMA Basic Education (formerly St. Augustine International School), and Southern Luzon College – Cavite. These institutions offer a comprehensive range of programs from Senior High School to Graduate Studies, including specialized courses in Data Science, Cybersecurity, and Blockchain Technology. The system is renowned for its IT-focused programs, equipping students with the skills and knowledge needed in the modern digital landscape.</p>
                                    <p>The institution has established partnerships with global brands such as Cisco, Microsoft, and CompTIA, providing students with a competitive edge and access to internationally recognized certifications. AMAES also has a strong affiliation with Pearson VUE, a global leader in computer-based testing, making it one of the few certified institutions for high-stakes certification and licensure exams.</p>
                                    <p>AMAES is committed to excellence, as evidenced by its accreditations from CHED, PACUCOA, ABET, and ISO 9001:2008. It also provides placement and linkages programs, connecting students to over 20,000 companies worldwide for internships and job opportunities. With its focus on quality and innovative education, AMAES continues to shape future leaders who are tech-savvy, globally competitive, and ready to meet the demands of the times. AMA Education System is your education partner today and tomorrow.</p>

                                    <h2>About AMA University and Colleges</h2>
                                    <p>AMA Education System is a leading provider of IT-based education in the Philippines, and its flagship institution, AMA University and Colleges, is a member of this esteemed system. Founded by Dr. Amable R. Aguiluz V in 1980 and named after his father, Hon. Amable M. Aguiluz, AMA has become the largest network of colleges and universities in Asia, producing graduates who are highly competent in various technologies and applications needed in the industry.</p>
                                    <p>AMA’s focus on IT education means that technology is integrated into all of its programs, providing students with an advantage in the use of different industry-required business applications and making them tech-savvy. The AMA Education System is dedicated to providing quality education that prepares students for promising careers, and as the pioneer in IT education and online learning in the Philippines, AMAES offers a holistic, high-quality education to produce graduates who are ready to meet the demands of the times. This commitment to excellence has made AMA Education System a reliable and trusted provider of education in the Philippines.</p>

                                    <p class="source-note">Source: <a href="https://www.amaes.edu.ph/about-ama-university-and-colleges/" target="_blank" rel="noopener">amaes.edu.ph/about-ama-university-and-colleges</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="loginForm" class="login-form">
        <form action="../profiles/authenticate.php" method="POST">
            <h3 id="loginTitle">Login</h3>
            <label for="username">Student ID/Email:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <a href="../forgot-password.html" class="forgot-password">Forgot Password? Contact an Admin</a>
            <button type="submit">Login</button>
            <span id="closeForm" class="close-btn">X</span>
        </form>
    </div>

    <script src="../assets/js/index-js/jquery.min.js"></script>
    <script src="../assets/js/index-js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/index-js/loginscript.js"></script>
</body>
</html>
