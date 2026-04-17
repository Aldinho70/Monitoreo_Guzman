<?php
header('Content-Type: application/json');

$token = "2fe8024e0ab91aa6c8ed82717b71bddcECDC362358DF7D90986F5173D405CD0D42DE7B38";

$url = "https://hst-api.wialon.com/wialon/ajax.html";

$params = [
    "svc" => "token/login",
    "params" => json_encode(["token" => $token])
];

$query = http_build_query($params);

$ch = curl_init("$url?$query");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        "error" => true,
        "message" => curl_error($ch)
    ]);
} else {
    echo $response;
}

curl_close($ch);
