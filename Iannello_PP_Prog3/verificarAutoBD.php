<?php
require_once "clases/auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $obj_auto = isset($_POST["obj_auto"]) ? $_POST["obj_auto"] : null;

    $auto = new AutoBD(json_decode($obj_auto)->patente);
    $autos = AutoBD::traer();

    $resultado = $auto->existe($autos);

    $retorno = "{}";

    if ($resultado) {
        foreach($autos as $value){
            if($auto->patente == $value->patente){
                $auto->marca = $value->marca;
                $auto->color = $value->color;
                $auto->precio = $value->precio;
                $auto->pathFoto = $value->pathFoto;
            }
        }

        $retorno = $auto->toJSON();
    }

    echo $retorno;
}
?>