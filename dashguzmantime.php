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
//USUARIO: GUZMAN DASHBOARD
$token = 'f930188f7a50def848ffb8f5132715e0BBD52AA236D31FEAF68835ABA39CE4885C4E541D';
$result = $wialon_api->login($token);
$json = json_decode($result, true);
if (!isset($json['error'])) {
    $ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    $geos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_resource","propName":"geofences","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');
    $SID = $json["eid"];

    $json1 = json_decode($ecos);
    $Json2 = json_decode($geos,true);
    //var_dump($Json2); 
    $rid = $json2["items"][0]["id"];

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
    $arrsinreportar = array();

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

$diferencia = $diferencia_dias . " dias, " . $diferencia_horas_restantes . " horas y ," . $diferencia_minutos_restantes . " minutos";

//echo "Diferencia: " . $diferencia_dias . " d��as, " . $diferencia_horas_restantes . " horas y " . $diferencia_minutos_restantes . " minutos";
        
        
        $longitud = $unidades->pos->x;
        $latitud = $unidades->pos->y;
        $altitud = $unidades->pos->z;
        $velocidad = $unidades->pos->s;
        $bateria = $unidades->prms->battery->v;
        $voltaje = $unidades->prms->pwr_ext->v;
        $online = $unidades->netconn;
        $url3 = 'https://geocode-maps.wialon.com/hst-api.wialon.com/gis_geocode?coords=[{"lon":'.$longitud.',"lat":'.$latitud.'}]&flags=45321&uid=25483957';
    //$direccion = file_get_contents($url3)


$curl = curl_init($url3);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$direccion = curl_exec($curl);
curl_close($curl);


                 $Doble = "inserte bien el campo";
                              $caja = "inserte bien el campo";
 
$campos = array();
        // para campos personalizados
        foreach ($unidades->flds as $campospers) {
            
            
            $nombre = $campospers->n;
            $valor = $campospers->v;
            $creadots = $campospers->ct;
            $ultmodts = $campospers->mt;
             $fechacreacion = date("Y-m-d H:i:s", $creadots);
             $fechamod = date("Y-m-d H:i:s", $ultmodts);
             
             
$diferencia_minutosc = round((strtotime($hora_actual) - strtotime($fechacreacion)) / 60);

$diferencia_horasc = floor($diferencia_minutosc / 60);
$diferencia_minutos_restantesc = $diferencia_minutosc % 60;

$diferencia_diasc = floor($diferencia_horasc / 24);
$diferencia_horas_restantesc = $diferencia_horasc % 24;

$diferenciac = $diferencia_diasc . " dias, " . $diferencia_horas_restantesc . " horas y " . $diferencia_minutos_restantesc . " minutos";



$diferencia_minutosm = round((strtotime($hora_actual) - strtotime($fechamod)) / 60);

$diferencia_horasm = floor($diferencia_minutosm / 60);
$diferencia_minutos_restantesm = $diferencia_minutosm % 60;

$diferencia_diasm = floor($diferencia_horasm / 24);
$diferencia_horas_restantesm = $diferencia_horasm % 24;

$diferenciam = $diferencia_diasm . " dias, " . $diferencia_horas_restantesm . " horas y " . $diferencia_minutos_restantesm . " minutos";
            
           
            if ($campospers->n === "1STATUSDASHBOARD"){
                $status = $campospers->v;}

            if ($campospers->n === "2 DESTINO")
               { $destino = $campospers->v;}

            if ($campospers->n === "1 ORIGEN")
                {$origen = $campospers->v;}
                

            if ($campospers->n === "CAJA"){
            $caja = $campospers->v;}
                
            if ($campospers->n === "Doble"){
                $Doble = $campospers->v;}


                
            if ($campospers->n === "EQUIPO"){
                $equipo = $campospers->v;}
                
                if ($campospers->n === "2 DESTINO" ){
                  $camposarr = array( 
                      "Unidad" => $name,
                      "Campo" =>$nombre,
                      "Valor" => $valor,
                      "Creado" => $diferenciac,
                      "Ult_Modificacion" => $diferenciam
                      );  
                       array_push($campos, $camposarr);}
                      
                                      if ($campospers->n === "1 ORIGEN" ){
                  $camposarr = array( 
                    "Unidad" => $name,
                      "Campo" =>$nombre,
                      "Valor" => $valor,
                      "Creado" => $diferenciac,
                      "Ult_Modificacion" => $diferenciam
                      );  
                       array_push($campos, $camposarr);
                      
                      
                                      }
                                      
                                      if ($campospers->n === "1STATUSDASHBOARD" ){
                  $camposarr = array( 
                    "Unidad" => $name,
                      "Campo" =>$nombre,
                      "Valor" => $valor,
                      "Creado" => $diferenciac,
                      "Ult_Modificacion" => $diferenciam
                      );  
                       array_push($campos, $camposarr);
                      
                      
                                      }
                
        }
        
         foreach ($unidades->aflds as $camposadm) {
            
            $nombre = $camposadm->n;
            $valor = $camposadm->v;

            if ($camposadm->n === "2 DESTINO")
               { $destino = $camposadm->v;}

            if ($camposadm->n === "1 ORIGEN")
                {$origen = $camposadm->v;}
                

            if ($camposadm->n === "CAJA"){
            $caja = $camposadm->v;}
                
            if ($camposadm->n === "Doble"){
                $Doble = $camposadm->v;}
                
            if ($camposadm->n === "EQUIPO"){
                $equipo = $camposadm->v;}
                
        }
            

        


        
        
if ($equipo === "RUPTELA") {
    $ignicion = $unidades->prms->in4->v ;
}

if ($equipo === "SUNTECH") {
    $ignicion = $unidades->prms->ign->v ;
}

if ($equipo === "QUECLINK") {
    $ignicion = $unidades->prms->ign->v ;
}
if ($equipo === "MEITRACK") {
    $ignicion = $unidades->prms->in2->v ;
}
if ($equipo === "CELLOCATOR") {
    $ignicion = $unidades->prms->in6->v ;
}
if ($equipo === "CALAMP") {
$ignicion = $unidades->prms->accum_2->v;
$bits = decbin($ignicion);

// Recorre cada bit y muestra el valor booleano
for ($i = 0; $i < strlen($bits); $i++) {
    $bit = $bits[$i];
    $ignicion = $bit == '1' ? 1 : 0;
}
    
}




        foreach ($unidades->aflds as $camposADM) {
            
            
            
            
                        
            $nombreA = $camposADM->n;
            $valorA = $camposADM->v;
            $creadotsA = $camposADM->ct;
            $ultmodtsA = $camposADM->mt;
             $fechacreacionA = date("Y-m-d H:i:s", $creadotsA);
             $fechamodA = date("Y-m-d H:i:s", $ultmodtsA);
             
             
$diferencia_minutoscA = round((strtotime($hora_actual) - strtotime($fechacreacionA)) / 60);

$diferencia_horascA = floor($diferencia_minutoscA / 60);
$diferencia_minutos_restantescA = $diferencia_minutoscA % 60;

$diferencia_diascA = floor($diferencia_horascA / 24);
$diferencia_horas_restantescA = $diferencia_horascA % 24;

$diferenciac = $diferencia_diasA . " dias, " . $diferencia_horas_restantesA . " horas y " . $diferencia_minutos_restantescA . " minutos";



$diferencia_minutosmA = round((strtotime($hora_actual) - strtotime($fechamodA)) / 60);

$diferencia_horasmA = floor($diferencia_minutosmA / 60);
$diferencia_minutos_restantesmA = $diferencia_minutosmA % 60;

$diferencia_diasmA = floor($diferencia_horasmA / 24);
$diferencia_horas_restantesmA = $diferencia_horasmA % 24;

$diferenciamA = $diferencia_diasmA . " dias, " . $diferencia_horas_restantesmA . " horas y " . $diferencia_minutos_restantesmA . " minutos";

            if ($camposADM->n === "1STATUSDASHBOARD"){
                $status = $camposADM->v;}
                
                
                if ($camposADM->n === "1STATUSDASHBOARD" ){
                  $camposarr = array( 
                    "Unidad" => $name,
                      "Campo" =>$nombreA,
                      "Valor" => $valorA,
                      "Creado" => $diferenciacA,
                      "Ult_Modificacion" => $diferenciamA
                      );  
                       array_push($campos, $camposarr);
                
        }}





        $arrarmado = array(

            "Unidad" => $name,
            "timestamp" => $timestamp,
            "Origen" => $origen,
            "Destino" => $destino,
            "Diferencia_tiempo" => $diferencia,
            "Direccion" => $direccion,
            "Latitud" => $latitud,
            "longitud" => $longitud,
            "Velocidad" => $velocidad,
            "Ignicion" =>$ignicion,
            "Doble"=>$Doble,
            "Campos" => $campos,
            "Caja"=>$caja,
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
     // cargados sin reportar
if ($status === "CARGADO" && $ignicion === 1 && $diferencia_minutos > 3) {
    array_push($arrcargadosdead, $arrarmado);
}
if ($status === "CARGADO" && $ignicion === 0 && $diferencia_minutos > 30) {
    array_push($arrcargadosdead, $arrarmado);
}

     // sin reportar
if ($ignicion === 1 && $diferencia_minutos > 3|| $ignicion === 0 && $diferencia_minutos > 30 ||$diferencia_minutos > 35) {
    array_push($arrsinreportar, $arrarmado);
    
}/*
if ($ignicion === 0 && $diferencia_minutos > 30) {
    array_push($arrsinreportar, $arrarmado);
}
*/


        
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
    echo "Error en Tractos: " . WialonError::error($json['error']);
}




    $wialon_api2 = new Wialon();

    // old username and password login is deprecated, use token login
    //USUARIO: CAJAS GUZMAN DASHBOARD
    $token2 = '551527f22ace2479fecc70da76114c827537E70538D2802E2F9D304A8582E3174B1E71B5';
    $result2 = $wialon_api2->login($token2);
    $json2 = json_decode($result2, true);
    if (!isset($json2['error'])) {
        //$ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
        $cajas = $wialon_api2->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
        //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');
        $SID2 = $json2["eid"];







$json3 = json_decode($cajas);

    //var_dump($json3);

    $arrcajass = array();
foreach($json3->items as $cajasguzman){

            $cajaname = $cajasguzman->nm;
   $timestampc = $cajasguzman->pos->t;
        
        $cajadate = date("Y-m-d H:i:s", $timestampc);     
           $diferencia_minutos2 = round((strtotime($hora_actual) - strtotime($cajadate)) / 60);

$diferencia_horas2 = floor($diferencia_minutos2 / 60);
$diferencia_minutos_restantes2 = $diferencia_minutos2 % 60;

$diferencia_dias2 = floor($diferencia_horas2 / 24);
$diferencia_horas_restantes2 = $diferencia_horas2 % 24;

$diferencia2 = $diferencia_dias2 . " dias, " . $diferencia_horas_restantes2 . " horas y " . $diferencia_minutos_restantes2 . " minutos";

//echo "Diferencia: " . $diferencia_dias . " d��as, " . $diferencia_horas_restantes . " horas y " . $diferencia_minutos_restantes . " minutos";
        
        
        
        if($diferencia_minutos2 < 30)
             {
                 $reportando = "SI";
                 
             }
             else
             {
                 $reportando = "NO";
                 
             }
             
             
            $temperaturagzm = isset($cajasguzman->prms->temp_sens_0->v) ? $cajasguzman->prms->temp_sens_0->v / 10 : null;

            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->accum_0->v) ? $cajasguzman->prms->accum_0->v * 0.0625 : null;
            }
            
            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->temp_0->v) ? $cajasguzman->prms->temp_0->v : null;
            }
            
            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->temp1->v) ? $cajasguzman->prms->temp1->v : null;
            }
            
            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->onewire1_temp->v) ? $cajasguzman->prms->onewire1_temp->v : null;
            }

            if (is_null($temperaturagzm)) {
                $temperaturagzm = isset($cajasguzman->prms->onewire1_data->v) ? $cajasguzman->prms->onewire1_data->v : null;
            }
            
        $arrcajasarmado =array(
            "Caja"=> $cajaname,
            "Temperatura"=>$temperaturagzm,
            "Reportando" =>$diferencia2


        );
