<?php
    require_once "clases/Auto.php";
    require_once "clases/IParte1.php";
    require_once "clases/IParte2.php";
    require_once "clases/IParte3.php";

    class AutoBD extends Auto implements IParte1, IParte2, IParte3{
        public string $pathFoto;

        public function __construct(string $patente = "", string $marca = "", string $color = "", int $precio = 0, string $pathFoto = "") {
            parent::__construct($patente, $marca, $color, $precio);
            $this->pathFoto = $pathFoto;
        }

        public function toJSON() {
            $obj = new \stdClass();
            $obj->patente = $this->patente;
            $obj->marca = $this->marca;
            $obj->color = $this->color;
            $obj->precio = $this->precio;
            $obj->pathFoto = $this->pathFoto;
    
            return json_encode($obj);
        }

        public function agregar(){
            try{
                $conexion = AccesoPDO::retornarUnObjetoAcceso();
    
                $sql = $conexion->retornarConsulta("INSERT INTO `autos`(`patente`, `marca`, `color`, `precio`, `foto`) VALUES (:patente,:marca,:color,:precio,:foto)");
                $sql->bindParam(':patente', $this->patente, PDO::PARAM_STR);
                $sql->bindParam(':marca', $this->marca, PDO::PARAM_STR);
                $sql->bindParam(':color', $this->color, PDO::PARAM_STR);
                $sql->bindParam(':precio', $this->precio, PDO::PARAM_INT);
                $sql->bindParam(':foto', $this->pathFoto, PDO::PARAM_STR);
            
                $resultado = $sql->execute();
            } catch (PDOException $e){
                echo "Error al agregar a la base de datos: " . $e->getMessage();
                $resultado = false;
            }
    
            return $resultado;
        }

        public static function traer(){
            $autos = array();
        
            try{
                $conexion = AccesoPDO::retornarUnObjetoAcceso();
                
                $sql = $conexion->retornarConsulta("SELECT * FROM `autos`");
    
                if($sql != false){
                    $sql->execute();
                    $contenido = $sql->fetchAll();
    
                    foreach($contenido as $linea){
                        if($linea["foto"] != null){
                            $auto = new AutoBD($linea["patente"], $linea["marca"], $linea["color"], $linea["precio"], $linea["foto"]);
                        } else {
                            $auto = new AutoBD($linea["patente"], $linea["marca"], $linea["color"], $linea["precio"]);
                        }
                            
                        array_push($autos, $auto);
                    }
                }
            } catch (PDOException $e){
                echo "Error al agregar a la base de datos: " . $e->getMessage();
            }
    
            return $autos;
        }

        public static function eliminar(string $patente){
            try{
                $conexion = AccesoPDO::retornarUnObjetoAcceso();
    
                $sql = $conexion->retornarConsulta("DELETE FROM `autos` WHERE `patente` = :patente");
                $sql->bindParam(':patente', $patente, PDO::PARAM_STR);
    
                $resultado = $sql->execute();
            } catch(PDOException $e){
                $resultado = false;
            }
    
            return $resultado;
        }

        public function modificar(){
            try{
                $conexion = AccesoPDO::retornarUnObjetoAcceso();
    
                $sql = $conexion->retornarConsulta("UPDATE `autos` SET `marca`=:marca,`color`=:color,`precio`=:precio,`foto`=:foto WHERE `patente` = :patente");
                $sql->bindParam(':patente', $this->patente, PDO::PARAM_STR);
                $sql->bindParam(':marca', $this->marca, PDO::PARAM_STR);
                $sql->bindParam(':color', $this->color, PDO::PARAM_STR);
                $sql->bindParam(':precio', $this->precio, PDO::PARAM_INT);
                $sql->bindParam(':foto', $this->pathFoto, PDO::PARAM_STR);
    
                $resultado = $sql->execute();
            } catch(PDOException $e){
                echo "Error al modificar: " . $e->getMessage();
                $resultado = false;
            }
    
            return $resultado;
        }

        public function existe(array $autos){
            $resultado = false;

            foreach($autos as $auto){
                if($auto->patente === $this->patente){
                    $resultado = true;
                    break;
                }
            }

            return $resultado;
        }
        
        public function guardarEnArchivo(){
            $obj = new stdClass();
            $obj->exito = false;
            $obj->mensaje = "Error al guardar.";

            $nombreFoto = $this->patente . ".borrado." . date("His") . ".jpg";
            $nuevoPath = "./autosBorrados/" . $nombreFoto;
            $viejoPath = "./autos/imagenes/" . $this->pathFoto;

            if(rename($viejoPath, $nuevoPath)){
                $this->pathFoto = $nuevoPath;

                $archivo = fopen("./archivos/autosbd_borrados.txt", "a");

                $contenidoActual = file_get_contents("./archivos/autosbd_borrados.txt");
                $autosEliminados = json_decode($contenidoActual, true);

                if ($autosEliminados === null) {
                    $autosEliminados = [];
                }

                array_push($autosEliminados, $this->toJSON());

                $contenidoNuevo = json_encode($autosEliminados);
                $resultado = file_put_contents("./archivos/autosbd_borrados.txt", $contenidoNuevo);
    
                fclose($archivo);

                if($resultado){
                    $obj->exito = $resultado;
                    $obj->mensaje = "Guardado con exito."; 
                }
            }

            return json_encode($obj);
        }

        public static function traerEliminadosBD(){
            $path = "./archivos/autosbd_borrados.txt";
            $autosEliminados = array();

            if(file_exists($path)){
                $contenido = file_get_contents($path);

                $data = json_decode($contenido, true);

                if($data){
                    foreach($data as $linea){
                        $autoJson = json_decode($linea);
                        
                        $auto = new AutoBD($autoJson->patente, $autoJson->marca, $autoJson->color, $autoJson->precio, $autoJson->pathFoto);
                        array_push($autosEliminados, $auto);
                    }
                }
            }

            return $autosEliminados;
        }
    }

?>