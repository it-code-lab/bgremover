<?php
require_once __DIR__ . '/../vendor/autoload.php';

$replicateApiKey = $_ENV['REPLICATE_API_KEY'];
$base64Image = base64_encode(file_get_contents($_FILES['image']['tmp_name']));

$input = [
    'image' => "data:image/png;base64,$base64Image",
    'model' => 'u2net'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.replicate.com/v1/predictions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'version' => 'YOUR_U2NET_MODEL_VERSION_ID',
    'input' => $input
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $replicateApiKey,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);

// Retrieve result from Replicate polling or webhook (simplified here)
$bgRemovedImageUrl = $data['output'] ?? null;
if ($bgRemovedImageUrl) {
    echo file_get_contents($bgRemovedImageUrl);
} else {
    http_response_code(500);
    echo "Background removal failed.";
}
exit;

