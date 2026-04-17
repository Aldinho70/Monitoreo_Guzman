<?php
$time_start = microtime(true); // capturar tiempo de inicio
header("Content-Type: application/json");

date_default_timezone_set('Etc/GMT+6');
$hora_actual = date("Y-m-d H:i:s");

//print_r("Carlos Alberto Sanchez Villegas.\n");

error_reporting(E_ERROR | E_PARSE); //error_reporting(0);

include('wialon.php');
$wialon_api = new Wialon();

// old username and password login is deprecated, use token login
//USUARIO: CAJAS GUZMAN DASHBOARD
$token = '551527f22ace2479fecc70da76114c827E106EC2C14E6E68FC6778B8C33AE8FD2AAF9F7D';
$result = $wialon_api->login($token);
$json = json_decode($result, true);
if (!isset($json['error'])) {
    $ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    //$geos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_resource","propName":"geofences","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');
    $SID = $json["eid"];

    $json1 = json_decode($ecos);


    //$Json2 = json_decode($geos,true);
    //var_dump($Json2); 
    //var_dump($json1);

    $arraycajas = array();

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

        //echo "Diferencia: " . $diferencia_dias . " d¨ªas, " . $diferencia_horas_restantes . " horas y " . $diferencia_minutos_restantes . " minutos";



        $longitud = $unidades->pos->x;
        $latitud = $unidades->pos->y;
        $altitud = $unidades->pos->z;
        $velocidad = $unidades->pos->s;
        $bateria = $unidades->prms->battery->v;
        $ignicion = $unidades->prms->ign->v;
        $voltaje = $unidades->prms->pwr_ext->v;

        $url3 = 'https://geocode-maps.wialon.com/hst-api.wialon.com/gis_geocode?coords=[{"lon":' . $longitud . ',"lat":' . $latitud . '}]&flags=45321&uid=25483957';
        //$direccion = file_get_contents($url3)


        $curl = curl_init($url3);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $direccion = curl_exec($curl);
        curl_close($curl);



        if (is_null($voltaje)) {
            $voltaje = $unidades->prms->power->v / 1000;
        }


        // $url3 = 'https://geocode-maps.wialon.com/hst-api.wialon.com/gis_geocode?coords=[{"lon":'.$longitud.',"lat":'.$latitud.'}]&flags=45321&uid=25483957';
        //$direccion = file_get_contents($url3)



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



// Funci¨®n de comparaci¨®n por la clave "Ultimo_mensaje"
function compararPorUltimoMensaje($a, $b) {
    return strcmp($b["timestamp"], $a["timestamp"]);
}

// Ordenar el array por la clave "Ultimo_mensaje"
usort($arraycajas, 'compararPorUltimoMensaje');


print_r($arraycajas);

// Codificando $arrsinstatus en JSON
$json_arrscajasd = json_encode($arraycajas);
file_put_contents('json_arrcajasd.json', $json_arrscajasd);



$time_end = microtime(true); // capturar tiempo de finalizaciÃ³n

$time = $time_end - $time_start; // calcular tiempo total

echo "El script tardÃ³ $time segundos en ejecutarse.";
?>