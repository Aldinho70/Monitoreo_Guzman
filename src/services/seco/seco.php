<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$url = "http://ws4cjdg.com/OPERACION_GUZMAN/seco.json";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        "error" => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

echo $response;