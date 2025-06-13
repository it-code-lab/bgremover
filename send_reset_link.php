<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="style.css">

</head>
<body>
    <?php include("components/header.php"); ?>
<?php
require_once("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Store token and expiry
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires_at, $email]);

        // Email reset link
        $reset_link = "http://localhost/bgremover/reset_password.php?token=$token";

        // Send the email (using mail() or display for dev)
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n$reset_link";
        $headers = "From: no-reply@bgremover.com";

        // Uncomment this when mail is configured:
        // mail($email, $subject, $message, $headers);

        echo "Reset link has been sent to your email.<br><a href='$reset_link'>[Dev: Click here to test]</a>";
    } else {
        echo "Email not found.";
    }
} else {
    header("Location: forgot_password.php");
    exit();
}
?>
</body