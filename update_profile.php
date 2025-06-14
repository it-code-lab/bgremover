<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = $_POST['first_name'];
  $last = $_POST['last_name'];

  $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
  $stmt->execute([$first, $last, $_SESSION['user_id']]);

  $_SESSION['first_name'] = $first;
  $_SESSION['last_name'] = $last;

  header("Location: profile.php?updated=1");
  exit();
}
?>
