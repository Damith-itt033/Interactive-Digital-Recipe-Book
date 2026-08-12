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
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($category_id) || empty($ingredients) || empty($instructions)) {
        $msg = "Please fill in all required fields.";
        $msgType = "danger";
    } else {
        // Image URL fallback (Removed as per user request)
        $image_url = null;


        // Handle File Upload
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
        $stmt = $pdo->prepare("INSERT INTO recipes (user_id, category_id, title, instructions, ingredients, prep_time, cook_time, servings, difficulty, image_url, calories) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");