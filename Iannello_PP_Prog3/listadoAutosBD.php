<?php
require_once "clases/Auto.php";
require_once "clases/AutoBD.php";
require_once "clases/AccesoPDO.php";

$autos = AutoBD::traer();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
</head>
<body>
    <h1>Listado de Autos</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Patente</th>
                <th>Marca</th>
                <th>Color</th>
                <th>Precio</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($autos as $auto) : ?>
                <tr>
                    <td><?php echo $auto->patente; ?></td>
                    <td><?php echo $auto->marca; ?></td>
                    <td><?php echo $auto->color; ?></td>
                    <td><?php echo $auto->precio; ?></td>
                    <td><?php echo $auto->pathFoto; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>