<?php
declare(strict_types=1);

set_time_limit(0);
ini_set('max_execution_time', '0');
error_reporting(E_ALL);

header("Content-Type: application/json");
date_default_timezone_set('Etc/GMT+6');

require_once 'wialon.php';

function logmsg($m) {
    error_log('[WIALON] ' . $m);
}

function getTemp($u) {
    $p = $u->lmsg->p ?? null;
    if (!$p) return null;

    $candidates = [
        fn($p) => isset($p->temp_sens_0) ? $p->temp_sens_0 / 10 : null,
        fn($p) => isset($p->accum_0) ? $p->accum_0 * 0.0625 : null,
        fn($p) => $p->temp_0 ?? null,
        fn($p) => $p->temp1 ?? null,
        fn($p) => $p->onewire1_temp ?? null,
        fn($p) => $p->onewire1_data ?? null,
    ];

    foreach ($candidates as $fn) {
        $t = $fn($p);
        if ($t !== null && $t <= 55) return $t;
    }
    return null;
}

function diffHuman(int $ts, int $now): string {
    $min = intdiv($now - $ts, 60);
    $h = intdiv($min, 60);
    $d = intdiv($h, 24);
    return "{$d} dias, " . ($h % 24) . " horas y " . ($min % 60) . " minutos";
}

try {

    $wialon = new Wialon();
    $token = '551527f22ace2479fecc70da76114c827E106EC2C14E6E68FC6778B8C33AE8FD2AAF9F7D';

    logmsg("Login Wialon...");
    $login = json_decode($wialon->login($token), true);

    if (isset($login['error'])) {
        throw new Exception("Login error: " . $login['error']);
    }

    $now = time();

    // FLAGS m¨ªnimos necesarios: pos, params, last msg, custom fields
    $flags = 1 | 1024 | 4096 | 8192;

    $query = json_encode([
        "spec" => [
            "itemsType" => "avl_unit",
            "propName" => "sys_name",
            "propValueMask" => "*",
            "sortType" => "sys_name"
        ],
        "force" => 1,
        "flags" => $flags,
        "from" => 0,
        "to" => 0
    ]);

    logmsg("Consultando unidades...");
    $resp = json_decode($wialon->core_search_items($query));

    $arraycajas = [];
    $fallatemperatura = [];

    foreach ($resp->items ?? [] as $u) {

        $pos = $u->pos ?? null;
        if (!$pos) continue;

        $timestamp = (int)$pos->t;
        $diffMin = intdiv($now - $timestamp, 60);

        $voltaje = $u->prms->pwr_ext->v ?? ($u->prms->power->v ?? null);
        if ($voltaje) $voltaje = $voltaje / 1000;

        $arr = [
            "Unidad" => $u->nm,
            "timestamp" => $timestamp,
            "Ultimo_mensaje" => diffHuman($timestamp, $now),
            "Direccion" => "", // geocode removido
            "Latitud" => $pos->y,
            "longitud" => $pos->x,
            "Velocidad" => $pos->s ?? 0,
            "Voltaje" => $voltaje,
            "Online" => $u->netconn ?? 0,
            "Temperatura" => getTemp($u)
        ];

        if ($diffMin > 30) $arraycajas[] = $arr;
        if ($arr["Temperatura"] === null) $fallatemperatura[] = $arr;
    }

    usort($arraycajas, fn($a,$b) => $b["timestamp"] <=> $a["timestamp"]);
    usort($fallatemperatura, fn($a,$b) => $b["timestamp"] <=> $a["timestamp"]);

    file_put_contents('json_arrcajas.json', json_encode($arraycajas));
    file_put_contents('json_arrfallatemp.json', json_encode($fallatemperatura));

    $wialon->logout();

    echo json_encode([
        "cajas" => $arraycajas,
        "fallas_temp" => $fallatemperatura,
        "total_unidades" => count($resp->items ?? [])
    ]);

} catch (Throwable $e) {
    logmsg($e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
