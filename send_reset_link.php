<?php
session_start();
require_once("db.php");
require_once 'mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id, first_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Store token and expiry
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires_at, $email]);

        // Send email
        sendPasswordResetEmail($email, $token, $user['first_name'] ?? 'User');
    }

    // Redirect to confirmation (secure approach)
    $_SESSION['reset_requested'] = true;
    header("Location: reset_confirmation.php");
    exit;
} else {
    header("Location: forgot_password.php");
    exit;
}
