<?php
include('dashguzman.php');
//Unidades Cargadas Sin Reportar
$unidadescargadasdead = count($arrcargadosdead);
$sinestatusCarga = "";
foreach ($arrcargadosdead as $unidad) {
    $sinestatusCarga .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Unidades Cargadas Sin Reportar
//Unidades Cargadas en Movimiento
$unidadescargadasmov = count($arrcargadosmov);
$statusMovimiento = "";
foreach ($arrcargadosmov as $unidad) {
    $statusMovimiento .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Unidades Cargadas en Movimiento
//Unidades Cargadas Detenidas
$unidadescargadasdet = count($arrcargadosdet);
$statusCargaDet = "";
foreach ($arrcargadosdet as $unidad) {
    $statusCargaDet .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Unidades Cargadas Detenidas
//Unidades Cargadas Desviadas
$unidadesdesviadas = count($arrdesvioruta);
//Unidades Cargadas Desviadas
//Unidades Vacias en Movimiento
$unidadesvaciasmov = count($arrvaciosmov);
$statusVaciosMov = "";
foreach ($arrvaciosmov as $unidad) {
    $statusVaciosMov .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Unidades Vacis en Movimiento
//Unidades Vacias Detenidas
$unidadesvaciasdet = count($arrvaciosdet);
$statusVaciosDet = "";
foreach ($arrvaciosdet as $unidad) {
    $statusVaciosDet .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Unidades Vacias Detenidas
//Unidades Vacias
$unidadesvacias = count($arrvacios);
$statusVacios = "";
foreach ($arrvacios as $unidad) {
    $statusVacios .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Unidades Vacias
//Total Unidades Cargando
$unidadescargando = count($arrcargando);
$TotalUnitCargando = "";
foreach ($arrcargando as $unidad) {
    $TotalUnitCargando .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Total Unidades Cargando
//Total Unidades Descargando
$unidadesdescargando = count($arrdescargando);
$TotalUnitDesc = "";
foreach ($arrdescargando as $unidad) {
    $TotalUnitDesc .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Total Unidades Descargando
//Total Unidades Cargadas
$unidadescargadas = count($arrcargados);
$TotalUnitCargadas = "";
foreach ($arrcargados as $unidad) {
    $TotalUnitCargadas .= "<label>" . $unidad["Unidad"] . "</label><br>";
}
//Total Unidades Cargadas
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="100">
    <link rel="stylesheet" href="OG.css">
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
        <ul>
            <li><a class="active" href="#">Vista General</a></li>
            <li><a href="#Reporte">Reporte</a></li>
        </ul>
    </header>
    <!---->
    <!---->
    <main>
        <div></div>
        <div class="containerofcontainer">
            <div class="container">
                <div class="inner" id="a"><!--MODAL 1--><!--Unidades Cargadas sin Reportar-->
                    <button id="botonModal">
                        <p>Unidades Cargadas sin Reportar:
                        <h3><?php echo $unidadescargadasdead ?></h3>
                        </p>
                    </button>
                    <div id="myModal" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h4>Unidades Cargadas sin Reportar:</h4>
                            <p><?php echo $sinestatusCarga ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal = document.getElementById("myModal");

                    // Get the button that opens the modal
                    var btn = document.getElementById("botonModal");

                    // Get the <span> element that closes the modal
                    var span = document.getElementsByClassName("close")[0];

                    // When the user clicks the button, open the modal 
                    btn.onclick = function() {
                        modal.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span.onclick = function() {
                        modal.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                </script>
                <!------------------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="b">
                    <!--Unidades Cargadas en Movimiento-->
                    <button id="botonModal2">
                        <p>Unidades Cargadas en Movimiento:
                        <h3><?php echo $unidadescargadasmov ?></h3>
                        </p>
                    </button>
                    <div id="myModal2" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close2">&times;</span>
                            <h4>Unidades Cargadas en Movimiento:</h4>
                            <p><?php echo $statusMovimiento ?></p>
                            <hr>
                        </div>
                    </div>
                </div>

                <script>
                    // Get the modal
                    var modal2 = document.getElementById("myModal2");

                    // Get the button that opens the modal
                    var btn2 = document.getElementById("botonModal2");

                    // Get the <span> element that closes the modal
                    var span2 = modal2.getElementsByClassName("close2")[0];

                    // When the user clicks the button, open the modal 
                    btn2.onclick = function() {
                        modal2.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span2.onclick = function() {
                        modal2.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal2.onclick = function(event) {
                        if (event.target == modal2) {
                            modal2.style.display = "none";
                        }
                    }
                </script>
                <!------------------------------------------------------------------------------------------------------------------------------------->
                <div class="inner" id="c"><!--Unidades Cargadas Detenidas-->
                    <button id="botonModal3">
                        <p>Unidades Cargadas Detenidas:
                        <h3><?php echo $unidadescargadasdet ?></h3>
                        </p>
                    </button>
                    <div id="myModal3" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close3">&times;</span>
                            <h4>Unidades Cargadas Detenidas:</h4>
                            <p><?php echo $statusCargaDet ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal3 = document.getElementById("myModal3");

                    // Get the button that opens the modal
                    var btn3 = document.getElementById("botonModal3");

                    // Get the <span> element that closes the modal
                    var span3 = modal3.getElementsByClassName("close3")[0];

                    // When the user clicks the button, open the modal 
                    btn3.onclick = function() {
                        modal3.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span3.onclick = function() {
                        modal3.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal3.onclick = function(event) {
                        if (event.target == modal3) {
                            modal3.style.display = "none";
                        }
                    }
                </script>
                <!------------------------------------------------------------------------------------------------------------------------------------>
                <div class="inner" id="d"><!---Unidades Cargadas Desviadas--->
                    <button id="botonModal4">
                        <p>Unidades Cargadas Desviadas:
                        <h3><?php echo $unidadesdesviadas ?></h3>
                        </p>
                    </button>
                    <div id="myModal4" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close4">&times;</span>
                            <h4>Unidades Cargadas Desviadas:</h4>
                            <style>
                                .content2 p {
                                    text-decoration: underline;
                                }
                            </style>

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
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal4 = document.getElementById("myModal4");

                    // Get the button that opens the modal
                    var btn4 = document.getElementById("botonModal4");

                    // Get the <span> element that closes the modal
                    var span4 = modal4.getElementsByClassName("close4")[0];

                    // When the user clicks the button, open the modal 
                    btn4.onclick = function() {
                        modal4.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span4.onclick = function() {
                        modal4.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal4.onclick = function(event) {
                        if (event.target == modal4) {
                            modal4.style.display = "none";
                        }
                    }
                </script>
                <div class="inner" id="e"><!--Unidades Vacias en Movimiento-->
                    <button id="botonModal5">
                        <p>Unidades Vacias en Movimiento:
                        <h3><?php echo $unidadesvaciasmov ?></h3>
                        </p>
                    </button>
                    <div id="myModal5" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close5">&times;</span>
                            <h4>Unidades Vacias en Movimiento:</h4>
                            <p><?php echo $statusVaciosMov ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal5 = document.getElementById("myModal5");

                    // Get the button that opens the modal
                    var btn5 = document.getElementById("botonModal5");

                    // Get the <span> element that closes the modal
                    var span5 = modal5.getElementsByClassName("close5")[0];

                    // When the user clicks the button, open the modal 
                    btn5.onclick = function() {
                        modal5.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span5.onclick = function() {
                        modal5.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal5.onclick = function(event) {
                        if (event.target == modal5) {
                            modal5.style.display = "none";
                        }
                    }
                </script>
                <div class="inner" id="f"><!--Unidades Vacias Detenidas-->
                    <button id="botonModal6">
                        <p>Unidades Vacias Detenidas:
                        <h3><?php echo $unidadesvaciasdet ?></h3>
                        </p>
                    </button>
                    <div id="myModal6" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close6">&times;</span>
                            <h4>Unidades Vacias Detenidas:</h4>
                            <p><?php echo $statusVaciosDet ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal6 = document.getElementById("myModal6");

                    // Get the button that opens the modal
                    var btn6 = document.getElementById("botonModal6");

                    // Get the <span> element that closes the modal
                    var span6 = modal6.getElementsByClassName("close6")[0];

                    // When the user clicks the button, open the modal 
                    btn6.onclick = function() {
                        modal6.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span6.onclick = function() {
                        modal6.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal6.onclick = function(event) {
                        if (event.target == modal6) {
                            modal6.style.display = "none";
                        }
                    }
                </script>
                <div class="inner" id="g"><!--Unidades Vacias-->
                    <button id="botonModal7">
                        <p>Total Unidades Vacias:
                        <h3><?php echo $unidadesvacias ?></h3>
                        </p>
                    </button>
                    <div id="myModal7" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close7">&times;</span>
                            <h4>Total Unidades Vacias:</h4>
                            <p><?php echo $statusVacios ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal7 = document.getElementById("myModal7");

                    // Get the button that opens the modal
                    var btn7 = document.getElementById("botonModal7");

                    // Get the <span> element that closes the modal
                    var span7 = modal7.getElementsByClassName("close7")[0];

                    // When the user clicks the button, open the modal 
                    btn7.onclick = function() {
                        modal7.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span7.onclick = function() {
                        modal7.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal7.onclick = function(event) {
                        if (event.target == modal7) {
                            modal7.style.display = "none";
                        }
                    }
                </script>
                <div class="inner" id="h"><!--Total Unidades Cargando-->
                    <button id="botonModal8">
                        <p>Total Unidades Cargando:
                        <h3><?php echo $unidadescargando ?></h3>
                        </p>
                    </button>
                    <div id="myModal8" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close8">&times;</span>
                            <h4>Total Unidades Cargando:</h4>
                            <p><?php echo $TotalUnitCargando ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal8 = document.getElementById("myModal8");

                    // Get the button that opens the modal
                    var btn8 = document.getElementById("botonModal8");

                    // Get the <span> element that closes the modal
                    var span8 = modal8.getElementsByClassName("close8")[0];

                    // When the user clicks the button, open the modal 
                    btn8.onclick = function() {
                        modal8.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span8.onclick = function() {
                        modal8.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal8.onclick = function(event) {
                        if (event.target == modal8) {
                            modal8.style.display = "none";
                        }
                    }
                </script>
                <div class="inner" id="i"><!-- Total Unidades Descargando-->
                    <button id="botonModal9">
                        <p>Total Unidades Descargando:
                        <h3><?php echo $unidadesdescargando ?></h3>
                        </p>
                    </button>
                    <div id="myModal9" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close9">&times;</span>
                            <h4>Total Unidades Descargando:</h4>
                            <p><?php echo $TotalUnitDesc ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal9 = document.getElementById("myModal9");

                    // Get the button that opens the modal
                    var btn9 = document.getElementById("botonModal9");

                    // Get the <span> element that closes the modal
                    var span9 = modal9.getElementsByClassName("close9")[0];

                    // When the user clicks the button, open the modal 
                    btn9.onclick = function() {
                        modal9.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span9.onclick = function() {
                        modal9.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal9.onclick = function(event) {
                        if (event.target == modal9) {
                            modal9.style.display = "none";
                        }
                    }
                </script>
                <div class="inner" id="j"><!--Total Unidades Descargando-->
                    <button id="botonModal10">
                        <p>Total Unidades Descargando:
                        <h3><?php echo $unidadesdescargando ?></h3>
                        </p>
                    </button>
                    <div id="myModal10" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close10">&times;</span>
                            <h4>Total Unidades Descargando:</h4>
                            <p><?php echo $TotalUnitDesc ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal10 = document.getElementById("myModal10");

                    // Get the button that opens the modal
                    var btn10 = document.getElementById("botonModal10");

                    // Get the <span> element that closes the modal
                    var span10 = modal10.getElementsByClassName("close10")[0];

                    // When the user clicks the button, open the modal 
                    btn10.onclick = function() {
                        modal10.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span10.onclick = function() {
                        modal10.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal10.onclick = function(event) {
                        if (event.target == modal10) {
                            modal10.style.display = "none";
                        }
                    }
                </script>
                <div class="inner" id="k"><!--Total Unidades -->
                    <button id="botonModal11">
                        <p>Total Unidades Cargadas:
                        <h3><?php echo $unidadescargadas ?></h3>
                        </p>
                    </button>
                    <div id="myModal11" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close11">&times;</span>
                            <h4>Total Unidades Cargadas:</h4>
                            <p><?php echo $TotalUnitCargadas ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <script>
                    // Get the modal
                    var modal11 = document.getElementById("myModal11");

                    // Get the button that opens the modal
                    var btn11 = document.getElementById("botonModal11");

                    // Get the <span> element that closes the modal
                    var span11 = modal11.getElementsByClassName("close11")[0];

                    // When the user clicks the button, open the modal 
                    btn11.onclick = function() {
                        modal11.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span11.onclick = function() {
                        modal11.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    modal11.onclick = function(event) {
                        if (event.target == modal11) {
                            modal11.style.display = "none";
                        }
                    }
                </script>
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
                    //google.maps.event.addListener(marker,'click',function(e) {
                map.setZoom(9);
                map.setCenter(e.latLng);
            });
                }

                window.initMap = initMap;
            </script>



            <div id="map"></div>
        </div>
    </main>
    <!---->
    <!---->
    <footer></footer>
    <!---->
    <!---->
</body>

</html>