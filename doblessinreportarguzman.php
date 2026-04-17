
<?php
$time_start = microtime(true); // capturar tiempo de inicio
header("Content-Type: application/json");
date_default_timezone_set('Etc/GMT+6');
$hora_actual = date("Y-m-d H:i:s");
//print_r("Carlos Alberto Sanchez Villegas.\n");
//Usuario: GUZMAN DASHBOARD
//Contraseña: gUZMAN123.
error_reporting(E_ERROR | E_PARSE); //error_reporting(0);

include('wialon.php');
$wialon_api = new Wialon();
print_r("test php");

// old username and password login is deprecated, use token login
//USUARIO: DOBLESGUZMAN
$token = '616ec32c39df3a8ca8675c2714354302CCC50277C0A2CD3DD1704C538EC2EDE66E2937B4';
$result = $wialon_api->login($token);
$json = json_decode($result, true);
if (!isset($json['error'])) {
    $ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    $geos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_resource","propName":"geofences","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');
    $SID = $json["eid"];

    $json1 = json_decode($ecos);
    $Json2 = json_decode($geos, true);
    //var_dump($Json2); 
    $rid = $json2["items"][0]["id"];

    $arrdobles = array();

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

        //echo "Diferencia: " . $diferencia_dias . " dias, " . $diferencia_horas_restantes . " horas y " . $diferencia_minutos_restantes . " minutos";


        $longitud = $unidades->pos->x;
        $latitud = $unidades->pos->y;
        $altitud = $unidades->pos->z;
        $velocidad = $unidades->pos->s;
        $bateria = $unidades->prms->battery->v;
        $voltaje = $unidades->prms->pwr_ext->v;
        $online = $unidades->netconn;
        $url3 = 'https://geocode-maps.wialon.com/hst-api.wialon.com/gis_geocode?coords=[{"lon":' . $longitud . ',"lat":' . $latitud . '}]&flags=45321&uid=25483957';
        //$direccion = file_get_contents($url3)


        $curl = curl_init($url3);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $direccion = json_decode(curl_exec($curl));
        curl_close($curl);







        $arrarmado = array(

            "Unidad" => $name,
            "timestamp" => $timestamp,
            "Diferencia_tiempo" => $diferencia,
            "Direccion" => $direccion,
            "Latitud" => $latitud,
            "longitud" => $longitud,
            "Velocidad" => $velocidad,
            "Ignicion" => $ignicion,
            "Voltaje"  =>$voltaje
        );



        // cargados sin reportar
        if ($diferencia_minutos > 31) {
            array_push($arrdobles, $arrarmado);
        }
    }


    $wialon_api->logout();
} else {
    echo WialonError::error($json['error']);
}



// Función de comparación por la clave "Ultimo_mensaje"
function compararPorUltimoMensaje($a, $b) {
    return strcmp($b["timestamp"], $a["timestamp"]);
}

// Ordenar el array por la clave "Ultimo_mensaje"
usort($arrdobles, 'compararPorUltimoMensaje');


// Codificando $arrsinreportar en JSON
$sinreportar = json_encode($arrdobles);
file_put_contents('json_arrdoblessinreportar.json', $sinreportar);

print_r($arrdobles);
$time_end = microtime(true); // capturar tiempo de finalizaci贸n

$time = $time_end - $time_start; // calcular tiempo total

echo "El script tard贸 $time segundos en ejecutarse.";
?>