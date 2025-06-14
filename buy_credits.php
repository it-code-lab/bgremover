<?php
session_start();
require_once 'db.php';
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// Define available credit packs
$creditPacks = [
    20 => ['price_cents' => 500, 'label' => 'Credit Pack (20 images)'],
    100 => ['price_cents' => 2000, 'label' => 'Credit Pack (100 images)']
];

// Validate the requested pack
$selectedPack = isset($_GET['pack']) ? (int)$_GET['pack'] : 0;

if (!isset($creditPacks[$selectedPack])) {
    echo "<p style='color:red; text-align:center;'>Invalid credit pack selected.</p>";
    echo "<p style='text-align:center;'><a href='pricing.php'>← Go Back to Pricing</a></p>";
    exit;
}

$pack = $creditPacks[$selectedPack];

// Create Stripe Checkout session
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [
        [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => ['name' => $pack['label']],
                'unit_amount' => $pack['price_cents'],
            ],
            'quantity' => 1,
        ]
    ],
    'mode' => 'payment',
    'success_url' => 'http://localhost/bgremover/success.php?session_id={CHECKOUT_SESSION_ID}&credits=' . $selectedPack,
    'cancel_url' => 'http://localhost/bgremover/dashboard.php',
]);

// Redirect to Stripe Checkout
header("Location: " . $session->url);
exit;
?>
