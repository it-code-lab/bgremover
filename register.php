<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include("components/header.php"); ?>
  <main>
    <form action="create_user.php" method="POST" style="padding: 20px;">
      Email: <input type="email" name="email" required><br><br>
      Password: <input type="password" name="password" required><br><br>
      <button type="submit">Sign Up</button>
    </form>
    </main>
</body>
</html>
