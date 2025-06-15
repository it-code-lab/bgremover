<?php
function process_with_huggingface($imagePath, $user_id)
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
    curl_close($ch);

    $eventData = json_decode($eventResponse, true);
    $event_id = $eventData['event_id'] ?? null;
    if (!$event_id) {
        http_response_code(500);
        //DND- For debugging
        //echo json_encode($eventData);        
        echo "Failed to get event ID.";
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

        $stmt = $pdo->prepare("INSERT INTO usage_log (user_id, used_at, usage_type) VALUES (?, NOW(), 'free')");
        $stmt->execute([$user_id]);

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