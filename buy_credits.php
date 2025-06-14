<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

require_once 'db.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once 'conversion.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// Step 1: Define available credit packs in USD
$creditPacks = [
    20  => ['usd_price' => 5.00, 'label' => 'Credit Pack (20 images)'],
    100 => ['usd_price' => 20.00, 'label' => 'Credit Pack (100 images)']
];

// Step 2: Validate selected pack
$selectedPack = isset($_GET['pack']) ? (int)$_GET['pack'] : 0;
if (!isset($creditPacks[$selectedPack])) {
    echo "<p style='color:red; text-align:center;'>Invalid credit pack selected.</p>";
    echo "<p style='text-align:center;'><a href='pricing.php'>← Go Back to Pricing</a></p>";
    exit;
}

// Step 3: Get user currency or default to USD
$rates = getRates();
$defaultCurrency = 'usd';
$currency = $_GET['currency'] ?? detectCurrencyFromIP(); // fallback IP detection if implemented
$currency = strtolower($currency);
if (!in_array($currency, array_keys($rates))) {
    $currency = $defaultCurrency;
}

// Step 4: Calculate local price using real-time exchange
$usdPrice = $creditPacks[$selectedPack]['usd_price'];
$conversionRate = $rates[$currency];
$convertedAmount = round($usdPrice * $conversionRate, 2);
$unitAmountCents = intval(round($convertedAmount * 100));

// Step 5: Create Stripe Checkout session
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => $currency,
            'product_data' => ['name' => $creditPacks[$selectedPack]['label']],
            'unit_amount' => $unitAmountCents,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost/bgremover/success.php?session_id={CHECKOUT_SESSION_ID}&credits=' . $selectedPack,
    'cancel_url' => 'http://localhost/bgremover/dashboard.php',
    'metadata' => [
        'credits' => $selectedPack,
        'usd_price' => $usdPrice,
        'currency' => $currency,
        'converted_price' => $convertedAmount
    ]
]);

// Step 6: Redirect to Stripe Checkout
header("Location: " . $session->url);
exit;
?>
