<?php
require_once "clases/auto.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $patente = isset($_POST["patente"]) ? $_POST["patente"] : null;
    $marca = isset($_POST["marca"]) ? $_POST["marca"] : null;
    $color = isset($_POST["color"]) ? $_POST["color"] : null;
    $precio = isset($_POST["precio"]) ? $_POST["precio"] : null;

    $auto = new Auto($patente, $marca, $color, $precio);

    $resultado = $auto->guardarJSON('./archivos/autos.json');
    
    echo json_decode($resultado)->mensaje;
}

?>