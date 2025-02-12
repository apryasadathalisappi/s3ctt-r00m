<?php
$adminKeyUrl = "https://gist.githubusercontent.com/apryasadathalisappi/3d38800b59ee484e395ddf9133467797/raw";
$roomCode = $_GET['room'];
$enteredKey = $_GET['key'];
$githubRepo = "apryasadathalisappi/s3ctt-r00m";
$githubToken = "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";
$filename = "RoomChat/$roomCode.json";

// Verify admin key
$adminKey = trim(file_get_contents($adminKeyUrl));
if ($enteredKey !== $adminKey) {
    die(json_encode(["error" => "Unauthorized"]));
}

// Delete file
$githubApiUrl = "https://api.github.com/repos/$githubRepo/contents/$filename";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $githubApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "PHP");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: token $githubToken"]);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

$data = json_encode(["message" => "Deleted room $roomCode", "sha" => $response['sha']]);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_exec($ch);
curl_close($ch);

echo json_encode(["status" => "success"]);
?>
