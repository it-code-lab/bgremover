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

$first = $_POST['first_name'];
$last = $_POST['last_name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(16));

$stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, verification_token) VALUES (?, ?, ?, ?, ?)");
try {
  $stmt->execute([$first, $last, $email, $password, $token]);
  echo "Please verify your account: <a href='verify.php?token=$token'>Click to verify</a>";
} catch (PDOException $e) {
  echo "User already exists!";
}

?>
</body>