<?php
require_once "clases/auto.php";

$autos = Auto::traerJSON('./archivos/autos.json');

foreach($autos as $linea){
    echo $linea->toJSON() . "<br>";
}

?>