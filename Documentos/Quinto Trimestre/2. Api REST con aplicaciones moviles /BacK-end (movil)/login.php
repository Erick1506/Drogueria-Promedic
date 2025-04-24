<?php
require "conexion.php";


// Permitir CORS para todas las solicitudes
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST'); // Permitir el método POST
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
header('Access-Control-Allow-Headers: Content-Type, Accept'); // Permitir los encabezados necesarios



// Obtener datos del cuerpo JSON
$data = json_decode(file_get_contents("php://input"));

// Mostrar los datos que recibimos en JSON para depuración
// var_dump($data);  // Descomentar para depuración

// Validar que los campos 'email' y 'password' están presentes
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "Faltan campos"]);
    exit;
}

// Validar que los campos no estén vacíos
$email = trim($data->email);
$password = trim($data->password);

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Los campos no pueden estar vacíos"]);
    exit;
}

// Validar el formato del correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Correo electrónico no válido"]);
    exit;
}

// Escapar las variables para evitar inyecciones SQL
$email = $conn->real_escape_string($email);
$password = $conn->real_escape_string($password);

// Consulta al administrador
$sql = "SELECT * FROM administrador WHERE Correo = '$email'";
$result = $conn->query($sql);

// Verificar si se encontró un registro con ese correo
if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();

    // Verificar si la contraseña es correcta usando bcrypt
    if (password_verify($password, $admin["Contraseña"])) {
        echo json_encode([
            "success" => true,
            "message" => "Inicio de sesión exitoso",
            "data" => [
                "id" => $admin["Id_Administrador"],
                "nombre" => $admin["Nombre"],
                "apellido" => $admin["Apellido"],
                "correo" => $admin["Correo"]
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Administrador no encontrado"]);
}

$conn->close();
?>
