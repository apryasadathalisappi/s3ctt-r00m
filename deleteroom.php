<?php
// GitHub repository details
$repoOwner = 'apryasadathalisappi';
$repoName = 's3ctt-r00m';
$folderPath = 'RoomChat';

// GitHub API URL for the repository
$apiUrl = "https://api.github.com/repos/$repoOwner/$repoName/contents/$folderPath";

// Gist details
$gistId = '3d38800b59ee484e395ddf9133467797';
$gistUrl = "https://api.github.com/gists/$gistId";

// Personal access token
$accessToken = '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';

// Fetch the admin key from the Gist
function fetchAdminKey($gistUrl, $accessToken) {
    $ch = curl_init($gistUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: PHP Script',
        'Authorization: token ' . $accessToken
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $gistData = json_decode($response, true);
    if (isset($gistData['files']['admin-key.txt']['content'])) {
        return trim($gistData['files']['admin-key.txt']['content']); // Trim to remove any extra spaces or newlines
    }
    return null;
}

// Get the admin key
$adminKey = fetchAdminKey($gistUrl, $accessToken);

if (!$adminKey) {
    echo json_encode(['status' => 'false', 'message' => 'Failed to fetch admin key']);
    exit;
}

// Get the room code and key from the request
$roomCode = $_GET['room'];
$key = $_GET['key'];

// Verify the admin key
if ($key !== $adminKey) {
    echo json_encode(['status' => 'false', 'message' => 'Invalid key']);
    exit;
}

// Fetch the file SHA (required for deletion)
$ch = curl_init("$apiUrl/$roomCode.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: PHP Script',
    'Authorization: token ' . $accessToken
]);
$response = curl_exec($ch);
curl_close($ch);

$fileData = json_decode($response, true);
if (!isset($fileData['sha'])) {
    echo json_encode(['status' => 'false', 'message' => 'Room not found']);
    exit;
}

// Delete the file
$data = [
    'message' => 'Delete room: ' . $roomCode,
    'sha' => $fileData['sha']
];

$ch = curl_init("$apiUrl/$roomCode.json");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: PHP Script',
    'Authorization: token ' . $accessToken,
    'Content-Type: application/json'
]);
$response = curl_exec($ch);
curl_close($ch);

echo json_encode(['status' => 'true']);
?>
