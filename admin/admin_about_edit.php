<?php
// Admin About editor: simple form to update pages.about
// NOTE: Integrate with your admin auth/session guard if available.
session_start();
// Minimal guard example: require an 'admin_logged_in' session flag. Adjust as per your auth.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If your system uses a different admin session var or path, adjust here.
    // For now, allow access but show a notice. Replace with header('Location: /profiles/employee/employee_dashboard.php'); if needed.
}

$conn = new mysqli("localhost", "root", "", "library_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure table exists
$conn->query("CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    hero_image VARCHAR(1024) DEFAULT NULL,
    content MEDIUMTEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Seed if missing
$conn->query("INSERT IGNORE INTO pages (slug, title, hero_image, content) VALUES (
    'about',
    'About AMA Cavite Library',
    'assets/images/AMAschool.jpg',
    'Welcome to the AMA Cavite Campus Online Library. This page is editable by admin.'
)");

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $hero_image = trim($_POST['hero_image'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '') $errors[] = 'Title is required';

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE pages SET title=?, hero_image=?, content=? WHERE slug='about'");
        $stmt->bind_param('sss', $title, $hero_image, $content);
        $success = $stmt->execute();
        if (!$success) {
            $errors[] = 'Failed to update: ' . $conn->error;
        }
    }
}

// Fetch current values
$stmt = $conn->prepare("SELECT title, hero_image, content, updated_at FROM pages WHERE slug='about' LIMIT 1");
$stmt->execute();
$result = $stmt->get_result();
$page = $result->fetch_assoc();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit About Page</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/index-style.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="row g-3 align-items-center mb-3">
        <div class="col-md">
            <h1 class="h3 mb-0">About Page Editor</h1>
            <div class="text-muted small">Update the content displayed on the public About page.</div>
        </div>
        <div class="col-md-auto">
            <a href="../pages/about.php" class="btn btn-outline-secondary btn-sm">View Public Page</a>
            <a href="../index.php" class="btn btn-primary btn-sm">Back to Home</a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($success): ?>
        <div class="alert alert-success">About page updated successfully.</div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
        <div class="alert alert-warning">Note: This page is not currently protected. Hook it into your admin auth by checking your session or redirecting unauthorized users.</div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-7">
            <form method="post" class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($page['title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="hero_image" class="form-label">Hero Image (relative path or URL)</label>
                        <div class="input-group">
                            <span class="input-group-text">IMG</span>
                            <input type="text" id="hero_image" name="hero_image" class="form-control" value="<?php echo htmlspecialchars($page['hero_image'] ?? ''); ?>" placeholder="assets/images/AMAschool.jpg">
                        </div>
                        <div class="form-text">Example: assets/images/AMAschool.jpg</div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea id="content" name="content" rows="12" class="form-control"><?php echo htmlspecialchars($page['content'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">Last updated: <?php echo htmlspecialchars($page['updated_at'] ?? ''); ?></small>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <strong>Live Preview</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <?php if (!empty($page['hero_image'])): ?>
                            <img id="preview-img" src="<?php echo htmlspecialchars('../' . ltrim($page['hero_image'], '/')); ?>" class="img-fluid rounded-3 border" style="max-height: 220px; object-fit: cover;" alt="Preview">
                        <?php else: ?>
                            <div id="preview-img-placeholder" class="bg-light border rounded-3 p-5 text-muted">No image</div>
                        <?php endif; ?>
                    </div>
                    <h5 id="preview-title"><?php echo htmlspecialchars($page['title'] ?? ''); ?></h5>
                    <div id="preview-content" class="small text-secondary" style="white-space: pre-wrap;">
                        <?php echo htmlspecialchars($page['content'] ?? ''); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
  const title = document.getElementById('title');
  const hero = document.getElementById('hero_image');
  const content = document.getElementById('content');
  const pTitle = document.getElementById('preview-title');
  const pImg = document.getElementById('preview-img');
  const pImgPh = document.getElementById('preview-img-placeholder');
  const pContent = document.getElementById('preview-content');

  if (title) title.addEventListener('input', () => pTitle.textContent = title.value);
  if (content) content.addEventListener('input', () => pContent.textContent = content.value);
  if (hero) hero.addEventListener('input', () => {
    const v = hero.value.trim();
    if (v) {
      if (pImg) { pImg.src = '../' + v.replace(/^\/+/, ''); pImg.style.display = ''; }
      if (pImgPh) pImgPh.style.display = 'none';
    } else {
      if (pImg) pImg.style.display = 'none';
      if (pImgPh) pImgPh.style.display = '';
    }
  });
})();
</script>

<script src="../assets/js/index-js/jquery.min.js"></script>
<script src="../assets/js/index-js/bootstrap.bundle.min.js"></script>
</body>
</html>
