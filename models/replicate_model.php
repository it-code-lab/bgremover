<?php
function process_with_replicate($imagePath, $user_id)
{
    global $pdo;
    //Reference:https://replicate.com/851-labs/background-remover/api

    $base64 = base64_encode(file_get_contents($imagePath));
    $data = [
        "version" => "a029dff38972b5fda4ec5d75d7d1cd25aeff621d2cf4946a41055d7db66b80bc",
        "input" => ["image" => "data:image/jpeg;base64," . $base64]
    ];

    //DND
    //$api_token = getenv('REPLICATE_API_KEY'); // or $_ENV['REPLICATE_API_KEY']

    $api_token = ""; // Set your Replicate token here
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
        http_response_code($http_status);
        echo "API Error: $response";
        exit;
    }

    $result = json_decode($response, true);
    $output_url = $result['output'] ?? null;
    if (!$output_url) {
        http_response_code(500);
        echo "No output URL returned from Replicate.";
        exit;
    }

    $image_data = file_get_contents($output_url);
    if ($image_data) {
        // header("Content-Type: image/png");
        // echo $image_data;

        $stmt = $pdo->prepare("INSERT INTO usage_log (user_id, used_at, usage_type) VALUES (?, NOW(), 'paid')");
        $stmt->execute([$user_id]);

        //Reduce the credit count by 1
        $stmt = $pdo->prepare("UPDATE users SET credits = credits - 1 WHERE id = ?");
        $stmt->execute([$user_id]);
        
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
