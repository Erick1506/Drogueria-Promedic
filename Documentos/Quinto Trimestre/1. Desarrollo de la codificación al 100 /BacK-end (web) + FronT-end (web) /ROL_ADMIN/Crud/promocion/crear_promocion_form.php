<?php 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../Iniciar_sesion/iniciar_sesion.php");
    exit();
}
$conn = new mysqli("localhost:3307", "root", "", "promedicch");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Promoción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../cuadro.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            padding-top: 100px; /* espacio para el navbar */
        }

        .container {
            max-width: 600px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        label {
            font-weight: 600;
            color: #333;
        }

        .form-select, .form-control {
            border-radius: 8px;
        }

        button.btn-primary {
            width: 100%;
            padding: 10px;
            font-weight: bold;
            border-radius: 8px;
        }

        button.btn-primary:hover {
            background-color: #0056b3;
        }

        .navbar-brand h2 {
    margin: 0;
    font-size: 36px; /* Adjust the size as needed */
    color: #000000;
}


        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Make the navbar wider */
        .navbar .container-fluid {
            max-width: 100%; /* Full width */
            padding: 0px; /* Add padding on both sides */
        }
    </style>
    
    <script>
        function fetchClasificaciones(idCategoria) {
            fetch('get_clasificaciones.php?id_categoria=' + idCategoria)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById("clasificacion");
                    select.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(item => {
                        select.innerHTML += `<option value="${item.Id_Clasificacion}">${item.Nombre_Clasificacion}</option>`;
                    });
                });
        }

        function fetchProductos(idClasificacion) {
            fetch('get_productos.php?id_clasificacion=' + idClasificacion)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById("producto");
                    select.innerHTML = '<option value="">Seleccione...</option>';
                    data.forEach(item => {
                        select.innerHTML += `<option value="${item.Id_Producto}">${item.Nombre_Producto}</option>`;
                    });
                });
        }

        function actualizarDescuento() {
            const tipoPromo = document.getElementById("tipo_promocion").value;
            const descuento = document.getElementById("descuento");
            descuento.value = tipoPromo == 1 ? 0 : '';
            descuento.readOnly = tipoPromo == 1;
        }
    </script>
</head>

<body>

<!-- Menú de navegación -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <h2>Promedic</h2>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="http://localhost/PROMEDIC/ROL_ADMIN/Crud/index.php">Inicio</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contenedor de formulario -->
<div class="container">
    <h2>Crear nueva promoción</h2>
    <form action="guardar_promocion.php" method="POST">
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria" id="categoria" class="form-select" onchange="fetchClasificaciones(this.value)" required>
                <option value="">Seleccione...</option>
                <?php
                $cat = $conn->query("SELECT * FROM Categoria");
                while ($row = $cat->fetch_assoc()) {
                    echo "<option value='{$row['Id_Categoria']}'>{$row['Nombre_Categoria']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Clasificación</label>
            <select name="clasificacion" id="clasificacion" class="form-select" onchange="fetchProductos(this.value)" required></select>
        </div>

        <div class="mb-3">
            <label>Producto</label>
            <select name="producto" id="producto" class="form-select" required></select>
        </div>

        <div class="mb-3">
            <label>Tipo de Promoción</label>
            <select name="tipo_promocion" id="tipo_promocion" class="form-select" onchange="actualizarDescuento()" required>
                <option value="">Seleccione...</option>
                <?php
                $tipos = $conn->query("SELECT * FROM Tipo_Promocion");
                while ($row = $tipos->fetch_assoc()) {
                    echo "<option value='{$row['Id_Tipo_Promocion']}'>{$row['Tipo_Promocion']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Descuento (%)</label>
            <input type="number" name="descuento" id="descuento" class="form-control" min="0" max="100">
        </div>

        <div class="mb-3">
            <label>Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Promoción</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
