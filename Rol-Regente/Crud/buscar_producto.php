<?php
// Encabezado para indicar que la respuesta es JSON
header("Content-Type: application/json");

// Habilitar la visualización de errores (para depuración)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar a la base de datos
$servidor = "localhost:3307";  // Cambia por tu configuración
$usuario = "root";        // Usuario de la BD
$password = "";           // Contraseña de la BD
$base_datos = "promedicch"; // Nombre de la base de datos

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    echo json_encode(["error" => "Error de conexión: " . $conexion->connect_error]);
    exit();
}
// Buscar productos
$query = $_GET['query'] ?? '';
$sql = "SELECT * FROM productos WHERE Nombre_Producto LIKE '%$query%'";
// Realiza la consulta y devuelvo los resultados en formato JSON


// Determinar si la solicitud es GET o POST
$query = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Si es POST, leer los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['query']) && !empty($data['query'])) {
        $query = $conexion->real_escape_string($data['query']);
    }
} elseif (isset($_GET['query']) && !empty($_GET['query'])) {
    // Si es GET, obtener la consulta desde la URL
    $query = $conexion->real_escape_string($_GET['query']);
}

// Si la consulta está vacía, devolver un error
if (empty($query)) {
    echo json_encode(["error" => "No se recibió la consulta"]);
    exit();
}

// Consulta a la base de datos (con JOIN para obtener marca y estado)
$sql = "
    SELECT 
        p.Id_Producto, 
        p.Nombre_Producto, 
        p.Descripcion_Producto, 
        p.Precio, 
        p.Cantidad_Stock, 
        p.Peso, 
        p.Fecha_Vencimiento, 
        p.Codigo_Barras, 
        m.Marca_Producto AS Nombre_Marca, 
        e.Tipo_Estado_Producto AS Nombre_Estado
    FROM producto p
    LEFT JOIN marca m ON p.Id_Marca = m.Id_Marca
    LEFT JOIN estado_producto e ON p.Id_Estado_Producto = e.Id_Estado_Producto
    WHERE p.Nombre_Producto LIKE '%$query%'
";

$resultado = $conexion->query($sql);

// Verificar si hay resultados
$producto = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $producto[] = $fila;
    }
    echo json_encode($producto);
} else {
    echo json_encode(["mensaje" => "No se encontraron productos"]);
}

// Cerrar conexión
$conexion->close();
?>