<?php 
  /* Clase Singleton (Solo se puede instanciar una vez). */
  class Conexion {

    /* Aquí guardaremos la instancia y la conexión a base de datos. */
    private static $instance = null;
    private $conn;
    
    /* Datos para conectar a la base de datos. */ 
    private $host = '127.0.0.1';
    private $user = 'root';
    private $pass = '';
    private $name = 'test';
    
    /* Ponemos el constructor privado para que no se pueda instanciar la clase. */
    private function __construct() {
      $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
    }
    
    /* Utilizaremos este método para instanciar la clase una única vez. */
    public static function getInstance() {
      if(!self::$instance)
        self::$instance = new Conexion();

      return self::$instance;
    }
    
    /* Con éste método obtendremos la conexión. */
    public function getConnection() {
      return $this->conn;
    }
  }
?>