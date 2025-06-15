<head>
  <title>Login</title>
  <link rel="stylesheet" href="signup_styles.css">
  <?php include 'head-main.html'; ?>
</head>
<body>
    <?php include("components/header.php"); ?>

<?php
session_start();
session_destroy();
header("Location: login.php");
?>
</body>
