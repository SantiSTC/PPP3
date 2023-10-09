<?php
require_once "clases/auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] == "borrar"){
    $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : null;

    $patente = json_decode($auto_json)->patente;
    $marca = json_decode($auto_json)->marca;
    $color = json_decode($auto_json)->color;
    $precio = json_decode($auto_json)->precio;
    $foto = json_decode($auto_json)->pathFoto;

    if($foto != null){
        $auto = new AutoBD($patente, $marca, $color, $precio, $foto);
    } else {
        $auto = new AutoBD($patente, $marca, $color, $precio);
    }

    $auto->modificar();

    $obj = new stdClass();
    $obj->exito = false;
    $obj->mensaje = "Error al eliminar el usuario.";

    if ($resultado) {
        $obj->exito = true;
        $obj->mensaje = "Usuario eliminado con éxito.";
    }

    echo json_encode($obj);
}