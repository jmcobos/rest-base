<?php
    require_once ('Database/Conexion.php');

    class Persona {
        
        protected $conexion;

        public function __construct($conexion) {
            $this->conexion = $conexion;
        }

        public function GetAll() {
            $result = $this->conexion->query('SELECT * FROM Personas;');
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        public function GetById($id) {
            $result = $this->conexion->query('SELECT * FROM Personas WHERE Id = ' . $id . ';');
            $usuario = $result->fetch_all(MYSQLI_ASSOC);
            if (count($usuario) == 0)
                return null;
            else
                return $usuario[0];
        }

        public function GetByNombre($nombre) {
            $result = $this->conexion->query('SELECT * FROM Personas WHERE Nombre = "' . $nombre . '";');
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function Post($datos) {
            $query = $this->conexion->prepare("INSERT INTO Personas (Nombre, Apellido_01, Apellido_02) VALUES (?, ?, ?);");
            $query->bind_param('sss', $datos['Nombre'], $datos['Apellido_01'], $datos['Apellido_02']);
            $result = $query->execute(); 
            return $result;        
        }

        public function Put($datos) {
            $query = $this->conexion->prepare("UPDATE Personas SET Nombre = ?, Apellido_01 = ?, Apellido_02 = ? WHERE Id = ?;");
            $query->bind_param('sssi', $datos['Nombre'], $datos['Apellido_01'], $datos['Apellido_02'], $datos['Id']);
            $result = $query->execute(); 
            return $datos;
        }

        public function Delete($id) {
            $query = $this->conexion->prepare("DELETE FROM Personas WHERE Id = ?;");
            $query->bind_param('i', $id);
            $result = $query->execute(); 
            return $id;
        }

        public function Autenticar($usuario) {
            $persona = $this->GetByNombre($usuario['Nombre']);
            if (count($persona) == 1) {
                if ($usuario['Password'] == $persona[0]['Password']) {
                    return $persona[0];
                } else {
                    return false;
                }
            } else {
                return null;
            }
        }

    }
?>