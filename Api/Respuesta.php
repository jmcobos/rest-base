<?php

    require_once ("Security/Autorizar.php");

    class Respuesta {

        protected $autorizar;

        public function __construct (Autorizar $autorizar) {
            $this->autorizar = $autorizar;
        }

        /* Método que sirve para generar las respuestas */
        public function GenerarRespuestaConToken($usuario) {
            
            $headers['accept'] = 'application/json';
            $headers['lang'] = 'es';

            if ($usuario != null)
                $headers['token'] = $this->autorizar->GenerarToken($usuario['Id'], $usuario['Nombre']);

            $response['headers'] = $headers;

            $response['method']='POST';
            $response['status']=200;
            $response['statusText']='OK';
            $response['data']=$usuario;
            
            if (isset($usuario) == false) {
                $response['method']='POST';
                $response['status']=204;
                $response['statusText']='No Content';
                $response['data'] = null; 
            } 

            if (isset($usuario) == true && $usuario == false) {
                $response['method']='POST';
                $response['status']=401;
                $response['statusText']='Unauthorized';
                $response['data']=$usuario;
            }
            
            return $response;
        }

        /* Método que sirve para generar las respuestas */
        public function GenerarRespuestaSinToken($datos, $verbo) {
            
            $headers['accept'] = 'application/json';
            $headers['lang'] = 'es';
            $response['headers'] = $headers;

            $response['method'] = $verbo;
            $response['status']=200;
            $response['statusText']='OK';
            $response['data']=$datos;
            
            if (isset($datos) == false) {
                $response['status']=204;
                $response['statusText']='No Content';
                $response['data'] = null; 
            } 

            if (isset($datos) == true && $datos == false) {
                $response['status']=401;
                $response['statusText']='Unauthorized';
                $response['data']=$datos;
            }
            
            return $response;
        }

    }

?>