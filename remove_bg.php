<?php

session_start();
require_once 'db.php';
require 'vendor/autoload.php';

// Delete old files from uploads folder
$uploadDir = 'uploads/';
$files = glob($uploadDir . '*');
$now = time();

foreach ($files as $file) {
    if (is_file($file)) {
        $modifiedTime = filemtime($file);
        if ($now - $modifiedTime > 3600) {
            unlink($file);
        }
    }
}

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

$originalName = basename($_FILES['image']['name']);
$extension = pathinfo($originalName, PATHINFO_EXTENSION);
$targetDir = 'uploads/';
if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
$targetPath = $targetDir . uniqid('img_', true) . '.' . $extension;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // $imagePath = $_FILES['image']['tmp_name'];
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
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

            //include 'models/huggingface_model.php';
            process_with_huggingface($targetPath, $user_id, 'free');

        } else {
            // include 'models/huggingface_model.php';
            // process_with_huggingface($targetPath, $user_id);            
            //include 'models/replicate_model.php';
            process_with_replicate($targetPath, $user_id, 'paid');
        }
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to move uploaded file."]);
    }
} else {
    http_response_code(400);
    echo "No image uploaded.";
}

function process_with_huggingface($imagePath, $user_id, $usage_tp = 'free')
{
    global $pdo;

    //Reference: https://huggingface.co/spaces/not-lain/background-removal


    //Upload to temporary public storage for Hugging Face (e.g., tmpfiles.org or your server if public)
    //DND
    //error_log($imagePath);
    $upload_url = uploadToTempServer($imagePath);
    //error_log($upload_url);

    //$upload_url = "https://i.pinimg.com/736x/a8/34/e6/a834e6b7be8a10d045b84654543bbcba.jpg"; // Replace with real upload logic

    if (!$upload_url) {
        http_response_code(500);
        echo "Failed to make image available via public URL for Hugging Face API.";
        exit;
    }

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

    if ($eventResponse === false) {
        process_with_replicate($imagePath, $user_id, $usage_tp);
        exit;
    }

    curl_close($ch);

    $eventData = json_decode($eventResponse, true);
    $event_id = $eventData['event_id'] ?? null;
    if (!$event_id) {
        //http_response_code(500);

        //DND- For debugging
        //echo json_encode($eventData); 

        //echo "Failed to get event ID.";
        process_with_replicate($imagePath, $user_id, $usage_tp);
        exit;
    }

    $pollUrl = "https://not-lain-background-removal.hf.space/gradio_api/call/image/$event_id";
    $ch = curl_init($pollUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $pollResponse = curl_exec($ch);
    curl_close($ch);

    preg_match_all('/"url":\s*"([^"]+)"/', $pollResponse, $matches);
    $imageUrls = $matches[1];

    if (count($imageUrls) >= 1) {
        $finalUrl = $imageUrls[0]; // Usually first is result image ??
        // header("Content-Type: image/png");
        // echo file_get_contents($finalUrl);

        $stmt = $pdo->prepare("INSERT INTO usage_log (user_id, used_at, usage_type) VALUES (?, NOW(), ?)");
        $stmt->execute([$user_id, $usage_tp]);

        //Reduce the credit count by 1
        if ($usage_tp === 'paid') {
            $stmt = $pdo->prepare("UPDATE users SET credits = credits - 1 WHERE id = ?");
            $stmt->execute([$user_id]);
        }

        //For test only - Reduce the credit count by 1
        // $stmt = $pdo->prepare("UPDATE users SET credits = credits - 1 WHERE id = ?");
        // $stmt->execute([$user_id]);

        header("Content-Type: application/json");
        echo json_encode([
            "image_base64" => base64_encode(file_get_contents($finalUrl)),
            "credits" => get_user_credits($user_id),
            "remaining_free_uses" => get_remaining_free_usage_for_today($user_id)
        ]);


    } else {
        http_response_code(500);
        echo "Failed to extract image from Hugging Face response.";
    }
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
    //error_log("Upload response: " . print_r($json, true));
    if (isset($json['data']['url'])) {
        return str_replace('tmpfiles.org', 'tmpfiles.org/dl', $json['data']['url']);
    }

    return null;
}



function process_with_replicate($imagePath, $user_id, $usage_tp = 'paid')
{
    global $pdo;
    //Reference:https://replicate.com/851-labs/background-remover/api

    $base64 = base64_encode(file_get_contents($imagePath));
    $data = [
        "version" => "a029dff38972b5fda4ec5d75d7d1cd25aeff621d2cf4946a41055d7db66b80bc",
        "input" => ["image" => "data:image/jpeg;base64," . $base64]
    ];

    //DND
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $api_token = $_ENV['REPLICATE_API_KEY'] ;

    //$api_token = ""; // Set your Replicate token here
    $ch = curl_init("https://api.replicate.com/v1/predictions");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $api_token",
            "Content-Type: application/json",
            "Prefer: wait"
        ]
    ]);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status !== 201 && $http_status !== 200) {
        //http_response_code($http_status);
        //echo "API Error: $response";
        process_with_huggingface($imagePath, $user_id, $usage_tp);
        exit;
    }

    $result = json_decode($response, true);
    $output_url = $result['output'] ?? null;
    if (!$output_url) {
        http_response_code(500);
        echo "No output URL returned from Replicate.";
        exit;
    }
    //error_log("Replicate output URL: " . $output_url);

    $image_data = file_get_contents($output_url);
    if ($image_data) {
        // header("Content-Type: image/png");
        // echo $image_data;

        $stmt = $pdo->prepare("INSERT INTO usage_log (user_id, used_at, usage_type) VALUES (?, NOW(), ?)");
        $stmt->execute([$user_id, $usage_tp]);

        //Reduce the credit count by 1
        if ($usage_tp === 'paid') {
            $stmt = $pdo->prepare("UPDATE users SET credits = credits - 1 WHERE id = ?");
            $stmt->execute([$user_id]);
        }


        header("Content-Type: application/json");
        echo json_encode([
            "image_base64" => base64_encode($image_data),
            "credits" => get_user_credits($user_id),
            "remaining_free_uses" => get_remaining_free_usage_for_today($user_id)
        ]);


    } else {
        http_response_code(500);
        echo "Error fetching output image.";
    }
}

?>