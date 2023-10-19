<?php
require_once "clases/auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : null;

    $patente = json_decode($auto_json)->patente;

    $resultado = AutoBD::eliminar($patente);

    $obj = new stdClass();
    $obj->exito = false;
    $obj->mensaje = "Error al eliminar el auto.";

    if ($resultado) {
        $obj->exito = true;
        $obj->mensaje = "Auto eliminado con éxito.";

        $auto = new Auto(json_decode($auto_json)->patente, json_decode($auto_json)->marca, json_decode($auto_json)->color, json_decode($auto_json)->precio);
        $auto->guardarJSON('./archivos/autos_eliminados.json');
    }

    echo json_encode($obj);
}