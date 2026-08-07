<?php
// Configuration & PDO MySQL Database Connection
// Savory Share (Feastify) Phase 2

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'feastify_db');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("<div style='font-family:sans-serif; padding: 20px; text-align:center; background:#fef2f2; color:#991b1b; border:1px solid #fca5a5; margin:50px auto; max-width:600px; border-radius:10px;'>
        <h2>Database Connection Error</h2>
        <p>Could not connect to MySQL database <strong>" . DB_NAME . "</strong>.</p>
        <p><small>Please make sure XAMPP / MySQL service is running and you have imported <code>schema.sql</code>.</small></p>
        <p><code>" . htmlspecialchars($e->getMessage()) . "</code></p>
    </div>");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
