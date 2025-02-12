<?php
// GitHub repository details
$repoOwner = 'apryasadathalisappi';
$repoName = 's3ctt-r00m';
$folderPath = 'RoomChat'; // Folder where room files are stored

// GitHub API URL
$apiUrl = "https://api.github.com/repos/$repoOwner/$repoName/contents/$folderPath";

// Personal access token
$accessToken = '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';

// Generate a random 8-character room code
function generateRoomCode($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// Check if a room file already exists
function roomExists($roomCode, $apiUrl, $accessToken) {
    $ch = curl_init("$apiUrl/$roomCode.json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: PHP Script',
        'Authorization: token ' . $accessToken
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $fileData = json_decode($response, true);
    return isset($fileData['sha']);
}

// Create a new room
$roomCode = generateRoomCode();
while (roomExists($roomCode, $apiUrl, $accessToken)) {
    $roomCode = generateRoomCode();
}

// Initial chat data
$initialData = [
    [
        "name" => "SERVER",
        "message" => "CREATED AT " . date('Y-m-d H:i:s'),
        "time" => date('H:i:s')
    ]
];
$encodedContent = base64_encode(json_encode($initialData, JSON_PRETTY_PRINT));

// Prepare the data for the API request
$data = [
    'message' => 'Create new room: ' . $roomCode,
    'content' => $encodedContent
];

// Create the file on GitHub
$ch = curl_init("$apiUrl/$roomCode.json");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: PHP Script',
    'Authorization: token ' . $accessToken,
    'Content-Type: application/json'
]);
$response = curl_exec($ch);
curl_close($ch);

echo json_encode(['status' => 'true', 'room' => $roomCode]);
?>
