<?php
    require_once ("Database/Conexion.php");  
    require_once ("Security/Autorizar.php");
    require_once ("Services/Persona.php");
    require_once ("Respuesta.php");

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: text/html; charset=utf-8');

    header('Content-Type: application/json');  

    class Main {

        public function Index() {          

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $this->Get();
                    break;     
                case 'POST':
                    $this->Post();
                    break;                
                case 'PUT':
                    $this->Put();
                    break;      
                case 'DELETE':
                    $this->Delete();
                    break;
                default:
                    echo json_encode("Método no soportado");
                    break;
            }
        }

        /* Se encarga de instanciar y conectar con la base de datos. */
        private function Iniciar($action) {
            $instancia = Conexion::getInstance();
            $conexion = $instancia->getConnection();
            switch ($action) {
                case 'personas': 
                    $return = new Persona($conexion); 
                    break;
            }
            return $return;
        }

        /* Servicios GET */
        private function Get() {

            $action = $_REQUEST['action'];
            $contexto = $this->Iniciar($action);
            
            switch ($action) {

                case 'personas':

                    $persona = $this->Iniciar($action);

                    $tokenizar = new Autorizar();
                    $autorizado = $tokenizar->Autorizar(apache_request_headers());

                    if ($autorizado['status'] == 200) {
                        if(isset($_GET['id']))              
                            $datos = $persona->GetById($_GET['id']);
                        else
                            $datos = $persona->GetAll();
                    } else {
                        $datos = false;
                    }

                    $response = new Respuesta(new Autorizar());
                    $respuesta = $response->GenerarRespuestaSinToken($datos, 'GET');
                    
                    break;

                default: 
                    $respuesta = 'La URL no es correcta.';
                    break;
            }

            echo json_encode($respuesta);
        }
        
        /* Servicio POST */
        private function Post() {

            $datos = json_decode(file_get_contents('php://input'), true);
            $action = $_REQUEST['action'];

            switch ($action) {
                case 'personas':                      
                    $persona = $this->Iniciar($action);
                    if (isset($datos['Logeo'])) {                                           
                        if ($datos['Logeo']) {                                               
                            $usuario = $persona->Autenticar($datos);                          
                            $response = new Respuesta(new Autorizar());
                            $respuesta = $response->GenerarRespuestaConToken($usuario, 'POST');
                        } 
                    } else {                      
                        $tokenizar = new Autorizar();
                        $autorizado = $tokenizar->Autorizar(apache_request_headers());
    
                        if ($autorizado['status'] == 200) {
                            $usuario = $persona->Post($datos);
                        }

                        /*  AQUI HABRIA QUE CONSULTAR EL TOKEN Y VER QUE SEA CORRECTO.

                            $gestionToken = new Autorizar();
                            return $gestionToken->Autorizar($token);
                        */
                        $response = new Respuesta(new Autorizar());
                        $respuesta = $response->GenerarRespuestaSinToken($usuario, 'POST');
                    }
                    break;
            }
            
            echo json_encode($respuesta);
        }

        /* Servicio PUT */
        private function Put() {

            $datos = json_decode(file_get_contents('php://input'), true);
            $action = $_REQUEST['action'];

            switch ($action) {
                case 'personas':                      
                    $persona = $this->Iniciar($action);

                    $resultado = null;
                    if (isset($datos)) 
                        $resultado = $persona->Put($datos);

                    $response = new Respuesta(new Autorizar());
                    $respuesta = $response->GenerarRespuestaSinToken($resultado, 'PUT');
                    
                    break;
            }

            echo json_encode($respuesta);
        }

        /* Servicio DELETE */
        private function Delete() {

            $action = $_REQUEST['action'];
            $contexto = $this->Iniciar($action);
            
            switch ($action) {

                case 'personas':

                    $resultado = null;

                    if(isset($_GET['id'])) 
                        $resultado = $contexto->Delete($_GET['id']);

                    $response = new Respuesta(new Autorizar());
                    $respuesta = $response->GenerarRespuestaSinToken($resultado, 'DELETE');
            }                    
            echo json_encode($respuesta);    
        }

    }

?>