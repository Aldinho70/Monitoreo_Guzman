<?php
$time_start = microtime(true); // capturar tiempo de inicio

// Set the content type to JSON
header('Content-Type: application/json');
//print_r("Carlos Alberto Sanchez Villegas.\n");
ini_set('memory_limit', '2056M');
set_time_limit(0);

date_default_timezone_set('Etc/GMT+6');
$hora_actual = date("Y-m-d H:i:s");


error_reporting(E_PARSE | E_ERROR);

include('wialon.php');
$wialon_api = new Wialon();

// old username and password login is deprecated, use token login
//USUARIO: WEBSERVICEGUZMAN
$token = "d26901b3a1b754d30e264a18da1e24e37AC702FCC0735B72987A8FAFDD6E86F43350A5CB"; //EL MENTADO TOKEN

$result = $wialon_api->login($token);
$json = json_decode($result, true);
if (!isset($json['error'])) {
    $grupos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit_group","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');  //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');

    //$ecos = $wialon_api->core_search_items('{"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"sys_name"},"force":1,"flags":4611686018427387903,"from":0,"to":0}');  //echo $wialon_api->core_search_item('{"id":16733835,"flags":4097}');
    //$eco3 = $wialon_api->core_search_item('{"id":"'.$unitID.'","flags":4611686018427387903}'); // esta es la json string
    $SID = $json["eid"];
    //$ecos2 = $wialon_api->core_search_item('{"id":"20254292","flags":4611686018427387903}'); // esta es la json string

//var_dump(json_decode($ecos2));



    //$json1 = json_decode($eco3, true);
    $json1 = json_decode($grupos);

    //var_dump($json1);


$arraytotal = array();
    foreach ($json1->items as $grupos) {

        $name = $grupos->nm;

        //unidades ya es un array
        $unidades = $grupos->u;

        // $gruposind = array(
        //     "grupo" => $name


        // );



        foreach ($unidades as $unidad) {
            $ecos = json_decode($wialon_api->core_search_item('{"id":"' . $unidad . '","flags":5242881}')); // esta es la json string

        //var_dump($ecos);



            $uname = $ecos->item->nm;
            $uimei = $ecos->item->uid;
            $utimestamp = $ecos->item->pos->t;

            $fechaunidad = date("Y-m-d H:i:s", $utimestamp);

            $diferencia_minutos = round((strtotime($hora_actual) - strtotime($fechaunidad)) / 60);

            $diferencia_horas = floor($diferencia_minutos / 60);
            $diferencia_minutos_restantes = $diferencia_minutos % 60;

            $diferencia_dias = floor($diferencia_horas / 24);
            $diferencia_horas_restantes = $diferencia_horas % 24;

            //esta es la buena para saber hace cuanto reporto
            $diferencia = $diferencia_dias . " dias, " . $diferencia_horas_restantes . " horas y ," . $diferencia_minutos_restantes . " minutos";



            //sensores
            $equipo = null;

            foreach ($ecos->item->flds as $flds) {
                if ($flds->n === "EQUIPO") {
                    $equipo = $flds->v;
                }
            }

            foreach ($ecos->item->aflds as $aflds) {
                if ($aflds->n === "EQUIPO") {
                    $equipo = $aflds->v;
                }
            }





            if ($equipo === "TELTONIKA") {
                $voltaje = $ecos->item->prms->pwr_ext->v;
            }

            if ($equipo === "RUPTELA") {
                $voltaje = $ecos->item->prms->power->v;
            }

            if ($equipo === "SUNTECH") {
                $voltaje = $ecos->item->prms->pwr_ext->v;
            }

            if ($equipo === "QUECLINK") {
                $voltaje = $ecos->item->prms->pwr_ext->v;
            }
            if ($equipo === "MEITRACK") {
                $voltaje = $ecos->item->prms->pwr_ext->v;
            }
            if ($equipo === "CELLOCATOR") {
                $voltaje = $ecos->item->prms->pwr_ext->v;
            }
            if ($equipo === "CALAMP") {
                $voltaje = $ecos->item->prms->accum_0->v / 1000;
            }
            if ($diferencia_horas > 72) {


                

            $arrunidadind = array(
                "cliente" => $name,
                "unidad" => $uname,
                //"imei" => $uimei,
                "tiempo_sin_reportar" => $diferencia,
                "timestamp" => $utimestamp,
                "voltaje" => $voltaje
            );
            print_r($arrunidadind);


            array_push($arraytotal,$arrunidadind);
            }


//print_r($arrunidadind);




        }
    }
    $jsontotal = json_encode($arrtotal);



    $wialon_api->logout();
} else {
    echo WialonError::error($json['error']);
}

//print_r($jsontotal);
//var_dump($arrtotal);
$archivo = 'unidadessinreportarjd.json';

file_put_contents($archivo, $jsontotal);

$time_end = microtime(true); // capturar tiempo de finalización

$time = $time_end - $time_start; // calcular tiempo total

echo "El script tardó $time segundos en ejecutarse.";

?>