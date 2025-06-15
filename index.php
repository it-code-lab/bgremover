<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="/BGREMOVER/images/icon.png">
  <meta charset="UTF-8">
  <title>Home</title>
  <link rel="stylesheet" href="style.css">
  <meta name="description" content="Remove background from images instantly. Free and paid versions available.">
  <meta name="robots" content="index, follow">
</head>

<body>
  <?php include("components/header.php"); ?>
  <main>
    <section class='hero'>
      <h1>Remove Backgrounds with 1 Click</h1>
      <p>Upload your image and get a transparent background in seconds.</p>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a class='cta-button' href='dashboard.php'>Remove Background Now</a>
      <?php else: ?>
        <a class='cta-button' href='register.php'>Get Started Free</a>
      <?php endif; ?>

    </section>

    <section class='features'>
      <h2>Why Choose Us?</h2>
      <ul>
        <li>⚡ Fast & Accurate Background Removal</li>
        <li>🧠 Powered by AI</li>
        <li>💾 Download in High Resolution</li>
        <li>🔒 Secure & Private</li>
      </ul>
    </section>
  </main>

  <script src="header.js"></script>
</body>

</html>