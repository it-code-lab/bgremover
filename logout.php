<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="signup_styles.css">

</head>
<body>
    <?php include("components/header.php"); ?>

<?php
session_start();
session_destroy();
header("Location: login.php");
?>
</body>
