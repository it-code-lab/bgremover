<?php
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
