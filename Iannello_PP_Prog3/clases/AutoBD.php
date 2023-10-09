<?php
    require_once "clases/Auto.php";
    require_once "clases/IParte1.php";

    class AutoBD extends Auto implements IParte1{
        public string $pathFoto;

        public function __construct(string $patente = "", string $marca = "", string $color = "", float $precio = 0, string $pathFoto = "") {
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
                $sql->bindParam(':precio', $this->precio, PDO::PARAM_FLOAT);
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
                            
                        $autos[] = $auto;
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
                $sql->bindParam(':patente', $patente, PDO::PARAM_INT);
    
                $resultado = $sql->execute();
            } catch(PDOException $e){
                $resultado = false;
            }
    
            return $resultado;
        }

        public function modificar(){
            try{
                $conexion = AccesoPDO::retornarUnObjetoAcceso();
    
                $sql = $conexion->retornarConsulta("UPDATE `autos` SET `patente`=:patente,`marca`=:marca,`color`=:color,`precio`=:precio,`foto`=:foto WHERE `patente` = :patente");
                $sql->bindParam(':patente', $this->patente, PDO::PARAM_STR);
                $sql->bindParam(':marca', $this->marca, PDO::PARAM_STR);
                $sql->bindParam(':color', $this->color, PDO::PARAM_STR);
                $sql->bindParam(':precio', $this->precio, PDO::PARAM_FLOAT);
                $sql->bindParam(':foto', $this->pathFoto, PDO::PARAM_STR);
    
                $resultado = $sql->execute();
            } catch(PDOException $e){
                echo "Error al modificar: " . $e->getMessage();
                $resultado = false;
            }
    
            return $resultado;
        }
        

    }

?>