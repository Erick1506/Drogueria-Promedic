import React, { useEffect, useState } from "react";
import axios from "axios";
import { FaBell, FaTrash } from "react-icons/fa";

const ProductosVencidos = ({ ancho = 1200, margenIzquierdo = 100, margenDerecho = 100 }) => {
  const [productos, setProductos] = useState([]);
  const [notificaciones, setNotificaciones] = useState([]);
  const [nuevoMensaje, setNuevoMensaje] = useState("");
  const [cargando, setCargando] = useState(true);

  // Fetch data from APIs when the component mounts
  useEffect(() => {
    const fetchData = async () => {
      try {
        const productosResponse = await axios.get(
          "http://localhost/productos/productos-vencidos.php"
        );
        const notificacionesResponse = await axios.get(
          "http://localhost/productos/notificaciones.php"
        );

        setProductos(productosResponse.data || []);
        setNotificaciones(notificacionesResponse.data || []);
      } catch (error) {
        console.error("Error al cargar datos:", error);
      } finally {
        setCargando(false);
      }
    };
    fetchData();
  }, []);

  // Function to add a new notification
  const agregarNotificacion = async () => {
    if (!nuevoMensaje.trim()) {
      alert("El mensaje no puede estar vacío.");
      return;
    }

    try {
      const response = await axios.post(
        "http://localhost/productos/notificaciones.php",
        { mensaje: nuevoMensaje }
      );
      if (response.data.status === "success") {
        setNotificaciones([
          ...notificaciones,
          { id: response.data.id, mensaje: nuevoMensaje },
        ]);
        setNuevoMensaje("");
      } else {
        alert(response.data.mensaje);
      }
    } catch (error) {
      console.error("Error al agregar notificación:", error);
    }
  };

  // Function to delete a notification
  const eliminarNotificacion = async (id) => {
    try {
      const response = await axios.delete(
        "http://localhost/productos/notificaciones.php",
        { data: { id } }
      );
      if (response.data.status === "success") {
        setNotificaciones(
          notificaciones.filter((notificacion) => notificacion.id !== id)
        );
      } else {
        alert(response.data.mensaje);
      }
    } catch (error) {
      console.error("Error al eliminar notificación:", error);
    }
  };

  // Show loading message while fetching data
  if (cargando) {
    return <div>Cargando datos...</div>;
  }

  // Estilos mejorados para hacer la UI más estética y centrada
  const styles = {
    container: {
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      gap: "20px",
      padding: "20px",
      width: "900px",
      height: "auto", // This allows the height to adjust based on the content
      marginLeft: "350px",
      marginRight: "700px",
      marginTop: "-600px", // Adjust this based on your layout needs
      border: "1px solid #ddd",
      borderRadius: "10px",
      backgroundColor: "#f9f9f9",
    },
    header: {
      textAlign: "center",
      fontFamily: "Arial, sans-serif",
      color: "#333",
      fontSize: "28px",
      fontWeight: "bold",
    },
    section: {
      width: "100%",
    },
    notificationForm: {
      display: "flex",
      alignItems: "center",
      gap: "15px",
      marginBottom: "25px",
      justifyContent: "center",
    },
    notificationList: {
      listStyleType: "none",
      padding: "0",
      width: "100%",
      margin: "0",
    },
    notificationItem: {
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between",
      padding: "12px 20px",
      borderBottom: "1px solid #ddd",
      borderRadius: "8px",
      backgroundColor: "#fff",
      marginBottom: "12px",
      boxShadow: "0 4px 8px rgba(0, 0, 0, 0.1)",
      transition: "background-color 0.3s ease",
    },
    productList: {
      listStyleType: "none",
      padding: "0",
      width: "100%",
      margin: "0",
    },
    productItem: {
      padding: "12px 20px",
      border: "1px solid #ddd",
      borderRadius: "8px",
      backgroundColor: "#fff",
      marginBottom: "12px",
      boxShadow: "0 4px 8px rgba(0, 0, 0, 0.1)",
      transition: "background-color 0.3s ease",
    },
    expired: {
      backgroundColor: "#ffe6e6",
      borderColor: "#ff4d4d",
      color: "#ff4d4d",
    },
    valid: {
      backgroundColor: "#e6ffe6",
      borderColor: "#4caf50",
      color: "#4caf50",
    },
    input: {
      padding: "10px",
      borderRadius: "5px",
      border: "1px solid #ccc",
      flex: 1,
      fontSize: "16px",
    },
    button: {
      padding: "10px 20px",
      backgroundColor: "#007bff",
      color: "white",
      border: "none",
      borderRadius: "5px",
      cursor: "pointer",
      fontSize: "16px",
    },
    deleteButton: {
      backgroundColor: "#dc3545",
      color: "white",
      border: "none",
      borderRadius: "5px",
      padding: "5px 12px",
      cursor: "pointer",
    },
  };
  

  return (
    <div style={styles.container}>
      <h2 style={styles.header}>Gestión de Productos y Notificaciones</h2>

      {/* Sección de Notificaciones */}
      <div style={styles.section}>
        <h3>Notificaciones</h3>
        <form
          style={styles.notificationForm}
          onSubmit={(e) => {
            e.preventDefault();
            agregarNotificacion();
          }}
        >
          <input
            type="text"
            value={nuevoMensaje}
            onChange={(e) => setNuevoMensaje(e.target.value)}
            placeholder="Escribe una nueva notificación"
            style={styles.input}
          />
          <button type="submit" style={styles.button}>
            Agregar
          </button>
        </form>

        <ul style={styles.notificationList}>
          {notificaciones.length === 0 ? (
            <p>No hay notificaciones.</p>
          ) : (
            notificaciones.map((notificacion) => (
              <li key={notificacion.id} style={styles.notificationItem}>
                <FaBell style={{ color: "#007bff", marginRight: "10px" }} />
                {notificacion.mensaje}
                <button
                  onClick={() => eliminarNotificacion(notificacion.id)}
                  style={styles.deleteButton}
                >
                  <FaTrash />
                </button>
              </li>
            ))
          )}
        </ul>
      </div>

      {/* Sección de Productos Vencidos */}
      <div style={styles.section}>
        <h3>Productos Vencidos</h3>
        <ul style={styles.productList}>
          {productos.length === 0 ? (
            <p>No hay productos vencidos.</p>
          ) : (
            productos.map((producto) => (
              <li
                key={producto.id}
                style={{
                  ...styles.productItem,
                  ...(producto.estado.includes("vencido")
                    ? styles.expired
                    : styles.valid),
                }}
              >
                <h4>{producto.nombre}</h4>
                <p>Fecha de vencimiento: {producto.Fecha_Vencimiento}</p>
                <p>{producto.estado.join(", ")}</p>
              </li>
            ))
          )}
        </ul>
      </div>
    </div>
  );
};

export default ProductosVencidos;
