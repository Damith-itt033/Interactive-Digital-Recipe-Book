<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashed])) {
                $success = "Registration successful! You can now <a href='login.php'>Login</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Savory Share</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/wireframe.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm border-0 rounded-4 p-4">
          <div class="text-center mb-4">
            <a href="../index.php" class="text-decoration-none text-dark fw-bold fs-3">Savory Share</a>
            <p class="text-muted">Create a new account</p>
          </div>
          
          <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <?php if ($success): ?>
            <div class="alert alert-success py-2"><?= $success ?></div>
          <?php endif; ?>
          
          <form method="POST">
            <div class="mb-3">
                <label class="wireframe-label mb-1">Username</label>
                <input type="text" name="username" class="form-control rounded-3" placeholder="e.g. John Doe" required>