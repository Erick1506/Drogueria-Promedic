<?php
include 'conexion.php';

header('Content-Type: application/json');

// Obtener el ID del producto de la solicitud GET
$id = $_GET['id'] ?? null;

// Manejar la solicitud GET para obtener datos de producto
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($id) || !is_numeric($id)) {
        echo json_encode(['error' => 'ID inválido o no proporcionado']);
        exit;
    }
    $id = (int)$id;

    // Consulta para obtener el producto
    $sqlProducto = "SELECT * FROM producto WHERE Id_Producto = ?";
    $stmtProducto = $conn->prepare($sqlProducto);

    if (!$stmtProducto) {
        echo json_encode(['error' => 'Error al preparar la consulta del producto: ' . $conn->error]);
        exit;
    }

    $stmtProducto->bind_param("i", $id);
    $stmtProducto->execute();
    $resultProducto = $stmtProducto->get_result();
    $producto = $resultProducto->fetch_assoc();

    if (!$producto) {
        echo json_encode(['error' => 'Producto no encontrado']);
        exit;
    }

    // Función para obtener opciones de otras tablas
    function obtenerOpciones($conexion, $sql) {
        $result = $conexion->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Obtener categorías, proveedores, marcas, estados y clasificaciones
    $categorias = obtenerOpciones($conn, "SELECT Id_Categoria, Nombre_Categoria FROM categoria");
    $proveedores = obtenerOpciones($conn, "SELECT Id_Proveedor, Nombre_Proveedor FROM proveedor");
    $marcas = obtenerOpciones($conn, "SELECT Id_Marca, Marca_Producto FROM marca");
    $estados = obtenerOpciones($conn, "SELECT Id_Estado_Producto, Tipo_Estado_Producto FROM estado_producto");
    $clasificaciones = obtenerOpciones($conn, "SELECT Id_Clasificacion, Nombre_Clasificacion FROM clasificacion");

    // Preparar la respuesta
    $respuesta = [
        'producto' => $producto,
        'categorias' => $categorias,
        'marcas' => $marcas,
        'proveedores' => $proveedores,
        'estados' => $estados,
        'clasificaciones' => $clasificaciones
    ];

    echo json_encode($respuesta, JSON_PRETTY_PRINT);
    exit;
}

// Manejar la solicitud POST para actualizar los datos del producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id_producto'] ?? null;
    $nombre = $_POST['nombre_producto'] ?? null;
    $descripcion = $_POST['descripcion_producto'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $cantidad_stock = $_POST['cantidad_stock'] ?? null;
    $cantidad_minima = $_POST['cantidad_minima'] ?? null;
    $cantidad_maxima = $_POST['cantidad_maxima'] ?? null;
    $costo_adquisicion = $_POST['costo_adquisicion'] ?? null;
    $peso = $_POST['peso'] ?? null;
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
    $codigo_barras = $_POST['codigo_barras'] ?? null;
    $id_categoria = $_POST['id_categoria'] ?? null;
    $id_marca = $_POST['id_marca'] ?? null;
    $id_proveedor = $_POST['id_proveedor'] ?? null;
    $id_estado = $_POST['id_estado_producto'] ?? null;
    $id_clasificacion = $_POST['id_clasificacion'] ?? null;

    // Validar que los datos no están vacíos o incorrectos
    if (empty($id) || empty($nombre) || empty($descripcion) || empty($precio) || empty($cantidad_stock) || empty($cantidad_minima) || empty($cantidad_maxima) || empty($costo_adquisicion) || empty($peso) || empty($fecha_vencimiento) || empty($codigo_barras)) {
        echo json_encode(['error' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Realizar la actualización en la base de datos
    $sqlUpdate = "UPDATE producto SET 
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
        Id_Categoria = ?, 
        Id_Marca = ?, 
        Id_Proveedor = ?, 
        Id_Estado_Producto = ?, 
        Id_Clasificacion = ? 
        WHERE Id_Producto = ?";

    $stmtUpdate = $conn->prepare($sqlUpdate);
    if (!$stmtUpdate) {
        echo json_encode(['error' => 'Error al preparar la consulta de actualización: ' . $conn->error]);
        exit;
    }

    // Vincular los parámetros con el SQL
    $stmtUpdate->bind_param('ssdiiiissiiiiiii', 
        $nombre, 
        $descripcion, 
        $precio, 
        $cantidad_stock, 
        $cantidad_minima, 
        $cantidad_maxima, 
        $costo_adquisicion, 
        $peso, 
        $fecha_vencimiento, 
        $codigo_barras, 
        $id_categoria, 
        $id_marca, 
        $id_proveedor, 
        $id_estado, 
        $id_clasificacion, 
        $id
    );

    // Ejecutar la consulta
    if ($stmtUpdate->execute()) {
        echo json_encode(['success' => 'Producto actualizado correctamente.']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el producto: ' . $stmtUpdate->error]);
    }

  
}

?>
