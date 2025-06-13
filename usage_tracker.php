require_once("db.php");

$ip = $_SERVER['REMOTE_ADDR'];
$stmt = $pdo->prepare("INSERT INTO usage_log (ip_address) VALUES (?)");
$stmt->execute([$ip]);
