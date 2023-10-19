<?php

    class Auto {
        public string $patente;
        public string $marca;
        public string $color;
        public int $precio;

        public function __construct(string $patente, string $marca = "", string $color = "", int $precio = 0){
            $this->patente = $patente;
            $this->marca = $marca;
            $this->color = $color;
            $this->precio = $precio;
        }

        public function toJSON(){
            $obj = new stdClass();

            $obj->patente = $this->patente;
            $obj->marca = $this->marca;
            $obj->color = $this->color;
            $obj->precio = $this->precio;
            
            return json_encode($obj);
        }

        public function guardarJSON(string $path){
            $obj = new stdClass();
            $obj->exito = false;
            $obj->mensaje = "Error al guardar.";
        
            $archivo = fopen($path, "a");
        
            $contenidoActual = file_get_contents($path);
    
            $objetosExistentes = json_decode($contenidoActual);
        
            $objetosExistentes[] = json_decode($this->ToJSON());
    
            $retorno = file_put_contents($path, json_encode($objetosExistentes));
        
            if($retorno !== false){
                $obj->exito = true;
                $obj->mensaje = "Guardado con éxito.";
            }
        
            fclose($archivo);
            return json_encode($obj);
        }

        public static function traerJSON($path){
            $autos = array();
    
            if(file_exists($path)){
                $contenido = file_get_contents($path);
    
                if($contenido !== false){
                    $lineas = explode("\n\r", $contenido);
    
                    foreach($lineas as $linea){
                        $data = json_decode($linea);

                        if($data !== null){
                            $data = $data[0];
                            $auto = new Auto($data->patente, $data->marca, $data->color, $data->precio);
    
                            $autos[] = $auto;
                        }
                    }
                }
            }
    
            return $autos;
        }

        public static function verificarAutoJSON($auto){
            $autos = Auto::traerJSON("./archivos/autos.json");

            $obj = new stdClass();
            $obj->exito = false;
            $obj->mensaje = "Auto no encontrado";

            foreach($autos as $value){
                if($value->patente == $auto->patente){
                    $obj->exito = true;
                    $obj->mensaje = "Auto encontrado.";
                    break;
                }
            }

            return json_encode($obj);
        }
    }
?>