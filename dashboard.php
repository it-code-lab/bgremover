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
  <h2>Welcome, <?= htmlspecialchars($_SESSION['email']) ?></h2>
  <a href="logout.php">Logout</a>
  <a href="buy_credits.php">Buy Credits</a>

  <form id="uploadForm" class="dashboard-form" enctype="multipart/form-data">
    <input type="file" name="image" id="imageInput" required>
    <button type="submit">Remove Background</button>
  </form>

  <div id="result"></div>
</div>


<script src="script.js"></script>
</body>
