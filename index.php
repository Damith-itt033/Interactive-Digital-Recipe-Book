<?php
session_start();
require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Savory Share - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/wireframe.css">
</head>
<body>

  <!-- Top Navigation Bar -->
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
          <li class="nav-item"><a class="nav-link-custom active" href="index.php"><i class="fa-solid fa-compass me-1"></i> Discover</a></li>
          <li class="nav-item"><a class="nav-link-custom" href="dashboard.php"><i class="fa-solid fa-utensils me-1"></i> Browse Recipes</a></li>
          <li class="nav-item"><a class="nav-link-custom" href="contact.php"><i class="fa-solid fa-envelope me-1"></i> Contact Us</a></li>
        </ul>
        <div class="d-flex align-items-center gap-2">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="submit-recipe.php" class="btn btn-amber me-2"><i class="fa-solid fa-pen-nib me-1"></i> Share Recipe</a>
            <span class="me-3 fw-bold text-dark"><i class="fa-solid fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="auth/logout.php" class="btn btn-outline-danger rounded-pill px-3">Logout</a>
          <?php else: ?>
            <a href="auth/login.php" class="btn btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-right-to-bracket me-1"></i> Login</a>
            <a href="auth/register.php" class="btn btn-amber"><i class="fa-solid fa-pen-nib me-1"></i> Share Recipe</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Container -->
  <main class="container my-4">

    <!-- Hero Card Spotlight Section -->
    <section class="hero-card-navy my-4">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <span class="hero-tag mb-3"><i class="fa-solid fa-star me-1"></i> FEATURED RECIPE OF THE DAY</span>
          <h1 class="hero-title my-3">The Ultimate Homemade Creamy Ramen</h1>
          <p class="text-light opacity-75 mb-4 fs-6">
            Learn the secrets of authentic, high-fidelity Japanese tonkotsu broth from home with basic, easy-to-find ingredients. Beautifully textured and ready in under 30 minutes.
          </p>

          <div class="d-flex flex-wrap gap-2 mb-4">
            <span class="hero-meta-badge"><i class="fa-regular fa-clock me-1"></i> 25 Mins</span>
            <span class="hero-meta-badge"><i class="fa-solid fa-signal me-1"></i> Easy</span>
            <span class="hero-meta-badge"><i class="fa-solid fa-fire me-1"></i> 410 kcal</span>
          </div>

          <div class="d-flex gap-3">
            <a href="dashboard.php" class="btn btn-amber btn-lg px-4 fs-6"><i class="fa-solid fa-book-open me-2"></i>Start Cooking</a>
            <button class="btn btn-outline-light rounded-pill px-4 fs-6"><i class="fa-regular fa-heart me-2"></i>Save to Favorites</button>
          </div>