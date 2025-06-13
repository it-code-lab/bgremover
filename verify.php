<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="signup_styles.css">

</head>
<body>
    <?php include("components/header.php"); ?>
<?php
require_once("db.php");

if (!isset($_GET['token'])) {
  die("No token.");
}

$stmt = $pdo->prepare("UPDATE users SET is_verified = TRUE, verification_token = NULL WHERE verification_token = ?");
$stmt->execute([$_GET['token']]);

if ($stmt->rowCount() > 0) {
  echo "Account verified! <a href='login.php'>Login here</a>";
} else {
  echo "Invalid or expired token.";
}
?>
</body>