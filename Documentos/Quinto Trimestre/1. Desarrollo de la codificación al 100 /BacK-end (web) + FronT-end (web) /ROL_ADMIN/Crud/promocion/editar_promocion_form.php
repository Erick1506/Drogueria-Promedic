<?php
// Iniciar sesión si no está iniciada
session_start();

// Verificar si hay sesión activa de admin
if (!isset($_SESSION['admin_id'])) {
    // Si no hay sesión activa de admin, redirigir al inicio de sesión
    $_SESSION['error_mensaje'] = "Ups, no tienes permisos para acceder a esta página.";
    header("Location: http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php");
    exit();
}

// Evitar que el navegador guarde la página en caché para evitar regresar con "Atrás"
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Verificar que no se intente acceder a una página de regente
if (isset($_SESSION['regente_id'])) {
    // Redirigir a la página de regente
    header("Location: http://localhost/PROMEDIC/ROL_REGENTE/Crud/");
    exit();
}

// Si el usuario decide cerrar sesión, destruir la sesión y redirigir
if (isset($_GET['cerrar_sesion'])) {
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header("Location: http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php"); // Redirige al inicio de sesión
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Promoción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/Dise.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border: 2px solid #0d6efd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            margin-top: 10px;
        }
        #descuento-section {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center text-primary">Editar Promoción</h2>
            <?php
            // Conexión a la base de datos
            $servername = "localhost:3307";
            $username = "root";
            $password = "";
            $database = "promedicch";

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $sql = "SELECT p.Id_Promocion, p.Fecha_Fin, tp.Id_Tipo_Promocion, tp.Tipo_Promocion
                        FROM Promocion p
                        INNER JOIN Tipo_Promocion tp ON p.Id_Tipo_Promocion = tp.Id_Tipo_Promocion
                        WHERE p.Id_Promocion = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                } else {
                    echo "<div class='alert alert-danger'>Promoción no encontrada.</div>";
                    exit;
                }

                $stmt->close();
            } else {
                echo "<div class='alert alert-danger'>ID de promoción no proporcionado.</div>";
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fecha_fin = $_POST['fecha_fin'];
                $id_tipo_promocion = intval($_POST['tipo_promocion']);
                $descuento = isset($_POST['descuento']) ? $_POST['descuento'] : null;

                $sql_update = "UPDATE Promocion SET Fecha_Fin = ?, Id_Tipo_Promocion = ? WHERE Id_Promocion = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("sii", $fecha_fin, $id_tipo_promocion, $id);

                if ($stmt_update->execute()) {
                    // Si es una promoción con descuento, actualizar el campo de descuento
                    if ($descuento !== null) {
                        $sql_update_desc = "UPDATE Promocion SET Descuento = ? WHERE Id_Promocion = ?";
                        $stmt_desc = $conn->prepare($sql_update_desc);
                        $stmt_desc->bind_param("di", $descuento, $id);
                        $stmt_desc->execute();
                    }
                    header("Location: promocion.php?msg=Promoción actualizada correctamente");
                    exit;
                } else {
                    echo "<div class='alert alert-danger'>Error al actualizar la promoción.</div>";
                }

                $stmt_update->close();
            }

            $conn->close();
            ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="tipo_promocion" class="form-label">Tipo de Promoción</label>
                    <select name="tipo_promocion" id="tipo_promocion" class="form-select" required onchange="toggleDescuentoField()">
                        <?php
                        // Cargar tipos de promoción
                        $conn = new mysqli($servername, $username, $password, $database);

                        $sql_tipos = "SELECT Id_Tipo_Promocion, Tipo_Promocion FROM Tipo_Promocion";
                        $result_tipos = $conn->query($sql_tipos);

                        if ($result_tipos->num_rows > 0) {
                            while ($tipo = $result_tipos->fetch_assoc()) {
                                $selected = ($tipo['Id_Tipo_Promocion'] == $row['Id_Tipo_Promocion']) ? 'selected' : '';
                                echo "<option value='" . $tipo['Id_Tipo_Promocion'] . "' $selected>" . htmlspecialchars($tipo['Tipo_Promocion']) . "</option>";
                            }
                        }

                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($row['Fecha_Fin']); ?>" required>
                </div>

                <!-- Sección de descuento -->
                <div id="descuento-section" class="mb-3">
                    <label for="descuento" class="form-label">Porcentaje de Descuento</label>
                    <input type="number" name="descuento" id="descuento" class="form-control" min="0" max="100" placeholder="Porcentaje de descuento">
                </div>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="promocion.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDescuentoField() {
            const tipoPromocion = document.getElementById('tipo_promocion').value;
            const descuentoSection = document.getElementById('descuento-section');
            if (tipoPromocion === '2') {  // Aquí se asume que '1' es el Id_Tipo_Promocion de la promoción
                descuentoSection.style.display = 'block';
            } else {
                descuentoSection.style.display = 'none';
            }
        }

        // Llamar la función al cargar la página para manejar el estado inicial
        window.onload = function() {
            toggleDescuentoField();
        }
    </script>
</body>
</html>
