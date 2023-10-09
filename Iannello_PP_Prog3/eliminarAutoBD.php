<?php
require_once "clases/auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] == "borrar"){
    $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : null;

    $patente = json_decode($auto_json)->patente;

    $resultado = AutoBD::eliminar($patente);

    $obj = new stdClass();
    $obj->exito = false;
    $obj->mensaje = "Error al eliminar el usuario.";

    if ($resultado) {
        $obj->exito = true;
        $obj->mensaje = "Usuario eliminado con éxito.";
    }

    echo json_encode($obj);
}