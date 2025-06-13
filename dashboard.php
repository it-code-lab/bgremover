<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="dashboard_styles.css">


</head>
<body>
  <?php include("components/header.php"); ?>
<div class="dashboard-container">
<h2>Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?></h2>

<div class="credit-summary">
  You have <strong>12 credits</strong> remaining.
</div>

<div class="usage-stats">
  Today’s usage: <strong>2 / 3 free background removals used</strong>
</div>

<a href="buy_credits.php" class="cta-button">Buy More Credits</a>

<form id="uploadForm" class="dashboard-form" enctype="multipart/form-data">
  <input type="file" name="image" id="imageInput" required>
  <button type="submit">Remove Background</button>
</form>

<div id="result"></div>

</div>


<script src="script.js"></script>
</body>
