<?php
// This is just a dummy response to simulate result
$imageData = file_get_contents($_FILES['image']['tmp_name']);
header("Content-Type: image/png");
echo $imageData; // simulate passthrough for testing
exit;
?>