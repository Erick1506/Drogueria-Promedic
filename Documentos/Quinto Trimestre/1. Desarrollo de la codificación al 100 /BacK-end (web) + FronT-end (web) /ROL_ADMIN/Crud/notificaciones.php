<?php

header('Content-Type: application/json');


$host     = 'localhost:3307';   // Ajusta según tu configuración
$dbname   = 'promedicch';       // Nombre de la base de datos
$username = 'root';             // Usuario de MySQL
$password = '';                 // Contraseña de MySQL

// Crear conexión PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Agregar una notificación: se espera parámetro "mensaje" en POST
    if (isset($_POST['mensaje']) && !empty(trim($_POST['mensaje']))) {
        $mensaje = trim($_POST['mensaje']);
        $stmt = $pdo->prepare("INSERT INTO mensajes_a_regente (mensaje) VALUES (:mensaje)");
        if ($stmt->execute([':mensaje' => $mensaje])) {
            echo json_encode(['status' => 'success', 'mensaje' => 'Notificación agregada.']);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al guardar la notificación.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'El mensaje no puede estar vacío.']);
    }
} elseif ($method === 'DELETE') {
    // Eliminar una notificación
    parse_str(file_get_contents("php://input"), $data);
    $mensaje = isset($data['mensaje']) ? $data['mensaje'] : '';
    
    if (empty($mensaje)) {
        echo json_encode(['status' => 'error', 'mensaje' => 'No se proporcionó el mensaje a eliminar.']);
    } else {
        // Se elimina por el contenido del mensaje. (Si hay posibilidades de duplicados, es recomendable usar un id.)
        $stmt = $pdo->prepare("DELETE FROM mensajes_a_regente WHERE mensaje = :mensaje");
        if ($stmt->execute([':mensaje' => $mensaje])) {
            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'mensaje' => 'Notificación eliminada.']);
            } else {
                echo json_encode(['status' => 'error', 'mensaje' => 'Notificación no encontrada.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al eliminar la notificación.']);
        }
    }
} else {
    // GET: Devolver todas las notificaciones
    $stmt = $pdo->prepare("SELECT mensaje, fecha FROM mensajes_a_regente ORDER BY fecha DESC");
    $stmt->execute();
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($notificaciones);
}
?>
