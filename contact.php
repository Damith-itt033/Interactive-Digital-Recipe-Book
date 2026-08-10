<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
$msg = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $category = sanitize_input($_POST['category'] ?? 'general');
    $message = sanitize_input($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $msg = "Please fill in all required fields.";
        $msgType = "danger";
    } elseif (!is_valid_email($email)) {
        $msg = "Please enter a valid email address.";
        $msgType = "danger";
    } else {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, category, message) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $category, $message])) {
            $msg = "Thank you! Your message has been successfully saved to our database.";
            $msgType = "success";
        } else {
            $msg = "An error occurred while saving your message.";
            $msgType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Savory Share - Contact Us</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/wireframe.css">
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom py-3 sticky-top">
    <div class="container">
      <a class="navbar-brand-custom" href="index.php">
        <span class="brand-badge">S</span>
        <div>
          <div class="lh-1">Savory Share</div>
          <small class="text-muted fs-7 font-monospace">ICT1209 Web Project</small>
        </div>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav mx-auto gap-1">
          <li class="nav-item"><a class="nav-link-custom" href="index.php"><i class="fa-solid fa-compass me-1"></i> Discover</a></li>
          <li class="nav-item"><a class="nav-link-custom" href="dashboard.php"><i class="fa-solid fa-utensils me-1"></i> Browse Recipes</a></li>
          <li class="nav-item"><a class="nav-link-custom active" href="contact.php"><i class="fa-solid fa-envelope me-1"></i> Contact Us</a></li>
        </ul>
        <div class="d-flex align-items-center gap-2">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="submit-recipe.php" class="btn btn-amber me-2"><i class="fa-solid fa-pen-nib me-1"></i> Share Recipe</a>
            <span class="me-3 fw-bold text-dark"><i class="fa-solid fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="auth/logout.php" class="btn btn-outline-danger rounded-pill px-3">Logout</a>
          <?php else: ?>
            <a href="auth/login.php" class="btn btn-outline-secondary rounded-pill px-3">Login</a>
            <a href="auth/register.php" class="btn btn-amber">Share Recipe</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <main class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-7">
        <div class="text-center mb-4">
          <span class="badge bg-amber text-warning border px-3 py-2 rounded-pill mb-2"><i class="fa-regular fa-paper-plane me-1"></i> Client Inquiry Channel</span>
          <h2 class="fw-bold">Get In Touch With Us</h2>
          <p class="text-muted">Have a culinary question, feedback, or need help with a recipe submission? Drop us a line below.</p>
        </div>

        <div class="form-wireframe-box">
          <?php if ($msg): ?>
            <div class="alert alert-<?= $msgType ?> py-3 rounded-3 mb-4">
              <i class="fa-solid fa-circle-<?= $msgType === 'success' ? 'check' : 'exclamation' ?> me-2"></i>
              <?= htmlspecialchars($msg) ?>
            </div>
          <?php endif; ?>

          <form action="contact.php" method="POST">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="txtName" class="wireframe-label mb-1">Your Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="txtName" class="form-control rounded-3" placeholder="e.g. Damith Dissanayaka" required value="<?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '' ?>">
              </div>
              <div class="col-md-6">
                <label for="txtEmail" class="wireframe-label mb-1">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" id="txtEmail" class="form-control rounded-3" placeholder="e.g. damith@example.com" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="selCategory" class="wireframe-label mb-1">Inquiry Category</label>
              <select name="category" id="selCategory" class="form-select rounded-3">
                <option value="general">General Question</option>
                <option value="recipe">Recipe Submission Help</option>
                <option value="technical">Technical Issue / Bug</option>
                <option value="feedback">Feedback &amp; Suggestions</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="txtMessage" class="wireframe-label mb-1">Your Message <span class="text-danger">*</span></label>
              <textarea name="message" id="txtMessage" class="form-control rounded-3" rows="5" placeholder="Write your detailed query or message here..." required></textarea>
              <div class="form-text fs-7 text-muted mt-1"><i class="fa-solid fa-circle-info me-1 text-warning"></i> This form will submit to MySQL database via PHP Backend.</div>
            </div>

            <button type="submit" class="btn btn-amber btn-lg w-100 py-3 rounded-3 fw-bold fs-6"><i class="fa-solid fa-paper-plane me-2"></i>Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-white border-top py-4 mt-5">
    <div class="container text-center text-muted fs-7">
      <p class="mb-1">Savory Share (Feastify) &copy; 2026 — ICT 1209 Web Technologies Mini Project</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
