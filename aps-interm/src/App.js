import React from "react";
import "./App.css"; // Asegúrate de tener el CSS adecuado para estilizar la aplicación
import ProductosVencidos from "./components/ProductosVencidos";
import "bootstrap/dist/css/bootstrap.min.css";

function App() {
  return (
    <div
      className="App"
      style={{
        backgroundAttachment: "fixed",
        backgroundColor: "#f5f5f5", // Blanco hueso
        minHeight: "100vh",
      }}
    >
      {/* Barra de Navegación */}
      <nav className="navbar navbar-expand-lg navbar-dark bg-primary shadow fixed-top">
        <div className="container-fluid">
          <a className="navbar-brand" href="#">
            <h2 className="text-white">Panel de Notificaciones</h2>
          </a>
          <button
            className="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className="collapse navbar-collapse" id="navbarNav">
            <ul className="navbar-nav me-auto mb-2 mb-lg-0">
              <li className="nav-item">
                <a
                  className="nav-link active text-white"
                  aria-current="page"
                  href="http://localhost/PROMEDIC/ROL_ADMIN/Crud/Index.php"
                >
                  Inicio
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      {/* Espaciado para la barra fija */}
      <div style={{ height: "60px" }}></div>

      {/* Contenido Principal */}
      <div className="container mt-4">
        <div className="row">
          {/* Notificaciones */}
          <div className="col-md-12 mb-4">
            <div className="inventory-notifications d-flex flex-wrap justify-content-between">
              {/* Notificación: Productos Vencidos */}
              <div
                className="notification alert alert-danger flex-fill me-3 mb-3 shadow-sm"
                role="alert"
              >
                <h4 className="alert-heading">Vencido</h4>
                <p>
                  Este producto ya ha pasado su fecha de vencimiento y no debe
                  ser utilizado.
                </p>
              </div>

              {/* Notificación: Próximo a Vencer */}
              <div
                className="notification alert alert-warning flex-fill me-3 mb-3 shadow-sm"
                role="alert"
              >
                <h4 className="alert-heading">Próximo a Vencer</h4>
                <p>
                  Este producto se vencerá en los próximos 30 días. Considere su
                  reposición.
                </p>
              </div>

              {/* Notificación: En Buen Estado */}
              <div
                className="notification alert alert-success flex-fill mb-3 shadow-sm"
                role="alert"
              >
                <h4 className="alert-heading">En Buen Estado</h4>
                <p>
                  Este producto está dentro de su fecha de vencimiento y en
                  condiciones adecuadas.
                </p>
              </div>
            </div>
          </div>

          {/* Listado de Productos Vencidos */}
          <div className="col-md-12">
            {/* Componente con diseño visual mejorado */}
            <div className="row row-cols-1 row-cols-md-3 g-4">
              <ProductosVencidos />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default App;
