<?php
// utils/sendJson.php>

/* =========================
   CONFIGURACIÓN INTERCEPTOR
========================= */
define('INTERCEPTOR_URL', 'https://ws4cjdg.com/OPERACION_GUZMAN/save_json.php');
define('INTERCEPTOR_KEY', 'GUZMAN_SECURE_KEY_2025');

/* =========================
   FUNCIÓN ENVÍO CON DEBUG
========================= */
function sendJson($filename, $data)
{
    $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE);

    $payload = [
        'key'      => INTERCEPTOR_KEY,
        'filename' => $filename,
        'content'  => $jsonContent
    ];

    $ch = curl_init(INTERCEPTOR_URL);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return [
            'file'  => $filename,
            'error' => $error
        ];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'file'      => $filename,
        'http_code' => $httpCode,
        'raw_reply' => $response
    ];
}
?>