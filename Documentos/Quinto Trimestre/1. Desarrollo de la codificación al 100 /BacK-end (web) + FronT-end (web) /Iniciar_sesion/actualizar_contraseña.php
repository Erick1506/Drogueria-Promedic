<?php 
// Incluir la conexión a la base de datos
include('Conexion.php');

// Verificar que la conexión está establecida
if (!isset($mysqli) || $mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Obtener el token y la nueva contraseña
$token = $_POST['token'];
$nueva_contraseña = password_hash($_POST['nueva_contraseña'], PASSWORD_BCRYPT); // Encriptar la contraseña

// Variables para identificar la tabla y columna correspondientes
$tabla = null;
$columna_contraseña = null;

// Verificar que el token es válido en la tabla `regente`
$queryRegente = "SELECT * FROM regente WHERE token_recuperacion = ? AND token_expiracion > NOW()";
$stmtRegente = $mysqli->prepare($queryRegente);
$stmtRegente->bind_param("s", $token);
$stmtRegente->execute();
$resultRegente = $stmtRegente->get_result();

if ($resultRegente->num_rows > 0) {
    $tabla = "regente";
    $columna_contraseña = "Contraseña_Encriptada"; // Columna para la contraseña en regente
}

// Si no está en `regente`, buscar en la tabla `administrador`
if (!$tabla) {
    $queryAdmin = "SELECT * FROM administrador WHERE token_recuperacion = ? AND token_expiracion > NOW()";
    $stmtAdmin = $mysqli->prepare($queryAdmin);
    $stmtAdmin->bind_param("s", $token);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    if ($resultAdmin->num_rows > 0) {
        $tabla = "administrador";
        $columna_contraseña = "Contraseña"; // Columna para la contraseña en administrador
    }
}

// Si el token es válido y se encontró en alguna tabla
if ($tabla && $columna_contraseña) {
    // Construir y ejecutar la consulta de actualización
    $queryUpdate = "UPDATE $tabla SET $columna_contraseña = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE token_recuperacion = ?";
    $stmtUpdate = $mysqli->prepare($queryUpdate);
    $stmtUpdate->bind_param("ss", $nueva_contraseña, $token);
    $stmtUpdate->execute();

    // Alerta de éxito y redirección
    echo "<script>
            alert('Tu contraseña ha sido actualizada con éxito.');
            window.location.href = 'http://localhost/PROMEDIC/Iniciar_sesion/Iniciar_sesion.php'; // Redirigir a la página de login
          </script>";
} else {
    // Alerta de error si el enlace es inválido o ha expirado
    echo "<script>
            alert('El enlace de restablecimiento es inválido o ha expirado.');
            window.location.href = 'http://localhost/PROMEDIC/Iniciar_sesion/solicitar_recuperacion.php'; // Redirigir a la página de recuperación
          </script>";
}

// Cerrar la conexión
$mysqli->close();
?>
