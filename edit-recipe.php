<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$msg = '';
$msgType = '';
$recipe_id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$recipe_id) {
    header("Location: dashboard.php");
    exit;
}

// Fetch the existing recipe and verify ownership
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->execute([$recipe_id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    echo "Recipe not found.";
    exit;
}

// Security: Only the author can edit
if ($recipe['user_id'] != $_SESSION['user_id']) {
    echo "You do not have permission to edit this recipe.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_input($_POST['title'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $ingredients = sanitize_input($_POST['ingredients'] ?? '');
    $instructions = sanitize_input($_POST['instructions'] ?? '');
    $prep_time = (int)($_POST['prep_time'] ?? 0);
    $cook_time = (int)($_POST['cook_time'] ?? 0);
    $servings = (int)($_POST['servings'] ?? 1);
    $difficulty = $_POST['difficulty'] ?? 'Medium';
    $calories = (int)($_POST['calories'] ?? 0);

    if (empty($title) || empty($category_id) || empty($ingredients) || empty($instructions)) {
        $msg = "Please fill in all required fields.";
        $msgType = "danger";
    } else {
        $image_url = $recipe['image_url']; // Keep existing image by default

        // Handle File Upload if a new file is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['image']['name']));
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image_url = $targetPath;
            }
        }

        $updateStmt = $pdo->prepare("UPDATE recipes SET category_id = ?, title = ?, instructions = ?, ingredients = ?, prep_time = ?, cook_time = ?, servings = ?, difficulty = ?, image_url = ?, calories = ? WHERE id = ?");
        
        if ($updateStmt->execute([$category_id, $title, $instructions, $ingredients, $prep_time, $cook_time, $servings, $difficulty, $image_url, $calories, $recipe_id])) {
            $msg = "Success! Your recipe has been updated.";
            $msgType = "success";
            
            // Refresh recipe data
            $stmt->execute([$recipe_id]);
            $recipe = $stmt->fetch();
        } else {
            $msg = "An error occurred while updating your recipe.";
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
  <title>Savory Share - Edit Recipe</title>
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
          <li class="nav-item"><a class="nav-link-custom" href="contact.php"><i class="fa-solid fa-envelope me-1"></i> Contact Us</a></li>
        </ul>
        <div class="d-flex align-items-center gap-2">
            <a href="submit-recipe.php" class="btn btn-amber me-2"><i class="fa-solid fa-pen-nib me-1"></i> Share Recipe</a>
            <span class="me-3 fw-bold text-dark"><i class="fa-solid fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="auth/logout.php" class="btn btn-outline-danger rounded-pill px-3">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <main class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="text-center mb-4">
          <h2 class="fw-bold">Edit Your Recipe</h2>
          <p class="text-muted">Update the details below to save your changes.</p>
        </div>

        <div class="form-wireframe-box p-4 bg-white rounded-4 border shadow-sm">
          <?php if ($msg): ?>
            <div class="alert alert-<?= $msgType ?> py-3 rounded-3 mb-4">
              <i class="fa-solid fa-circle-<?= $msgType === 'success' ? 'check' : 'exclamation' ?> me-2"></i>
              <?= htmlspecialchars($msg) ?>
            </div>
          <?php endif; ?>

          <form action="edit-recipe.php?id=<?= $recipe_id ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $recipe_id ?>">
            
            <div class="mb-3">
              <label class="wireframe-label mb-1">Recipe Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control rounded-3" value="<?= htmlspecialchars($recipe['title']) ?>" required>
            </div>

            <div class="mb-3">
              <label class="wireframe-label mb-1">Category <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select rounded-3" required>
                <option value="">Select Category...</option>
                <?php
                $catStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
                while ($c = $catStmt->fetch()) {
                    $selected = ($c['id'] == $recipe['category_id']) ? 'selected' : '';
                    echo '<option value="' . $c['id'] . '" ' . $selected . '>' . htmlspecialchars($c['name']) . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="wireframe-label mb-1">Recipe Image <span class="text-muted">(Optional - leave blank to keep current image)</span></label>
              <?php if (!empty($recipe['image_url'])): ?>
                  <div class="mb-2">
                      <img src="<?= htmlspecialchars($recipe['image_url']) ?>" alt="Current Image" class="img-thumbnail" style="max-height: 100px;">
                  </div>
              <?php endif; ?>