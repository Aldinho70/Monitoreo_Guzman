<?php
$time_start = microtime(true); // capturar tiempo de inicio
header("Content-Type: application/json");
set_time_limit(0);
ini_set('memory_limit', '2048M');

error_reporting(E_ERROR | E_PARSE); //error_reporting(0);

include('../wialon.php');
require_once '../Utils/sendJson.php';
$wialon_api = new Wialon();

$token = '36bcd0bff1677e00ca6b5f8e244cb1ab7BCF8EB9906A9621C99D02A71106231F528D03A6';
$result = $wialon_api->login($token);
$json = json_decode($result, true);
if (!isset($json['error'])) {
    $ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    
    $SID = $json["eid"];




    $json1 = json_decode($ecos);
    $Json2 = json_decode($geos, true);
    //var_dump($Json2); 
    $rid = $json2["items"][0]["id"];
    $arrtractos = array();




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
         $url3 = 'https://geocode-maps.wialon.com/hst-api.wialon.com/gis_geocode?coords=[{"lon":'.$longitud.',"lat":'.$latitud.'}]&flags=45321&uid=25483957';
        //$direccion = file_get_contents($url3)


        $curl = curl_init($url3);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $direccion = curl_exec($curl);
        curl_close($curl);



        // para campos personalizados
        foreach ($unidades->flds as $campospers) {

            if ($campospers->n === "2 DESTINO"){
            $destino = $campospers->v;}

            if ($campospers->n === "1 ORIGEN")
            {$origen = $campospers->v;}

            if ($campospers->n === "CAJA")
            $caja = $campospers->v;

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
            "Direccion" => $direccion,
            "Latitud" => $latitud,
            "longitud" => $longitud,
            "Velocidad" => $velocidad,
            "Caja" => $caja,
            "Voltaje" => $voltaje,
            "Online" => $online,
            "Status" => $status,
            "Geocerca" => $geonames,
            "Status ruta" => $ruta
        );




        if ($status === "CARGADO") {

            array_push($arrtractos, $arrarmado);        }
       
    
    }

    $wialon_api->logout();
} else {
    echo WialonError::error($json['error']);
}

//print_r($arrtractos);


    $wialon_api2 = new Wialon();

    // old username and password login is deprecated, use token login
    $token2 = '36bcd0bff1677e00ca6b5f8e244cb1ab7BCF8EB9906A9621C99D02A71106231F528D03A6';
    $result2 = $wialon_api2->login($token2);
    $json2 = json_decode($result2, true);
    if (!isset($json2['error'])) {
        //$ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
        $cajas = $wialon_api2->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":1049641,"from":0,"to":0}');
        //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');
        $SID2 = $json2["eid"];







$json3 = json_decode($cajas);

    //var_dump($json3);

    $arrcajas = array();
foreach($json3->items as $cajasguzman){


            $cajaname = $cajasguzman->nm;
            $temperaturagzm = isset($cajasguzman->prms->temp_sens_0->v) ? $cajasguzman->prms->temp_sens_0->v / 10 ."°C" : null;

            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->accum_0->v) ? $cajasguzman->prms->accum_0->v * 0.0625 ."°C": null;
            }
            
            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->temp_0->v) ? $cajasguzman->prms->temp_0->v ."°C": null;
            }
            
            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->temp1->v) ? $cajasguzman->prms->temp1->v ."°C": null;
            }
            
            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->onewire1_temp->v) ? $cajasguzman->prms->onewire1_temp->v ."°C": null;
            }

            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->onewire1_data->v) ? $cajasguzman->prms->onewire1_data->v ."°C": null;
            }
        $arrcajasarmado =array(
            "Caja"=> $cajaname,
            "Temperatura"=>$temperaturagzm



        );
array_push($arrcajas,$arrcajasarmado);
    }


    $wialon_api2->logout();
} else {
    echo WialonError::error($json2['error']);
}


//print_r($arrcajas);

