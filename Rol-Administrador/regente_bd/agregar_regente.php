<?php
include 'conexion_regente.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    // Obtener los valores del formulario y verificar que existan
    $nombre = $_POST['nombre'] ?? null;
    $apellido = $_POST['apellido'] ?? null;
    $dni = $_POST['dni'] ?? null;
    $fecha_contratacion = $_POST['fecha_contratacion'] ?? null;
    $licencia = $_POST['licencia'] ?? null;
    $correo = $_POST['correo'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $id_turno = $_POST['turno'] ?? null; // Id_Turno
    $contraseña_normal = $_POST['contraseña_normal'] ?? null;
    $rol = $_POST['rol'] ?? null; // Rol (Regente o Administrador)

    // Validar que todos los campos requeridos estén presentes
    $errores = [];

    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
    if (empty($apellido)) $errores[] = "El apellido es obligatorio.";
    if (empty($dni)) $errores[] = "El DNI es obligatorio.";
    if (empty($fecha_contratacion)) $errores[] = "La fecha de contratación es obligatoria.";
    if (empty($licencia)) $errores[] = "La licencia es obligatoria.";
    if (empty($correo)) $errores[] = "El correo es obligatorio.";
    if (empty($telefono)) $errores[] = "El teléfono es obligatorio.";
    if (empty($id_turno)) $errores[] = "El ID de turno es obligatorio.";
    if (empty($contraseña_normal)) $errores[] = "La contraseña es obligatoria.";
    if (empty($rol)) $errores[] = "El rol es obligatorio.";

    if (count($errores) > 0) {
        // Mostrar errores como alertas
        echo "<script>alert('" . implode("\\n", $errores) . "'); window.history.back();</script>";
        exit;
    }

    // Encriptar la contraseña antes de guardarla
    $contraseña_encriptada = password_hash($contraseña_normal, PASSWORD_BCRYPT);

    // Determinar la tabla según el rol
    if ($rol === 'regente') {
        $sql = 'INSERT INTO regente (Nombre, Apellido, DNI, Fecha_Contratacion, Licencia, Correo, Telefono, Id_Turno, Contraseña_Normal, Contraseña_Encriptada) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    } elseif ($rol === 'administrador') {
        $sql = 'INSERT INTO administrador (Nombre, Apellido, DNI, Fecha_Contratacion, Correo, Telefono, Id_Turno, Contraseña_Normal, Contraseña_Encriptada) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
    } else {
        // Rol no válido
        echo "<script>alert('El rol especificado no es válido.'); window.history.back();</script>";
        exit;
    }

    // Preparar la consulta SQL para la tabla correspondiente
    $stmt = $pdo->prepare($sql);

    // Ejecutar la consulta con los valores obtenidos del formulario
    $params = [$nombre, $apellido, $dni, $fecha_contratacion, $correo, $telefono, $id_turno, $contraseña_normal, $contraseña_encriptada];
    if ($rol === 'regente') {
        array_splice($params, 4, 0, [$licencia]); // Agregar licencia para el regente
    }

    $stmt->execute($params);

    // Redirigir a la página principal después de agregar
    header('Location: interfaz_regente.php');
    exit;
}
?>
