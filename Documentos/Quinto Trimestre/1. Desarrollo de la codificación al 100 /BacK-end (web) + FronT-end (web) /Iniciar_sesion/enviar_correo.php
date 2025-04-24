<?php
// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


// Verificar si se recibió el correo electrónico del formulario
if (!isset($_POST['email'])) {
    die('El correo electrónico no fue proporcionado.');
}

$email = $_POST['email']; // Obtener el correo electrónico del formulario

// Validar el correo electrónico (asegurarse de que es válido)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Dirección de correo electrónico no válida.');
}

// Conexión a la base de datos usando MySQLi
include('../ROL_ADMIN/Crud/Conexion.php'); 

// Verifica que la conexión exista
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Variable para identificar si el usuario fue encontrado
$usuarioEncontrado = null;
$tabla = null;

// Verificar si el correo electrónico existe en la tabla `regente`
$queryRegente = "SELECT * FROM regente WHERE Correo = ?";
$stmtRegente = mysqli_prepare($conn, $queryRegente);
mysqli_stmt_bind_param($stmtRegente, "s", $email);
mysqli_stmt_execute($stmtRegente);
$resultRegente = mysqli_stmt_get_result($stmtRegente);

if (mysqli_num_rows($resultRegente) > 0) {
    $usuarioEncontrado = mysqli_fetch_assoc($resultRegente);
    $tabla = "regente";
}

// Verificar si el correo electrónico existe en la tabla `administrador`
if (!$usuarioEncontrado) {
    $queryAdmin = "SELECT * FROM administrador WHERE Correo = ?";
    $stmtAdmin = mysqli_prepare($conn, $queryAdmin);
    mysqli_stmt_bind_param($stmtAdmin, "s", $email);
    mysqli_stmt_execute($stmtAdmin);
    $resultAdmin = mysqli_stmt_get_result($stmtAdmin);

    if (mysqli_num_rows($resultAdmin) > 0) {
        $usuarioEncontrado = mysqli_fetch_assoc($resultAdmin);
        $tabla = "administrador";
    }
}

// Si el usuario fue encontrado en alguna tabla
if ($usuarioEncontrado && $tabla) {
    // Generar un token único
    $token = bin2hex(random_bytes(50)); // Token de 50 bytes

    // Guardar el token y su fecha de expiración en la base de datos
    $queryUpdate = "UPDATE $tabla SET token_recuperacion = ?, token_expiracion = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE Correo = ?";
    $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "ss", $token, $email);
    mysqli_stmt_execute($stmtUpdate);

    // Configuración de PHPMailer para enviar el correo
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'promedic853@gmail.com'; // Tu correo de Gmail
        $mail->Password = 'xaaa wkkd yubh zaqm';  // Contraseña de aplicación de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // ✅ Desactivar verificación del certificado para desarrollo local  
        // (SI NO ES LOCAL VOLVER A ACTIVAR EL CERTIFIFCADO PARA MAYOR SEGURIDAD)
        
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        // Remitente y destinatario
        $mail->setFrom('promedic853@gmail.com', 'No Reply');
        $mail->addAddress($email);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña';
        $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña: 
                          <a href='http://localhost/PROMEDIC/Iniciar_sesion/restablecer_contraseña.php?token=$token'>Restablecer contraseña</a>";

        // Enviar el correo
        $mail->send();

        // Mostrar una alerta y redirigir
        echo "<script>
                alert('Te hemos enviado un enlace para restablecer tu contraseña.');
                window.location.href = 'http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php';
              </script>";
    } catch (Exception $e) {
        echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
    }
} else {
    // Mostrar una alerta si el correo no está registrado en ninguna tabla
    echo "<script>
            alert('El correo electrónico no está registrado.');
            window.location.href = 'http://localhost/PROMEDIC/Iniciar_sesion/solicitar_recuperacion.php';
          </script>";
}

// Cerrar conexión
mysqli_close($conn);
?>
