<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Secure session against fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Savory Share</title>
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
            <p class="text-muted">Login to your account</p>
          </div>
          
          <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-amber w-100 py-2 fw-bold">Login</button>
          </form>
          <div class="text-center mt-3">
            <small>Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
