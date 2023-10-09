<?php
require_once "clases/auto.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $patente = isset($_POST["patente"]) ? $_POST["patente"] : null;

    $auto = new Auto($patente);

    $resultado = Auto::verificarAutoJSON($auto);

    echo $resultado;
}

?>