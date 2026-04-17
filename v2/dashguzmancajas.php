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

/* =========================
   WIALON
========================= */
include 'wialon.php';

$wialon = new Wialon();
$token  = 'd26901b3a1b754d30e264a18da1e24e37D546C592086F4464E3316BF1EE0EC4375D7DBAB';

$loginRaw = $wialon->login($token);
$login    = json_decode($loginRaw, true);

if (isset($login['error'])) {
    die('Error login Wialon: ' . WialonError::error($login['error']));
}

/* =========================
   CONSULTA UNIDADES
========================= */
$unitsRaw = $wialon->core_search_items(
    '{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}'
);

$units = json_decode($unitsRaw);

if (empty($units->items)) {
    die('No se encontraron unidades');
}

/* =========================
   ARRAYS RESULTADO
========================= */
$arrayCajas       = [];
$fallaTemperatura = [];

$horaActual = date("Y-m-d H:i:s");

/* =========================
   PROCESAMIENTO
========================= */
foreach ($units->items as $u) {

    if (empty($u->pos)) continue;

    $timestamp = $u->pos->t;
    $fechaUnidad = date("Y-m-d H:i:s", $timestamp);

    $diffMin = round((strtotime($horaActual) - strtotime($fechaUnidad)) / 60);

    $lat = $u->pos->y ?? null;
    $lon = $u->pos->x ?? null;
    $vel = $u->pos->s ?? 0;
    $online = $u->netconn ?? 0;

    // $voltaje = $u->prms->pwr_ext->v ?? ($u->prms->power->v / 1000 ?? null);
    $voltaje = '';

    $temperatura = null;
    $p = $u->lmsg->p ?? null;

    if ($p) {
        if (isset($p->temp_sens_0))      $temperatura = $p->temp_sens_0 / 10;
        elseif (isset($p->accum_0))      $temperatura = $p->accum_0 * 0.0625;
        elseif (isset($p->temp_0))       $temperatura = $p->temp_0;
        elseif (isset($p->temp1))        $temperatura = $p->temp1;
        elseif (isset($p->onewire1_temp))$temperatura = $p->onewire1_temp;
        elseif (isset($p->onewire1_data))$temperatura = $p->onewire1_data;
    }

    if ($temperatura > 55) $temperatura = null;

    $direccion = null;
    // if ($lat && $lon) {
    //     $url = 'https://geocode-maps.wialon.com/hst-api.wialon.com/gis_geocode?coords=[{"lon":'.$lon.',"lat":'.$lat.'}]&flags=45321&uid=25483957';
    //     $c = curl_init($url);
    //     curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    //     $direccion = curl_exec($c);
    //     curl_close($c);
    // }

    $row = [
        'Unidad'         => $u->nm,
        'Timestamp'      => $timestamp,
        'MinutosSinRep'  => $diffMin,
        'Direccion'      => $direccion,
        'Latitud'        => $lat,
        'Longitud'       => $lon,
        'Velocidad'      => $vel,
        'Voltaje'        => $voltaje,
        'Online'         => $online,
        'Temperatura'    => $temperatura
    ];

    if ($diffMin > 30) {
        $arrayCajas[] = $row;
    }

    if (!$temperatura) {
        $fallaTemperatura[] = $row;
    }
}

/* =========================
   ORDENAMIENTO
========================= */
usort($arrayCajas, fn($a,$b) => $b['Timestamp'] <=> $a['Timestamp']);
usort($fallaTemperatura, fn($a,$b) => $b['Timestamp'] <=> $a['Timestamp']);

/* =========================
   ENVÍO INTERCEPTOR
========================= */
$responses = [];
$responses[] = sendJson('json_arrcajas.json', $arrayCajas);
$responses[] = sendJson('json_arrfallatemp.json', $fallaTemperatura);

$wialon->logout();

/* =========================
   SALIDA FINAL
========================= */
header('Content-Type: application/json');
echo json_encode([
    'status' => 'finished',
    'archivos' => [
        'json_arrcajas.json',
        'json_arrfallatemp.json'
    ],
    'respuestas' => $responses
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
