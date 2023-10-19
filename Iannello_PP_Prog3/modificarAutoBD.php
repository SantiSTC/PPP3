<?php
require_once "clases/auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : null;

    $patente = json_decode($auto_json)->patente;
    $marca = json_decode($auto_json)->marca;
    $color = json_decode($auto_json)->color;
    $precio = json_decode($auto_json)->precio;
    $foto = null;
    // $foto = json_decode($auto_json)->pathFoto;

    if($foto != null){
        $auto = new AutoBD($patente, $marca, $color, $precio, $foto);
    } else {
        $auto = new AutoBD($patente, $marca, $color, $precio);
    }

    $resultado = $auto->modificar();

    $obj = new stdClass();
    $obj->exito = false;
    $obj->mensaje = "Error al modificar el auto.";

    if ($resultado) {
        $obj->exito = true;
        $obj->mensaje = "Auto modificado con éxito.";
    }

    echo json_encode($obj);
}