array_push($arrcajass,$arrcajasarmado);
    }
    
//print_r($arrcajas);

    $wialon_api2->logout();
} else {
    echo "Error en cajas: " . WialonError::error($json2['error']);
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
// Nuevo array donde se almacenar��n los resultados
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

// Nuevo array donde se almacenar��n los resultados
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





foreach ($arrcargadosdead as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajass, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajass[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];
        $cajareportando = $datosCaja["Reportando"];
        $arrcargadosdead[$key]["Temperatura"] = $temperaturazzz;
                $arrcargadosdead[$key]["Caja_Reportando"] = $cajareportando;

    }
}

foreach ($arrcargados as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajass, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajass[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];
        $cajareportando = $datosCaja["Reportando"];
        $arrcargados[$key]["Temperatura"] = $temperaturazzz;
                $arrcargados[$key]["Caja_Reportando"] = $cajareportando;

    }
}

foreach ($arrcargadosdet as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajass, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajass[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];
        $cajareportando = $datosCaja["Reportando"];
        $arrcargadosdet[$key]["Temperatura"] = $temperaturazzz;
                $arrcargadosdet[$key]["Caja_Reportando"] = $cajareportando;

    }
}


foreach ($arrcargadosmov as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajass, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajass[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];
        $cajareportando = $datosCaja["Reportando"];
        $arrcargadosmov[$key]["Temperatura"] = $temperaturazzz;
                $arrcargadosmov[$key]["Caja_Reportando"] = $cajareportando;

    }
}

