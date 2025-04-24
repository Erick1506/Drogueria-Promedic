<?php
require_once 'conexion.php';

// Permitir CORS para todas las solicitudes
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST,GET,PUT'); // Permitir LOS METODOS
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
header('Access-Control-Allow-Headers: Content-Type, Accept'); // Permitir los encabezados necesarios


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    function limpiar($valor)
    {
        return htmlspecialchars(trim($valor));
    }

    // Asignación de valor para $codigo_barras antes de verificar si existe
    $codigo_barras = limpiar($data['codigo_barras'] ?? '');

    // Verificar si el código de barras ya existe
    $consulta_codigo = $conn->prepare("SELECT Id_Producto FROM Producto WHERE Codigo_Barras = ?");
    if ($consulta_codigo === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de código de barras: ' . $conn->error]);
        exit;
    }

    $consulta_codigo->bind_param('s', $codigo_barras); // Aquí pasamos el código de barras como parámetro
    $consulta_codigo->execute();
    $consulta_codigo->store_result();

    if ($consulta_codigo->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El código de barras ya existe en otro producto.']);
        exit;
    }

    // Asignación de valores restantes después de la verificación
    $nombre_producto = limpiar($data['nombre_producto'] ?? '');
    $descripcion_producto = limpiar($data['descripcion_producto'] ?? '');
    $precio = floatval($data['precio'] ?? 0);
    $cantidad_stock = intval($data['cantidad_stock'] ?? 0);
    $cantidad_minima = intval($data['cantidad_minima'] ?? 0);
    $cantidad_maxima = intval($data['cantidad_maxima'] ?? 0);
    $costo_adquisicion = floatval($data['costo_adquisicion'] ?? 0);
    $peso = limpiar($data['peso'] ?? '');
    $fecha_vencimiento = $data['fecha_vencimiento'] ?? null;
    $id_marca = intval($data['id_marca'] ?? 0);
    $id_categoria = intval($data['id_categoria'] ?? 0);
    $id_clasificacion = intval($data['id_clasificacion'] ?? 0);
    $id_estado_producto = intval($data['id_estado_producto'] ?? 0);
    $id_proveedor = intval($data['id_proveedor'] ?? 0);

    // Validaciones
    if (
        empty($nombre_producto) || empty($descripcion_producto) || $precio <= 0 ||
        $cantidad_stock <= 0 || $id_marca == 0 || $id_categoria == 0 ||
        $id_clasificacion == 0 || $id_estado_producto == 0 || $id_proveedor == 0
    ) {
        echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos obligatorios.']);
        exit;
    }

    if (!empty($fecha_vencimiento) && !DateTime::createFromFormat('Y-m-d', $fecha_vencimiento)) {
        echo json_encode(['success' => false, 'message' => 'Fecha de vencimiento no válida.']);
        exit;
    }

    $query = "INSERT INTO Producto (Nombre_Producto, Descripcion_Producto, Precio, Cantidad_Stock, Cantidad_Minima, Cantidad_Maxima, Costo_Adquisicion, Peso, Fecha_Vencimiento, Codigo_Barras, Id_Marca, Id_Categoria, Id_Clasificacion, Id_Estado_Producto, Id_Proveedor)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('ssdiiddssiiiiii', $nombre_producto, $descripcion_producto, $precio, $cantidad_stock, $cantidad_minima, $cantidad_maxima, $costo_adquisicion, $peso, $fecha_vencimiento, $codigo_barras, $id_marca, $id_categoria, $id_clasificacion, $id_estado_producto, $id_proveedor);

    if ($stmt->execute()) {
        $id_producto = $conn->insert_id;

        if ($id_estado_producto == 3 && !empty($data['id_tipo_promocion'])) {
            $id_tipo_promocion = intval($data['id_tipo_promocion']);
            $fecha_inicio = $data['fecha_inicio'] ?? null;
            $fecha_fin = $data['fecha_fin'] ?? null;
            $descuento = ($id_tipo_promocion == 2) ? floatval($data['descuento'] ?? 0) : 0;

            if (empty($fecha_inicio) || empty($fecha_fin)) {
                echo json_encode(['success' => false, 'message' => 'Debes ingresar las fechas de inicio y fin de la promoción.']);
                exit;
            }

            $query_promocion = "INSERT INTO Promocion (Id_Producto, Id_Tipo_Promocion, Fecha_Inicio, Fecha_Fin, Descuento, Id_Administrador)
                                VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_promocion = $conn->prepare($query_promocion);
            if ($stmt_promocion === false) {
                echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de promoción: ' . $conn->error]);
                exit;
            }
            $id_admin = 1;
            $stmt_promocion->bind_param('iissii', $id_producto, $id_tipo_promocion, $fecha_inicio, $fecha_fin, $descuento, $id_admin);
            $stmt_promocion->execute();
        }

        $query_transaccion = "INSERT INTO Transacciones (Fecha_Transaccion, Cantidad, Id_Administrador, Id_Producto, Id_Tipo_Transaccion)
                              VALUES (NOW(), ?, 1, ?, 1)";
        $stmt_transaccion = $conn->prepare($query_transaccion);
        if ($stmt_transaccion === false) {
            echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de transacción: ' . $conn->error]);
            exit;
        }
        $stmt_transaccion->bind_param('ii', $cantidad_stock, $id_producto);
        $stmt_transaccion->execute();

        echo json_encode(['success' => true, 'message' => 'Producto agregado exitosamente.', 'id_producto' => $id_producto]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar el producto: ' . $stmt->error]);
    }
}
?>
