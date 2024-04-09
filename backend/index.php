<?php
// Incluimos el archivo de conexión a la base de datos
require_once 'db_connection.php';

// Definimos la clase para manejar las solicitudes
class Api {
    private $db;

    public function __construct() {
        // Creamos una nueva instancia de la conexión a la base de datos
        $this->db = new DatabaseConnection();
    }

    public function run() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        // Obtenemos la ruta de la solicitud
        $request = $_SERVER['REQUEST_URI'];
 
        // Analizamos la ruta para determinar la acción a realizar
        switch ($request) {
            case '/api/users':
                if ($requestMethod === 'GET') {
                    // Obtener todos los usuarios
                    $this->getUsers();
                } elseif ($requestMethod === 'POST') {
                    // Registrar un nuevo usuario
                    $this->registerUser();
                } else {
                    // Método no permitido
                    $this->methodNotAllowedResponse();
                }
            default:
                // Ruta no encontrada
                $this->notFoundResponse();
                break;
        }
    }

    private function registerUser() {
        // Obtener los datos del usuario desde el cuerpo de la solicitud
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true); // Decodificar a array asociativo
    
        // Verificar si se recibieron datos y si se pudo decodificar el JSON
        if ($data === null) {
            // No se pudo decodificar el JSON
            $this->errorResponse("No se pudo decodificar el JSON de la solicitud.");
            return;
        }
    
        // Extraer los valores de los campos del array asociativo
        $names = $data['names'];
        $firstSurname = $data['firstSurname'];
        $secondSurname = $data['secondSurname'];
        $email = $data['email'];
        $phone = $data['phone'];
        $zipCode = $data['zipCode'];
        $state = $data['state'];
    
        // Preparar la consulta SQL para insertar un nuevo usuario
        $stmt = $this->db->conn->prepare("INSERT INTO users (names, firstSurname, secondSurname, email, phone, zipCode, state) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $names, $firstSurname, $secondSurname, $email, $phone, $zipCode, $state);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Usuario registrado con éxito
            header('Content-Type: application/json');
            echo json_encode(array("message" => "Usuario registrado con éxito."));
        } else {
            // Error al registrar el usuario
            $this->errorResponse("Error al registrar el usuario: " . $stmt->error);
        }
    
        // Cerrar la sentencia preparada
        $stmt->close();
    }

    private function getUsers() {
        // Consultamos la base de datos para obtener los usuarios
        $result = $this->db->conn->query("SELECT * FROM users");

        // Convertimos los resultados a un array
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        // Devolvemos la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    private function notFoundResponse() {
        // Establecemos el código de estado HTTP a 404 y devolvemos un mensaje de error
        header("HTTP/1.1 404 Not Found");
        echo json_encode(array("message" => "La ruta no fue encontrada."));
    }

    private function methodNotAllowedResponse() {
        // Establecemos el código de estado HTTP a 405 y devolvemos un mensaje de error
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(array("message" => "Método no permitido."));
    }

    private function errorResponse($message) {
        // Establecemos el código de estado HTTP a 500 y devolvemos un mensaje de error
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(array("message" => $message));
    }
}

// Creamos una nueva instancia de la API y la ejecutamos
$api = new Api();
$api->run();