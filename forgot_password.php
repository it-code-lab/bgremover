<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" href="login_styles.css">
  <link rel="stylesheet" href="forgot_password_styles.css">
  <?php include 'head-main.html'; ?>
</head>
<body>
  <?php include("components/header.php"); ?>
<div class="form-container">
  <h2>Reset Your Password</h2>
  <form action="send_reset_link.php" method="POST">
    <div class="form-group">
      <label for="email">Enter your email:</label>
      <input type="email" name="email" id="email" required>
    </div>
    <button type="submit" class="submit-btn">Send Reset Link</button>
  </form>
  <div class="form-footer">
    <a href="login.php">Back to Login</a>
  </div>
</div>

</body