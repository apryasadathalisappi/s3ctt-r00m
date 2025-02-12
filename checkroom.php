<?php
// GitHub repository details
$repoOwner = 'apryasadathalisappi';
$repoName = 's3ctt-r00m';
$folderPath = 'RoomChat';

// GitHub API URL
$apiUrl = "https://api.github.com/repos/$repoOwner/$repoName/contents/$folderPath";

// Personal access token
$accessToken = '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';

// Get the room code from the request
$roomCode = $_GET['room'];

// Check if the room file exists
$ch = curl_init("$apiUrl/$roomCode.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: PHP Script',
    'Authorization: token ' . $accessToken
]);
$response = curl_exec($ch);
curl_close($ch);

$fileData = json_decode($response, true);
if (isset($fileData['sha'])) {
    echo json_encode(['status' => 'true']);
} else {
    echo json_encode(['status' => 'false']);
}
?>
