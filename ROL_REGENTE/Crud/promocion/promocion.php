<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../cuadro.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        .promocion-card {
            display: flex;
            flex-direction: column;
            margin: 10px;
        }

        .card {
            border: 2px solid #0d6efd;
        }

        .btn {
            margin-right: 5px;
        }

        /* Asegura que los productos se alineen en tres columnas por fila */
        @media (min-width: 768px) {
            .promociones-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .promocion-card {
                flex: 0 0 31%; /* Esto asegura que tres elementos estén por fila */
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><h2>Promedic</h2></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Inicio</a>
                    </li>
                </ul>
                <form class="d-flex mx-2" onsubmit="return false;">
                    <input class="form-control me-2" type="search" placeholder="Buscar por ID o nombre" aria-label="Search" id="searchInput">
                    <button class="btn btn-custom" type="button" onclick="buscarPromociones()">Buscar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Promociones</h1>

        <!-- Mensajes de estado -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success text-center">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="row promociones-container" id="promocionesContainer">
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

            // Consulta para obtener promociones y productos
            $sql = "SELECT p.Id_Promocion, p.Fecha_Inicio, p.Fecha_Fin, tp.Tipo_Promocion,
                        prod.Nombre_Producto, prod.Precio, prod.Descripcion_Producto, prod.Cantidad_Stock,
                        p.Descuento
                    FROM Promocion p
                    INNER JOIN Tipo_Promocion tp ON p.Id_Tipo_Promocion = tp.Id_Tipo_Promocion
                    INNER JOIN Producto prod ON p.Id_Producto = prod.Id_Producto";

            $result = $conn->query($sql);

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    ?>
                    <div class="col-md-4 mb-4 promocion-card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Producto: <?php echo htmlspecialchars($row['Nombre_Producto']); ?></h5>
                                <p class="card-text"><strong>Precio:</strong> $<?php echo htmlspecialchars($row['Precio']); ?></p>
                                <p class="card-text"><strong>Descripción:</strong> <?php echo htmlspecialchars($row['Descripcion_Producto']); ?></p>
                                <p class="card-text"><strong>Cantidad en Stock:</strong> <?php echo htmlspecialchars($row['Cantidad_Stock']); ?></p>
                                <hr>
                                <p class="card-text"><strong>Tipo de Promoción:</strong> <?php echo htmlspecialchars($row['Tipo_Promocion']); ?></p>
                                <p class="card-text"><strong>Fecha de Inicio:</strong> <?php echo htmlspecialchars($row['Fecha_Inicio']); ?></p>
                                <p class="card-text"><strong>Fecha de Fin:</strong> <?php echo htmlspecialchars($row['Fecha_Fin']); ?></p>

                                <?php if ($row['Tipo_Promocion'] == "Descuento" && $row['Descuento']): ?>
                                    <p class="card-text"><strong>Descuento:</strong> <?php echo htmlspecialchars($row['Descuento']); ?>%</p>
                                <?php endif; ?>

                                <a href="editar_promocion_form.php?id=<?php echo $row['Id_Promocion']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
            else: ?>
                <p class="text-center">No hay promociones disponibles.</p>
            <?php endif;

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function buscarPromociones() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let promociones = document.querySelectorAll(".promocion-card");

            promociones.forEach(function(promocion) {
                let nombre = promocion.querySelector(".card-title").textContent.toLowerCase();
                let descripcion = promocion.querySelector(".card-text").textContent.toLowerCase();
                if (nombre.includes(input) || descripcion.includes(input)) {
                    promocion.style.display = "";
                } else {
                    promocion.style.display = "none";
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
