<?php
require_once("db.php");
include("components/header.php");

$showForm = false;
$message = "";
$messageClass = "";

if (!isset($_GET['token'])) {
  $message = "Invalid reset link.";
  $messageClass = "alert-error";
} else {
  $token = $_GET['token'];
  $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()");
  $stmt->execute([$token]);
  $user = $stmt->fetch();

  if (!$user) {
    $message = "Reset link is invalid or has expired.";
    $messageClass = "alert-error";
  } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
      $message = "Passwords do not match.";
      $messageClass = "alert-error";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $new_password)) {
      $message = "Password must be at least 8 characters long, include an uppercase letter and a special character.";
      $messageClass = "alert-error";
    } else {
      $hashed = password_hash($new_password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
      $stmt->execute([$hashed, $user['id']]);

      $message = "✅ Password reset successfully. <a href='login.php'>Login now</a>";
      $messageClass = "alert-success";
    }
  } else {
    $showForm = true;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Create New Password</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .form-container {
      background: #fff;
      padding: 30px;
      max-width: 500px;
      margin: 40px auto;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #2b7cff;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .submit-btn {
      width: 100%;
      padding: 12px;
      background-color: #2b7cff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .submit-btn:hover {
      background-color: #1a64d4;
    }

    .form-footer {
      text-align: center;
      margin-top: 15px;
    }

    .alert {
      max-width: 600px;
      margin: 20px auto;
      padding: 15px;
      border-radius: 5px;
      text-align: center;
    }

    .alert-success {
      background-color: #e0f7e9;
      color: #2e7d32;
      border: 1px solid #a5d6a7;
    }

    .alert-error {
      background-color: #fdecea;
      color: #c62828;
      border: 1px solid #ef9a9a;
    }

    #strengthMsg {
      font-size: 0.9rem;
      margin-top: 5px;
    }

    .weak { color: #e53935; }
    .medium { color: #fbc02d; }
    .strong { color: #43a047; }
  </style>
</head>
<body>

<?php if ($message): ?>
  <div class="alert <?= $messageClass ?>"><?= $message ?></div>
<?php endif; ?>

<?php if ($showForm): ?>
  <div class="form-container">
    <h2>Create New Password</h2>
    <form method="POST" id="resetForm">
      <div class="form-group">
        <label for="new_password">New Password</label>
        <input type="password" name="new_password" id="new_password" required>
        <div id="strengthMsg"></div>
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <div id="matchMsg" style="font-size: 0.9rem;"></div>
      </div>

      <button type="submit" class="submit-btn">Reset Password</button>
    </form>
    <div class="form-footer">
      <a href="login.php">Back to Login</a>
    </div>
  </div>
<?php endif; ?>

<script>
  const password = document.getElementById("new_password");
  const confirm = document.getElementById("confirm_password");
  const strengthMsg = document.getElementById("strengthMsg");
  const matchMsg = document.getElementById("matchMsg");

  password?.addEventListener("input", function () {
    const val = password.value;
    if (val.length < 8) {
      strengthMsg.textContent = "Too short (min 8 characters)";
      strengthMsg.className = "weak";
    } else if (!/[A-Z]/.test(val) || !/[\W_]/.test(val)) {
      strengthMsg.textContent = "Needs at least 1 uppercase and 1 special character";
      strengthMsg.className = "medium";
    } else {
      strengthMsg.textContent = "Strong password";
      strengthMsg.className = "strong";
    }
  });

  confirm?.addEventListener("input", function () {
    if (confirm.value !== password.value) {
      matchMsg.textContent = "❌ Passwords do not match";
      matchMsg.style.color = "#e53935";
    } else {
      matchMsg.textContent = "✅ Passwords match";
      matchMsg.style.color = "#43a047";
    }
  });

  document.getElementById("resetForm")?.addEventListener("submit", function (e) {
    if (password.value !== confirm.value) {
      e.preventDefault();
      matchMsg.textContent = "❌ Passwords do not match";
      matchMsg.style.color = "#e53935";
    }
  });
</script>

</body>
</html>