foreach ($arrvacios as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajass, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajass[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];
        $cajareportando = $datosCaja["Reportando"];
        $arrvacios[$key]["Temperatura"] = $temperaturazzz;
                $arrvacios[$key]["Caja_Reportando"] = $cajareportando;

    }
}


foreach ($arrvaciosdet as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajass, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajass[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];
        $cajareportando = $datosCaja["Reportando"];
        $arrvaciosdet[$key]["Temperatura"] = $temperaturazzz;
                $arrvaciosdet[$key]["Caja_Reportando"] = $cajareportando;

    }
}


foreach ($arrvaciosmov as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajass, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajass[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];
        $cajareportando = $datosCaja["Reportando"];
        $arrvaciosmov[$key]["Temperatura"] = $temperaturazzz;
                $arrvaciosmov[$key]["Caja_Reportando"] = $cajareportando;

    }
}


foreach ($arrsinstatus as $key => $tracto) {
    $caja = $tracto["Caja"];
    $indice = array_search($caja, array_column($arrcajass, "Caja"));

    if ($indice !== false) {
        $datosCaja = $arrcajass[$indice];
        $temperaturazzz = $datosCaja["Temperatura"];
        $cajareportando = $datosCaja["Reportando"];
        $arrsinstatus[$key]["Temperatura"] = $temperaturazzz;
                $arrsinstatus[$key]["Caja_Reportando"] = $cajareportando;

    }
}


