<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $current = $_POST['current_password'];
  $new = $_POST['new_password'];

  $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
  $stmt->execute([$_SESSION['user_id']]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($current, $user['password'])) {
    header("Location: profile.php?error=password_incorrect");
    exit();
  }

  $newHash = password_hash($new, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->execute([$newHash, $_SESSION['user_id']]);

  header("Location: profile.php?password_changed=1");
  exit();
}
?>
