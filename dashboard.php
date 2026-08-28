<?php
session_start();
require_once 'includes/db.php';

// Handle Search & Filter logic (Phase 2 backend integration)
$search = $_GET['q'] ?? '';
$category = $_GET['category'] ?? '';

$query = "SELECT r.*, c.name as category_name, c.slug as category_slug FROM recipes r LEFT JOIN categories c ON r.category_id = c.id WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (r.title LIKE ? OR r.instructions LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $query .= " AND c.slug = ?";
    $params[] = $category;
}

$query .= " ORDER BY r.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$recipes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Savory Share - Recipe Directory</title>
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
          <li class="nav-item"><a class="nav-link-custom active" href="dashboard.php"><i class="fa-solid fa-utensils me-1"></i> Browse Recipes</a></li>
          <li class="nav-item"><a class="nav-link-custom" href="contact.php"><i class="fa-solid fa-envelope me-1"></i> Contact Us</a></li>
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
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="fw-bold m-0">Full Recipe Directory</h2>
        <p class="text-muted m-0">Multi-criteria filtering and detailed views. Find perfect meals matching your exact preferences.</p>
      </div>
      <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="fa-solid fa-layer-group me-1 text-warning"></i> Found <?= count($recipes) ?> Recipes</span>
    </div>

    <div class="row g-4">
      <!-- Sidebar Filters Block -->
      <div class="col-lg-3">
        <div class="bg-white p-4 rounded-4 border">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold m-0"><i class="fa-solid fa-sliders me-2 text-warning"></i>Filters</h6>
            <a href="dashboard.php" class="text-decoration-none text-muted fs-7">Reset All</a>
          </div>

          <form action="dashboard.php" method="GET">
              <div class="mb-4">
                <label class="wireframe-label mb-2">Meal Category</label>
                <?php
                // Fetch categories
                $catStmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
                while ($c = $catStmt->fetch()):
                ?>
                <div class="form-check fs-7 mb-1">
                  <input class="form-check-input" type="radio" name="category" id="cat_<?= $c['slug'] ?>" value="<?= $c['slug'] ?>" <?= ($category === $c['slug']) ? 'checked' : '' ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="cat_<?= $c['slug'] ?>"><?= htmlspecialchars($c['name']) ?></label>
                </div>
                <?php endwhile; ?>
              </div>

              <div class="mb-4">
                <label class="wireframe-label mb-2">Live Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-search"></i></button>
                </div>
              </div>
          </form>
        </div>
      </div>

      <!-- Main Directory Content Block -->
      <div class="col-lg-9">
        <!-- Recipe Matrix Grid -->
        <div class="row g-4">
          <?php if (count($recipes) > 0): foreach ($recipes as $recipe): ?>
          <div class="col-md-6 col-xl-4">
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
                  <i class="fa-solid fa-star me-1"></i> 4.5 <span class="text-muted ms-1">(Phase 2)</span>
                </div>
                <h6 class="fw-bold mb-2"><?= htmlspecialchars($recipe['title']) ?></h6>
                <p class="text-muted fs-7 mb-3"><?= htmlspecialchars(substr($recipe['instructions'], 0, 70)) ?>...</p>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top fs-7 text-muted">
                  <span><i class="fa-regular fa-clock me-1"></i> <?= htmlspecialchars($recipe['prep_time'] + $recipe['cook_time']) ?> Mins</span>
                  <button class="btn btn-link text-decoration-none fw-semibold text-warning p-0" data-bs-toggle="modal" data-bs-target="#recipeModal<?= $recipe['id'] ?>">Cook Recipe &rarr;</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal for Recipe Details -->
          <div class="modal fade" id="recipeModal<?= $recipe['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
              <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-body p-0">
                  <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3 bg-white p-2 shadow-sm rounded-circle" data-bs-dismiss="modal" aria-label="Close"></button>
                  <div class="row g-0">
                    <!-- Left: Ingredients & Instructions -->
                    <div class="col-md-4 bg-light p-4 border-end overflow-auto" style="max-height: 80vh;">
                      <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-check me-2 text-warning"></i>Ingredients</h5>
                      <div class="text-muted fs-6 mb-4" style="white-space: pre-wrap;"><?= htmlspecialchars($recipe['ingredients'] ?? 'No ingredients listed.') ?></div>
                      
                      <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-fire-burner me-2 text-warning"></i>Instructions</h5>
                      <div class="text-muted fs-6" style="white-space: pre-wrap;"><?= htmlspecialchars($recipe['instructions']) ?></div>
                    </div>
                    <!-- Middle: Title & Intro -->
                    <div class="col-md-4 p-5 d-flex flex-column justify-content-center">
                      <span class="badge bg-amber text-warning rounded-pill px-3 py-2 align-self-start mb-3"><?= strtoupper(htmlspecialchars($recipe['category_name'])) ?></span>
                      <div class="d-flex align-items-start justify-content-between mb-3">
                          <h2 class="fw-bold mb-0"><?= htmlspecialchars($recipe['title']) ?></h2>
                          <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['user_id']): ?>
                              <a href="edit-recipe.php?id=<?= $recipe['id'] ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3 ms-2 text-nowrap"><i class="fa-solid fa-pen-to-square me-1"></i>Edit</a>
                          <?php endif; ?>
                      </div>
                      <div class="d-flex align-items-center gap-3 mb-4 text-muted fs-7">
                          <span><i class="fa-regular fa-clock me-1"></i> <?= htmlspecialchars($recipe['prep_time'] + $recipe['cook_time']) ?> Mins</span>
                          <span><i class="fa-solid fa-signal me-1"></i> <?= htmlspecialchars($recipe['difficulty']) ?></span>
                          <span><i class="fa-solid fa-user-group me-1"></i> <?= htmlspecialchars($recipe['servings']) ?> Servings</span>
                      </div>
                      <p class="text-muted fs-6 lead">A delicious homemade <?= strtolower(htmlspecialchars($recipe['category_name'])) ?> recipe ready to be cooked and enjoyed.</p>
                    </div>
                    <!-- Right: Image -->
                    <div class="col-md-4">
                      <?php if (!empty($recipe['image_url'])): ?>
                        <img src="<?= htmlspecialchars($recipe['image_url']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="img-fluid w-100 h-100 object-fit-cover" style="min-height: 400px;">
                      <?php else: ?>
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary bg-opacity-10" style="min-height: 400px;">
                          <i class="fa-solid fa-utensils fs-1 text-muted opacity-25"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <?php endforeach; else: ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-solid fa-magnifying-glass fs-1 mb-3 opacity-50"></i>
                <h5>No recipes found matching your criteria.</h5>
                <a href="dashboard.php" class="btn btn-outline-secondary mt-2">Clear Filters</a>
            </div>
          <?php endif; ?>
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
