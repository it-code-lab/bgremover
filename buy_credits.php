<?php
require 'vendor/autoload.php';
require_once 'db.php';
session_start();

\Stripe\Stripe::setApiKey("sk_test_your_secret_key");

$session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => [[
    'price_data' => [
      'currency' => 'usd',
      'product_data' => ['name' => 'Credit Pack (20 images)'],
      'unit_amount' => 500, // $5
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => 'http://localhost/bgremover/success.php?session_id={CHECKOUT_SESSION_ID}',
  'cancel_url' => 'http://localhost/bgremover/dashboard.php',
]);

header("Location: " . $session->url);
exit;
?>
