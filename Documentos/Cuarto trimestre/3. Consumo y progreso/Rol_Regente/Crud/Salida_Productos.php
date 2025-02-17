<?php
// Configuración de la conexión a la base de datos
$host = "localhost:3307";
$dbname = "promedicch";
$username = "root";
$password = "";

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si los datos fueron enviados desde el formulario
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $idProducto = $_POST['idProducto'];
        $cantidad = $_POST['cantidad'];
        $idAdministrador = 1;
        $idRegente = $_POST['id_regente'];
        $fecha = date('Y-m-d'); // Fecha actual

        // Comenzamos una transacción para asegurar la consistencia
        $pdo->beginTransaction();

        // 1. Obtener el precio del producto y el descuento (si existe) desde las tablas Producto y Promocion
        $sqlPrecio = "
            SELECT p.Precio, pr.Descuento 
            FROM Producto p
            LEFT JOIN Promocion pr ON p.Id_Producto = pr.Id_Producto AND CURDATE() BETWEEN pr.Fecha_Inicio AND pr.Fecha_Fin
            WHERE p.Id_Producto = ?";
        $stmtPrecio = $pdo->prepare($sqlPrecio);
        $stmtPrecio->bindParam(1, $idProducto, PDO::PARAM_INT);

        if ($stmtPrecio->execute()) {
            $producto = $stmtPrecio->fetch(PDO::FETCH_ASSOC);

            if ($producto) {
                $precioUnitario = $producto['Precio'];
                $descuento = $producto['Descuento'] ?? 0; // Si no hay descuento, se asume 0

                // Calcular el precio con descuento (si existe)
                if ($descuento > 0) {
                    $precioUnitarioConDescuento = $precioUnitario * ((100 - $descuento) / 100);
                } else {
                    $precioUnitarioConDescuento = $precioUnitario;
                }

                // Calcular el total
                $total = $cantidad * $precioUnitarioConDescuento;

                // 2. Registrar en la tabla Transacciones
                $sqlTransaccion = "INSERT INTO transacciones (Fecha_Transaccion, Cantidad, Id_Administrador, Id_Producto, Id_Tipo_Transaccion) 
                                   VALUES (CURDATE(), ?, ?, ?, ?)";
                $stmtTransaccion = $pdo->prepare($sqlTransaccion);

                $idTipoTransaccion = 2; // Tipo de transacción (salida del inventario)

                $stmtTransaccion->bindParam(1, $cantidad, PDO::PARAM_INT);
                $stmtTransaccion->bindParam(2, $idAdministrador, PDO::PARAM_INT);
                $stmtTransaccion->bindParam(3, $idProducto, PDO::PARAM_INT);
                $stmtTransaccion->bindParam(4, $idTipoTransaccion, PDO::PARAM_INT);

                if ($stmtTransaccion->execute()) {
                    // 3. Registrar en la tabla Comprobante
                    $sqlComprobante = "INSERT INTO Comprobante (Id_Regente, Id_Producto, Cantidad, Fecha_Venta, Total)
                                       VALUES (?, ?, ?, ?, ?)";
                    $stmtComprobante = $pdo->prepare($sqlComprobante);
                    $stmtComprobante->bindParam(1, $idRegente, PDO::PARAM_INT);
                    $stmtComprobante->bindParam(2, $idProducto, PDO::PARAM_INT);
                    $stmtComprobante->bindParam(3, $cantidad, PDO::PARAM_INT);
                    $stmtComprobante->bindParam(4, $fecha);
                    $stmtComprobante->bindParam(5, $total, PDO::PARAM_STR);

                    if ($stmtComprobante->execute()) {
                        // 4. Actualizar el inventario
                        $sqlActualizarStock = "UPDATE Producto SET Cantidad_Stock = Cantidad_Stock - ? 
                                               WHERE Id_Producto = ? AND Cantidad_Stock >= ?";
                        $stmtActualizarStock = $pdo->prepare($sqlActualizarStock);
                        $stmtActualizarStock->bindParam(1, $cantidad, PDO::PARAM_INT);
                        $stmtActualizarStock->bindParam(2, $idProducto, PDO::PARAM_INT);
                        $stmtActualizarStock->bindParam(3, $cantidad, PDO::PARAM_INT);

                        if ($stmtActualizarStock->execute()) {
                            // Confirmamos la transacción si todo salió bien
                            $pdo->commit();
                            echo "<script>alert('Producto registrado y eliminado exitosamente.'); window.location.href = 'index.php';</script>";
                        } else {
                            // Rollback en caso de fallo en la actualización del inventario
                            $pdo->rollBack();
                            echo "<script>alert('No se pudo actualizar el inventario.'); window.history.back();</script>";
                        }
                    } else {
                        // Rollback en caso de fallo al registrar el comprobante
                        $pdo->rollBack();
                        echo "<script>alert('Error al registrar el comprobante.'); window.history.back();</script>";
                    }
                } else {
                    // Rollback en caso de fallo al registrar la transacción
                    $pdo->rollBack();
                    echo "<script>alert('Error al registrar la transacción.'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Producto no encontrado.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Error al obtener el precio del producto.'); window.history.back();</script>";
        }
    }
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
