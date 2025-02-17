-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-10-2024 a las 21:55:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecpromedic`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `Id_Administrador` int(11) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Apellido` varchar(45) NOT NULL,
  `Correo` varchar(45) NOT NULL,
  `Telefono` float NOT NULL,
  `Contraseña_Normal` varchar(255) DEFAULT NULL,
  `Contraseña_Encriptada` varbinary(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `Id_Categoria` int(11) NOT NULL,
  `Nombre_Categoria` varchar(400) NOT NULL,
  `Descripcion_Categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`Id_Categoria`, `Nombre_Categoria`, `Descripcion_Categoria`) VALUES
(1, 'Medicamentos', 'Medicamentos generales'),
(2, 'Suplementos', 'Suplementos alimenticios'),
(3, 'Cuidado Personal', 'Productos de cuidado personal'),
(4, 'Instrumental', 'Instrumentos médicos'),
(5, 'Ortopedia', 'Equipos ortopédicos'),
(6, 'Higiene', 'Productos de higiene personal'),
(7, 'Bebidas', 'Bebidas medicinales'),
(8, 'Accesorios', 'Accesorios médicos'),
(9, 'Vitaminas', 'Vitaminas y minerales'),
(10, 'Belleza', 'Productos de belleza'),
(11, 'Calzado', 'Calzado ortopédico'),
(12, 'Cosméticos', 'Cosméticos medicinales'),
(13, 'Ropa Médica', 'Uniformes y ropa para médicos'),
(14, 'Protección', 'Equipos de protección personal'),
(15, 'Electrónica', 'Dispositivos electrónicos médicos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE `clasificacion` (
  `Id_Clasificacion` int(11) NOT NULL,
  `Nombre_Clasificacion` varchar(200) NOT NULL,
  `Descripcion_Clasificacion` varchar(200) NOT NULL,
  `Id_Categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clasificacion`
--

INSERT INTO `clasificacion` (`Id_Clasificacion`, `Nombre_Clasificacion`, `Descripcion_Clasificacion`, `Id_Categoria`) VALUES
(1, 'Analgésicos', 'Medicamentos para aliviar el dolor', 1),
(2, 'Multivitamínicos', 'Suplementos con varias vitaminas', 2),
(3, 'Champú', 'Producto para el cuidado del cabello', 3),
(4, 'Pinzas Quirúrgicas', 'Instrumental utilizado en cirugías', 4),
(5, 'Sillas de Ruedas', 'Equipos ortopédicos para movilidad', 5),
(6, 'Jabón Antibacterial', 'Producto de higiene para manos', 6),
(7, 'Agua Mineral', 'Bebida rica en minerales', 7),
(8, 'Estetoscopio', 'Accesorio utilizado por médicos', 8),
(9, 'Vitamina C', 'Suplemento de ácido ascórbico', 9),
(10, 'Maquillaje Hipoalergénico', 'Productos de belleza para pieles sensibles', 10),
(11, 'Zapatos Ortopédicos', 'Calzado ortopédico para personas con problemas en los pies', 11),
(12, 'Labiales Medicinales', 'Cosméticos con propiedades curativas', 12),
(13, 'Batas Médicas', 'Ropa para personal de salud', 13),
(14, 'Mascarillas N95', 'Equipos de protección personal', 14),
(15, 'Monitores de Signos Vitales', 'Dispositivos electrónicos para medir signos vitales', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante`
--

CREATE TABLE `comprobante` (
  `Id_Comprobante` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Fecha_Venta` date NOT NULL,
  `Total` int(11) NOT NULL,
  `Id_Regente` int(11) DEFAULT NULL,
  `Id_Producto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuento`
--

CREATE TABLE `descuento` (
  `Id_Descuento` int(11) NOT NULL,
  `Descuento` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_producto`
--

CREATE TABLE `estado_producto` (
  `Id_Estado_Producto` int(11) NOT NULL,
  `Tipo_Estado_Producto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_producto`
--

INSERT INTO `estado_producto` (`Id_Estado_Producto`, `Tipo_Estado_Producto`) VALUES
(1, 'Agotado'),
(2, 'En Promoción'),
(3, 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_fin`
--

CREATE TABLE `fecha_fin` (
  `Id_Fecha_Fin` int(11) NOT NULL,
  `Fecha_Fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_inicio`
--

CREATE TABLE `fecha_inicio` (
  `Id_Fecha_Inicio` int(11) NOT NULL,
  `Fecha_Inicio` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_insercion`
--

CREATE TABLE `fecha_insercion` (
  `Id_Fecha_Insercion` int(11) NOT NULL,
  `Fecha_Insercion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_stock`
--

CREATE TABLE `fecha_stock` (
  `Id_Fecha_Stock` int(11) NOT NULL,
  `Fecha_Stock` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_vencimiento`
--

CREATE TABLE `fecha_vencimiento` (
  `Id_Fecha_Vencimiento` int(11) NOT NULL,
  `Fecha_Vencimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fecha_vencimiento`
--

INSERT INTO `fecha_vencimiento` (`Id_Fecha_Vencimiento`, `Fecha_Vencimiento`) VALUES
(1, '2025-01-15'),
(2, '2025-06-20'),
(3, '2026-03-10'),
(4, '2026-11-30'),
(5, '2025-09-05'),
(6, '2027-02-18'),
(7, '2027-07-25'),
(8, '2028-04-12'),
(9, '2028-12-01'),
(10, '2029-05-22'),
(11, '2029-09-14'),
(12, '2026-08-08'),
(13, '2027-10-19'),
(14, '2028-06-27'),
(15, '2029-03-03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formula_medica`
--

CREATE TABLE `formula_medica` (
  `Id_Formula` int(11) NOT NULL,
  `Nombre_Paciente` varchar(45) NOT NULL,
  `Identificacion_Paciente` int(11) NOT NULL,
  `Id_Administrador` int(11) DEFAULT NULL,
  `Id_Fecha_Insercion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `Id_Marca` int(11) NOT NULL,
  `Marca_Producto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`Id_Marca`, `Marca_Producto`) VALUES
(1, 'Pfizer'),
(2, 'Bayer'),
(3, 'Roche'),
(4, 'Johnson & Johnson'),
(5, 'Nestlé Health Science'),
(6, 'GNC'),
(7, 'LOréal'),
(8, 'Philips Healthcare'),
(9, '3M Healthcare'),
(10, 'Orthofix'),
(11, 'Hanesbrands Inc.'),
(12, 'Kimberly-Clark'),
(13, 'Nivea'),
(14, 'Colgate-Palmolive'),
(15, 'Medtronic');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion_stock`
--

CREATE TABLE `notificacion_stock` (
  `Id_Notificacion` int(11) NOT NULL,
  `Mensaje` varchar(200) NOT NULL,
  `Id_Administrador` int(11) DEFAULT NULL,
  `Id_Fecha_Stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `Id_Producto` int(11) NOT NULL,
  `Nombre_Producto` varchar(200) NOT NULL,
  `Descripcion_Producto` varchar(200) NOT NULL,
  `Codigo_Barras` int(11) NOT NULL,
  `Costo_Adquisicion` double NOT NULL,
  `Peso` varchar(200) NOT NULL,
  `Precio` double NOT NULL,
  `Cantidad_Stock` int(11) NOT NULL,
  `Id_Clasificacion` int(11) DEFAULT NULL,
  `Id_Categoria` int(11) DEFAULT NULL,
  `Id_Estado_Producto` int(11) DEFAULT NULL,
  `Id_Marca` int(11) DEFAULT NULL,
  `Id_Fecha_Vencimiento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `Id_Promocion` int(11) NOT NULL,
  `Id_Administrador` int(11) DEFAULT NULL,
  `Id_Producto` int(11) DEFAULT NULL,
  `Id_Descuento` int(11) DEFAULT NULL,
  `Id_Fecha_Inicio` int(11) DEFAULT NULL,
  `Id_Fecha_Fin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `Id_Proveedor` int(11) NOT NULL,
  `Nombre_Proveedor` varchar(45) NOT NULL,
  `Direccion_Proveedor` varchar(45) NOT NULL,
  `Correo` varchar(45) NOT NULL,
  `Telefono` float NOT NULL,
  `Id_Administrador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regente`
--

CREATE TABLE `regente` (
  `Id_Regente` int(11) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Apellido` varchar(45) NOT NULL,
  `DNI` int(11) NOT NULL,
  `Fecha_Contratacion` date NOT NULL,
  `Licencia` int(11) NOT NULL,
  `Correo` varchar(45) NOT NULL,
  `Telefono` float DEFAULT NULL,
  `Id_Turno` int(11) DEFAULT NULL,
  `Contraseña_Normal` varchar(255) DEFAULT NULL,
  `Contraseña_Encriptada` varbinary(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `regente`
--

INSERT INTO `regente` (`Id_Regente`, `Nombre`, `Apellido`, `DNI`, `Fecha_Contratacion`, `Licencia`, `Correo`, `Telefono`, `Id_Turno`, `Contraseña_Normal`, `Contraseña_Encriptada`) VALUES
(3, 'Juan', 'Perez', 90980, '2024-10-05', 123456, 'juan@gmail.com', 42525400, 2, NULL, 0x24327924313024415a47306a584949343049533941306d2e6f39594a2e557a3969357252302e646c6a47684f79724478576b397162586936584e744b),
(4, 'stiven', 'mora', 1234, '2024-10-01', 2341, 'darwin@gmail.com', 3214570000, 1, NULL, 0x243279243130244c4b5339383476536668314c687951646250614c304f57776e7266575136316a687a6676684e41353745514b5955512f654a466669);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_transaccion`
--

CREATE TABLE `tipo_transaccion` (
  `Id_Tipo_Transaccion` int(11) NOT NULL,
  `Tipo_Transaccion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `Id_Transacciones` int(11) NOT NULL,
  `Fecha_Transacciones` date NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Detalles_Adicionales` varchar(45) NOT NULL,
  `Id_Administrador` int(11) DEFAULT NULL,
  `Id_Producto` int(11) DEFAULT NULL,
  `Id_Tipo_Transaccion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `Id_Turno` int(11) NOT NULL,
  `Turno` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`Id_Turno`, `Turno`) VALUES
(1, 'Mañana'),
(2, 'Tarde'),
(3, 'Noche');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`Id_Administrador`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`Id_Categoria`);

--
-- Indices de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  ADD PRIMARY KEY (`Id_Clasificacion`),
  ADD KEY `Id_Categoria` (`Id_Categoria`);

--
-- Indices de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  ADD PRIMARY KEY (`Id_Comprobante`),
  ADD KEY `Id_Regente` (`Id_Regente`),
  ADD KEY `Id_Producto` (`Id_Producto`);

--
-- Indices de la tabla `descuento`
--
ALTER TABLE `descuento`
  ADD PRIMARY KEY (`Id_Descuento`);

--
-- Indices de la tabla `estado_producto`
--
ALTER TABLE `estado_producto`
  ADD PRIMARY KEY (`Id_Estado_Producto`);

--
-- Indices de la tabla `fecha_fin`
--
ALTER TABLE `fecha_fin`
  ADD PRIMARY KEY (`Id_Fecha_Fin`);

--
-- Indices de la tabla `fecha_inicio`
--
ALTER TABLE `fecha_inicio`
  ADD PRIMARY KEY (`Id_Fecha_Inicio`);

--
-- Indices de la tabla `fecha_insercion`
--
ALTER TABLE `fecha_insercion`
  ADD PRIMARY KEY (`Id_Fecha_Insercion`);

--
-- Indices de la tabla `fecha_stock`
--
ALTER TABLE `fecha_stock`
  ADD PRIMARY KEY (`Id_Fecha_Stock`);

--
-- Indices de la tabla `fecha_vencimiento`
--
ALTER TABLE `fecha_vencimiento`
  ADD PRIMARY KEY (`Id_Fecha_Vencimiento`);

--
-- Indices de la tabla `formula_medica`
--
ALTER TABLE `formula_medica`
  ADD PRIMARY KEY (`Id_Formula`),
  ADD KEY `Id_Administrador` (`Id_Administrador`),
  ADD KEY `Id_Fecha_Insercion` (`Id_Fecha_Insercion`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`Id_Marca`);

--
-- Indices de la tabla `notificacion_stock`
--
ALTER TABLE `notificacion_stock`
  ADD PRIMARY KEY (`Id_Notificacion`),
  ADD KEY `Id_Administrador` (`Id_Administrador`),
  ADD KEY `Id_Fecha_Stock` (`Id_Fecha_Stock`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`Id_Producto`),
  ADD KEY `Id_Clasificacion` (`Id_Clasificacion`),
  ADD KEY `Id_Categoria` (`Id_Categoria`),
  ADD KEY `Id_Estado_Producto` (`Id_Estado_Producto`),
  ADD KEY `Id_Marca` (`Id_Marca`),
  ADD KEY `Id_Fecha_Vencimiento` (`Id_Fecha_Vencimiento`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`Id_Promocion`),
  ADD KEY `Id_Administrador` (`Id_Administrador`),
  ADD KEY `Id_Producto` (`Id_Producto`),
  ADD KEY `Id_Descuento` (`Id_Descuento`),
  ADD KEY `Id_Fecha_Inicio` (`Id_Fecha_Inicio`),
  ADD KEY `Id_Fecha_Fin` (`Id_Fecha_Fin`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`Id_Proveedor`),
  ADD KEY `Id_Administrador` (`Id_Administrador`);

--
-- Indices de la tabla `regente`
--
ALTER TABLE `regente`
  ADD PRIMARY KEY (`Id_Regente`),
  ADD KEY `Id_Turno` (`Id_Turno`);

--
-- Indices de la tabla `tipo_transaccion`
--
ALTER TABLE `tipo_transaccion`
  ADD PRIMARY KEY (`Id_Tipo_Transaccion`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`Id_Transacciones`),
  ADD KEY `Id_Administrador` (`Id_Administrador`),
  ADD KEY `Id_Producto` (`Id_Producto`),
  ADD KEY `Id_Tipo_Transaccion` (`Id_Tipo_Transaccion`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`Id_Turno`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `Id_Administrador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `Id_Categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  MODIFY `Id_Clasificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  MODIFY `Id_Comprobante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `descuento`
--
ALTER TABLE `descuento`
  MODIFY `Id_Descuento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_producto`
--
ALTER TABLE `estado_producto`
  MODIFY `Id_Estado_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `fecha_fin`
--
ALTER TABLE `fecha_fin`
  MODIFY `Id_Fecha_Fin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fecha_inicio`
--
ALTER TABLE `fecha_inicio`
  MODIFY `Id_Fecha_Inicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fecha_insercion`
--
ALTER TABLE `fecha_insercion`
  MODIFY `Id_Fecha_Insercion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fecha_stock`
--
ALTER TABLE `fecha_stock`
  MODIFY `Id_Fecha_Stock` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fecha_vencimiento`
--
ALTER TABLE `fecha_vencimiento`
  MODIFY `Id_Fecha_Vencimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `formula_medica`
--
ALTER TABLE `formula_medica`
  MODIFY `Id_Formula` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `Id_Marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `notificacion_stock`
--
ALTER TABLE `notificacion_stock`
  MODIFY `Id_Notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Id_Producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `Id_Promocion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `Id_Proveedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `regente`
--
ALTER TABLE `regente`
  MODIFY `Id_Regente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_transaccion`
--
ALTER TABLE `tipo_transaccion`
  MODIFY `Id_Tipo_Transaccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `Id_Transacciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `Id_Turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  ADD CONSTRAINT `clasificacion_ibfk_1` FOREIGN KEY (`Id_Categoria`) REFERENCES `categoria` (`Id_Categoria`);

--
-- Filtros para la tabla `comprobante`
--
ALTER TABLE `comprobante`
  ADD CONSTRAINT `comprobante_ibfk_1` FOREIGN KEY (`Id_Regente`) REFERENCES `regente` (`Id_Regente`),
  ADD CONSTRAINT `comprobante_ibfk_2` FOREIGN KEY (`Id_Producto`) REFERENCES `producto` (`Id_Producto`);

--
-- Filtros para la tabla `formula_medica`
--
ALTER TABLE `formula_medica`
  ADD CONSTRAINT `formula_medica_ibfk_1` FOREIGN KEY (`Id_Administrador`) REFERENCES `administrador` (`Id_Administrador`),
  ADD CONSTRAINT `formula_medica_ibfk_2` FOREIGN KEY (`Id_Fecha_Insercion`) REFERENCES `fecha_insercion` (`Id_Fecha_Insercion`);

--
-- Filtros para la tabla `notificacion_stock`
--
ALTER TABLE `notificacion_stock`
  ADD CONSTRAINT `notificacion_stock_ibfk_1` FOREIGN KEY (`Id_Administrador`) REFERENCES `administrador` (`Id_Administrador`),
  ADD CONSTRAINT `notificacion_stock_ibfk_2` FOREIGN KEY (`Id_Fecha_Stock`) REFERENCES `fecha_stock` (`Id_Fecha_Stock`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`Id_Clasificacion`) REFERENCES `clasificacion` (`Id_Clasificacion`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`Id_Categoria`) REFERENCES `categoria` (`Id_Categoria`),
  ADD CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`Id_Estado_Producto`) REFERENCES `estado_producto` (`Id_Estado_Producto`),
  ADD CONSTRAINT `producto_ibfk_4` FOREIGN KEY (`Id_Marca`) REFERENCES `marca` (`Id_Marca`),
  ADD CONSTRAINT `producto_ibfk_5` FOREIGN KEY (`Id_Fecha_Vencimiento`) REFERENCES `fecha_vencimiento` (`Id_Fecha_Vencimiento`);

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `promocion_ibfk_1` FOREIGN KEY (`Id_Administrador`) REFERENCES `administrador` (`Id_Administrador`),
  ADD CONSTRAINT `promocion_ibfk_2` FOREIGN KEY (`Id_Producto`) REFERENCES `producto` (`Id_Producto`),
  ADD CONSTRAINT `promocion_ibfk_3` FOREIGN KEY (`Id_Descuento`) REFERENCES `descuento` (`Id_Descuento`),
  ADD CONSTRAINT `promocion_ibfk_4` FOREIGN KEY (`Id_Fecha_Inicio`) REFERENCES `fecha_inicio` (`Id_Fecha_Inicio`),
  ADD CONSTRAINT `promocion_ibfk_5` FOREIGN KEY (`Id_Fecha_Fin`) REFERENCES `fecha_fin` (`Id_Fecha_Fin`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`Id_Administrador`) REFERENCES `administrador` (`Id_Administrador`);

--
-- Filtros para la tabla `regente`
--
ALTER TABLE `regente`
  ADD CONSTRAINT `regente_ibfk_1` FOREIGN KEY (`Id_Turno`) REFERENCES `turno` (`Id_Turno`);

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD CONSTRAINT `transacciones_ibfk_1` FOREIGN KEY (`Id_Administrador`) REFERENCES `administrador` (`Id_Administrador`),
  ADD CONSTRAINT `transacciones_ibfk_2` FOREIGN KEY (`Id_Producto`) REFERENCES `producto` (`Id_Producto`),
  ADD CONSTRAINT `transacciones_ibfk_3` FOREIGN KEY (`Id_Tipo_Transaccion`) REFERENCES `tipo_transaccion` (`Id_Tipo_Transaccion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
