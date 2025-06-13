<?php if (!isset($_GET['token'])) die("No token."); ?>

<form action="update_password.php" method="POST">
  <input type="hidden" name="token" value="<?= $_GET['token'] ?>" />
  New Password: <input type="password" name="password" required />
  <button type="submit">Reset Password</button>
</form>
