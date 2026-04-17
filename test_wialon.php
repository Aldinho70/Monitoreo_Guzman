<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
header('Content-Type: application/json');

date_default_timezone_set('America/Monterrey');

$token = 'f930188f7a50def848ffb8f5132715e0BBD52AA236D31FEAF68835ABA39CE4885C4E541D';
$base  = 'https://hst-api.wialon.com/wialon/ajax.html';

function debug($title, $data) {
    echo "\n\n===== {$title} =====\n";
    echo is_string($data) ? $data : json_encode($data, JSON_PRETTY_PRINT);
}

function curlRequest(string $url, array $post = []): array {
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($post),
        CURLOPT_TIMEOUT => 40,
        CURLOPT_VERBOSE => true,
        CURLOPT_HEADER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);

    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);

    $response = curl_exec($ch);
    $error    = curl_error($ch);
    $info     = curl_getinfo($ch);

    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);

    curl_close($ch);

    return [
        'response' => $response,
        'error' => $error,
        'info' => $info,
        'verbose' => $verboseLog
    ];
}

try {

    debug("PHP VERSION", phpversion());
    debug("CURL ENABLED", function_exists('curl_version') ? curl_version() : 'NO CURL');

    // 1️⃣ DNS TEST
    $ip = gethostbyname('hst-api.wialon.com');
    debug("DNS RESOLUTION", $ip);

    // 2️⃣ LOGIN
    $loginUrl = "{$base}?svc=token/login";

    $login = curlRequest($loginUrl, [
        "params" => json_encode(["token" => $token])
    ]);

    debug("CURL INFO LOGIN", $login['info']);
    debug("CURL VERBOSE LOGIN", $login['verbose']);

    if ($login['error']) {
        throw new Exception("CURL ERROR LOGIN: " . $login['error']);
    }

    $body = substr($login['response'], $login['info']['header_size']);
    debug("LOGIN RAW RESPONSE", $body);

    $jsonLogin = json_decode($body, true);

    if (!$jsonLogin) {
        throw new Exception("Respuesta no es JSON válido en login");
    }

    if (isset($jsonLogin['error'])) {
        throw new Exception("Wialon error login: " . $jsonLogin['error']);
    }

    $sid = $jsonLogin['eid'];
    debug("SID", $sid);

    // 3️⃣ SEARCH UNITS
    $searchUrl = "{$base}?svc=core/search_items&sid={$sid}";

    $params = [
        "spec" => [
            "itemsType" => "avl_unit",
            "propName" => "sys_name",
            "propValueMask" => "*",
            "sortType" => "sys_name"
        ],
        "force" => 1,
        "flags" => 4611686018427387903,
        "from" => 0,
        "to" => 0
    ];

    $units = curlRequest($searchUrl, [
        "params" => json_encode($params)
    ]);

    debug("CURL INFO UNITS", $units['info']);
    debug("CURL VERBOSE UNITS", $units['verbose']);

    if ($units['error']) {
        throw new Exception("CURL ERROR UNITS: " . $units['error']);
    }

    $unitsBody = substr($units['response'], $units['info']['header_size']);
    debug("UNITS RAW RESPONSE (first 2000 chars)", substr($unitsBody, 0, 2000));

    $jsonUnits = json_decode($unitsBody, true);

    if (!$jsonUnits) {
        throw new Exception("Respuesta unidades no es JSON válido");
    }

    debug("TOTAL UNIDADES", count($jsonUnits['items'] ?? []));

    // 4️⃣ LOGOUT
    $logoutUrl = "{$base}?svc=core/logout&sid={$sid}";
    curlRequest($logoutUrl);

    debug("RESULTADO FINAL", "OK - Comunicación exitosa con Wialon");

} catch (Throwable $e) {
    http_response_code(500);
    debug("ERROR GENERAL", $e->getMessage());
}
