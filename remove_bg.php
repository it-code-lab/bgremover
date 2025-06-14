<?php

session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;

// Define get_user_credits if not already defined
if (!function_exists('get_user_credits')) {
    function get_user_credits($user_id) {
        global $pdo;
        if (!$user_id) return 0;
        $stmt = $pdo->prepare("SELECT credits FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['credits'] : 0;
    }
}

$credits = get_user_credits($user_id);

$is_free_mode = ($credits == 0);

// Enforce daily & lifetime limits
if ($is_free_mode) {
    $today = date('Y-m-d');

    // Count today's usage
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ? AND DATE(used_at) = ?");
    $stmt->execute([$user_id, $today]);
    $daily_usage = $stmt->fetchColumn();

    // Count total usage
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total_usage = $stmt->fetchColumn();

    if ($daily_usage >= 3 || $total_usage >= 10) {
        http_response_code(429);
        echo json_encode([
            "error" => "Usage limit reached. Please upgrade for more.",
            "redirect" => "dashboard.php?error=freeusageexceeded"
        ]);
        exit;
    }

}

// Log the usage
$stmt = $pdo->prepare("INSERT INTO usage_log (user_id, used_at) VALUES (?, NOW())");
$stmt->execute([$user_id]);

// Call appropriate model
if ($is_free_mode) {
    include 'models/rembg_model.php'; // lighter or faster model
} else {
    include 'models/u2net_model.php'; // replicate u2net
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $imagePath = $_FILES['image']['tmp_name'];
    $base64 = base64_encode(file_get_contents($imagePath));

    $data = [
        "version" => "cf9f046611fc72d5c95f6fb505f7c4f6ebc47d044ec45a7a2226f0e24dfc65d9", // U-2-Net
        "input" => ["image" => "data:image/jpeg;base64," . $base64]
    ];

    $ch = curl_init("https://api.replicate.com/v1/predictions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Token YOUR_API_TOKEN",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    $prediction = json_decode($response, true);
    $status = $prediction["status"];
    $poll_url = $prediction["urls"]["get"];

    // Polling for completion
    while ($status !== "succeeded" && $status !== "failed") {
        sleep(1);
        $ch = curl_init($poll_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Token YOUR_API_TOKEN"]);
        $response = curl_exec($ch);
        curl_close($ch);
        $prediction = json_decode($response, true);
        $status = $prediction["status"];
    }

    if ($status === "succeeded") {
        $output_url = $prediction["output"];
        header("Content-Type: image/png");
        echo file_get_contents($output_url);
    } else {
        http_response_code(500);
        echo "Failed to process image.";
    }
}
?>
