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