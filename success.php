<?php
require 'vendor/autoload.php';
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['session_id'])) {
    header("Location: login.php");
    exit();
}

$stripe = new \Stripe\StripeClient('sk_test_51KQJ0wHYpvIFwCYEZw0SqDuSt9jvXAArlPaDI7GxwAaiNJyfgPEGNbyi8CKYx9YT2S3ZiZoHB4ilxuq6XsbdJBfP00cpwvugQ8');

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
