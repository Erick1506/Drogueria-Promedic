// src/utils/validations.js
// Funciones para validar formularios
export const validarCampoRequerido = (valor) => {
    return valor !== null && valor !== undefined && valor.toString().trim() !== '';
  };
  
  export const validarNumero = (valor) => {
    return !isNaN(valor);
  };
  
  