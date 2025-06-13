<?php
require_once("db.php");

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(16)); // generate unique token

$stmt = $pdo->prepare("INSERT INTO users (email, password, verification_token) VALUES (?, ?, ?)");
try {
  $stmt->execute([$email, $password, $token]);

  // Simulate email sending for now (print link)
  echo "Please verify your account: <a href='verify.php?token=$token'>Click to verify</a>";
} catch (PDOException $e) {
  echo "Error: Email already exists.";
}
?>
