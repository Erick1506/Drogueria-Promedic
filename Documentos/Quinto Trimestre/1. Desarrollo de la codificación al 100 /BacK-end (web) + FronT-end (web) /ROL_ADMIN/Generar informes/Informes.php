<?php
session_start();

// Verificación de sesión admin
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error_mensaje'] = "Ups, no tienes permisos para acceder a esta página.";
    header("Location: http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php");
    exit();
}

// Evitar navegación con "Atrás"
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Conexión a la base
$conn = new mysqli("localhost:3307", "root", "", "promedicch");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar categorías
$categorias = $conn->query("SELECT Id_Categoria, Nombre_Categoria FROM categoria");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Informe de Estadísticas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="informes.css"> <!-- Tu hoja de estilos personalizada si aplica -->
 <style>
            body {
    background-image: url('cuadro.jpg');
    background-size: cover; /* Asegura que la imagen cubra toda la pantalla */
    background-attachment: fixed; /* Mantiene la imagen fija al hacer scroll */
    background-position: center; /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
    min-height: 100vh; /* Asegura que el fondo cubra toda la pantalla */
}

.table {
    background-color: rgba(255, 255, 255, 0.9); /* Ajusta la transparencia de las tablas */
    border: 1px solid #ddd; /* Añade borde para mayor visibilidad */
}

.pdf-btn {
            margin-bottom: 20px;
            padding: 10px 30px;
            background-color: #dc3545; /* Rojo */
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .pdf-btn:hover {
            background-color: #c82333; /* Rojo oscuro */
        }
.btn-back {
    margin-top: -3px;
    background-color: #28a745; /* Verde */
    color: white;
    padding: 10px 30px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
}

.btn-back:hover {
    background-color: #218838; /* Verde oscuro */
}

.container {
    max-width: 1000px;
}
    
        .btn-back {
            margin-top: -3px;
            background-color: #28a745; /* Verde */
            color: white;
            padding: 10px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .btn-back:hover {
            background-color: #218838; /* Verde oscuro */
        }

        .container {
            max-width: 1000px;
        }
    </style>
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <div class="d-flex align-items-center">
      <a class="navbar-brand mb-0 h1 me-3" href="#">
        <h2 class="mb-0">Promedic</h2>
      </a>
      <ul class="navbar-nav flex-row">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="http://localhost/PROMEDIC/ROL_ADMIN/Crud/">Inicio</a>
        </li>
      </ul>
    </div>
  </div>
</nav>



<!-- Contenido -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="descargar_pdf.php" method="POST">
                <h1 class="text-center mb-4">Generar Informe de Estadísticas</h1>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de fin:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="tipo_informe" class="form-label">Tipo de informe:</label>
                    <select id="tipo_informe" name="tipo_informe" class="form-select" required>
                        <option value="">Seleccione tipo</option>
                        <option value="categoria">Por Categoría</option>
                        <option value="clasificacion">Por Clasificación</option>
                        <option value="producto">Por Producto</option>
                    </select>
                </div>

                <div id="categoria_div" class="mb-3 d-none">
                    <label for="categoria_id" class="form-label">Categoría:</label>
                    <select id="categoria_id" name="categoria_id" class="form-select">
                        <option value="">Seleccione categoría</option>
                        <?php while($cat = $categorias->fetch_assoc()): ?>
                            <option value="<?= $cat['Id_Categoria'] ?>"><?= $cat['Nombre_Categoria'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div id="clasificacion_div" class="mb-3 d-none">
                    <label for="clasificacion_id" class="form-label">Clasificación:</label>
                    <select id="clasificacion_id" name="clasificacion_id" class="form-select"></select>
                </div>

                <div id="producto_div" class="mb-3 d-none">
                    <label for="producto_id" class="form-label">Producto:</label>
                    <select id="producto_id" name="producto_id" class="form-select"></select>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary w-100 me-2">Generar Informe</button>
                    <a href="../Crud/index.html" class="btn btn-secondary w-100 ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
document.getElementById('tipo_informe').addEventListener('change', function () {
    const tipo = this.value;
    document.getElementById('categoria_div').classList.toggle('d-none', tipo === "");
    document.getElementById('clasificacion_div').classList.toggle('d-none', tipo !== "clasificacion" && tipo !== "producto");
    document.getElementById('producto_div').classList.toggle('d-none', tipo !== "producto");
});

document.getElementById('categoria_id').addEventListener('change', function () {
    const idCategoria = this.value;
    fetch('obtener_clasificaciones.php?categoria=' + idCategoria)
        .then(res => res.json())
        .then(data => {
            const clasSelect = document.getElementById('clasificacion_id');
            clasSelect.innerHTML = '<option value="">Seleccione clasificación</option>';
            data.forEach(clas => {
                clasSelect.innerHTML += `<option value="${clas.Id_Clasificacion}">${clas.Nombre_Clasificacion}</option>`;
            });
        });
});

document.getElementById('clasificacion_id').addEventListener('change', function () {
    const idClasificacion = this.value;
    fetch('obtener_productos.php?clasificacion=' + idClasificacion)
        .then(res => res.json())
        .then(data => {
            const prodSelect = document.getElementById('producto_id');
            prodSelect.innerHTML = '<option value="">Seleccione producto</option>';
            data.forEach(prod => {
                prodSelect.innerHTML += `<option value="${prod.Id_Producto}">${prod.Nombre_Producto}</option>`;
            });
        });
});
</script>

</body>
</html>
