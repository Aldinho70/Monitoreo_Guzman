<?php
include('../wialon.php');
require_once '../Utils/sendJson.php';

error_reporting(E_ERROR | E_PARSE);

$wialon_api = new Wialon();

$token = '36bcd0bff1677e00ca6b5f8e244cb1ab7BCF8EB9906A9621C99D02A71106231F528D03A6';
$result = $wialon_api->login($token);
$json = json_decode($result, true);
if (!isset($json['error'])) {
    $ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');

    //Eliminar
    // $geos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_resource","propName":"geofences","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');
    // $SID = $json["eid"];
    // $Json2 = json_decode($geos,true);
    // $rid = $json2["items"][0]["id"];

    $json1 = json_decode($ecos);

    $arrgeneral = array();
    $arrcargados = array();
    $arrvacios = array();
    $arrcargando = array();
    $arrdescargando = array();
    $arrcargadosmov = array();
    $arrcargadosdet = array();
    $arrvaciosmov = array();
    $arrvaciosdet = array();
    $arrcargadosdead = array();
    $arrsinstatus = array();

    foreach ($json1->items as $unidades) {
        $name = $unidades->nm;
        $id = $unidades->id;
        $timestamp = $unidades->t;
        date_default_timezone_set('America/Monterrey');
        $fecha = date("Y-m-d H:i:s", $timestamp);
        $longitud = $unidades->pos->x;
        $latitud = $unidades->pos->y;
        $altitud = $unidades->pos->z;
        $velocidad = $unidades->pos->s;
        $bateria = $unidades->prms->battery->v;
        $ignicion = $unidades->prms->ign->v;
        $voltaje = $unidades->prms->pwr_ext->v;
        $online = $unidades->netconn;

        // para campos personalizados
        foreach ($unidades->flds as $campospers) {

            if ($campospers->n === "2 DESTINO")
                $destino = $campospers->v;

            if ($campospers->n === "1 ORIGEN")
                $origen = $campospers->v;
        }

        foreach ($unidades->aflds as $camposADM) {

            if ($camposADM->n === "1STATUSDASHBOARD")
                $status = $camposADM->v;
        }

        $arrarmado = array(
            "Unidad" => $name,
            "Origen" => $origen,
            "Destino" => $destino,
            "Ultimo reporte" => $fecha,
            //"Direccion" => $direccion,
            "Latitud" => $latitud,
            "longitud" => $longitud,
            "Velocidad" => $velocidad,
            "Voltaje" => $voltaje,
            "Online" => $online,
            "Status" => $status,
            "Geocerca" => $geonames,
            "Status ruta" => $ruta
        );

        // para los vacios
        if ($status === "VACIO") {
            array_push($arrvacios, $arrarmado);
        }

        //para los cargados
        if ($status === "CARGADO") {
            array_push($arrcargados, $arrarmado);
        }
        //cargados sin reportar
        if ($status === "CARGADO" && $online === 0) {
            array_push($arrcargadosdead, $arrarmado);
        }
        //cargados en movimiento
        if ($status === "CARGADO" && $velocidad > 2) {
            array_push($arrcargadosmov, $arrarmado);
        }

        //cargados detenidos
        if ($status === "CARGADO" && $velocidad == 0) {
            array_push($arrcargadosdet, $arrarmado);
        }

        //vacios detenidos
        if ($status === "VACIO" && $velocidad <= 2) {
            array_push($arrvaciosdet, $arrarmado);
        }

        //vacios movimiento
        if ($status === "VACIO" && $velocidad > 2) {
            array_push($arrvaciosmov, $arrarmado);
        }

        //para los cargando
        if ($status === "ESPERA DE CARGA") {
            array_push($arrcargando, $arrarmado);
        }

        //para los descargando
        if ($status === "ESPERA DESCARGA") {
            array_push($arrdescargando, $arrarmado);
        }


        //para sin status
        if ($status === "ESPERA DESCARGA" && $velocidad >= 15) {
            array_push($arrsinstatus, $arrarmado);
        }

        if ($status === "ESPERA DE CARGA" && $velocidad >= 15) {
            array_push($arrsinstatus, $arrarmado);
        }

    }

    $wialon_api->logout();
} else {
    echo WialonError::error($json['error']);
}

