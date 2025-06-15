<!DOCTYPE html>
<html lang="en">

<head>
  <title>Login</title>
  <link rel="stylesheet" href="login_styles.css">
  <?php include 'head-main.html'; ?>
</head>

<body>
  <?php include("components/header.php"); ?>
  <main>
    <div class="form-container">
      <h2>Login to Your Account</h2>

      <?php if (isset($_GET['error']) && $_GET['error'] === 'unverified'): ?>
        <div class="alert alert-error">
          ⚠ Please verify your email before logging in.
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['error']) && $_GET['error'] === 'loginerror'): ?>
        <div class="alert alert-error">
          ⚠ Email id or password is incorrect.
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
        <div class="alert alert-success">
          ✅ Your account has been created. Please check your email and verify your account before logging in.

        </div>
      <?php endif; ?>
      <?php if (isset($_GET['verified'])): ?>
        <div class="alert alert-success">
          ✅ Your email has been verified. Please log in.
        </div>
      <?php endif; ?>



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