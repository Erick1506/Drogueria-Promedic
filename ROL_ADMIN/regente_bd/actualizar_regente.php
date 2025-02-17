<?php
include 'conexion_regente.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'actualizar') {
    // Obtener el ID del regente a actualizar
    $id = $_POST['id_regente'];
    
    // Obtener los nuevos valores del formulario
    $nombre = $_POST['nuevo_nombre'] ?? null;
    $apellido = $_POST['nuevo_apellido'] ?? null;
    $dni = $_POST['nuevo_dni'] ?? null;
    $correo = $_POST['nuevo_correo'] ?? null;
    $telefono = $_POST['nuevo_telefono'] ?? null;
    $fecha_contratacion = $_POST['nueva_fecha_contratacion'] ?? null;
    $licencia = $_POST['nueva_licencia'] ?? null;
    $id_turno = $_POST['nuevo_turno'] ?? null; // Cambio a Id_Turno
    $contraseña_normal = $_POST['nueva_contraseña_normal'] ?? null;

    // Inicializar consulta SQL y parámetros
    $sql = 'UPDATE regente SET';
    $params = [];
    $updates = [];

    // Añadir campos a actualizar si tienen un valor
    if (!empty($nombre)) {
        $updates[] = ' Nombre = ?';
        $params[] = $nombre;
    }
    if (!empty($apellido)) {
        $updates[] = ' Apellido = ?';
        $params[] = $apellido;
    }
    if (!empty($dni)) {
        $updates[] = ' DNI = ?';
        $params[] = $dni;
    }
    if (!empty($correo)) {
        $updates[] = ' Correo = ?';
        $params[] = $correo;
    }
    if (!empty($telefono)) {
        $updates[] = ' Telefono = ?';
        $params[] = $telefono;
    }
    if (!empty($fecha_contratacion)) {
        $updates[] = ' Fecha_Contratacion = ?';
        $params[] = $fecha_contratacion;
    }
    if (!empty($licencia)) {
        $updates[] = ' Licencia = ?';
        $params[] = $licencia;
    }
    if (!empty($id_turno)) { // Cambio a Id_Turno
        $updates[] = ' Id_Turno = ?';
        $params[] = $id_turno;
    }
    if (!empty($contraseña_normal)) {
        $updates[] = ' Contraseña_Normal = ?';
        $params[] = $contraseña_normal;
        $contraseña_encriptada = password_hash($contraseña_normal, PASSWORD_BCRYPT);
        $updates[] = ' Contraseña_Encriptada = ?';
        $params[] = $contraseña_encriptada;
    }

    // Solo ejecutar la actualización si hay algo que actualizar
    if (!empty($updates)) {
        $sql .= implode(', ', $updates); // Combinar los campos a actualizar
        $sql .= ' WHERE Id_Regente = ?';
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Redirigir a la página principal después de actualizar
        header('Location: interfaz_regente.php');
        exit;
    } else {
        // Si no hay campos para actualizar
        echo "No hay campos para actualizar.";
    }
}
?>
