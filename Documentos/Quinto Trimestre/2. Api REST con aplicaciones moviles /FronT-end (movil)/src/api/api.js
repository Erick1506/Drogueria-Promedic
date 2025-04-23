import axios from 'axios';

const API_BASE_URL = 'http://192.168.1.37/react_native_promedic/BacKend';

//192.168.1.37 api de mi casa

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
});

// funcion para iniciar sesion
export const login = async (email, password) => {
  try {
    const response = await api.post('/login.php', { email, password });
    return response.data;
  } catch (error) {
    throw error;
  }
};

// funcion para obtener los producto de la bd
export const getProducts = async () => {
  try {
    const response = await api.get('/listar_productos.php');
    return response.data;
  } catch (error) {
    throw error;
  }
};

//funcion para agregar un producto

export const addProduct = async (productData) => {
  try {
    const response = await fetch(`${API_BASE_URL}/agrega.php`,{ 
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(productData),
    });

    if (!response.ok) {
      const errorText = await response.text(); // Captura el texto de error
      throw new Error(errorText || 'Error al agregar el producto');
    }

    return await response.json(); // Devuelve la respuesta JSON si es exitosa
  } catch (error) {
    console.error('Error en la solicitud:', error);
    throw error; // Vuelve a lanzar el error para que pueda ser manejado en el componente
  }
};
  

// funcion para actualizar un producto
export const updateProduct = async (id, productData) => {
  const res = await fetch(`${API_BASE_URL}/actualiza.php?id=${id}`, {
    method: 'PUT', // Asegúrate de que se use el método PUT
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(productData)
  });

  if (!res.ok) throw new Error('Error al actualizar el producto');
  const data = await res.json();
  console.log('Respuesta de la API:', data); // Agrega este log
  return data.success || data.error;
  
};



// funcion para obtener datos necesarios, ej; categorias y clasificaciones
export const getDatosIniciales = async () => {
  const res = await fetch(`${API_BASE_URL}/get_datos.php`);
  return await res.json();
};

export default api;
