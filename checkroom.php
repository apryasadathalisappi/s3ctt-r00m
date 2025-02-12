<?php
$githubRepo = "apryasadathalisappi/s3ctt-r00m";
$roomCode = $_GET['room'];
$githubToken = "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";
$filename = "RoomChat/$roomCode.json";
$githubApiUrl = "https://api.github.com/repos/$githubRepo/contents/$filename";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $githubApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "PHP");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: token $githubToken"]);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($response['message']) || $response['message'] == "Not Found") {
    echo json_encode(["status" => "false"]);
} else {
    echo json_encode(["status" => "true"]);
}
?>
