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
    <title>Gestión de Proveedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">

    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 80px; /* Ajusta este valor según la altura del navbar */
        }
        .navbar {
            z-index: 1030; /* Asegura que esté por encima de otros elementos */
        }
    </style>
</head>
<body>
    <!-- Navbar fijo -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><h2>Promedic</h2></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="http://localhost/PROMEDIC/Rol_Admin/Crud/index.php">Inicio</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        <div class="container-form">
            <h2>Proveedor</h2>

            <!-- Formulario para Agregar_proveedor -->
            <form action="agregar_proveedor.php" method="post">
                <div class="container-input">
                    <input type="text" name="nombre_proveedor" placeholder="Nombre del Proveedor" required>
                </div>
                <div class="container-input">
                    <input type="text" name="direccion_proveedor" placeholder="Dirección del Proveedor" required>
                </div>
                <div class="container-input">
                    <input type="email" name="correo_proveedor" placeholder="Correo del Proveedor" required>
                </div>
                <div class="container-input">
                    <input type="text" name="telefono_proveedor" placeholder="Teléfono del Proveedor" required>
                </div>
               
                <button type="submit" name="accion" value="agregar" class="button">Agregar Proveedor</button>
            </form>

            <!-- Formulario para Buscar_proveedor -->
            <form action="interfaz_proveedor.php" method="get">
                <div class="container-input">
                    <input type="text" id="buscar-proveedor" name="buscar_proveedor" placeholder="Buscar por nombre">
                </div>
                <button type="submit" name="accion" value="buscar" class="button">Buscar Proveedor</button>
            </form>

            <!-- Tabla de Proveedores -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>ID Administrador</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'conexion_proveedor.php';

                    // Obtener_proveedores
                    function obtenerProveedor($busqueda = '') {
                        global $pdo;
                        if ($busqueda) {
                            $stmt = $pdo->prepare('SELECT * FROM proveedor WHERE Nombre_Proveedor LIKE ?');
                            $stmt->execute(['%' . $busqueda . '%']);
                        } else {
                            $stmt = $pdo->query('SELECT * FROM proveedor');
                        }
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }

                    $busqueda = isset($_GET['buscar_proveedor']) ? $_GET['buscar_proveedor'] : '';
                    $proveedores = obtenerProveedor($busqueda);

                    if ($proveedores) {
                        foreach ($proveedores as $proveedor) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($proveedor['Id_Proveedor']) . "</td>
                                    <td>" . htmlspecialchars($proveedor['Nombre_Proveedor']) . "</td>
                                    <td>" . htmlspecialchars($proveedor['Direccion_Proveedor']) . "</td>
                                    <td>" . htmlspecialchars($proveedor['Correo']) . "</td>
                                    <td>" . htmlspecialchars($proveedor['Telefono']) . "</td>
                                    <td>" . htmlspecialchars($proveedor['Id_Administrador']) . "</td>
                                    <td>
                                      <a href='editar_proveedor.php?id=" . htmlspecialchars($proveedor['Id_Proveedor']) . "' class='button-link'>Editar</a>
                                      <br>
                                        <form action='eliminar_proveedor.php' method='post' style='display:inline;'>
                                            <input type='hidden' name='id_eliminar_proveedor' value='" . htmlspecialchars($proveedor['Id_Proveedor']) . "'>
                                            <button type='submit' name='accion' value='eliminar' class='button'>Eliminar</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No se encontraron resultados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

                                        