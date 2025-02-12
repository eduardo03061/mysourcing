<?php
 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Aceppt, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Content-Type: application/json; charset=utf-8');

// Incluimos el archivo de conexión a la base de datos
require_once 'db_connection.php';

// Definimos la clase para manejar las solicitudes
class Api
{
    private $db;

    public function __construct()
    {
        // Creamos una nueva instancia de la conexión a la base de datos
        $this->db = new DatabaseConnection();
    }

    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        // Obtenemos la ruta de la solicitud
        $request = $_SERVER['REQUEST_URI'];
        // Analizamos la ruta para determinar la acción a realizar

        $requestPath = strtok($request, '?'); // Obtiene la ruta sin los parámetros
        $queryString = strtok(''); // Obtiene la cadena de consulta sin la ruta

        // Ahora, analizamos los parámetros de la cadena de consulta
        parse_str($queryString, $queryParams);

        switch ($requestPath) {
            case '/api/users':
                if ($requestMethod === 'GET') {
                    // Obtener todos los usuarios
                    $this->getUsers($queryParams);
                } elseif ($requestMethod === 'POST') {
                    // Registrar un nuevo usuario
                    $this->registerUser();
                } elseif ($requestMethod === 'OPTIONS') {
                    exit(0);
                } else {
                    // Método no permitido
                    $this->methodNotAllowedResponse();
                }
                break;
            case preg_match('/\/api\/zipcode\/(\d+)/', $request, $matches) ? $request : null:
                if ($requestMethod === 'GET') {
                    // Validar el código postal
                    $zipCode = $matches[1];
                    $this->validateZipCode($zipCode);
                } elseif ($requestMethod === 'OPTIONS') {
                    exit(0);
                } else {
                    // Método no permitido
                    $this->methodNotAllowedResponse();
                }
                break;

            default:
                break;
        }
    }

    private function validateZipCode($zipCode)
    {

        $state = $this->isValidZipCode($zipCode);


        header('Content-Type: application/json');
        echo json_encode($state);
    }

    private function getUsers($queryParams)
    {

        $recordsPerPage = 5;

        $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;

        $offset = ($page - 1) * $recordsPerPage;

       
        if (isset($queryParams['query'])) {
            
            $searchTerm = $queryParams['query'];
            $result = $this->db->conn->query("SELECT * FROM users WHERE names LIKE '%$searchTerm%' OR firstSurname LIKE '%$searchTerm%' OR secondSurname LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%' OR zipCode LIKE '%$searchTerm%' OR state LIKE '%$searchTerm%' ORDER BY id LIMIT $recordsPerPage OFFSET $offset");
            // Convertimos los resultados a un array
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            // Devolvemos la respuesta en formato JSON
            header('Content-Type: application/json');


            $totals = $this->db->conn->query("SELECT COUNT(*) as totals FROM users WHERE names LIKE '%$searchTerm%' OR firstSurname LIKE '%$searchTerm%' OR secondSurname LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%' OR zipCode LIKE '%$searchTerm%' OR state LIKE '%$searchTerm%'");
            
            $data = array();
            $data['users'] = $users;
            $data['totals'][] = $totals->fetch_assoc();
            
            echo json_encode($data);
        } else {

            // Consultamos la base de datos para obtener los usuarios
            $result = $this->db->conn->query("SELECT * FROM users ORDER BY id LIMIT $recordsPerPage OFFSET $offset");

            // Convertimos los resultados a un array
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            // Devolvemos la respuesta en formato JSON
            header('Content-Type: application/json');
             


            $totals = $this->db->conn->query("SELECT COUNT(*) as totals FROM users");
            
            $data = array();
            $data['users'] = $users;
            $data['totals'][] = $totals->fetch_assoc();
            
            echo json_encode($data);
        }
    }

    private function registerUser()
    {
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

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Aqui se puede remplazar por la funcion isValidEmail que valida mediante expresion regular
            $this->errorResponse("El correo electronico proporcionado no es valido.");
            return;
        }

        // Validar el código postal contra la API de Copomex
        if (!$this->isValidZipCode($zipCode)) {
            $this->errorResponse("El codigo postal proporcionado no es valido.");
            return;
        }

        // Validar que los campos solo contengan letras
        if (!ctype_alpha(str_replace(' ', '', $names)) || !ctype_alpha(str_replace(' ', '', $firstSurname)) || !ctype_alpha(str_replace(' ', '', $secondSurname))) {
            $this->errorResponse("Los campos 'names', 'firstSurname', 'secondSurname' solo deben contener letras y espacios.");
            return;
        }

        // Validar que el campo phone solo contenga n�meros
        if (!ctype_digit($phone)) {
            $this->errorResponse("El campo 'phone' solo debe contener numeros.");
            return;
        }

        // Preparar la consulta SQL para insertar un nuevo usuario
        $stmt = $this->db->conn->prepare("INSERT INTO users (names, firstSurname, secondSurname, email, phone, zipCode, state) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $names, $firstSurname, $secondSurname, $email, $phone, $zipCode, $state);

        // Ejecutar la consulta
        try {
            if ($stmt->execute()) {
                // Usuario registrado con éxito
                header('Content-Type: application/json');
                echo json_encode(array("message" => "Usuario registrado con exito."));
            } else {
                // Error al registrar el usuario
                if ($stmt->errno === 1062) { // Código de error para duplicidad de clave �nica
                    $this->errorResponse("El correo electrónico ya está registrado.");
                } else {
                    $this->errorResponse("Error al registrar el usuario: " . $stmt->error);
                }
            }
        } catch (Exception $e) {
            // Manejar cualquier excepción que pueda surgir durante la ejecución de la consulta
            $this->errorResponse("Error al registrar el usuario: " . $e->getMessage());
        }

        // Cerrar la sentencia preparada
        $stmt->close();
    }

    private function isValidEmail($email)
    {
        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        return preg_match($pattern, $email);
    }

    private function isValidZipCode($zipCode)
    {
        try {
            // Construir la URL de la API de Copomex con el código postal 
            //Alternar con el token=pruebas
            $apiUrl = "https://api.copomex.com/query/info_cp/" . $zipCode . "?token=654b5939-b11b-40dd-82b6-902e1e176b35";

            // Realizar la solicitud HTTP a la API de Copomex
            $response = file_get_contents($apiUrl);

            if ($response === false) {
                throw new Exception("Error de peticion");
            }

            // Decodificar la respuesta JSON
            $data = json_decode($response, true);

            // Verificar si la respuesta contiene información (el código postal es válido)
            if ($data[0]['response']['estado']) {
                return $data[0]['response']['estado'];
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            $this->errorResponse("Error al registrar el usuario: " . $e->getMessage());
        }
    }





    private function notFoundResponse()
    {
        // Establecemos el código de estado HTTP a 404 y devolvemos un mensaje de error
        header("HTTP/1.1 404 Not Found");
        echo json_encode(array("message" => "La ruta no fue encontrada."));
    }

    private function methodNotAllowedResponse()
    {
        // Establecemos el código de estado HTTP a 405 y devolvemos un mensaje de error
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(array("message" => "Método no permitido."));
    }

    private function errorResponse($message)
    {
        // Establecemos el código de estado HTTP a 500 y devolvemos un mensaje de error
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(array("message" => $message));
    }
}

// Creamos una nueva instancia de la API y la ejecutamos
$api = new Api();
$api->run();
