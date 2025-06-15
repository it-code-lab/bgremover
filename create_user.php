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
  require_once 'mailer.php';

  $first = $_POST['first_name'];
  $last = $_POST['last_name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $token = bin2hex(random_bytes(16));

  $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, verification_token) VALUES (?, ?, ?, ?, ?)");
  try {
    $stmt->execute([$first, $last, $email, $password, $token]);

    // Send verification email
    // $verify_link = "http://localhost/bgremover/verify_email.php?token=$token";
    // $subject = "Verify Your CleanPix Account";
    // $message = "Hi $first_name,\n\nClick the link below to verify your account:\n$verify_link";
    // $headers = "From: no-reply@bgremover.com";

    sendVerificationEmail($email, $first_name, $token);

    // Uncomment this when mail is working
    // mail($email, $subject, $message, $headers);
  
      
    // echo "Please verify your account: <a href='verify.php?token=$token'>Click to verify</a>";
    header("Location: login.php?signup=success");
    exit();

  } catch (PDOException $e) {
    header("Location: register.php?error=user_exists");
    exit();

  }

  ?>
</body>