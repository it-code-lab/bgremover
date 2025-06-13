<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="signup_styles.css">

</head>
<body>
  <?php include("components/header.php"); ?>
  <main>
<div class="form-container">
  <h2>Create an Account</h2>
  <form action="create_user.php" method="POST">
    <div class="form-group">
      <label for="first_name">First Name:</label>
      <input type="text" name="first_name" id="first_name" required />
    </div>
    <div class="form-group">
      <label for="last_name">Last Name:</label>
      <input type="text" name="last_name" id="last_name" required />
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required />
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required />
    </div>
    <button type="submit" class="submit-btn">Sign Up</button>
  </form>
</div>


    </main>
    <script src="header.js"></script>

</body>
</html>
