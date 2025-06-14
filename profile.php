<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="profile_styles.css">
  <?php include("components/header.php"); ?>
</head>

<body>
  <div class="profile-container">
    <?php if (isset($_GET['updated'])): ?>
      <div class="alert-success">
        ✅ Profile updated successfully!
      </div>
    <?php endif; ?>

    <h2>Your Profile</h2>

    <div class="profile-section">
      <h3>Personal Info</h3>
      <form action="update_profile.php" method="POST">
        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($_SESSION['first_name']) ?>" required>

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($_SESSION['last_name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>

        <button type="submit">Update Info</button>
      </form>
    </div>

    <div class="profile-section">
      <h3>Change Password</h3>
      <form action="change_password.php" method="POST">
        <label>Current Password</label>
        <input type="password" name="current_password" required>

        <label>New Password</label>
        <input type="password" name="new_password" required>

        <button type="submit">Change Password</button>
      </form>
    </div>
  </div>
</body>

</html>