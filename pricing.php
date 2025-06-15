<?php
session_start();
require_once 'conversion.php';
// Only fetch IP-based currency if not already stored
if (!isset($_SESSION['user_currency'])) {
  $ip = $_SERVER['REMOTE_ADDR'];
  //DND
  //$response = @file_get_contents("https://ipapi.co/{$ip}/json/");
  $response = "";
  if ($response) {
    $data = json_decode($response, true);
    $countryCode = strtoupper($data['country'] ?? '');
    $currencyMap = [
      'US' => 'usd', 'IN' => 'inr', 'CA' => 'cad', 'GB' => 'gbp', 'AU' => 'aud',
      'EU' => 'eur', 'JP' => 'jpy', 'SG' => 'sgd', 'NZ' => 'nzd', 'ZA' => 'zar',
      'BR' => 'brl', 'MX' => 'mxn', 'PH' => 'php', 'AE' => 'aed', 'HK' => 'hkd',
      'MY' => 'myr', 'CH' => 'chf', 'SE' => 'sek', 'DK' => 'dkk', 'NO' => 'nok'
    ];
    $_SESSION['user_currency'] = $currencyMap[$countryCode] ?? 'usd';
  } else {
    $_SESSION['user_currency'] = 'usd';
  }
}

// Use currency from URL or session fallback
$currency = $_GET['currency'] ?? $_SESSION['user_currency'] ?? 'usd';
if (!in_array($currency, SUPPORTED)) $currency = 'usd';

$rates = getRates();


$symbolMap = [
  'usd' => '$', 'inr' => '₹', 'eur' => '€', 'gbp' => '£', 'aud' => 'A$', 'cad' => 'C$',
  'jpy' => '¥', 'sgd' => 'S$', 'nzd' => 'NZ$', 'zar' => 'R', 'brl' => 'R$', 'mxn' => 'MX$',
   'aed' => 'د.إ', 'hkd' => 'HK$', 'chf' => 'CHF',
  'sek' => 'kr',  'nok' => 'kr'
];

$conversionRates = [
  'usd' => 1, 'inr' => 83, 'eur' => 0.91, 'gbp' => 0.78, 'aud' => 1.5,
  'cad' => 1.36, 'jpy' => 155, 'sgd' => 1.35, 'nzd' => 1.6, 'zar' => 18,
  'brl' => 5.2, 'mxn' => 17, 'php' => 58, 'aed' => 3.67, 'hkd' => 7.8,
  'myr' => 4.7, 'chf' => 0.9, 'sek' => 10.7, 'dkk' => 6.9, 'nok' => 10.5
];

$symbol = $symbolMap[$currency];
// $rate = $conversionRates[$currency] ?? 1;

$starterPrice = round(5 * $rates[$currency], 2);
$proPrice = round(20 * $rates[$currency], 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/png" href="/BGREMOVER/images/icon.png">
  <title>Pricing</title>
  <meta name="description" content="Remove background from images instantly. Free and paid versions available.">
  <meta name="robots" content="index, follow">

  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="pricing_styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <?php include("components/header.php"); ?>

  <main>
    <section class="pricing-section">
      <h1>Flexible Credit Packs</h1>
      <p class="subtext">No subscriptions. Buy credits when you need them.</p>

      <!-- Currency selector -->
      <form method="GET" class="currency-form" style="text-align: right; margin-bottom: 20px;">
        <label for="currency">Currency: </label>
        <select name="currency" id="currency" onchange="this.form.submit()">
          <?php foreach ($symbolMap as $code => $sym): ?>
            <option value="<?= $code ?>" <?= $currency === $code ? 'selected' : '' ?>>
              <?= strtoupper($code) ?> (<?= $sym ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </form>

      <div class="pricing-cards">
        <!-- Free Trial -->
        <div class="pricing-card">
          <h2 class="title free">Free Trial</h2>
          <ul>
            <li>✓ 3 removals per day</li>
            <li>✓ Up to 10 total (lifetime)</li>
            <li class="muted">✘ No high-res output</li>
            <li class="muted">✘ Watermarked</li>
          </ul>
          <div class="price"><?= $symbol . number_format(0, 2) ?></div>
          <p class="note">Automatically applied</p>
        </div>

        <!-- Starter Pack -->
        <div class="pricing-card">
          <h2 class="title">Starter Pack</h2>
          <ul>
            <li>✓ 20 credits</li>
            <li>✓ High-res, no watermark</li>
            <li>✓ Never expires</li>
          </ul>
          <div class="price"><?= $symbol . number_format($starterPrice, 2) ?></div>
          <a href="buy_credits.php?pack=20&currency=<?= $currency ?>" class="buy-btn">Buy Now</a>
        </div>

        <!-- Pro Pack -->
        <div class="pricing-card">
          <h2 class="title">Pro Pack</h2>
          <ul>
            <li>✓ 100 credits</li>
            <li>✓ High-res, no watermark</li>
            <li>✓ Never expires</li>
          </ul>
          <div class="price"><?= $symbol . number_format($proPrice, 2) ?></div>
          <a href="buy_credits.php?pack=100&currency=<?= $currency ?>" class="buy-btn">Buy Now</a>
        </div>
      </div>
    </section>
  </main>

  <script src="header.js"></script>
</body>
</html>
