<?php
require_once "clases/Auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : null;
    $foto = isset($_FILES["foto"]) ? $_FILES["foto"] : null;

    $autoData = json_decode($auto_json);

    $obj = new stdClass();
    $obj->exito =  false;
    $obj->mensaje = "Error al modificar el auto.";

    if($autoData){
        foreach(AutoBD::traer() as $auto){
            if($auto->patente == $autoData->patente){
                $viejoPath = "./autos/imagenes/" . $auto->pathFoto;
                break;
            }
        }
        
        $nuevoPath = $autoData->patente . ".modificado." . date("His") . ".jpg";

        $auto = new AutoBD($autoData->patente, $autoData->marca, $autoData->color, $autoData->precio, $nuevoPath);

        $resultado = $auto->modificar();

        if($resultado){
            if(rename($viejoPath, "./autosModificados/" . $nuevoPath)){
                $obj->exito =  true;
                $obj->mensaje = "Auto modificado con exito.";
            }
        }      
    } else {
        $obj = new stdClass();
        $obj->exito = false;
        $obj->mensaje = "Error en los datos recibidos.";
    }

    echo json_encode($obj);

} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $autosEliminados = AutoBD::traerEliminadosBD();

    echo "<html>
            <head>
            <title>Autos Borrados</title>
            </head>
            <body>
            <h1>Autos Borrados</h1>";

    echo "<table border='1'>
            <thead>
            <tr>
            <th>Patente</th>
            <th>Marca</th>
            <th>Color</th>
            <th>Precio</th>
            <th>Foto</th>
            </tr>
            </thead>
            <tbody>";
    
    foreach($autosEliminados as $auto){
        echo "<tr>
                <td> . $auto->patente . </td>
                <td> . $auto->marca . </td>
                <td> . $auto->color . </td>
                <td> . $auto->precio . </td>
                <td><img src=" . $auto->pathFoto .  "width='100'>
                </td>
                </tr>";
    }

    echo "</tbody>
            </table>
            </body>
            </html>";
}
?>