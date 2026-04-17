<?php
declare(strict_types=1);

header('Content-Type: application/json');

$token = '733a7307cd0dd55c139f57fcaa9269d3F2C3174113FA868A9CA730A6B29A073E52098058';

$url = 'https://hst-api.wialon.com/wialon/ajax.html';

$params = [
    'svc' => 'token/login',
    'params' => json_encode(['token' => $token])
];

$ch = curl_init($url . '?' . http_build_query($params));

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
]);


$response = curl_exec($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode([
        'error' => 'cURL error',
        'detail' => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

echo json_encode([
    'http_code' => $httpCode,
    'response' => $data,
    'sid' => $data['eid'] ?? null
], JSON_PRETTY_PRINT);
