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

    function get_remaining_free_usage_for_today($user_id)
    {
        global $pdo;
        if (!$user_id) return 0;

        $today = date('Y-m-d');

        // Count how many time free usage was used today
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ? AND DATE(used_at) = ? AND usage_type = 'free'");
        $stmt->execute([$user_id, $today]);
        $daily_usage = (int) $stmt->fetchColumn();

        // Count total free usage used so far
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usage_log WHERE user_id = ? and usage_type = 'free' ");
        $stmt->execute([$user_id]);
        $total_usage = $stmt->fetchColumn();

        $remaining_free_uses_for_today = 3 - $daily_usage;

        if ($remaining_free_uses_for_today > (10 - $total_usage)) {
            $remaining_free_uses_for_today = 10 - $total_usage;
        }

        return $remaining_free_uses_for_today;
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
        include 'models/replicate_model.php';
        process_with_replicate($imagePath, $user_id);
    }

} else {
    http_response_code(400);
    echo "No image uploaded.";
}

?>