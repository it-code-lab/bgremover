<?php
require 'vendor/autoload.php';
require_once 'db.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['session_id'])) {
    header("Location: login.php");
    exit();
}

$stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

try {
    $session_id = $_GET['session_id'];
    $session = $stripe->checkout->sessions->retrieve($session_id);

    if ($session && $session->payment_status === 'paid') {
        $user_id = $_SESSION['user_id'];
        $credits = isset($session->metadata->credits) ? (int)$session->metadata->credits : 0;

        $amount = $session->amount_total / 100;
        $currency = strtoupper($session->currency);

        // ✅ Update user credits
        $stmt = $pdo->prepare("UPDATE users SET credits = credits + ?, last_credit_purchased = NOW() WHERE id = ?");
        $stmt->execute([$credits, $user_id]);

        // ✅ Insert transaction record
        $txn = $pdo->prepare("INSERT INTO transactions (user_id, session_id, credits_added, amount_paid, currency) VALUES (?, ?, ?, ?, ?)");
        $txn->execute([$user_id, $session_id, $credits, $amount, $currency]);

        // ✅ Show confirmation screen before redirect
        echo "<!DOCTYPE html>
        <html>
        <head>
          <title>Payment Successful</title>
          <link rel='stylesheet' href='style.css'>
          <style>
            .confirmation { text-align: center; padding: 50px; }
            .confirmation h1 { color: green; }
            .cta-button { margin-top: 20px; }
          </style>
        </head>
        <body>
          <div class='confirmation'>
            <h1>🎉 Payment Successful!</h1>
            <p>Your purchase of <strong>$credits credits</strong> was successful.</p>
            <p>Amount Paid: <strong>$$amount $currency</strong></p>
            <a class='cta-button' href='dashboard.php'>Go to Dashboard</a>
          </div>
        </body>
        </html>";
        exit();
    } else {
        echo "Payment not completed.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
