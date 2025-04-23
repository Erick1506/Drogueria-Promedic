import React, { useState, useEffect } from 'react';
import { ScrollView, Text, TextInput, StyleSheet, Alert, Button, View, ImageBackground, Image } from 'react-native';
import { Picker } from '@react-native-picker/picker';
import { getDatosIniciales, addProduct, updateProduct } from '../../api/api';

const ProductFormScreen = ({ route, navigation }) => {
  const productToEdit = route.params?.product || null;

  // Estado de los campos del formulario
  const [nombreProducto, setNombreProducto] = useState('');
  const [descripcion, setDescripcion] = useState('');
  const [codigoBarras, setCodigoBarras] = useState('');
  const [costoAdquisicion, setCostoAdquisicion] = useState('');
  const [peso, setPeso] = useState('');
  const [precio, setPrecio] = useState('');
  const [cantidadStock, setCantidadStock] = useState('');
  const [cantidadMinima, setCantidadMinima] = useState('');
  const [cantidadMaxima, setCantidadMaxima] = useState('');
  const [fechaVencimiento, setFechaVencimiento] = useState('');

  const [idCategoria, setIdCategoria] = useState(null);
  const [idClasificacion, setIdClasificacion] = useState(null);
  const [idMarca, setIdMarca] = useState(null);
  const [idProveedor, setIdProveedor] = useState(null);
  const [idEstado, setIdEstado] = useState(null);
  const [idTipoPromocion, setIdTipoPromocion] = useState(null);
  const [fechaInicio, setFechaInicio] = useState('');
  const [fechaFin, setFechaFin] = useState('');
  const [descuento, setDescuento] = useState('');

  // Estado para los datos dinámicos
  const [categorias, setCategorias] = useState([]);
  const [clasificaciones, setClasificaciones] = useState([]);
  const [clasificacionesFiltradas, setClasificacionesFiltradas] = useState([]);
  const [estados, setEstados] = useState([]);
  const [marcas, setMarcas] = useState([]);
  const [proveedores, setProveedores] = useState([]);
  const [tiposPromocion, setTiposPromocion] = useState([]);

  // Cargar datos del producto a editar
  useEffect(() => {
    if (productToEdit) { // Cambia a productToEdit
      console.log('Producto a editar:', productToEdit); // Verifica que el producto tenga datos
      setNombreProducto(productToEdit.Nombre_Producto || '');
      setDescripcion(productToEdit.Descripcion_Producto || '');
      setCodigoBarras(productToEdit.Codigo_Barras || '');
      setCostoAdquisicion(productToEdit.Costo_Adquisicion || '');
      setPeso(productToEdit.Peso || '');
      setPrecio(productToEdit.Precio || '');
      setCantidadStock(productToEdit.Cantidad_Stock || '');
      setCantidadMinima(productToEdit.Cantidad_Minima || '');
      setCantidadMaxima(productToEdit.Cantidad_Maxima || '');
      setFechaVencimiento(productToEdit.Fecha_Vencimiento || '');
      setIdCategoria(productToEdit.Id_Categoria || null);
      setIdClasificacion(productToEdit.Id_Clasificacion || null);
      setIdMarca(productToEdit.Id_Marca || null);
      setIdProveedor(productToEdit.Id_Proveedor || null);
      setIdEstado(productToEdit.Id_Estado_Producto || null);
    }
  }, [productToEdit]); // Cambia a productToEdit

  // Obtener los datos iniciales de la API
  useEffect(() => {
    const fetchData = async () => {
      try {
        const datos = await getDatosIniciales();
        setCategorias(datos.categorias);
        setClasificaciones(datos.clasificaciones);
        setEstados(datos.estados_producto);
        setMarcas(datos.marcas);
        setProveedores(datos.proveedores);
        setTiposPromocion(datos.tipos_promocion);
      } catch (err) {
        Alert.alert('Error', 'No se pudo cargar la información');
      }
 };
    fetchData();
  }, []);

  // Filtrar las clasificaciones según la categoría seleccionada
  useEffect(() => {
    if (idCategoria) {
      const filtradas = clasificaciones.filter(clas => parseInt(clas.Id_Categoria) === parseInt(idCategoria));
      setClasificacionesFiltradas(filtradas);
      setIdClasificacion(null);
    } else {
      setClasificacionesFiltradas([]);
    }
  }, [idCategoria, clasificaciones]);

  // Función para enviar el formulario
  const handleSubmit = async () => {
    if (
      !nombreProducto || !descripcion || !codigoBarras || !costoAdquisicion || !peso || !precio ||
      !cantidadStock || !cantidadMinima || !cantidadMaxima || !fechaVencimiento ||
      !idCategoria || !idClasificacion || !idMarca || !idEstado || !idProveedor
    ) {
      Alert.alert('Error', 'Completa todos los campos obligatorios');
      return;
    }

    if (
      isNaN(codigoBarras) || isNaN(costoAdquisicion) || isNaN(precio) ||
      isNaN(cantidadStock) || isNaN(cantidadMinima) || isNaN(cantidadMaxima)
    ) {
      Alert.alert('Error', 'Los campos numéricos deben contener solo números válidos');
      return;
    }

    const productData = {
      nombre_producto: nombreProducto,
      descripcion_producto: descripcion,
      codigo_barras: parseInt(codigoBarras),
      costo_adquisicion: parseFloat(costoAdquisicion),
      peso,
      precio: parseFloat(precio),
      cantidad_stock: parseInt(cantidadStock),
      cantidad_minima: parseInt(cantidadMinima),
      cantidad_maxima: parseInt(cantidadMaxima),
      fecha_vencimiento: fechaVencimiento,
      id_categoria: parseInt(idCategoria),
      id_clasificacion: parseInt(idClasificacion),
      id_marca: parseInt(idMarca),
      id_estado_producto: parseInt(idEstado),
      id_proveedor: parseInt(idProveedor),
    };

    if (parseInt(idEstado) === 3) {
      if (!idTipoPromocion || !fechaInicio || !fechaFin) {
        Alert.alert('Error', 'Completa los campos de promoción obligatorios');
        return;
      }

      const tipo = tiposPromocion.find(t => t.id === parseInt(idTipoPromocion))?.nombre?.toLowerCase();
      if (tipo === 'descuento' && (!descuento || isNaN(descuento))) {
        Alert.alert('Error', 'Debes ingresar un descuento válido');
        return;
      }

      productData.id_tipo_promocion = parseInt(idTipoPromocion);
      productData.fecha_inicio = fechaInicio;
      productData.fecha_fin = fechaFin;
      if (tipo === 'descuento') productData.descuento = parseFloat(descuento);
    }

    try {
      if (productToEdit) {
        await updateProduct(productToEdit.Id_Producto, productData);
        Alert.alert('Actualizado', 'Producto actualizado con éxito');
      } else {
        await addProduct(productData);
        Alert.alert('Éxito', 'Producto guardado correctamente');
      }
      navigation.goBack();
    } catch (error) {
      Alert.alert('Error', error.message);
    }
  };

  const renderPicker = (label, selectedValue, setValue, items) => (
    <>
      <Text style={styles.label}>{label}</Text>
      <Picker selectedValue={selectedValue} onValueChange={setValue} style={styles.input}>
        <Picker.Item label="Seleccionar..." value={null} />
        {items.map(item => (
          <Picker.Item key={item.id} label={item.nombre} value={item.id} />
        ))}
      </Picker>
    </>
  );

  return (
    <ImageBackground source={require('../../../assets/images/inicio.jpg')} style={styles.container}>
      <Text style={styles.logo}>
        <Image source={require('../../../assets/images/logo.png')} style={styles.logoImage} />
        PROMEDIC
      </Text>
      <ScrollView style={styles.formContainer}>
        <Text style={styles.sectionTitle}>Información del Producto</Text>
        <TextInput style={styles.input} value={nombreProducto} onChangeText={setNombreProducto} placeholder="Nombre del Producto" />
        <TextInput style={styles.input} value={descripcion} onChangeText={setDescripcion} placeholder="Descripción" />
        <TextInput style={styles.input} value={codigoBarras} onChangeText={setCodigoBarras} placeholder="Código de Barras" keyboardType="numeric" />
        <TextInput style={styles.input} value={costoAdquisicion} onChangeText={setCostoAdquisicion} placeholder="Costo de Adquisición" keyboardType="numeric" />
        <TextInput style={styles.input} value={peso} onChangeText={setPeso} placeholder="Peso" />
        <TextInput style={styles.input} value={precio} onChangeText={setPrecio} placeholder="Precio" keyboardType="numeric" />
        <TextInput style={styles.input} value={cantidadStock} onChangeText={setCantidadStock} placeholder="Cantidad en Stock" keyboardType="numeric" />
        <TextInput style={styles.input} value={cantidadMinima} onChangeText={setCantidadMinima} placeholder="Cantidad Mínima" keyboardType="numeric" />
        <TextInput style={styles.input} value={cantidadMaxima} onChangeText={setCantidadMaxima} placeholder="Cantidad Máxima" keyboardType="numeric" />
        <TextInput style={styles.input} value={fechaVencimiento} onChangeText={setFechaVencimiento} placeholder="Fecha de Vencimiento (YYYY-MM-DD)" />

        {renderPicker("Categoría", idCategoria, setIdCategoria, categorias)}
        {renderPicker("Clasificación", idClasificacion, setIdClasificacion, clasificacionesFiltradas)}
        {renderPicker("Marca", idMarca, setIdMarca, marcas)}
        {renderPicker("Proveedor", idProveedor, setIdProveedor, proveedores)}
        {renderPicker("Estado del Producto", idEstado, setIdEstado, estados)}

        {parseInt(idEstado) === 3 && (
          <>
            <Text style={styles.sectionTitle}>Promoción</Text>
            {renderPicker("Tipo de Promoción", idTipoPromocion, setIdTipoPromocion, tiposPromocion)}
            <TextInput style={styles.input} value={fechaInicio} onChangeText={setFechaInicio} placeholder="Fecha de Inicio (YYYY-MM-DD)" />
            <TextInput style={styles.input} value={fechaFin} onChangeText={setFechaFin} placeholder="Fecha de Fin (YYYY-MM-DD)" />
            <TextInput style={styles.input} value={descuento} onChangeText={setDescuento} placeholder="Descuento (%)" keyboardType="numeric" />
          </>
        )}

        <View style={styles.buttonContainer}>
          <Button title={productToEdit ? 'Actualizar Producto' : 'Agregar Producto'} onPress={handleSubmit} />
        </View>
      </ScrollView>
    </ImageBackground>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 15,
    backgroundColor: '#fff',
  },
  logo: {
    textAlign: 'center',
    fontSize: 30,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 20,
  },
  logoImage: {
    width: 40,
    height: 40,
    marginRight: 10,
  },
  formContainer: {
    flex: 1,
    marginTop: 20,
    backgroundColor: 'rgba(255, 255, 255, 0.9)',
    borderRadius: 8,
    padding: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 10,
    color: '#333',
  },
  input: {
    borderWidth: 1,
    borderColor: '#007BFF',
    padding: 10,
    marginBottom: 10,
    borderRadius: 5,
  },
  label: {
    fontSize: 14,
    marginBottom: 5,
    color: '#333',
  },
  buttonContainer: {
    marginTop: 15,
    marginBottom: 45,
    paddingHorizontal: 60,
  },
});

export default ProductFormScreen;