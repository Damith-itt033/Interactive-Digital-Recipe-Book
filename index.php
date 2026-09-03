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
        </div>

        <div class="col-lg-5">
          <div class="rounded-4 overflow-hidden shadow-lg border border-secondary">
            <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=800&q=80" alt="Creamy Ramen" class="img-fluid w-100">
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Search Prompt -->
    <section class="text-center my-5">
      <h3 class="fw-bold mb-2">What are we cooking today?</h3>
      <p class="text-muted">Explore our dynamically filtered, curated student collection.</p>
      
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="input-group input-group-lg shadow-sm rounded-pill border p-1 bg-white">
            <span class="input-group-text bg-transparent border-0 ps-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" class="form-control border-0 shadow-none fs-6" placeholder="Search recipes by name or ingredient (e.g. avocado)...">
            <button class="btn btn-amber rounded-pill px-4" type="button">Search</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Curated Recipe Grid (Dynamic) -->
    <section class="my-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Curated Recipes</h4>
        <a href="dashboard.php" class="text-decoration-none fw-semibold text-warning">View All Directory &rarr;</a>
      </div>

      <div class="row g-4">
        <?php
        // Fetch up to 4 recipes dynamically from the database
        $stmt = $pdo->query("SELECT r.*, c.name as category_name, c.slug as category_slug FROM recipes r LEFT JOIN categories c ON r.category_id = c.id ORDER BY r.created_at DESC LIMIT 4");
        $recipes = $stmt->fetchAll();
        
        if (count($recipes) > 0):
            foreach ($recipes as $recipe):
        ?>
        <div class="col-md-6 col-lg-3">
          <div class="recipe-card h-100">
            <?php if (!empty($recipe['image_url'])): ?>
            <div class="position-relative">
              <img src="<?= htmlspecialchars($recipe['image_url']) ?>" class="recipe-card-img" alt="<?= htmlspecialchars($recipe['title']) ?>">
              <span class="position-absolute top-0 start-0 m-3 meal-badge badge-<?= htmlspecialchars($recipe['category_slug']) ?>">
                  <?= strtoupper(htmlspecialchars($recipe['category_name'])) ?>
              </span>
            </div>
            <?php endif; ?>
            <div class="p-3">
              <?php if (empty($recipe['image_url'])): ?>
              <div class="mb-2">
                <span class="meal-badge badge-<?= htmlspecialchars($recipe['category_slug']) ?>">
                    <?= strtoupper(htmlspecialchars($recipe['category_name'])) ?>
                </span>
              </div>
              <?php endif; ?>
              <div class="d-flex align-items-center text-warning fs-7 mb-1">
                <i class="fa-solid fa-star me-1"></i> 5.0
              </div>
              <h5 class="fw-bold fs-6 mb-2"><?= htmlspecialchars($recipe['title']) ?></h5>
              <p class="text-muted fs-7 mb-3"><?= htmlspecialchars(substr($recipe['instructions'], 0, 80)) ?>...</p>
              <div class="d-flex justify-content-between align-items-center pt-2 border-top fs-7 text-muted">
                <span><i class="fa-regular fa-clock me-1"></i> <?= htmlspecialchars($recipe['prep_time']) ?> Mins</span>
                <button class="btn btn-link text-decoration-none fw-semibold text-warning p-0" data-bs-toggle="modal" data-bs-target="#recipeModal<?= $recipe['id'] ?>">Cook Recipe &rarr;</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal for Recipe Details -->