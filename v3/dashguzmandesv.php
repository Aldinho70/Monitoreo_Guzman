<?php
include('../wialon.php');
require_once '../Utils/sendJson.php';

header("Content-Type: application/json");
set_time_limit(0);
date_default_timezone_set('Etc/GMT+6');
error_reporting(E_ERROR | E_PARSE); //error_reporting(0);

$wialon_api = new Wialon();

//USUARIO: GUZMAN DASHBOARD
$token = '36bcd0bff1677e00ca6b5f8e244cb1ab7BCF8EB9906A9621C99D02A71106231F528D03A6';
$result = $wialon_api->login($token);
$json = json_decode($result, true);
if (!isset($json['error'])) {
    $ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    $geos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_resource","propName":"geofences","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    $SID = $json["eid"];
    
    $json1 = json_decode($ecos);
    $Json2 = json_decode($geos,true);
    $hora_actual = date("Y-m-d H:i:s");
    $rid = $json2["items"][0]["id"];
    $arrdesvioruta = array();

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
        $online = $unidades->netconn;
       
        // para campos personalizados
        foreach ($unidades->flds as $campospers) {

            if ($campospers->n === "2 DESTINO")
                $destino = $campospers->v;

            if ($campospers->n === "1 ORIGEN"){
                $origen = $campospers->v;}
                if ($campospers->n === "1STATUSDASHBOARD"){
                $status = $campospers->v;}
        }

        foreach ($unidades->aflds as $camposADM) {
            if ($camposADM->n === "1STATUSDASHBOARD")
                $status = $camposADM->v;
        }

        $radius = 1;

        $zoneIds = array(
            "26760441" => array() // recurso
        );

        // Crea el array de parámetros para la solicitud
        $params = array(
            "spec" => array(
                "lat" => $latitud,
                "lon" => $longitud,
                "radius" => $radius,
                "zoneId" => $zoneIds
            )
        );

        // Convierte el array de parámetros a formato JSON
        $jsonParams = json_encode($params);

        // Define la URL de la solicitud con los parámetros y la sesión SID
        $url = "https://hst-api.wialon.com/wialon/ajax.html?svc=resource/get_zones_by_point&params=" . $jsonParams . "&sid=" . $SID;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $responsegeo = curl_exec($curl);
        curl_close($curl);

        // Convierte la respuesta de JSON a un array asociativo de PHP
        $data = json_decode($responsegeo, true);

        // OJO CON ESTA MADRE QUE AL USAR ARRAY PUSH TE OBLIGA A SACARLAS DE ESTA FORMA 
        foreach ($data as $gid) {
            $keys = array_keys($gid);
        }
        //var_dump($keys);

        $itemId2 = 26760441; // reemplaza con el ID del recurso que deseas obtener
        $col = $keys; // reemplaza con los IDs de las geocercas que deseas obtener
        //$flags = 0x1C; // puedes cambiar esto si deseas un formato de resultado diferente al predeterminado
        $flags2 = 0x10; // puedes cambiar esto si deseas un formato de resultado diferente al predeterminado

        $params2 = array(
            "itemId" => $itemId2,
            "col" => $col,
            "flags" => $flags2
        );

        $jsonParams2 = json_encode($params2);
        $url5 = "https://hst-api.wialon.com/wialon/ajax.html?svc=resource/get_zone_data&params=" . $jsonParams2 . "&sid=" . $SID;
        $curl5 = curl_init($url5);
        curl_setopt($curl5, CURLOPT_RETURNTRANSFER, true);
        $data2 = curl_exec($curl5);
        curl_close($curl5);

        $data2json = json_decode($data2, true);
        $geonames = array_column($data2json, 'n');

        foreach ($data2json as $geocercadef) {
            $geoname1 = $geocercadef["n"];
            $geocercaid = $geocercadef["id"];
            $geonameenco = json_encode($geoname1);
            //var_dump($geocercadef);
        }

        $geonmdeco = json_decode($geonmsenco);
        $geonmsenco = json_encode($geonmdeco);
        $geonames_string = implode(', ', $geonames);

        foreach ($geonames as $geos) {
            if (strpos($geos, $destino) !== false) {
                // La cadena "DESTINO" se encuentra en este elemento del array Geocerca
                $ruta = "en orden"; // "El destino $destino está dentro de la geocerca $geocerca";
                break; // detener la iteración después de encontrar una coincidencia
            } else {
                $ruta = "DESVIO DE RUTA";
            }
            if (strpos($geos, "PENSION CRUZ DENDHO") !== false) {
                // La cadena "DESTINO" se encuentra en este elemento del array Geocerca
                $ruta = "en orden"; // "El destino $destino está dentro de la geocerca $geocerca";
                break; // detener la iteración después de encontrar una coincidencia
            }
            if (strpos($geos, "PENSION EL TRIANGULO") !== false) {
                // La cadena "DESTINO" se encuentra en este elemento del array Geocerca
                $ruta = "en orden"; // "El destino $destino está dentro de la geocerca $geocerca";
                break; // detener la iteración después de encontrar una coincidencia
            }
        }

        $arrarmado = array(

            "Unidad" => $name,
            "Origen" => $origen,
            "Destino" => $destino,
            "Ultimo_Mensaje" => $diferencia,
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

        if ($status === "CARGADO" && $ruta === "DESVIO DE RUTA") {

            array_push($arrdesvioruta, $arrarmado);
        }
    }
    $wialon_api->logout();
} else {
    echo WialonError::error($json['error']);
}

// Codificando $arrdesvioruta en JSON
// $json_arrdesvioruta = json_encode($arrdesvioruta);
// file_put_contents('json_arrdesvioruta.json',$json_arrdesvioruta);

$responses[] = sendJson('json_arrdesvioruta.json', $arrdesvioruta);
print_r($responses);
?>