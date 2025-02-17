<?php
include 'conexion_proveedor.php'; // Conexión a la base de datos de proveedores

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'agregar') {
    // Obtener los valores del formulario
    $nombre = $_POST['nombre_proveedor'];
    $direccion = $_POST['direccion_proveedor'];
    $correo = $_POST['correo_proveedor'];
    $telefono = $_POST['telefono_proveedor'];
    $id_administrador = 1;

    // Preparar la consulta SQL para insertar en la tabla proveedor
    $stmt = $pdo->prepare('INSERT INTO proveedor (Nombre_Proveedor, Direccion_Proveedor, Correo, Telefono, Id_Administrador) VALUES (?, ?, ?, ?, ?)');

    // Ejecutar la consulta con los valores obtenidos del formulario
    $stmt->execute([$nombre, $direccion, $correo, $telefono, $id_administrador]);

    // Redirigir a la página principal después de agregar
    header('Location: interfaz_proveedor.php');
    exit;
}
?>