/*

$arrdef = array();

foreach($arrtractos as $paquete){
    $tractoname = $paquete["Unidad"];
$tractocaja = $paquete["Caja"];

    foreach($arrcajas as $paqcaja){
$cajadef = $paqcaja["Caja"];

    }

if($tractocaja == $cajadef)
{
        $paquete["Temperatura"] = $paqcaja["Temperatura"];}


    $combo = array(
    "unidad" =>$tractoname,
    "caja" =>$tractocaja,
    "temperatura de caja" => $temp
    
);
array_push($arrdef,$combo);
}
*/
/*
// Nuevo array donde se almacenarán los resultados
$nuevoArray = array();

// Recorrer el array 1
foreach ($arrtractos as $objeto1) {
    $cajat = $objeto1["Caja"];

    // Buscar una coincidencia en el array 2
    foreach ($arrcajas as $objeto2) {
        $cajan = $objeto2["Caja"];

        // Comprobar si hay una coincidencia en el valor de "Caja"
        if ($cajat === $cajan) {
            // Asignar la temperatura correspondiente
            $objeto1["Temperatura"] = $objeto2["Temperatura"];
            
        }
    }

    // Agregar el objeto actualizado al nuevo array
    $nuevoArray[] = $objeto1;
}

// Mostrar el nuevo array con las temperaturas asignadas
print_r($nuevoArray);



print_r($arrdef);
*/

/*

// Nuevo array donde se almacenarán los resultados
$nuevoArray = array();

// Recorrer el array 1
foreach ($arrtractos as $objeto1) {
    $caja1 = $objeto1["Caja"];

    // Buscar una coincidencia en el array 2
    foreach ($arrcajas as $objeto2) {
        $caja2 = $objeto2["Caja"];

        // Comprobar si hay una coincidencia en el valor de "Caja"
        if ($caja1 === $caja2) {$temp = $objeto2["Temperatura"];}
            // Crear un nuevo objeto con las propiedades y valores actualizados
            $nuevoObjeto = array(
                "Unidad" => $objeto1["Unidad"],
                "Latitud" => $objeto1["Latitud"],
                "longitud" => $objeto1["longitud"],
                "Caja" => $objeto1["Caja"],
                "Velocidad" => $objeto1["Velocidad"],
                "Voltaje" => $objeto1["Voltaje"],
                "Online" => $objeto1["Online"],
                "Temperatura" => $objeto2["Temperatura"]
            );

            // Agregar el nuevo objeto al nuevo array
            $nuevoArray[] = $nuevoObjeto;
            break;
        
    }
}

// Mostrar el nuevo array con las temperaturas asignadas
print_r($nuevoArray);


*/

/*
foreach ($tractos as &$tracto) {
    $caja = $tracto['Caja'];
    if (isset($arrcajas[$caja])) {
        $arrcajas[$caja] = array_merge($arrcajas[$caja], $tracto);
    }
}

print_r($arrcajas);
*/






foreach ($arrtractos as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajas, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajas[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];

        $arrtractos[$key]["Temperatura"] = $temperaturazzz;
    }
}

// Codificando $arrdesvioruta en JSON
// $json_arrcargados = json_encode($arrtractos);
// file_put_contents('json_arrcargadosc.json', $json_arrcargados);

$responses[] = sendJson('json_arrcargadosc.json', $arrtractos);
print_r($responses);

//print_r($arrtractos);










// Nuevo array donde se almacenarán los resultados
$nuevoArray = array();

// Recorrer el array 1
for ($i = 0; $i < count($arrtractos); $i++) {
    $caja1 = $arrtractos[$i]["Caja"];
    $temperaturaAsignada = false; // Variable para controlar si se asignó la temperatura

    // Buscar una coincidencia en el array 2
    for ($j = 0; $j < count($arrcajas); $j++) {
        $caja2 = $arrcajas[$j]["Caja"];

        // Comprobar si hay una coincidencia en el valor de "Caja"
        if ($caja1 === $caja2) {
            $arrtractos[$i]["Temperatura"] = $arrcajas[$j]["Temperatura"];
            $temperaturaAsignada = true; // Se asignó la temperatura
            
        }
    }

    // Si no se asignó la temperatura, mantener el objeto original
    if (!$temperaturaAsignada) {
        $nuevoArray[] = $arrtractos[$i];
    } else {
        $nuevoArray[] = $arrtractos[$i];
    }
}


// Mostrar el nuevo array con las temperaturas asignadas
print_r($nuevoArray);

$time_end = microtime(true); // capturar tiempo de finalización

$time = $time_end - $time_start; // calcular tiempo total

echo "El script tardó $time segundos en ejecutarse.";
?>