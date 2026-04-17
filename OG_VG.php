<?php
include('dashguzman.php');
//Unidades Cargadas Sin Reportar
$unidadescargadasdead = count($arrcargadosdead);
$sinestatusCarga = "";
foreach ($arrcargadosdead as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $sinestatusCarga .= "<label>" . $unidad["Unidad"] . ", Origen: " . $unidad["Origen"] . ", Destino: " . $unidad["Destino"] . "</label><br>";
}
//Unidades Cargadas Sin Reportar
//Unidades Cargadas en Movimiento
$unidadescargadasmov = count($arrcargadosmov);
$statusMovimiento = "";
foreach ($arrcargadosmov as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $statusMovimiento .= "<label>" . $unidad["Unidad"] . ", Origen: " . $unidad["Origen"] . ", Destino: " . $unidad["Destino"] . "</label><br>";
}
//Unidades Cargadas en Movimiento
//Unidades Cargadas Detenidas
$unidadescargadasdet = count($arrcargadosdet);
$statusCargaDet = "";
foreach ($arrcargadosdet as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $statusCargaDet .= "<label>" . $unidad["Unidad"] . ", Origen: " . $unidad["Origen"] . ", Destino: " . $unidad["Destino"] . "</label><br>";
}
//Unidades Cargadas Detenidas
//Unidades Cargadas Desviadas
$unidadesdesviadas = count($arrdesvioruta);
//Unidades Cargadas Desviadas
//Unidades Vacias en Movimiento
$unidadesvaciasmov = count($arrvaciosmov);
$statusVaciosMov = "";
foreach ($arrvaciosmov as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
  $statusVaciosMov .= "<label>" . $unidad["Unidad"] . ", Origen: " . $unidad["Origen"] . ", Destino: " . $unidad["Destino"] . "</label><br>";
}
//Unidades Vacis en Movimiento
//Unidades Vacias Detenidas
$unidadesvaciasdet = count($arrvaciosdet);
$statusVaciosDet = "";
foreach ($arrvaciosdet as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $statusVaciosDet .= "<label>" . $unidad["Unidad"] . ", Origen: " . $unidad["Origen"] . ", Destino: " . $unidad["Destino"] . "</label><br>";
}
//Unidades Vacias Detenidas
//Unidades Vacias
$unidadesvacias = count($arrvacios);
$statusVacios = "";
foreach ($arrvacios as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $statusVacios .= "<label>" . $unidad["Unidad"] . ", Origen: " . $unidad["Origen"] . ", Destino: " . $unidad["Destino"] . "</label><br>";
}
//Unidades Vacias
//Total Unidades Cargando
$unidadescargando = count($arrcargando);
$TotalUnitCargando = "";
foreach ($arrcargando as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $TotalUnitCargando .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Total Unidades Cargando
//Total Unidades Descargando
$unidadesdescargando = count($arrdescargando);
$TotalUnitDesc = "";
foreach ($arrdescargando as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $TotalUnitDesc .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Total Unidades Descargando
//Total Unidades Cargadas
$unidadescargadas = count($arrcargados);
$TotalUnitCargadas = "";
foreach ($arrcargados as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $TotalUnitCargadas .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Total Unidades Cargadas
//Sin Estatus
$unidadessinstatus = count($arrsinstatus);
$sinestatusHTML = "";
foreach ($arrsinstatus as $unidad) {
    $latitud =$unidad["Latitud"];
    $longitud =$unidad["longitud"];
    $sinestatusHTML .= "<label>" . $unidad["Unidad"] . ", Origen: " . $unidad["Origen"] . ", Destino: " . $unidad["Destino"] . "</label><br>";
}
//Sin Estatus
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="100">
    <link rel="stylesheet" href="OG_2.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>OPERACION GUZMAN</title>
</head>

<body>
    <!---->
    <header>
        <nav class="navbar navbar-light bg-dark">
            <a class="navbar-brand" href="#">
                <img src="/tguzman.jpg" width="50" height="50" alt="">
                <h2 style="color: white;">Operacion Guzman</h2>
            </a>
        </nav>
        <!--<ul>-->
            <!--<li><a class="active" href="#">Vista General</a></li>-->
            <!--<li><a href="#Reporte">Reporte</a></li>-->
        <!--</ul>-->
    </header>
    <!---->
    <!---->
    <main>
        <div></div>
        <div class="containerofcontainer">
            <div class="container">
                <div class="inner" id="a"><!--MODAL 1--><!--Unidades Cargadas sin Reportar-->
                    <button id="botonModal" onclick="showDiv()">
                        <p>Unidades Cargadas sin Reportar:
                        <h3><?php echo $unidadescargadasdead ?></h3>
                        </p>
                    </button>
                </div>
                <!------------------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="b">
                    <!--Unidades Cargadas en Movimiento-->
                    <button id="botonModal2" onclick="showDiv2()">
                        <p>Unidades Cargadas en Movimiento:
                        <h3><?php echo $unidadescargadasmov ?></h3>
                        </p>
                    </button>
                </div>
                <!------------------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="c"><!--Unidades Cargadas Detenidas-->
                    <button id="botonModal3" onclick="showDiv3()">
                        <p>Unidades Cargadas Detenidas:
                        <h3><?php echo $unidadescargadasdet ?></h3>
                        </p>
                    </button>
                </div>
                <!------------------------------------------------------------------------------------------------------------------------------------>
                <div class="inner" id="d"><!---Unidades Cargadas Desviadas--->
                    <button id="botonModal4" onclick="showDiv4()">
                        <p>Unidades Cargadas Desviadas:
                        <h3><?php echo $unidadesdesviadas ?></h3>
                        </p>
                    </button>
                </div>
                <!------------------------------------------------------------------------------------------------------------------------------------>
                <div class="inner" id="e"><!--Unidades Vacias en Movimiento-->
                    <button id="botonModal5" onclick="showDiv5()">
                        <p>Unidades Vacias en Movimiento:
                        <h3><?php echo $unidadesvaciasmov ?></h3>
                        </p>
                    </button>
                </div>
                <!----------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="f"><!--Unidades Vacias Detenidas-->
                    <button id="botonModal6" onclick="showDiv6()">
                        <p>Unidades Vacias Detenidas:
                        <h3><?php echo $unidadesvaciasdet ?></h3>
                        </p>
                    </button>
                </div>
                <!------------------------------------------------------------------------------------------------------------------------------------>
                <div class="inner" id="g"><!--Unidades Vacias-->
                    <button id="botonModal7" onclick="showDiv7()">
                        <p>Total Unidades Vacias:
                        <h3><?php echo $unidadesvacias ?></h3>
                        </p>
                    </button>
                </div>
                <!----------------------------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="h"><!--Total Unidades Cargando-->
                    <button id="botonModal8" onclick="showDiv8()">
                        <p>Total Unidades Cargando:
                        <h3><?php echo $unidadescargando ?></h3>
                        </p>
                    </button>
                </div>
                <!----------------------------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="i"><!-- Total Unidades Descargando-->
                    <button id="botonModal9" onclick="showDiv9()">
                        <p>Total Unidades Descargando:
                        <h3><?php echo $unidadesdescargando ?></h3>
                        </p>
                    </button>
                </div>
                <!----------------------------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="j"><!--Total Unidades Descargando-->
                    <button id="botonModal10" onclick="showDiv10()">
                        <p>Total Unidades Cargadas:
                        <h3><?php echo $unidadescargadas ?></h3>
                        </p>
                    </button>
                </div>
                <!----------------------------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="k"><!--Total Unidades -->
                    <button id="botonModal11" onclick="showDiv11()">
                        <p>Total Unidades S/N Estatus:
                        <h3><?php echo $unidadessinstatus ?></h3>
                        </p>
                    </button>
                </div>
                <div class="inner" id="l"><!--Unidades -->

                </div>


            </div>
            <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEsx7PVLRF_dl69ASpxaEHhsCxpkTckd0&callback=initMap&v=weekly">

            </script>
            <script>
                // Initialize and add the map
                function initMap() {
                    // The location of torreon
                    const torreon = {
                        lat: 25.54389,
                        lng: -103.41898
                    };
                    //el mapa centrado en torreon 
                    const map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 5,
                        center: torreon,
                    });
                    <?php
                    foreach ($arrcargados as $unidad) {
                        //print_r($unidad); 
                        $nombre = $unidad["Unidad"];
                        $latituds = $unidad["Latitud"];
                        $longituds = $unidad["longitud"];
                        $location = array("lat" => $latituds, "lng" => $longituds);

                        echo "var marker = new google.maps.Marker({position: {lat: $latituds, lng: $longituds}, map: map, label:'$nombre'});\n";
                    }

                    ?>


                    // la ubicacion del marcador en torreon
                    const marker2 = new google.maps.Marker({
                        position: marker,
                        map: map,
                        label: label
                    });
                }

                window.initMap = initMap;
            </script>
            <div id="map"></div>
        </div>
        <!---SCRIPT PARA MOSTRAR EL DIV--->
        <script>
            function showDiv() {
                // Monstramos este:
                document.getElementById('welcomeDiv').style.display = "block";
                // Ocultamos estos menes:
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";
            }

            function showDiv2() {
                document.getElementById('welcomeDiv2').style.display = "block";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";
            }

            function showDiv3() {
                document.getElementById('welcomeDiv3').style.display = "block";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";
            }

            function showDiv4() {
                document.getElementById('welcomeDiv4').style.display = "block";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";
            }

            function showDiv5() {
                document.getElementById('welcomeDiv5').style.display = "block";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDi2').style.display = "none";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";
            }

            function showDiv6() {
                document.getElementById('welcomeDiv6').style.display = "block";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";
            }

            function showDiv7() {
                document.getElementById('welcomeDiv7').style.display = "block";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";

            }

            function showDiv8() {
                document.getElementById('welcomeDiv8').style.display = "block";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";
            }

            function showDiv9() {
                document.getElementById('welcomeDiv9').style.display = "block";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";

            }

            function showDiv10() {
                document.getElementById('welcomeDiv10').style.display = "block";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv4').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv').style.display = "none";
                document.getElementById('welcomeDiv11').style.display = "none";

            }

            function showDiv11() {
                document.getElementById('welcomeDiv11').style.display = "block";
                document.getElementById('welcomeDiv10').style.display = "none";
                document.getElementById('welcomeDiv9').style.display = "none";
                document.getElementById('welcomeDiv8').style.display = "none";
                document.getElementById('welcomeDiv7').style.display = "none";
                document.getElementById('welcomeDiv6').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv5').style.display = "none";
                document.getElementById('welcomeDiv3').style.display = "none";
                document.getElementById('welcomeDiv2').style.display = "none";
                document.getElementById('welcomeDiv').style.display = "none";
            }
        </script>
        <!---SCRIPT PARA MOSTRAR EL DIV--->
        <div id="summaryh">
            <div id="container">
                <div id="welcomeDiv" style="display:none;" class="answer_list">
                    <h4>Unidades Cargadas sin Reportar:</h4>
                    <p><?php echo $sinestatusCarga ?></p>
                    <hr>
                </div>
                <!---welcomeDiv2--->
                <div id="welcomeDiv2" style="display:none;" class="answer_list">
                    <h4>Unidades Cargadas en Movimiento:</h4>
                    <p><?php echo $statusMovimiento ?></p>
                    <hr>
                </div>
                <!---welcomeDiv3--->
                <div id="welcomeDiv3" style="display:none;" class="answer-list">
                    <h4>Unidades Cargadas Detenidas:</h4>
                    <p><?php echo $statusCargaDet ?></p>
                    <hr>
                </div>
                <!---welcomeDiv4--->
                <div id="welcomeDiv4" style="display:none;" class="answer-list">
                    <h4>Unidades Cargadas Desviadas:</h4>
                    <?php
                    foreach ($arrdesvioruta as $unidad) {
                        $uname = $unidad["Unidad"];
                        $latitudops = $unidad["Latitud"];
                        $longitudops = $unidad["longitud"];
                        $origendb = $unidad["Origen"];
                        $destinodb = $unidad["Destino"];
                        $velocidaddb = $unidad["Velocidad"];

                        echo '<div class="content2">';
                        echo '  <p>Unidad: ' . $uname . '</p>';
                        echo '  <p>Origen: ' . $origendb . '</p>';
                        echo '  <p>Destino: ' . $destinodb . '</p>';
                        echo '  <p>Velocidad: ' . $velocidaddb . '</p>';
                        echo '</div>';
                    }
                    ?>
                    <hr>
                </div>
                <!---welcomeDiv5--->
                <div id="welcomeDiv5" style="display:none;" class="answer-list">
                    <h4>Unidades Vacias en Movimiento:</h4>
                    <p><?php echo $statusVaciosMov ?></p>
                    <hr>
                </div>
                <!---welcomeDiv6--->
                <div id="welcomeDiv6" style="display:none;" class="answer-list">
                    <h4>Unidades Vacias Detenidas:</h4>
                    <p><?php echo $statusVaciosDet ?></p>
                    <hr>
                </div>
                <!---welcomeDiv7--->
                <div id="welcomeDiv7" style="display:none;" class="answer_list">
                    <h4>Total Unidades Vacias:</h4>
                    <p><?php echo $statusVacios ?></p>
                    <hr>
                </div>
                <!---welcomeDiv8--->
                <div id="welcomeDiv8" style="display:none;" class="answer_list">
                    <h4>Total Unidades Cargando:</h4>
                    <p><?php echo $TotalUnitCargando ?></p>
                    <hr>
                </div>
                <!---welcomeDiv9--->
                <div id="welcomeDiv9" style="display:none;" class="answer_list">
                    <h4>Total Unidades Descargando:</h4>
                    <p><?php echo $TotalUnitDesc ?></p>
                    <hr>
                </div>
                <!---welcomeDiv10--->
                <div id="welcomeDiv10" style="display:none;" class="answer_list">
                    <h4>Total Unidades Cargadas:</h4>
                    <p><?php echo $TotalUnitCargadas ?></p>
                    <hr>
                </div>
                <!---welcomeDiv11--->
                <div id="welcomeDiv11" style="display:none;" class="answer_list">
                    <h4>Total Unidades S/N Estatus:</h4>
                    <p><?php echo $sinestatusHTML ?></p>
                    <hr>
                </div>
                <!---welcomeDiv12--->
            </div>
        </div>
    </main>
    <!---->
    <!---->
    <footer></footer>
    <!---->
    <!---->
</body>

</html>