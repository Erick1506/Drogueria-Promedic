// src/screens/Products/ProductDetailScreen.js
import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';

const ProductDetailScreen = ({ route, navigation }) => {
  const { product } = route.params;

  // Verificamos si el producto está disponible
  if (!product) {
    return (
      <View style={styles.container}>
        <Text>Producto no encontrado</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>{product.Nombre_Producto}</Text>
      <Text>{product.Descripcion_Producto}</Text>
      <Text>Precio: {product.Precio}</Text>
      <Text>Cantidad: {product.Cantidad_Stock}</Text>
      {/* Usamos TouchableOpacity en lugar de Button */}
      <TouchableOpacity
        style={styles.button}
        onPress={() => navigation.navigate('ProductForm', { product })}
      >
        <Text style={styles.buttonText}>Editar</Text>
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16 },
  title: { fontSize: 24, marginBottom: 16 },
  button: {
    backgroundColor: '#007BFF',
    padding: 12,
    borderRadius: 8,
    marginTop: 16,
    alignItems: 'center',
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
  },
});

export default ProductDetailScreen;
