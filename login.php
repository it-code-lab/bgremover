<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="login_styles.css">

</head>
<body>
  <?php include("components/header.php"); ?>
  <main>
<div class="form-container">
  <h2>Login to Your Account</h2>
  <form action="authenticate.php" method="POST">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required />
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required />
    </div>
    <button type="submit" class="submit-btn">Login</button>
    <p style="text-align:center; margin-top:15px;">
  Don't have an account?
  <a href="register.php">Sign up here</a><br>
  <a href="forgot_password.php">Forgot Password?</a>
</p>

  </form>
</div>

    </main>
    <script src="header.js"></script>

</body>
</html>
