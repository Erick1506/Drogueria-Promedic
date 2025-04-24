<?php
session_start();
$conn = new mysqli("localhost:3307", "root", "", "promedicch");
// Verifica si el producto ya tiene una promoción activa
$producto_id = $_POST['producto'];
$fecha_actual = date('Y-m-d');

$verificar = $conn->query("SELECT * FROM Promocion WHERE Id_Producto = '$producto_id' AND Fecha_Fin >= '$fecha_actual'");

if ($verificar->num_rows > 0) {
    // Ya tiene promoción activa
    header("Location: http://localhost/PROMEDIC/ROL_ADMIN/Crud/promocion/promocion.php?msg=El producto ya tiene una promoción activa");
    exit();
}

$idAdmin = $_SESSION['admin_id'];
$idProducto = $_POST['producto'];
$idTipoPromocion = $_POST['tipo_promocion'];
$fechaInicio = $_POST['fecha_inicio'];
$fechaFin = $_POST['fecha_fin'];
$descuento = $_POST['tipo_promocion'] == 1 ? 0 : $_POST['descuento']; // 2x1 es 0

$sql = "INSERT INTO Promocion (Id_Administrador, Id_Producto, Id_Tipo_Promocion, Fecha_Inicio, Fecha_Fin, Descuento)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiissi", $idAdmin, $idProducto, $idTipoPromocion, $fechaInicio, $fechaFin, $descuento);

if ($stmt->execute()) {
    header("http://localhost/PROMEDIC/ROL_ADMIN/Crud/promocion/promocion.php");
} else {
    echo "Error al guardar promoción: " . $conn->error;
}
?>
