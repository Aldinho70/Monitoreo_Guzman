<?php
declare(strict_types=1);

// API pública de prueba
$url = 'https://api.agify.io?name=Jesus';

// Inicializar cURL
$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
]);

$response = curl_exec($ch);

// Manejo de errores
if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);
    die("❌ Error en la petición cURL: $error");
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Mostrar resultados
echo "<h2>Resultado de la prueba de conexión</h2>";
echo "<strong>Código HTTP:</strong> $httpCode<br><br>";

$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("❌ Error al decodificar JSON");
}

echo "<pre>";
print_r($data);
echo "</pre>";
