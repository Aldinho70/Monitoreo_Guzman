<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'wialon.php';
date_default_timezone_set('America/Monterrey');

function safe($o, ...$p) {
    foreach ($p as $k) {
        if (!isset($o->$k)) return null;
        $o = $o->$k;
    }
    return $o;
}

$token = 'f930188f7a50def848ffb8f5132715e0BBD52AA236D31FEAF68835ABA39CE4885C4E541D';
$wialon = new Wialon();

try {

    if (!is_writable(__DIR__)) {
        throw new Exception("Sin permisos de escritura en carpeta");
    }

    $login = json_decode($wialon->login($token), true);
    if (isset($login['error'])) {
        throw new Exception("Error login Wialon");
    }

    $ecos = $wialon->core_search_items('{
        "spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},
        "force":1,
        "flags":4611686018427387903,
        "from":0,
        "to":0
    }');

    $json1 = json_decode($ecos);

    // === ARREGLOS ORIGINALES ===
    $arrcargados = [];
    $arrvacios = [];
    $arrcargando = [];
    $arrdescargando = [];
    $arrcargadosmov = [];
    $arrcargadosdet = [];
    $arrvaciosmov = [];
    $arrvaciosdet = [];
    $arrcargadosdead = [];
    $arrsinstatus = [];

    foreach ($json1->items ?? [] as $u) {

        $origen = null;
        $destino = null;
        $status = null;

        foreach ($u->flds ?? [] as $f) {
            if ($f->n === "1 ORIGEN")  $origen  = $f->v;
            if ($f->n === "2 DESTINO") $destino = $f->v;
        }

        foreach ($u->aflds ?? [] as $f) {
            if ($f->n === "1STATUSDASHBOARD") $status = $f->v;
        }

        $velocidad = safe($u, 'pos', 's') ?? 0;
        $online    = $u->netconn ?? 0;

        $arrarmado = [
            "Unidad" => $u->nm ?? null,
            "Origen" => $origen,
            "Destino" => $destino,
            "Ultimo reporte" => date("Y-m-d H:i:s", $u->t ?? time()),
            "Latitud" => safe($u, 'pos', 'y'),
            "longitud" => safe($u, 'pos', 'x'),
            "Velocidad" => $velocidad,
            "Voltaje" => safe($u, 'prms', 'pwr_ext', 'v'),
            "Online" => $online,
            "Status" => $status
        ];

        // === MISMA LOGICA ORIGINAL ===

        if ($status === "VACIO") {
            $arrvacios[] = $arrarmado;
        }

        if ($status === "CARGADO") {
            $arrcargados[] = $arrarmado;
        }

        if ($status === "CARGADO" && $online == 0) {
            $arrcargadosdead[] = $arrarmado;
        }

        if ($status === "CARGADO" && $velocidad > 2) {
            $arrcargadosmov[] = $arrarmado;
        }

        if ($status === "CARGADO" && $velocidad == 0) {
            $arrcargadosdet[] = $arrarmado;
        }

        if ($status === "VACIO" && $velocidad <= 2) {
            $arrvaciosdet[] = $arrarmado;
        }

        if ($status === "VACIO" && $velocidad > 2) {
            $arrvaciosmov[] = $arrarmado;
        }

        if ($status === "ESPERA DE CARGA") {
            $arrcargando[] = $arrarmado;
        }

        if ($status === "ESPERA DESCARGA") {
            $arrdescargando[] = $arrarmado;
        }

        if ($status === "ESPERA DESCARGA" && $velocidad >= 15) {
            $arrsinstatus[] = $arrarmado;
        }

        if ($status === "ESPERA DE CARGA" && $velocidad >= 15) {
            $arrsinstatus[] = $arrarmado;
        }
    }

    $wialon->logout();

    // === MISMOS ARCHIVOS QUE EL LEGACY ===
    file_put_contents('json_arrcargados.json',     json_encode($arrcargados));
    file_put_contents('json_arrvacios.json',       json_encode($arrvacios));
    file_put_contents('json_arrcargando.json',     json_encode($arrcargando));
    file_put_contents('json_arrdescargando.json',  json_encode($arrdescargando));
    file_put_contents('json_arrcargadosmov.json',  json_encode($arrcargadosmov));
    file_put_contents('json_arrcargadosdet.json',  json_encode($arrcargadosdet));
    file_put_contents('json_arrvaciosmov.json',    json_encode($arrvaciosmov));
    file_put_contents('json_arrvaciosdet.json',    json_encode($arrvaciosdet));
    file_put_contents('json_arrcargadosdead.json', json_encode($arrcargadosdead));
    file_put_contents('json_arrsinstatus.json',    json_encode($arrsinstatus));

    echo "OK";

} catch (Throwable $e) {
    echo $e->getMessage();
}
