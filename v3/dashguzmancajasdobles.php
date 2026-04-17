<?php
include('../wialon.php');
require_once '../Utils/sendJson.php';

header("Content-Type: application/json");
date_default_timezone_set('Etc/GMT+6');
error_reporting(E_ERROR | E_PARSE); //error_reporting(0);

$wialon_api = new Wialon();

//USUARIO: CAJAS GUZMAN DASHBOARD
$token = 'ad6ea3436a9d44b972f9a296dac25238D147A314A8ED671AAB29E8B257BB96F1DD538D9A';

$result = $wialon_api->login($token);

$json = json_decode($result, true);
if (!isset($json['error'])) {
    $ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    
    $json1 = json_decode($ecos);
    
    $arraycajas = array();
    $hora_actual = date("Y-m-d H:i:s");

    foreach ($json1->items as $unidades) {
        $name = $unidades->nm;
        $id = $unidades->id;
        $timestamp = $unidades->pos->t;
        $fechaunidad = date("Y-m-d H:i:s", $timestamp);
        $diferencia_minutos = round((strtotime($hora_actual) - strtotime($fechaunidad)) / 60);
        $diferencia_horas = floor($diferencia_minutos / 60);
        $diferencia_minutos_restantes = $diferencia_minutos % 60;
        $diferencia_dias = floor($diferencia_horas / 24);
        $diferencia_horas_restantes = $diferencia_horas % 24;
        $diferencia = $diferencia_dias . " dias, " . $diferencia_horas_restantes . " horas y " . $diferencia_minutos_restantes . " minutos";
        $longitud = $unidades->pos->x;
        $latitud = $unidades->pos->y;
        $altitud = $unidades->pos->z;
        $velocidad = $unidades->pos->s;
        $bateria = $unidades->prms->battery->v;
        $ignicion = $unidades->prms->ign->v;
        $voltaje = $unidades->prms->pwr_ext->v;
        $direccion = '';

        if (is_null($voltaje)) {
            $voltaje = $unidades->prms->power->v / 1000;
        }

        $arrarmado = array(
            "Unidad" => $name,
            "timestamp"=> $timestamp,
            "Ultimo_mensaje" => $diferencia,
            "Direccion" => $direccion,
            "Latitud" => $latitud,
            "longitud" => $longitud,
            "Velocidad" => $velocidad,
            "Voltaje" => $voltaje,
            "Online" => $online,
            "Temperatura" => $temperatura
        );

        if ($diferencia_minutos > 31) {
            array_push($arraycajas, $arrarmado);
        }
    }
    $wialon_api->logout();
} else {
    echo WialonError::error($json['error']);
}

function compararPorUltimoMensaje($a, $b) {
    return strcmp($b["timestamp"], $a["timestamp"]);
}
usort($arraycajas, 'compararPorUltimoMensaje');

// Codificando $arrsinstatus en JSON
// $json_arrscajasd = json_encode($arraycajas);
// file_put_contents('json_arrcajasd.json', $json_arrscajasd);

$responses[] = sendJson('json_arrcajasd.json', $arraycajas);
print_r($responses);

?>