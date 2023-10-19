<?php
require_once "clases/Auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : null;

    $auto_data = json_decode($auto_json);

    if ($auto_data) {
        $auto = new AutoBD($auto_data->patente, $auto_data->marca, $auto_data->color, $auto_data->precio);

        $resultado = $auto->agregar();

        $obj = new stdClass();
        $obj->exito = $resultado;
        $obj->mensaje = "Error al agregar el auto.";

        if ($resultado) {
            $obj->mensaje = "Auto agregado correctamente.";
        }

        echo json_encode($obj);
    } else {
        $obj = new stdClass();
        $obj->exito = false;
        $obj->mensaje = "Error en los datos recibidos.";

        echo json_encode($obj);
    }
}
?>