/*
// Codificando $arrdesvioruta en JSON
$json_arrcongelado = json_encode($arrtractos);
file_put_contents('json_arrcongeladoc.json', $json_arrcongelado);

print_r($arrtractos);
*/







/*
// Nuevo array donde se almacenar��n los resultados
$nuevoArray = array();

// Recorrer el array 1
for ($i = 0; $i < count($arrtractos); $i++) {
    $caja1 = $arrtractos[$i]["Caja"];
    $temperaturaAsignada = false; // Variable para controlar si se asign�� la temperatura

    // Buscar una coincidencia en el array 2
    for ($j = 0; $j < count($arrcajas); $j++) {
        $caja2 = $arrcajas[$j]["Caja"];

        // Comprobar si hay una coincidencia en el valor de "Caja"
        if ($caja1 === $caja2) {
            $arrtractos[$i]["Temperatura"] = $arrcajas[$j]["Temperatura"];
            $temperaturaAsignada = true; // Se asign�� la temperatura
            
        }
    }

    // Si no se asign�� la temperatura, mantener el objeto original
    if (!$temperaturaAsignada) {
        $nuevoArray[] = $arrtractos[$i];
    } else {
        $nuevoArray[] = $arrtractos[$i];
    }
}
*/

// Mostrar el nuevo array con las temperaturas asignadas


