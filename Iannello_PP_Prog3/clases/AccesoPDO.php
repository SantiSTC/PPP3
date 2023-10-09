<?php
    class AccesoPDO{
        private static AccesoPDO $objetoAccesoDatos;
        private PDO $objetoPDO;

        private function __construct()
        {
            try {
    
                $usuario = 'root';
                $clave = '';

                $this->objetoPDO = new PDO('mysql:host=localhost;dbname=garage_bd;charset=utf8', $usuario, $clave);
    
            } 
            catch (PDOException $e) 
            {
                print "Error!!!<br/>" . $e->getMessage();
                die();
            }
        }

        public function retornarConsulta(string $sql)
        {
            return $this->objetoPDO->prepare($sql);
        }

        public static function retornarUnObjetoAcceso() : AccesoPDO{
            if (!isset(self::$objetoAccesoDatos)) 
            {       
                self::$objetoAccesoDatos = new AccesoPDO(); 
            }

            return self::$objetoAccesoDatos;
        }

    }  

?>