
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Regente</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="e.css">
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
    <div class="container">
        <div class="container-form">
            <h2>Regente</h2>

            <!-- Formulario para Agregar Regente -->
            <form action="agregar_regente.php" method="post">
                <div class="container-input">
                    <input type="text" id="nombre-regente" name="nombre_regente" placeholder="Nombre del Regente" required>
                </div>
                <div class="container-input">
                    <input type="text" id="apellido-regente" name="apellido_regente" placeholder="Apellido del Regente" required>
                </div>
                <div class="container-input">
                    <input type="text" id="dni-regente" name="dni_regente" placeholder="DNI del Regente" required>
                </div>
                <div class="container-input">
                    <input type="date" id="fecha_contratacion-regente" name="fecha_contratacion_regente" placeholder="Fecha de contratación del Regente" required>
                </div>
                <div class="container-input">
                    <input type="text" id="licencia-regente" name="licencia_regente" placeholder="Licencia del Regente" required>
                </div>
                <div class="container-input">
                    <input type="email" id="correo-regente" name="correo_regente" placeholder="Correo del Regente" required>
                </div>
                <div class="container-input">
                    <input type="text" id="telefono-regente" name="telefono_regente" placeholder="Teléfono del Regente" required>
                </div>
                <div class="container-input">
    <select id="turno-regente" name="turno_regente" class="form-control" required>
        <option value="">Seleccione un Turno</option>
        <?php include 'turno.php'; ?>
    </select>
</div>
                <div class="container-input">
                    <input type="password" id="contrasena-regente" name="contraseña_normal_regente" placeholder="Contraseña del Regente" required>
                </div>
                <button type="submit" name="accion" value="agregar" class="button">Agregar Regente</button>
            </form>

            <!-- Formulario para Buscar Regente -->
            <form action="interfaz_regente.php" method="get">
                <div class="container-input">
                    <input type="text" id="buscar-regente" name="buscar_regente" placeholder="Buscar por nombre">
                </div>
                <button type="submit" name="accion" value="buscar" class="button">Buscar Regente</button>
            </form>

            <!-- Tabla de Regentes -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Fecha Contratación</th>
                        <th>Licencia</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Turno</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'conexion_regente.php';

                    // Función para obtener regentes con una búsqueda opcional
                    function obtenerRegente($busqueda = '') {
                        global $pdo;
                        if ($busqueda) {
                            $stmt = $pdo->prepare('SELECT * FROM regente WHERE Nombre LIKE ?');
                            $stmt->execute(['%' . $busqueda . '%']);
                        } else {
                            $stmt = $pdo->query('SELECT * FROM regente');
                        }
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }

                    $busqueda = $_GET['buscar_regente'] ?? '';
                    $regentes = obtenerRegente($busqueda);

                    if ($regentes) {
                        foreach ($regentes as $regente) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($regente['Id_Regente']) . "</td>
                                    <td>" . htmlspecialchars($regente['Nombre']) . "</td>
                                    <td>" . htmlspecialchars($regente['Apellido']) . "</td>
                                    <td>" . htmlspecialchars($regente['DNI']) . "</td>
                                    <td>" . htmlspecialchars($regente['Fecha_Contratacion']) . "</td>
                                    <td>" . htmlspecialchars($regente['Licencia']) . "</td>
                                    <td>" . htmlspecialchars($regente['Correo']) . "</td>
                                    <td>" . htmlspecialchars($regente['Telefono']) . "</td>
                                    <td>" . htmlspecialchars($regente['Id_Turno']) . "</td>
                                    <td>
                                        <a href='editar_regente.php?id=" . htmlspecialchars($regente['Id_Regente']) . "' class='button-link'>Editar</a>
                                        <form action='eliminar_regente.php' method='post' style='display:inline;'>
                                            <input type='hidden' name='id_eliminar_regente' value='" . htmlspecialchars($regente['Id_Regente']) . "'>
                                            <button type='submit' name='accion' value='eliminar' class='button'>Eliminar</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>No se encontraron resultados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
