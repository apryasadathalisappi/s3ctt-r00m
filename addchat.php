<?php
$githubRepo = "apryasadathalisappi/s3ctt-r00m";
$githubToken = "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";
$roomCode = $_GET['room'];
$name = $_GET['name'];
$message = $_GET['message'];
$time = $_GET['time'];
$filename = "RoomChat/$roomCode.json";
$githubApiUrl = "https://api.github.com/repos/$githubRepo/contents/$filename";

// Fetch current chat data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $githubApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "PHP");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: token $githubToken"]);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($response['content'])) {
    die(json_encode(["error" => "Room not found"]));
}

$chatData = json_decode(base64_decode($response['content']), true);
$chatData[] = ["name" => $name, "message" => $message, "time" => $time];
$updatedChat = json_encode($chatData, JSON_PRETTY_PRINT);
$encodedContent = base64_encode($updatedChat);

// Update file on GitHub
$data = json_encode([
    "message" => "Added message from $name",
    "content" => $encodedContent,
    "sha" => $response['sha']
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

echo json_encode(["status" => "success"]);
?>
