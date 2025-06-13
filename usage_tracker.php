require_once("db.php");
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  http_response_code(403);
  exit("Not logged in.");
}

// Check today's usage
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ? AND DATE(used_at) = CURDATE()");
$stmt->execute([$user_id]);
$count = $stmt->fetchColumn();

if ($count >= 3) {  // Limit: 3 per day
  http_response_code(429);
  exit("Daily limit reached.");
}

// Log usage
$stmt = $pdo->prepare("INSERT INTO usage_log (user_id) VALUES (?)");
$stmt->execute([$user_id]);
