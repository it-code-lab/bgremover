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

</head>
<body>
  <?php include("components/header.php"); ?>
  
  <main>
<section class="pricing">
  <h1>Pricing Plans</h1>
  <div class="pricing-cards">
    <div class="plan">
      <h2>Free</h2>
      <p>3 background removals per day</p>
      <div class="price">$0 / month</div>
    </div>
    <div class="plan">
      <h2>Pro</h2>
      <p>100 background removals / month</p>
      <div class="price">$5 / month</div>
      <a class="cta-button" href="buy_credits.php">Buy Now</a>
    </div>
  </div>
</section>

    </main>
    <script src="header.js"></script>

</body>
</html>