$wialon_api3 = new Wialon();

// old username and password login is deprecated, use token login
//USUARIO: DOBLESGUZMAN
$token3 = '616ec32c39df3a8ca8675c27143543026C64B3EC520E4FAFC269056DD4882E492F2E68D3';
$result3 = $wialon_api3->login($token3);
$json3 = json_decode($result3, true);
if (!isset($json3['error'])) {
    //$ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    $dobles = $wialon_api3->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');
    //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');
    $SID3 = $json["eid"];







$json4 = json_decode($dobles);

//var_dump($json3);

$arrdobles = array();
foreach($json4->items as $dobles){


        $doblename = $dobles->nm;
        $dobletime = $dobles->pos->t;
        $doblereportandod = $dobles->netconn;
        
           $fechadoble = date("Y-m-d H:i:s", $dobletime);
        
        
           $diferencia_minutos3 = round((strtotime($hora_actual) - strtotime($fechadoble)) / 60);

$diferencia_horas3 = floor($diferencia_minutos3 / 60);
$diferencia_minutos_restantes3 = $diferencia_minutos3 % 60;

$diferencia_dias3 = floor($diferencia_horas3 / 24);
$diferencia_horas_restantes3 = $diferencia_horas3 % 24;

$diferencia3 = $diferencia_dias3 . " dias, " . $diferencia_horas_restantes3 . " horas y " . $diferencia_minutos_restantes3 . " minutos";

//echo "Diferencia: " . $diferencia_dias . " d��as, " . $diferencia_horas_restantes . " horas y " . $diferencia_minutos_restantes . " minutos";
        
        
        
        if($diferencia_minutos3 < 30)
        {
            $reportandodoble = "SI";
        }
        else
        {
            $reportandodoble = "NO";
        }
        
    $arrdoblesarmado =array(
        "Doble"=> $doblename,
        "Reportando"=> $diferencia3



    );
array_push($arrdobles,$arrdoblesarmado);
}

//print_r($arrcajas);


$wialon_api3->logout();
} else {
    echo "Error en dobles: " . WialonError::error($json3['error']);
}






foreach ($arrcargadosdead as $key2 => $tracto2) {
    $dobled = $tracto2["Doble"];
    $indiced = array_search($dobled, array_column($arrdobles, "Doble"));

    if ($indiced !== false) {
        $datosdoble = $arrdobles[$indiced];
        
        $doblereportando = $datosdoble["Reportando"];
        $arrcargadosdead[$key2]["Doble_Reportando"] = $doblereportando;

    }
}

