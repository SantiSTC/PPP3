<?php
require_once "clases/Auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $patente = isset($_POST["patente"]) ? $_POST["patente"] : null;
    $marca = isset($_POST["marca"]) ? $_POST["marca"] : null;
    $color = isset($_POST["color"]) ? $_POST["color"] : null;
    $precio = isset($_POST["precio"]) ? $_POST["precio"] : null;
    $foto = isset($_FILES["foto"]) ? $_FILES["foto"] : null;

    $auto = new AutoBD($patente, $marca, $color, $precio);

    if(!$auto->existe(AutoBD::traer())){

        if($foto){
            $nombreFoto = $patente . "." . date("His") . ".jpg";
            $pathFoto = "./autos/imagenes/" . $nombreFoto;
            move_uploaded_file($foto["tmp_name"], $pathFoto);
            $auto->pathFoto = $nombreFoto;
        }

        $resultado = $auto->agregar();

        $obj = new stdClass();
        $obj->exito = $resultado;
        $obj->mensaje = "Error al agregar el auto.";

        if ($resultado) {
            $obj->mensaje = "Auto agregado correctamente.";
        }
    } else {
        $obj = new stdClass();
        $obj->exito = false;
        $obj->mensaje = "El auto ya existe en la base de datos.";
    }

    echo json_encode($obj);
}

?>