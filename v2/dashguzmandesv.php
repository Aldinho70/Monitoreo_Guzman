<?php

/* =========================
   CONFIGURACIÓN DEBUG
========================= */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);

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
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['file' => $filename, 'error' => $error];
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
   CONSULTAS BASE
========================= */
$unitsRaw = $wialon->core_search_items(
    '{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}'
);

$resourcesRaw = $wialon->core_search_items(
    '{"spec":{"itemsType":"avl_resource","propName":"geofences","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}'
);

$units = json_decode($unitsRaw);
$resources = json_decode($resourcesRaw, true);

if (empty($units->items)) {
    die('No se encontraron unidades');
}

$SID = $login['eid'];
$resourceId = $resources['items'][0]['id'] ?? 26760441;

/* =========================
   RESULTADO
========================= */
$desvioRuta = [];
$horaActual = date("Y-m-d H:i:s");

/* =========================
   PROCESAMIENTO
========================= */
foreach ($units->items as $u) {

    if (empty($u->pos)) continue;

    $lat = $u->pos->y;
    $lon = $u->pos->x;
    $vel = $u->pos->s ?? 0;
    $online = $u->netconn ?? 0;
    $timestamp = $u->pos->t;

    $diffMin = round((strtotime($horaActual) - $timestamp) / 60);

    $origen = $destino = $status = null;

    foreach ($u->flds ?? [] as $f) {
        if ($f->n === '1 ORIGEN')  $origen  = $f->v;
        if ($f->n === '2 DESTINO') $destino = $f->v;
    }

    foreach ($u->aflds ?? [] as $a) {
        if ($a->n === '1STATUSDASHBOARD') {
            $status = $a->v;
            break;
        }
    }

    if ($status !== 'CARGADO') continue;

    /* =========================
       GEO-CERCAS POR PUNTO
    ========================= */
    $params = json_encode([
        'spec' => [
            'lat' => $lat,
            'lon' => $lon,
            'radius' => 1,
            'zoneId' => [$resourceId => []]
        ]
    ]);

    $url = "https://hst-api.wialon.com/wialon/ajax.html?svc=resource/get_zones_by_point&params={$params}&sid={$SID}";
    $resp = json_decode(file_get_contents($url), true);

    if (empty($resp)) continue;

    $zoneIds = [];
    foreach ($resp as $r) {
        $zoneIds = array_merge($zoneIds, array_keys($r));
    }

    if (empty($zoneIds)) continue;

    $params2 = json_encode([
        'itemId' => $resourceId,
        'col'    => $zoneIds,
        'flags'  => 0x10
    ]);

    $url2 = "https://hst-api.wialon.com/wialon/ajax.html?svc=resource/get_zone_data&params={$params2}&sid={$SID}";
    $zones = json_decode(file_get_contents($url2), true);

    $geoNames = array_column($zones, 'n');

    $ruta = 'DESVIO DE RUTA';
    foreach ($geoNames as $geo) {
        if (
            strpos($geo, $destino) !== false ||
            strpos($geo, 'PENSION CRUZ DENDHO') !== false ||
            strpos($geo, 'PENSION EL TRIANGULO') !== false
        ) {
            $ruta = 'EN ORDEN';
            break;
        }
    }

    if ($ruta !== 'DESVIO DE RUTA') continue;

    $desvioRuta[] = [
        'Unidad'        => $u->nm,
        'Origen'        => $origen,
        'Destino'       => $destino,
        'Timestamp'     => $timestamp,
        'MinutosSinRep' => $diffMin,
        'Latitud'       => $lat,
        'Longitud'      => $lon,
        'Velocidad'     => $vel,
        'Voltaje'       => $u->prms->pwr_ext->v ?? null,
        'Online'        => $online,
        'Status'        => $status,
        'Geocercas'     => $geoNames,
        'StatusRuta'    => $ruta
    ];
}

/* =========================
   ENVÍO INTERCEPTOR
========================= */
$responses = [];
$responses[] = sendJson('json_arrdesvioruta.json', $desvioRuta);

$wialon->logout();

/* =========================
   SALIDA FINAL
========================= */
header('Content-Type: application/json');
echo json_encode([
    'status' => 'finished',
    'archivo' => 'json_arrdesvioruta.json',
    'total' => count($desvioRuta),
    'respuesta' => $responses
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
