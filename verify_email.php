<?php
require_once("db.php");

if (!isset($_GET['token'])) {
  die("Invalid link.");
}

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
  $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
  $stmt->execute([$user['id']]);
  header("Location: login.php?verified=1");
} else {
  echo "Invalid or expired token.";
}
?>
