import React, { useEffect, useState } from 'react';
import axios from 'axios';

const Notificaciones = () => {
  const [notificaciones, setNotificaciones] = useState([]);
  const [nuevoMensaje, setNuevoMensaje] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Obtener notificaciones al cargar el componente
    axios.get('http://localhost/notificaciones/notificaciones.php')
      .then((response) => {
        setNotificaciones(response.data);
        setLoading(false);
      })
      .catch((error) => {
        console.error('Error al obtener notificaciones:', error);
        setLoading(false);
      });
  }, []);

  // Función para agregar una nueva notificación
  const agregarNotificacion = () => {
    if (nuevoMensaje.trim()) {
      axios.post('http://localhost/notificaciones/notificaciones.php', { mensaje: nuevoMensaje })
        .then((response) => {
          if (response.data.status === 'success') {
            setNotificaciones((prevNotificaciones) => [...prevNotificaciones, nuevoMensaje]);
            setNuevoMensaje('');
          } else {
            alert(response.data.mensaje);
          }
        })
        .catch((error) => {
          console.error('Error al agregar notificación:', error);
        });
    } else {
      alert('El mensaje no puede estar vacío.');
    }
  };

  // Función para eliminar una notificación
  const eliminarNotificacion = (mensaje) => {
    axios.delete('http://localhost/notificaciones/notificaciones.php', { data: { mensaje } })
      .then((response) => {
        if (response.data.status === 'success') {
          setNotificaciones((prevNotificaciones) => 
            prevNotificaciones.filter((notificacion) => notificacion !== mensaje)
          );
        } else {
          alert(response.data.mensaje);
        }
      })
      .catch((error) => {
        console.error('Error al eliminar notificación:', error);
      });
  };

  if (loading) {
    return <div>Cargando notificaciones...</div>;
  }

  return (
    <div>
      <h2>Notificaciones del Inventario</h2>

      {/* Formulario para agregar una nueva notificación */}
      <div>
        <input
          type="text"
          value={nuevoMensaje}
          onChange={(e) => setNuevoMensaje(e.target.value)}
          placeholder="Escribe una notificación"
        />
        <button onClick={agregarNotificacion}>Agregar Notificación</button>
      </div>

      {/* Lista de notificaciones */}
      <div>
        {notificaciones.length === 0 ? (
          <div>No hay notificaciones</div>
        ) : (
          notificaciones.map((notificacion, index) => (
            <div key={index} className="notification">
              <p>{notificacion}</p>
              <button onClick={() => eliminarNotificacion(notificacion)}>Eliminar</button>
            </div>
          ))
        )}
      </div>
    </div>
  );
};

export default Notificaciones;
