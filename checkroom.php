<?php
// Configuration
$githubRepo = "apryasadathalisappi/s3ctt-r00m"; // Your repository
$githubToken = "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"; // Securely store this
$githubUsername = "apryasadathalisappi";
$folder = "RoomChat"; // Folder where chat JSON files are stored

// Generate unique room code
$roomCode = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
$filename = "$folder/$roomCode.json";

// Check if room already exists
$githubApiUrl = "https://api.github.com/repos/$githubRepo/contents/$filename";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $githubApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "PHP");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: token $githubToken"]);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($response['message']) || $response['message'] != "Not Found") {
    die(json_encode(["status" => "error", "message" => "Room already exists"]));
}

// Create initial JSON structure
$initialChat = json_encode([["name" => "SERVER", "message" => "CREATED AT " . date("Y-m-d"), "time" => date("H:i:s")]], JSON_PRETTY_PRINT);
$encodedContent = base64_encode($initialChat);

// Create the file via GitHub API
$data = json_encode([
    "message" => "Created room: $roomCode",
    "content" => $encodedContent
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $githubApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "PHP");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: token $githubToken"]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

echo json_encode(["status" => "success", "roomCode" => $roomCode]);
?>
