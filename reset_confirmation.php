<?php
session_start();
if (!isset($_SESSION['reset_requested'])) {
    header("Location: forgot_password.php");
    exit;
}
unset($_SESSION['reset_requested']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Link Sent</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .confirmation-box {
            background: #fff;
            padding: 30px;
            max-width: 500px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
            text-align: center;
        }
        .confirmation-box h2 {
            color: #2b7cff;
        }
        .confirmation-box p {
            color: #333;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <?php include("components/header.php"); ?>
    <div class="confirmation-box">
        <h2>✅ Reset Link Sent</h2>
        <p>We've sent a password reset link to your email address.</p>
        <p>Please check your inbox and follow the instructions to reset your password.</p>
    </div>
</body>
</html>
