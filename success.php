<?php
require 'vendor/autoload.php';
require_once 'db.php';

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['session_id'])) {
    header("Location: login.php");
    exit();
}

$stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

try {
    $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);

    if ($session && $session->payment_status === 'paid') {
        // ✅ Add credits to the user's account
        $stmt = $pdo->prepare("UPDATE users SET credits = credits + 20 WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);

        header("Location: dashboard.php?credits=added");
        exit();
    } else {
        echo "Payment not completed.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>