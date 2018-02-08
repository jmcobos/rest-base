<?php

    require_once ("vendor/firebase/php-jwt/Authentication/JWT.php");    

    class Autorizar {
        
        private $time;
        private $key;

        public function __construct() {
            $this->time = time();
            $this->key = 'miContraseña';
        }

        public function GenerarToken($id, $nombre) {
            
            $token = array(
                'iat' => $this->time, 
                'exp' => $this->time + (60*60),
                'data' => [
                    'id' => $id,
                    'name' => $nombre
                ]
            );    
            return JWT::encode($token, $this->key);

        }

        public function Autorizar($headers) {
            $token = $headers['token'];
            $respuesta = array();
            try {
                $data = JWT::decode($token, $this->key, array('HS256'));
                $respuesta['status'] = "200";
                $respuesta['message'] = "OK";
                return $respuesta;
            } catch (Exception $e) {
                $respuesta['status'] = "401";
                $respuesta['message'] = "Unauthorized: " . $e->getMessage();
                return $respuesta;
            }
        }
             
    }
?>