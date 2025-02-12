<?php
// Gist details
$gistId = '3d38800b59ee484e395ddf9133467797';
$gistUrl = "https://api.github.com/gists/$gistId";

// Personal access token
$accessToken = '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';

// Generate a new 10-character random key
function generateKey($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $key;
}

// Generate a new key
$newKey = generateKey();

// Update the Gist
$data = [
    'files' => [
        'admin-key.txt' => [ // Ensure this matches the file name in your Gist
            'content' => $newKey // Update the content with the new key
        ]
    ]
];

// Make the API request to update the Gist
$ch = curl_init($gistUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: PHP Script',
    'Authorization: token ' . $accessToken,
    'Content-Type: application/json'
]);
$response = curl_exec($ch);
curl_close($ch);

// Output the result
echo json_encode(['status' => 'true', 'new_key' => $newKey]);
?>