echo '<pre>';

// $arrcargados
print_r("arrcargados");
// print_r($arrcargados);
// $json_arrcargados = json_encode($arrcargados);
// file_put_contents('json_arrcargados.json', $json_arrcargados);
$responses[] = sendJson('json_arrcargados.json', $arrcargados);
print_r($responses);


// $arrvacios
print_r("arrvacios");
// $json_arrvacios = json_encode($arrvacios);
// file_put_contents('json_arrvacios.json', $json_arrvacios);
// $responses[] = sendJson('json_arrvacios.json', $json_arrvacios);
$responses[] = sendJson('json_arrvacios.json', $arrvacios);
print_r($responses);

// $arrcargando
print_r("arrcargando");
// $json_arrcargando = json_encode($arrcargando);
// print_r($arrcargando);
// file_put_contents('json_arrcargando.json', $json_arrcargando);
// $responses[] = sendJson('json_arrcargando.json', $json_arrcargando);
$responses[] = sendJson('json_arrcargando.json', $arrcargando);
print_r($responses);


// $arrdescargando
print_r("arrdescargando");
// print_r($arrdescargando);
// $json_arrdescargando = json_encode($arrdescargando);
// file_put_contents('json_arrdescargando.json', $json_arrdescargando);
// $responses[] = sendJson('json_arrdescargando.json', $json_arrdescargando);
$responses[] = sendJson('json_arrdescargando.json', $arrdescargando);
print_r($responses);


// $arrcargadosmov
print_r("arrcargadosmov");
// print_r($arrcargadosmov);
// $json_arrcargadosmov = json_encode($arrcargadosmov);
// file_put_contents('json_arrcargadosmov.json', $json_arrcargadosmov);
$responses[] = sendJson('json_arrcargadosmov.json', $arrcargadosmov);
print_r($responses);


// $arrcargadosdet
print_r("arrcargadosdet");
// print_r($arrcargadosdet);
// $json_arrcargadosdet = json_encode($arrcargadosdet);
// file_put_contents('json_arrcargadosdet.json', $json_arrcargadosdet);
$responses[] = sendJson('json_arrcargadosdet.json', $arrcargadosdet);
print_r($responses);


// $arrvaciosmov
print_r("arrvaciosmov");
// print_r($arrvaciosmov);
// $json_arrvaciosmov = json_encode($arrvaciosmov);
// file_put_contents('json_arrvaciosmov.json', $json_arrvaciosmov);
$responses[] = sendJson('json_arrvaciosmov.json', $arrvaciosmov);
print_r($responses);


// $arrvaciosdet
print_r("arrvaciosdet");
// print_r($arrvaciosdet);
// $json_arrvaciosdet = json_encode($arrvaciosdet);
// file_put_contents('json_arrvaciosdet.json', $json_arrvaciosdet);
$responses[] = sendJson('json_arrvaciosdet.json', $arrvaciosdet);
print_r($responses);


// $arrcargadosdead
print_r("arrcargadosdead");
// print_r($arrcargadosdead);
// $json_arrcargadosdead = json_encode($arrcargadosdead);
// file_put_contents('json_arrcargadosdead.json', $json_arrcargadosdead);
$responses[] = sendJson('json_arrcargadosdead.json', $arrcargadosdead);
print_r($responses);


// $arrsinstatus
print_r("arrsinstatus");
// print_r($arrsinstatus);
// $json_arrsinstatus = json_encode($arrsinstatus);
// file_put_contents('json_arrsinstatus.json', $json_arrsinstatus);
$responses[] = sendJson('json_arrsinstatus.json', $arrsinstatus);
print_r($responses);

echo '</pre>';

?>