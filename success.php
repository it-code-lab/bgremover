<?php
require_once 'db.php';
require 'vendor/autoload.php';
session_start();

\Stripe\Stripe::setApiKey("sk_test_your_secret_key");

$session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
$user_id = $_SESSION['user_id'];

if ($session->payment_status === 'paid') {
  $stmt = $pdo->prepare("UPDATE users SET credits = credits + 20 WHERE id = ?");
  $stmt->execute([$user_id]);
  echo "Payment successful. Credits added!";
} else {
  echo "Payment failed.";
}
?>