foreach ($arrcargados as $key2 => $tracto2) {
    $dobled = $tracto2["Doble"];
    $indiced = array_search($dobled, array_column($arrdobles, "Doble"));

    if ($indiced !== false) {
        $datosdoble = $arrdobles[$indiced];
        
        $doblereportando = $datosdoble["Reportando"];
        $arrcargados[$key2]["Doble_Reportando"] = $doblereportando;

    }
}
foreach ($arrcargadosdet as $key2 => $tracto2) {
    $dobled = $tracto2["Doble"];
    $indiced = array_search($dobled, array_column($arrdobles, "Doble"));

    if ($indiced !== false) {
        $datosdoble = $arrdobles[$indiced];
        
        $doblereportando = $datosdoble["Reportando"];
        $arrcargadosdet[$key2]["Doble_Reportando"] = $doblereportando;

    }
}

foreach ($arrcargadosmov as $key2 => $tracto2) {
    $dobled = $tracto2["Doble"];
    $indiced = array_search($dobled, array_column($arrdobles, "Doble"));

    if ($indiced !== false) {
        $datosdoble = $arrdobles[$indiced];
        
        $doblereportando = $datosdoble["Reportando"];
        $arrcargadosmov[$key2]["Doble_Reportando"] = $doblereportando;

    }
}



foreach ($arrvacios as $key2 => $tracto2) {
    $dobled = $tracto2["Doble"];
    $indiced = array_search($dobled, array_column($arrdobles, "Doble"));

    if ($indiced !== false) {
        $datosdoble = $arrdobles[$indiced];
        
        $doblereportando = $datosdoble["Reportando"];
        $arrvacios[$key2]["Doble_Reportando"] = $doblereportando;

    }
}

foreach ($arrvaciosdet as $key2 => $tracto2) {
    $dobled = $tracto2["Doble"];
    $indiced = array_search($dobled, array_column($arrdobles, "Doble"));

    if ($indiced !== false) {
        $datosdoble = $arrdobles[$indiced];
        
        $doblereportando = $datosdoble["Reportando"];
        $arrvaciosdet[$key2]["Doble_Reportando"] = $doblereportando;

    }
}

foreach ($arrvaciosmov as $key2 => $tracto2) {
    $dobled = $tracto2["Doble"];
    $indiced = array_search($dobled, array_column($arrdobles, "Doble"));

    if ($indiced !== false) {
        $datosdoble = $arrdobles[$indiced];
        
        $doblereportando = $datosdoble["Reportando"];
        $arrvaciosmov[$key2]["Doble_Reportando"] = $doblereportando;

    }
}

foreach ($arrsinstatus as $key2 => $tracto2) {
    $dobled = $tracto2["Doble"];
    $indiced = array_search($dobled, array_column($arrdobles, "Doble"));

    if ($indiced !== false) {
        $datosdoble = $arrdobles[$indiced];
        
        $doblereportando = $datosdoble["Reportando"];
        $arrsinstatus[$key2]["Doble_Reportando"] = $doblereportando;

    }
}




print_r($arrcargados);








/*
// Nuevo array donde se almacenar��n los resultados
$nuevoArray = array();

// Recorrer el array 1
for ($i = 0; $i < count($arrtractos); $i++) {
    $caja1 = $arrtractos[$i]["Caja"];
    $temperaturaAsignada = false; // Variable para controlar si se asign�� la temperatura

    // Buscar una coincidencia en el array 2
    for ($j = 0; $j < count($arrcajas); $j++) {
        $caja2 = $arrcajas[$j]["Caja"];

        // Comprobar si hay una coincidencia en el valor de "Caja"
        if ($caja1 === $caja2) {
            $arrtractos[$i]["Temperatura"] = $arrcajas[$j]["Temperatura"];
            $temperaturaAsignada = true; // Se asign�� la temperatura
            
        }
    }

    // Si no se asign�� la temperatura, mantener el objeto original
    if (!$temperaturaAsignada) {
        $nuevoArray[] = $arrtractos[$i];
    } else {
        $nuevoArray[] = $arrtractos[$i];
    }
}
*/

