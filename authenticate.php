<?php
require_once("db.php");
session_start();

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$_POST['email']]);
$user = $stmt->fetch();

if (!$user['is_verified']) {
  // die("Please verify your email before logging in.");
  header("Location: login.php?error=unverified");
}

if ($user && password_verify($_POST['password'], $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['first_name'] = $user['first_name'];
  $_SESSION['last_name'] = $user['last_name'];
  $_SESSION['email'] = $user['email']; // Optional if still needed internally
  $_SESSION['credits'] = $user['credits'];
  $_SESSION['credits'] = $user['credits'];

  $redirectTo = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'dashboard.php';
  unset($_SESSION['redirect_after_login']);
  header("Location: $redirectTo");
  exit();
  // echo "Login successful. Redirecting...";
  // header("Location: dashboard.php");
} else {
  // echo "Invalid login.";
  header("Location: login.php?error=loginerror");
}
?>