// src/navigation/AppNavigator.js
import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import LoginScreen from '../screens/Auth/LoginScreen';
import ProductListScreen from '../screens/Products/ProductListScreen';
import ProductDetailScreen from '../screens/Products/ProductDetailScreen';
import ProductFormScreen from '../screens/Products/ProductFormScreen';

const Stack = createNativeStackNavigator();

console.log("Navegación iniciada..."); // Añadido para depuración

const AppNavigator = () => {
  return (
    <Stack.Navigator initialRouteName="Login">
      {/* Rutas de autenticación */}
      <Stack.Screen name="Login" component={LoginScreen} options={{ headerShown: false }} />

      {/* Rutas protegidas - Productos */}
      <Stack.Screen name="ProductList" component={ProductListScreen} options={{ title: 'Productos' }} />
      <Stack.Screen name="ProductDetail" component={ProductDetailScreen} options={{ title: 'Detalle de Producto' }} />
      <Stack.Screen name="ProductForm" component={ProductFormScreen} options={{ title: 'Agregar/Editar Producto' }} />

    </Stack.Navigator>
  );
};

export default AppNavigator;
