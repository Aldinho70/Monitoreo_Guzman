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
   CONSULTA DE UNIDADES
========================= */
$unitsRaw = $wialon->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');

$units = json_decode($unitsRaw);

if (empty($units->items)) {
    die('No se encontraron unidades');
}

/* =========================
   ARRAYS RESULTADO
========================= */
$results = [
    'cargados'     => [],
    'vacios'       => [],
    'cargando'     => [],
    'descargando'  => [],
    'cargadosmov'  => [],
    'cargadosdet'  => [],
    'vaciosmov'    => [],
    'vaciosdet'    => [],
    'cargadosdead' => [],
    'sinstatus'    => []
];

/* =========================
   PROCESAMIENTO
========================= */
foreach ($units->items as $u) {

    $origen  = '';
    $destino = '';
    $status  = '';

    $pos       = $u->pos ?? null;
    $velocidad = $pos->s ?? 0;
    $online    = $u->netconn ?? 0;

    if (!empty($u->flds)) {
        foreach ($u->flds as $f) {
            if ($f->n === '1 ORIGEN')  $origen  = $f->v;
            if ($f->n === '2 DESTINO') $destino = $f->v;
        }
    }

    if (!empty($u->aflds)) {
        foreach ($u->aflds as $a) {
            if ($a->n === '1STATUSDASHBOARD') {
                $status = $a->v;
                break;
            }
        }
    }

    $row = [
        'Unidad'         => $u->nm,
        'Origen'         => $origen,
        'Destino'        => $destino,
        'UltimoReporte'  => '',
        'Latitud'        => $pos->y ?? null,
        'Longitud'       => $pos->x ?? null,
        'Velocidad'      => $velocidad,
        'Online'         => $online,
        'Status'         => $status
    ];

    switch ($status) {
        case 'VACIO':
            $results['vacios'][] = $row;
            ($velocidad > 2)
                ? $results['vaciosmov'][] = $row
                : $results['vaciosdet'][] = $row;
            break;

        case 'CARGADO':
            $results['cargados'][] = $row;
            if ($online == 0)  $results['cargadosdead'][] = $row;
            if ($velocidad > 2) $results['cargadosmov'][] = $row;
            if ($velocidad == 0) $results['cargadosdet'][] = $row;
            break;

        case 'ESPERA DE CARGA':
            $results['cargando'][] = $row;
            if ($velocidad >= 15) $results['sinstatus'][] = $row;
            break;

        case 'ESPERA DESCARGA':
            $results['descargando'][] = $row;
            if ($velocidad >= 15) $results['sinstatus'][] = $row;
            break;
    }
}

/* =========================
   ENVÍO Y SALIDA DEBUG
========================= */
echo '<pre>';
echo print_r($results);
echo '</pre>';

$responses = [];

// foreach ($results as $name => $data) {
//     $responses[] = sendJson("json_arr{$name}.json", $data);
// }

// $wialon->logout();

// header('Content-Type: application/json');
// echo json_encode([
//     'status'    => 'finished',
//     'archivos'  => array_keys($results),
//     'respuestas'=> $responses
// ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