// Mostrar el nuevo array con las temperaturas asignadas











/*
// Nuevo array donde se almacenar��n los resultados
$nuevoArray = array();

// Recorrer el array 1
for ($i = 0; $i < count($arrtractos); $i++) {
    $caja1 = $arrtractos[$i]["Caja"];
    $temperaturaAsignada = false; // Variable para controlar si se asign�� la temperatura

    // Buscar una coincidencia en el array 2
    for ($j = 0; $j < count($arrcajas); $j++) {
        $caja2 = $arrcajas[$j]["Caja"];

        // Comprobar si hay una coincidencia en el valor de "Caja"
        if ($caja1 === $caja2) {
            $arrtractos[$i]["Temperatura"] = $arrcajas[$j]["Temperatura"];
            $temperaturaAsignada = true; // Se asign�� la temperatura
            
        }
    }

    // Si no se asign�� la temperatura, mantener el objeto original
    if (!$temperaturaAsignada) {
        $nuevoArray[] = $arrtractos[$i];
    } else {
        $nuevoArray[] = $arrtractos[$i];
    }
}
*/

// Mostrar el nuevo array con las temperaturas asignadas


























// Codificando $arrcargados en JSON
$json_arrcargados = json_encode($arrcargados);
file_put_contents('json_arrcargados.json',$json_arrcargados);


// Codificando $arrvacios en JSON
$json_arrvacios = json_encode($arrvacios);
file_put_contents('json_arrvacios.json',$json_arrvacios);


// Codificando $arrcargando en JSON
$json_arrcargando = json_encode($arrcargando);
file_put_contents('json_arrcargando.json',$json_arrcargando);


// Codificando $arrdescargando en JSON
$json_arrdescargando = json_encode($arrdescargando);
file_put_contents('json_arrdescargando.json',$json_arrdescargando);


// Codificando $arrcargadosmov en JSON
$json_arrcargadosmov = json_encode($arrcargadosmov);
file_put_contents('json_arrcargadosmov.json',$json_arrcargadosmov);

// Codificando $arrcargadosdet en JSON
$json_arrcargadosdet = json_encode($arrcargadosdet);
file_put_contents('json_arrcargadosdet.json',$json_arrcargadosdet);


// Codificando $arrvaciosmov en JSON
$json_arrvaciosmov = json_encode($arrvaciosmov);
file_put_contents('json_arrvaciosmov.json',$json_arrvaciosmov);


// Codificando $arrvaciosdet en JSON
$json_arrvaciosdet = json_encode($arrvaciosdet);
file_put_contents('json_arrvaciosdet.json',$json_arrvaciosdet);

// Codificando $arrcargadosdead en JSON
$json_arrcargadosdead = json_encode($arrcargadosdead);
file_put_contents('json_arrcargadosdead.json',$json_arrcargadosdead);

// Codificando $arrsinstatus en JSON
$json_arrsinstatus = json_encode($arrsinstatus);
file_put_contents('json_arrsinstatus.json',$json_arrsinstatus);



// Función de comparación por la clave "Ultimo_mensaje"
function compararPorUltimoMensaje($a, $b) {
    return strcmp($b["timestamp"], $a["timestamp"]);
}

// Ordenar el array por la clave "Ultimo_mensaje"
usort($arrsinreportar, 'compararPorUltimoMensaje');


// Codificando $arrsinreportar en JSON
$sinreportar = json_encode($arrsinreportar);
file_put_contents('json_arrtractossinreportar.json',$sinreportar);

print_r($arrcargados);
$time_end = microtime(true); // capturar tiempo de finalización

$time = $time_end - $time_start; // calcular tiempo total

echo "El script tardó $time segundos en ejecutarse.";
?>