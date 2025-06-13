<?php
require_once("db.php");

$token = $_POST['token'];
$newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
$stmt->execute([$newPassword, $token]);

echo $stmt->rowCount() ? "Password updated!" : "Invalid token.";
?>
