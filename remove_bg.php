<?php

session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!function_exists('get_user_credits')) {
    function get_user_credits($user_id)
    {
        global $pdo;
        if (!$user_id) return 0;
        $stmt = $pdo->prepare("SELECT credits FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int) $result['credits'] : 0;
    }
}

$credits = get_user_credits($user_id);
$is_free_mode = ($credits == 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $imagePath = $_FILES['image']['tmp_name'];

    if ($is_free_mode) {
        $today = date('Y-m-d');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ? AND DATE(used_at) = ? and usage_type = 'free'");
        $stmt->execute([$user_id, $today]);
        $daily_usage = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ? and usage_type = 'free'");
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

        include 'models/huggingface_model.php';
        process_with_huggingface($imagePath, $user_id);

    } else {
        include 'models/u2net_model.php';
        process_with_u2net($imagePath, $user_id);
    }

} else {
    http_response_code(400);
    echo "No image uploaded.";
}

?>