
<?php
include 'conexion.php';

// Obtener datos del formulario con validación básica
$nombre_producto = isset($_POST['nombre_producto']) ? trim($_POST['nombre_producto']) : '';
$descripcion_producto = isset($_POST['descripcion_producto']) ? trim($_POST['descripcion_producto']) : '';
$precio = isset($_POST['precio']) ? (float) $_POST['precio'] : 0;
$cantidad_stock = isset($_POST['cantidad_stock']) ? (int) $_POST['cantidad_stock'] : 0;
$cantidad_minima = isset($_POST['cantidad_minima']) ? (int) $_POST['cantidad_minima'] : 0;
$cantidad_maxima = isset($_POST['cantidad_maxima']) ? (int) $_POST['cantidad_maxima'] : 0;
$costo_adquisicion = isset($_POST['costo_adquisicion']) ? (float) $_POST['costo_adquisicion'] : 0;
$peso = isset($_POST['peso']) ? trim($_POST['peso']) : ''; 
$fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : '';
$codigo_barras = isset($_POST['codigo_barras']) ? trim($_POST['codigo_barras']) : ''; 
$id_marca = isset($_POST['id_marca']) ? (int) $_POST['id_marca'] : 0;
$id_estado_producto = isset($_POST['id_estado_producto']) ? (int) $_POST['id_estado_producto'] : 0;
$id_categoria = isset($_POST['id_categoria']) ? (int) $_POST['id_categoria'] : 0;
$id_clasificacion = isset($_POST['id_clasificacion']) ? (int) $_POST['id_clasificacion'] : 0;
$id_proveedor = isset($_POST['id_proveedor']) ? (int) $_POST['id_proveedor'] : 0;

// Verificación de datos antes de insertar
if (empty($nombre_producto) || empty($descripcion_producto) || empty($precio) || empty($cantidad_stock) || empty($costo_adquisicion) || empty($peso) || empty($id_estado_producto) || empty($id_categoria) || empty($id_clasificacion) || empty($id_proveedor) || empty($id_marca)) {
    die("Todos los campos obligatorios deben ser completados.");
}

// Validación de formato para el precio y fecha
if (!is_numeric($precio)) {
    die("El precio debe ser un número válido.");
}

if (!empty($fecha_vencimiento) && !DateTime::createFromFormat('Y-m-d', $fecha_vencimiento)) {
    die("Fecha de vencimiento no válida.");
}

// Preparación de la consulta SQL
$sql = "INSERT INTO Producto (Nombre_Producto, Descripcion_Producto, Precio, Cantidad_Stock, Cantidad_Minima, Cantidad_Maxima, Costo_Adquisicion, Peso, Fecha_Vencimiento, Codigo_Barras, Id_Marca, Id_Estado_Producto, Id_Categoria, Id_Clasificacion, Id_Proveedor) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

// Ajustar el tipo de datos en bind_param
$stmt->bind_param("ssdiiddssiiiiii", $nombre_producto, $descripcion_producto, $precio, $cantidad_stock, $cantidad_minima, $cantidad_maxima, $costo_adquisicion, $peso, $fecha_vencimiento, $codigo_barras, $id_marca, $id_estado_producto, $id_categoria, $id_clasificacion, $id_proveedor);

// Ejecutar la consulta y verificar si se realizó correctamente
if ($stmt->execute()) {
    // Obtener el ID del producto recién insertado
    $id_producto = $conn->insert_id;

    // Prepara la consulta para insertar la transacción
    $sqlTransaccion = "INSERT INTO transacciones (Fecha_Transaccion, Cantidad, Id_Administrador, Id_Producto, Id_Tipo_Transaccion) 
                        VALUES (CURDATE(), ?, ?, ?, ?)";
    $stmtTransaccion = $conn->prepare($sqlTransaccion);

    if ($stmtTransaccion === false) {
        die('Error en la preparación de la consulta de transacción: ' . $conn->error);
    }

    // Parámetros de la transacción
    $id_administrador = 1;  // ID del administrador
    $id_tipo_transaccion = 1;  // Tipo de transacción (entrada al inventario)

    // Asociar los parámetros
    $stmtTransaccion->bind_param("iiii", $cantidad_stock, $id_administrador, $id_producto, $id_tipo_transaccion);

    // Ejecutar la consulta de la transacción
    if ($stmtTransaccion->execute()) {
        echo "Transacción registrada correctamente para el producto con ID $id_producto.";
    } else {
        die("Error al registrar la transacción: " . $stmtTransaccion->error);
    }

    $stmtTransaccion->close();
// Verificar si el estado del producto es "Promoción"
if ($id_estado_producto == 3) { // 3 es el ID para "Promoción"
    // Obtener datos de promoción
    $id_tipo_promocion = isset($_POST['id_tipo_promocion']) ? (int) $_POST['id_tipo_promocion'] : 0;
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
    $descuento = isset($_POST['Descuento']) && $id_tipo_promocion == 2 ? (float) $_POST['Descuento'] : null; // Solo para descuento

    // Verificación de datos de promoción
    if (empty($id_tipo_promocion) || empty($fecha_inicio) || empty($fecha_fin)) {
        die("Todos los campos de promoción deben ser completados.");
    }

    // Preparar la consulta para insertar en la tabla de promociones
    if ($descuento !== null) { // Si el descuento es proporcionado
        $sqlPromocion = "INSERT INTO Promocion (Id_Administrador, Id_Producto, Id_Tipo_Promocion, Fecha_Inicio, Fecha_Fin, Descuento) 
                         VALUES (?, ?, ?, ?, ?, ?)";
    } else { // Sin descuento
        $sqlPromocion = "INSERT INTO Promocion (Id_Administrador, Id_Producto, Id_Tipo_Promocion, Fecha_Inicio, Fecha_Fin) 
                         VALUES (?, ?, ?, ?, ?)";
    }

    $stmtPromocion = $conn->prepare($sqlPromocion);

    if ($stmtPromocion === false) {
        die('Error en la preparación de la consulta de promoción: ' . $conn->error);
    }

    if ($descuento !== null) {
        // Asociar los parámetros de promoción con descuento
        $stmtPromocion->bind_param("iiissd", $id_administrador, $id_producto, $id_tipo_promocion, $fecha_inicio, $fecha_fin, $descuento);
    } else {
        // Asociar los parámetros de promoción sin descuento
        $stmtPromocion->bind_param("iiiss", $id_administrador, $id_producto, $id_tipo_promocion, $fecha_inicio, $fecha_fin);
    }

    // Ejecutar la consulta de promoción
    if ($stmtPromocion->execute()) {
        echo "Promoción registrada correctamente para el producto con ID $id_producto.";
    } else {
        die("Error al registrar la promoción: " . $stmtPromocion->error);
    }

    $stmtPromocion->close();
}


    // Redirigir a index.php con un mensaje de éxito
    header("Location: http://localhost/PROMEDIC/ROL_ADMIN/Crud/index.php?mensaje=Producto agregado exitosamente.");
    exit(); // Asegúrate de salir después de la redirección
} else {
    die("Error en la inserción del producto: " . $stmt->error);
}

// Cerrar la conexión
$stmt->close();
$conn->close();