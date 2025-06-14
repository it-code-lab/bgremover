<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pricing</title>
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
          <div class="price">$0</div>
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
          <div class="price">$5</div>
          <a href="buy_credits.php?pack=20" class="buy-btn">Buy Now</a>
        </div>

        <!-- Pro Pack -->
        <div class="pricing-card">
          <h2 class="title">Pro Pack</h2>
          <ul>
            <li>✓ 100 credits</li>
            <li>✓ High-res, no watermark</li>
            <li>✓ Never expires</li>
          </ul>
          <div class="price">$20</div>
          <a href="buy_credits.php?pack=100" class="buy-btn">Buy Now</a>
        </div>
      </div>
    </section>
  </main>

  <script src="header.js"></script>
</body>
</html>
