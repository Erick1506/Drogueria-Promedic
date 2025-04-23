import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  FlatList,
  StyleSheet,
  Alert,
  TextInput,
  Image,
  TouchableOpacity,
  ImageBackground,
  Modal,
} from 'react-native';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { useNavigation } from '@react-navigation/native';
import { Card } from 'react-native-paper';
import { getProducts } from '../../api/api';

const ProductListScreen = () => {
  const navigation = useNavigation();
  const [products, setProducts] = useState([]);
  const [filteredProducts, setFilteredProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [isMenuVisible, setIsMenuVisible] = useState(false); // Estado para el menú
  const [isModalVisible, setIsModalVisible] = useState(false); // Estado para el modal de cerrar sesión
  const currentRoute = navigation.getState().routes[navigation.getState().index].name;

  const fetchProducts = async () => {
    try {
      const data = await getProducts(); // Usamos la función importada
      setProducts(data);
      setFilteredProducts(data);
      setLoading(false);
    } catch (error) {
      Alert.alert('Error', 'No se pudieron cargar los productos.');
      console.error(error);
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchProducts();
  }, []);

  const handleSearch = (text) => {
    setSearch(text);
    const filtered = products.filter((item) =>
      item.Nombre_Producto.toLowerCase().includes(text.toLowerCase())
    );
    setFilteredProducts(filtered);
  };

  const handleEdit = (product) => {
    navigation.navigate('ProductForm', { product });
  };

  const handleAddProduct = () => {
    navigation.navigate('ProductForm');
  };

  const toggleMenu = () => {
    setIsMenuVisible(!isMenuVisible);
  };

  const handleLogout = () => {
    // Aquí puedes agregar la lógica para cerrar sesión
    Alert.alert('Cerrar sesión', 'Sesión cerrada exitosamente.');
    // Redirigir a la pantalla de login
    navigation.navigate('Login');
  };

  const renderItem = ({ item }) => (
    <Card style={styles.card}>
      <View style={styles.cardContent}>
        <View style={styles.textSection}>
          <Text style={styles.productName}>{item.Nombre_Producto}</Text>
          <Text style={styles.productDesc}>{item.Descripcion_Producto}</Text>
          <Text style={styles.productPrice}>Precio: ${item.Precio}</Text>
        </View>
        <TouchableOpacity onPress={() => handleEdit(item)}>
          <Icon name="pencil" size={26} color="#2196f3" />
        </TouchableOpacity>
      </View>
    </Card>
  );

  return (
    <ImageBackground
      source={require('../../../assets/images/inicio.jpg')}
      style={styles.background}
    >
      <View style={styles.overlay}>
        {/* Encabezado con logo */}
        <View style={styles.header}>
          <Image source={require('../../../assets/images/logo.png')} style={styles.logo} />
          <Text style={styles.headerTitle}>PROMEDIC</Text>

          {/* Iconos en la esquina derecha */}
          <View style={{ flexDirection: 'row', alignItems: 'center', gap: 10 }}>
            {/* Icono de recarga */}
            <TouchableOpacity onPress={() => navigation.replace(currentRoute)}>
              <Icon name="refresh" size={30} color="#2196f3" />
            </TouchableOpacity>

            {/* Icono de usuario para el menú desplegable */}
            <TouchableOpacity onPress={toggleMenu}>
              <Icon name="account-circle" size={30} color="#2196f3" />
            </TouchableOpacity>
          </View>
        </View>

        {/* Menú desplegable */}
        {isMenuVisible && (
          <Modal
            transparent={true}
            animationType="fade"
            visible={isMenuVisible}
            onRequestClose={toggleMenu}
          >
            <View style={styles.modalBackground}>
              <View style={styles.menu}>
                <TouchableOpacity onPress={handleLogout}>
                  <Text style={styles.menuOption}>Cerrar sesión</Text>
                </TouchableOpacity>
                <TouchableOpacity onPress={toggleMenu}>
                  <Text style={styles.menuOption}>Cancelar</Text>
                </TouchableOpacity>
              </View>
            </View>
          </Modal>
        )}

        {/* Barra de búsqueda */}
        <TextInput
          style={styles.searchInput}
          placeholder="Buscar producto..."
          value={search}
          onChangeText={handleSearch}
          placeholderTextColor="#2196f3"
        />

        {/* Lista de productos */}
        {loading ? (
          <Text style={styles.loadingText}>Cargando productos...</Text>
        ) : (
          <FlatList
            data={filteredProducts}
            renderItem={renderItem}
            keyExtractor={(item) => item.Id_Producto.toString()}
            contentContainerStyle={styles.listContainer}
          />
        )}

        {/* Botón agregar */}
        <TouchableOpacity style={styles.addButton} onPress={handleAddProduct}>
          <Icon name="plus-circle" size={32} color="#fff" />
          <Text style={styles.addText}>Agregar Producto</Text>
        </TouchableOpacity>
      </View>
    </ImageBackground>
  );
};

const styles = StyleSheet.create({
  // Fondo de la pantalla, con ajuste para que cubra todo el espacio disponible
  background: {
    flex: 1,  // Permite que el fondo ocupe todo el espacio disponible
    resizeMode: 'cover',  // Hace que la imagen de fondo cubra todo el área sin distorsionar
  },

  // Capa superior con color semitransparente, usada para superponer sobre el fondo
  overlay: {
    flex: 1,  // Permite que la capa ocupe todo el espacio disponible
    backgroundColor: 'rgba(255,255,255,0.85)',  // Fondo blanco con 85% de opacidad para un leve oscurecimiento
    padding: 16,  // Espaciado interno para todos los elementos
  },

  // Estilos para el encabezado de la pantalla
  header: {
    flexDirection: 'row',  // Alinea el contenido de manera horizontal
    alignItems: 'center',  // Alinea los items verticalmente al centro
    marginBottom: 12,  // Espaciado inferior
    marginTop: 10,  // Espaciado superior
    justifyContent: 'space-between',  // Espacia los elementos al máximo dentro del contenedor
  },

  // Estilos para el logo dentro del encabezado
  logo: {
    width: 40,  // Ancho del logo
    height: 40,  // Altura del logo
    resizeMode: 'contain',  // Ajusta el logo manteniendo la proporción
    marginRight: 10,  // Margen a la derecha del logo para separarlo del texto
  },

  // Título del encabezado
  headerTitle: {
    fontSize: 24,  // Tamaño del texto
    color: '#2196f3',  // Color azul
    fontWeight: 'bold',  // Estilo en negrita
    transform: [{ translateX: -65 }],
  },

  // Estilos para la barra de búsqueda
  searchInput: {
    backgroundColor: '#fff',  // Fondo blanco para el campo de búsqueda
    borderRadius: 10,  // Bordes redondeados
    paddingHorizontal: 12,  // Espaciado horizontal dentro del campo
    paddingVertical: 8,  // Espaciado vertical dentro del campo
    borderWidth: 1,  // Borde del campo
    borderColor: '#2196f3',  // Color azul para el borde
    marginBottom: 10,  // Margen inferior
    color: '#000',  // Color del texto (negro)
  },

  // Contenedor para la lista de productos, con padding al fondo
  listContainer: {
    paddingBottom: 100,  // Espaciado en la parte inferior
  },

  // Estilos para el texto que indica que se está cargando la lista
  loadingText: {
    textAlign: 'center',  // Alineación centrada
    marginTop: 20,  // Margen superior
    color: '#555',  // Color gris
  },

  // Estilos para cada tarjeta de producto
  card: {
    backgroundColor: '#fff',  // Fondo blanco
    marginBottom: 12,  // Margen inferior entre tarjetas
    borderRadius: 10,  // Bordes redondeados
    elevation: 3,  // Sombra para dar profundidad
    padding: 10,  // Espaciado interno dentro de la tarjeta
    borderColor: '#2196f3',  // Borde azul
    borderWidth: 1,  // Ancho del borde
  },

  // Contenido de la tarjeta del producto, alineado horizontalmente
  cardContent: {
    flexDirection: 'row',  // Alineación horizontal de los elementos
    justifyContent: 'space-between',  // Separación máxima entre los elementos
    alignItems: 'center',  // Alinea los items verticalmente al centro
  },

  // Sección de texto dentro de la tarjeta
  textSection: {
    flex: 1,  // Toma el espacio restante
    marginRight: 10,  // Margen derecho
  },

  // Nombre del producto en la tarjeta
  productName: {
    fontSize: 18,  // Tamaño del texto
    color: '#000',  // Color negro
    fontWeight: 'bold',  // Estilo en negrita
  },

  // Descripción del producto
  productDesc: {
    color: '#555',  // Color gris
  },

  // Precio del producto, con color azul
  productPrice: {
    marginTop: 5,  // Margen superior
    color: '#2196f3',  // Color azul
    fontWeight: 'bold',  // Estilo en negrita
  },

  // Estilo para el botón de agregar producto
  addButton: {
    position: 'absolute',  // Posiciona el botón de manera absoluta en la pantalla
    bottom: 20,  // Posición 20 unidades desde el fondo
    alignSelf: 'center',  // Alinea el botón en el centro horizontal
    backgroundColor: '#2196f3',  // Color de fondo azul
    flexDirection: 'row',  // Alineación de los elementos de manera horizontal
    alignItems: 'center',  // Alinea los elementos verticalmente al centro
    padding: 12,  // Espaciado interno
    borderRadius: 30,  // Bordes redondeados
    elevation: 5,  // Sombra para dar profundidad
  },

  // Estilo para el texto dentro del botón de agregar producto
  addText: {
    color: '#fff',  // Color blanco
    fontWeight: 'bold',  // Estilo en negrita
    marginLeft: 8,  // Margen izquierdo para separar el texto del ícono
    fontSize: 16,  // Tamaño del texto
  },

  // Fondo y contenedor del modal
  modalBackground: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
  },

  // Estilo del menú dentro del modal
  menu: {
    backgroundColor: '#fff',
    borderRadius: 12,
    paddingVertical: 14,
    paddingHorizontal: 20,
    width: 200,  // Más pequeño
    alignItems: 'center',
    borderWidth: 2,
    borderColor: '#0d47a1', // Azul oscuro
    elevation: 6,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.25,
    shadowRadius: 4,
  },


  // Estilo de las opciones del menú
  menuOption: {
    fontSize: 20,                   // Texto más grande
    fontWeight: '600',              // Seminegrita
    marginBottom: 10,               // Espacio entre opciones
    color: '#1976d2',               // Azul más intenso
    textAlign: 'center',            // Alineación centrada
    letterSpacing: 0.5,             // Espaciado entre letras
  },

});


export default ProductListScreen;