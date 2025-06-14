<?php

session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;

// Define get_user_credits if not already defined
if (!function_exists('get_user_credits')) {
    function get_user_credits($user_id)
    {
        global $pdo;
        if (!$user_id)
            return 0;
        $stmt = $pdo->prepare("SELECT credits FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int) $result['credits'] : 0;
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

// // Log the usage
// $stmt = $pdo->prepare("INSERT INTO usage_log (user_id, used_at) VALUES (?, NOW())");
// $stmt->execute([$user_id]);

// Call appropriate model
// if ($is_free_mode) {
//     include 'models/rembg_model.php'; // lighter or faster model
// } else {
//     include 'models/u2net_model.php'; // replicate u2net
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && (!$is_free_mode)) {
    $imagePath = $_FILES['image']['tmp_name'];
    $base64 = base64_encode(file_get_contents($imagePath));

    //Reference:https://replicate.com/851-labs/background-remover/api

    $data = [
        "version" => "a029dff38972b5fda4ec5d75d7d1cd25aeff621d2cf4946a41055d7db66b80bc", // U-2-Net
        "input" => ["image" => "data:image/jpeg;base64," . $base64]
    ];

    //DND
    //$api_token = getenv('REPLICATE_API_KEY'); // or $_ENV['REPLICATE_API_KEY']

    $api_token = "";
    $curl = curl_init("https://api.replicate.com/v1/predictions");
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $api_token",
            "Content-Type: application/json",
            "Prefer: wait"
        ]
    ]);


    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_status !== 201 && $http_status !== 200) {
        http_response_code($http_status);
        echo "API Error: $response";
        exit;
    }

    $result = json_decode($response, true);
    if (!isset($result['output']) || empty($result['output'])) {
        http_response_code(500);
        echo "No output URL returned from Replicate.";
        exit;
    }

    $output_url = $result['output'];

    // Fetch and serve the final image
    $image_data = file_get_contents($output_url);
    if ($image_data) {
        header("Content-Type: image/png");
        echo $image_data;

        // Optional: Log usage
        $stmt = $pdo->prepare("INSERT INTO usage_log (user_id, used_at) VALUES (?, NOW())");
        $stmt->execute([$user_id]);
    } else {
        http_response_code(500);
        echo "Error fetching output image.";
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && $is_free_mode) {
    $imagePath = $_FILES['image']['tmp_name'];

    //Reference: https://huggingface.co/spaces/not-lain/background-removal


    // Upload to temporary public storage for Hugging Face (e.g., tmpfiles.org or your server if public)
    //DND
    //$upload_url = uploadToTempServer($imagePath);

    $upload_url = "https://i.pinimg.com/736x/a8/34/e6/a834e6b7be8a10d045b84654543bbcba.jpg";
    if (!$upload_url) {
        http_response_code(500);
        echo "Failed to make image available via public URL for Hugging Face API.";
        exit;
    }

    // Step 2: Make POST request to initiate background removal
    $postData = json_encode([
        "data" => [
            ["path" => $upload_url, "meta" => ["_type" => "gradio.FileData"]]
        ]
    ]);

    $ch = curl_init("https://not-lain-background-removal.hf.space/gradio_api/call/image");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $eventResponse = curl_exec($ch);
    curl_close($ch);

    // Extract event ID
    $eventData = json_decode($eventResponse, true);
    if (!isset($eventData['event_id'])) {
        http_response_code(500);
        
        //DND- For debugging
        //echo json_encode($eventData);

        //DND
        echo "Failed to get event ID.";
        exit();
    }
    $event_id = $eventData['event_id'];

    // Poll result from Hugging Face
    $pollUrl = "https://not-lain-background-removal.hf.space/gradio_api/call/image/$event_id";
    $ch = curl_init($pollUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $pollResponse = curl_exec($ch);
    curl_close($ch);

    // Extract final image URL
    preg_match_all('/"url":\s*"([^"]+)"/', $pollResponse, $matches);
    $imageUrls = $matches[1];

    if (count($imageUrls) >= 2) {
        $finalUrl = $imageUrls[0]; // Usually first is result image
        header("Content-Type: image/png");
        echo file_get_contents($finalUrl);
    } else {
        http_response_code(500);
        echo "Failed to extract image from Hugging Face response.";
    }
} else {
    http_response_code(400);
    echo "No image uploaded.";
}

// 🛠️ Helper to upload to tmpfiles.org (public temporary image host)
function uploadToTempServer($imagePath)
{
    $cfile = curl_file_create($imagePath, mime_content_type($imagePath), basename($imagePath));

    $ch = curl_init("https://tmpfiles.org/api/v1/upload");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['file' => $cfile]
    ]);

    $result = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($result, true);
    if (isset($json['data']['url'])) {
        return $json['data']['url'];
    }

    return null;
}
?>