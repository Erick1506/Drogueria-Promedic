<?php

// Permitir CORS para todas las solicitudes
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Si es una petición OPTIONS, solo responde con 200 y termina
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'conexion.php';


$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

// Obtener ID desde la URL
$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_producto <= 0) {
    echo json_encode(['error' => 'ID de producto inválido']);
    exit;
}

$campos = [
    'nombre_producto',
    'descripcion_producto',
    'precio',
    'cantidad_stock',
    'cantidad_minima',
    'cantidad_maxima',
    'costo_adquisicion',
    'peso',
    'fecha_vencimiento',
    'codigo_barras',
    'id_categoria',
    'id_marca',
    'id_proveedor',
    'id_estado_producto',
    'id_clasificacion'
];

foreach ($campos as $campo) {
    if (!isset($input[$campo])) {
        echo json_encode(['error' => "Falta el campo: $campo"]);
        exit;
    }
}

// Armamos la consulta
$sql = "UPDATE producto SET 
    nombre_producto = ?, 
    descripcion_producto = ?, 
    precio = ?, 
    cantidad_stock = ?, 
    cantidad_minima = ?, 
    cantidad_maxima = ?, 
    costo_adquisicion = ?, 
    peso = ?, 
    fecha_vencimiento = ?, 
    codigo_barras = ?, 
    id_categoria = ?, 
    id_marca = ?, 
    id_proveedor = ?, 
    id_estado_producto = ?, 
    id_clasificacion = ? 
    WHERE id_producto = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Error en prepare: ' . $conn->error]);
    exit;
}

$stmt->bind_param(
    'ssdiiidssiiiiiii',
    $input['nombre_producto'],
    $input['descripcion_producto'],
    $input['precio'],
    $input['cantidad_stock'],
    $input['cantidad_minima'],
    $input['cantidad_maxima'],
    $input['costo_adquisicion'],
    $input['peso'],
    $input['fecha_vencimiento'],
    $input['codigo_barras'],
    $input['id_categoria'],
    $input['id_marca'],
    $input['id_proveedor'],
    $input['id_estado_producto'],
    $input['id_clasificacion'],
    $id_producto
);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => 'Producto actualizado correctamente.',
            'data' => $input
        ]);
    } else {
        echo json_encode([
            'warning' => 'La consulta se ejecutó, pero no se modificó ningún dato.',
            'data' => $input
        ]);
    }
} else {
    echo json_encode(['error' => 'Error al ejecutar: ' . $stmt->error]);
}

$stmt->close();
$conn->close();


// hacer consulta para el id_estado = 3 promocion