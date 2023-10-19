<?php
require_once "clases/Auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : null;

    $autoData = json_decode($auto_json);

    if($autoData){
        $auto = new AutoBD($autoData->patente, $autoData->marca, $autoData->color, $autoData->precio, $autoData->pathFoto);

        if(AutoBD::eliminar($auto->patente)){
            $resultado = $auto->guardarEnArchivo();
            
            $obj = new stdClass();
            $obj->exito = $resultado ? true : false;
            $obj->mensaje = $resultado ? "Auto eliminado y guardado en archivo con exito." : "Error al agregar el auto.";
        } else {
            $obj = new stdClass();
            $obj->exito = false;
            $obj->mensaje = "Error al eliminar el auto de la base de datos.";
        }

        echo json_encode($obj);
    } else {
        $obj = new stdClass();
        $obj->exito = false;
        $obj->mensaje = "Error en los datos recibidos.";
        echo json_encode($obj);
    }

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