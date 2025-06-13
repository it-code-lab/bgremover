<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="style.css">

</head>
<body>
    <?php include("components/header.php"); ?>
<?php
require_once("db.php");

if (!isset($_GET['token'])) {
  echo "Invalid link.";
  exit();
}

$token = $_GET['token'];

// Check if token exists and is not expired
$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
  echo "Reset link is invalid or expired.";
  exit();
}

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
  $stmt->execute([$new_password, $user['id']]);
  echo "Password has been reset successfully. <a href='login.php'>Login</a>";
  exit();
}
?>

  <div class="form-container">
    <h2>Create New Password</h2>
    <form method="POST">
      <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
      </div>
      <button type="submit" class="submit-btn">Reset Password</button>
    </form>
    <div class="form-footer">
      <a href="login.php">Back to Login</a>
    </div>
  </div>
</body>

