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