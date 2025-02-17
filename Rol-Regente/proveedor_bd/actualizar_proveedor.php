<?php
include 'conexion_proveedor.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'actualizar') {
    // Obtener los valores del formulario
    $id_proveedor = $_POST['id_proveedor'];
    $nuevo_nombre = $_POST['nuevo_nombre'];
    $nueva_direccion = $_POST['nueva_direccion'];
    $nuevo_correo = $_POST['nuevo_correo'];
    $nuevo_telefono = $_POST['nuevo_telefono'];
    $nuevo_administrador = $_POST['nuevo_administrador'];

    // Preparar la consulta SQL para actualizar los datos del proveedor
    $stmt = $pdo->prepare('
        UPDATE proveedor 
        SET Nombre_Proveedor = ?, Direccion_Proveedor = ?, Correo = ?, Telefono = ?, Id_Administrador = ?
        WHERE Id_Proveedor = ?
    ');

    // Ejecutar la consulta con los valores obtenidos del formulario
    $stmt->execute([$nuevo_nombre, $nueva_direccion, $nuevo_correo, $nuevo_telefono, $nuevo_administrador, $id_proveedor]);

    // Redirigir a la página principal después de actualizar
    header('Location: interfaz_proveedor.php');
    exit;
}
?>
