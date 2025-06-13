<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>
<?php include("header.php"); ?>
<h2>Welcome, <?= htmlspecialchars($_SESSION['email']) ?></h2>
<a href="logout.php">Logout</a>

<form id="uploadForm" enctype="multipart/form-data">
  <input type="file" name="image" id="imageInput" required>
  <button type="submit">Remove Background</button>
</form>
<a href="buy_credits.php">Buy Credits</a>

<div id="result"></div>

<script src="script.js"></script>
