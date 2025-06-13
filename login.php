<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include("components/header.php"); ?>
  <main>
    <form action="authenticate.php" method="POST" style="padding: 20px;">
      Email: <input type="email" name="email" required><br><br>
      Password: <input type="password" name="password" required><br><br>
      <button type="submit">Login</button>
    </form>
    </main>
    <script src="header.js"></script>

</body>
</html>
