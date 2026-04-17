<?php

/* =========================
   CONFIGURACIÓN DEBUG
========================= */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Monterrey');

/* =========================
   CONFIGURACIÓN INTERCEPTOR
========================= */
define('INTERCEPTOR_URL', 'https://ws4cjdg.com/OPERACION_GUZMAN/save_json.php');
define('INTERCEPTOR_KEY', 'GUZMAN_SECURE_KEY_2025');

/* =========================
   FUNCIÓN ENVÍO JSON
========================= */
function sendJson($filename, $data)
{
    $payload = [
        'key'      => INTERCEPTOR_KEY,
        'filename' => $filename,
        'content'  => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
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
        return ['error' => $error];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'http_code' => $httpCode,
        'response'  => $response
    ];
}

/* =========================
   WIALON
========================= */
include 'wialon.php';

$startTime   = microtime(true);
$horaActual  = date('Y-m-d H:i:s');
$resultado   = [];

try {

    $wialon = new Wialon();
    $token  = 'd26901b3a1b754d30e264a18da1e24e37D546C592086F4464E3316BF1EE0EC4375D7DBABclea';

    $loginRaw = $wialon->login($token);
    $login    = json_decode($loginRaw, true);

    if (isset($login['error'])) {
        throw new Exception(WialonError::error($login['error']));
    }

    /* =========================
       CONSULTA UNIDADES
    ========================= */
    $unitsRaw = $wialon->core_search_items(
        '{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}'
    );

    $units = json_decode($unitsRaw);

    if (empty($units->items)) {
        throw new Exception('No se encontraron unidades');
    }

    /* =========================
       PROCESAMIENTO
    ========================= */
    foreach ($units->items as $u) {

        if (empty($u->pos->t)) continue;

        $timestampUnidad = $u->pos->t;
        $fechaUnidad     = date('Y-m-d H:i:s', $timestampUnidad);

        $diffMin = round((strtotime($horaActual) - strtotime($fechaUnidad)) / 60);

        // Ignorar unidades con reporte reciente
        if ($diffMin <= 31) continue;

        $horas = floor($diffMin / 60);
        $dias  = floor($horas / 24);

        $diferencia = "{$dias} dias, " .
                      ($horas % 24) . " horas y " .
                      ($diffMin % 60) . " minutos";

        $resultado[] = [
            'Unidad'            => $u->nm,
            'timestamp'         => $timestampUnidad,
            'Diferencia_tiempo' => $diferencia,
            'Direccion'         => '',
            'Latitud'           => $u->pos->y ?? null,
            'Longitud'          => $u->pos->x ?? null,
            'Velocidad'         => $u->pos->s ?? 0,
            'Voltaje'           => $u->prms->pwr_ext->v ?? null
        ];
    }

    /* =========================
       ORDENAR POR TIMESTAMP
    ========================= */
    usort($resultado, function ($a, $b) {
        return $b['timestamp'] <=> $a['timestamp'];
    });

    /* =========================
       ENVÍO INTERCEPTOR
    ========================= */
    $envio = sendJson('json_arrdoblessinreportar.json', $resultado);

    $wialon->logout();

    $response = [
        'status'      => 'ok',
        'hora'        => $horaActual,
        'total'       => count($resultado),
        'interceptor' => $envio,
        'tiempo_seg'  => round(microtime(true) - $startTime, 3)
    ];

} catch (Exception $e) {

    $response = [
        'status' => 'error',
        'error'  => $e->getMessage()
    ];
}

/* =========================
   SALIDA
========================= */
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
