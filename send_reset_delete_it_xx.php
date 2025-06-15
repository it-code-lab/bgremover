<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="signup_styles.css">

</head>

<body>
  <?php include("components/header.php"); ?>
  <?php
  require_once("db.php");

  $email = $_POST['email'];
  $token = bin2hex(random_bytes(16));

  $stmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
  $stmt->execute([$token, $email]);

  if ($stmt->rowCount() > 0) {
    echo "Reset link: <a href='reset_password.php?token=$token'>Click here to reset password</a>";
  } else {
    echo "Email not found.";
  }
  ?>
</body>