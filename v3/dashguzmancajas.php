<?php
include('../wialon.php');
require_once '../Utils/sendJson.php';

header("Content-Type: application/json");
date_default_timezone_set('Etc/GMT+6');

error_reporting(E_ERROR | E_PARSE); //error_reporting(0);

$hora_actual = date("Y-m-d H:i:s");

$wialon_api = new Wialon();

$token = '551527f22ace2479fecc70da76114c824578A38707D4EDF9A33EFF9BC16593C8679E0AE7';

$result = $wialon_api->login($token);
$json = json_decode($result, true);
if (!isset($json['error'])) {
    $ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    
    $arraycajas = array();
    $fallatemperatura = array();

    $json1 = json_decode($ecos);

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

        $temperatura = NULL;
        $longitud = $unidades->pos->x;
        $latitud = $unidades->pos->y;
        $altitud = $unidades->pos->z;
        $velocidad = $unidades->pos->s;
        $bateria = $unidades->prms->battery->v;
        $ignicion = $unidades->prms->ign->v;
        $voltaje = $unidades->prms->pwr_ext->v;
        $direccion = '';
        
        foreach($unidades->flds as $campospers)
        {
            if($campospers->n === "EQUIPO"){
                $equipo = $campospers->v;
            }
        }

        if (is_null($voltaje)) {
            $voltaje = $unidades->prms->power->v / 1000;
        }
        $online = $unidades->netconn;
        $temperatura = isset($unidades->lmsg->p->temp_sens_0) ? $unidades->lmsg->p->temp_sens_0 / 10 : null;

        if (is_null($temperatura)) {
            $temperatura = isset($unidades->lmsg->p->accum_0) ? $unidades->lmsg->p->accum_0 * 0.0625 : null;
        }
        
        if (is_null($temperatura)) {
            $temperatura = isset($unidades->lmsg->p->temp_0) ? $unidades->lmsg->p->temp_0 : null;
        }
        
        if (is_null($temperatura)) {
            $temperatura = isset($unidades->lmsg->p->temp1) ? $unidades->lmsg->p->temp1 : null;
        }
        
        if (is_null($temperatura)) {
            $temperatura = isset($unidades->lmsg->p->onewire1_temp) ? $unidades->lmsg->p->onewire1_temp : null;
        }

        if (is_null($temperatura)) {
            $temperatura = isset($unidades->lmsg->p->onewire1_data) ? $unidades->lmsg->p->onewire1_data : null;
        }
        if ($temperatura >55) {
            $temperatura = null;
        }
        
        $arrarmado = array(
            "Unidad" => $name,
            "timestamp" => $timestamp,
            "Ultimo_mensaje" => $diferencia,
            "Direccion" => $direccion,
            "Latitud" => $latitud,
            "longitud" => $longitud,
            "Velocidad" => $velocidad,
            "Voltaje" => $voltaje,
            "Online" => $online,
            "Temperatura" => $temperatura
        );

        if ($diferencia_minutos > 30) {
            array_push($arraycajas, $arrarmado);
        }

        if (!$temperatura) {
            array_push($fallatemperatura, $arrarmado);
        }
    }

    $wialon_api->logout();
} else {
    echo WialonError::error($json['error']);
}

function compararPorUltimoMensaje($a, $b) {
    return strcmp($b["timestamp"], $a["timestamp"]);
}

// Ordenar el array por la clave "Ultimo_mensaje"
usort($arraycajas, 'compararPorUltimoMensaje');
usort($fallatemperatura, 'compararPorUltimoMensaje');

// $json_arrscajas = json_encode($arraycajas);
// file_put_contents('json_arrcajas.json', $json_arrscajas);

$responses[] = sendJson('json_arrcajas.json', $arraycajas);
print_r($responses);

// $json_fallacajas = json_encode($fallatemperatura);
// file_put_contents('json_arrfallatemp.json', $json_fallacajas);

$responses[] = sendJson('json_arrfallatemp.json', $fallatemperatura);
print_r($responses);

?>