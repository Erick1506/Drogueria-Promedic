import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';
import 'bootstrap/dist/css/bootstrap.min.css';  // Para los estilos de Bootstrap
import 'bootstrap/dist/js/bootstrap.bundle.min.js';  // Para la funcionalidad de los modales y otros componentes de Bootstrap


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
