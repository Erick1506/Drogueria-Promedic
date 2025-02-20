-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 20-02-2025 a las 22:59:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `promedicch`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `Id_Administrador` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Apellido` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` float DEFAULT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL,
  `Contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`Id_Administrador`, `Nombre`, `Apellido`, `Correo`, `Telefono`, `token_recuperacion`, `token_expiracion`, `Contraseña`) VALUES
(1, 'Erick', 'Zarto', 'saragarcia310328@gmail.com', 3014370000, NULL, NULL, '$2y$10$Uqv3JihdlP0nDUc8K/EUNOZV8k5E6ZPBxIiS2n639yvsIGaFJHbWW');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `Id_Categoria` int(11) NOT NULL,
  `Nombre_Categoria` varchar(45) DEFAULT NULL,
  `Descripcion_Categoria` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`Id_Categoria`, `Nombre_Categoria`, `Descripcion_Categoria`) VALUES
(1, 'Categoria1', 'Medicamento que se usa para tratar ciertas af'),
(4, 'Categoria2', 'Categoria2'),
(5, 'Categoria principal', 'para los productos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE `clasificacion` (
  `Id_Clasificacion` int(11) NOT NULL,
  `Nombre_Clasificacion` varchar(45) DEFAULT NULL,
  `Descripcion_Clasificacion` varchar(45) DEFAULT NULL,
  `Id_Categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clasificacion`
--

INSERT INTO `clasificacion` (`Id_Clasificacion`, `Nombre_Clasificacion`, `Descripcion_Clasificacion`, `Id_Categoria`) VALUES
(1, 'Clasificacion1', 'Mas pastillas', 1),
(3, 'Clasificacion2', 'fsfsfssffs', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante`
--

CREATE TABLE `comprobante` (
  `Id_Comprobante` int(11) NOT NULL,
  `Id_Regente` int(11) DEFAULT NULL,
  `Id_Producto` int(11) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Fecha_Venta` date DEFAULT NULL,
  `Total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comprobante`
--

INSERT INTO `comprobante` (`Id_Comprobante`, `Id_Regente`, `Id_Producto`, `Cantidad`, `Fecha_Venta`, `Total`) VALUES
(1, 1, 6, 10, '2024-11-25', 11220),
(2, 1, 6, 1, '2024-11-25', 1122),
(3, 1, 8, 5, '2024-11-25', 5000),
(4, 1, 6, 4, '2024-11-25', 4488),
(5, 1, 12, 10, '2024-11-25', 20000),
(6, 1, 11, 10, '2024-11-26', 100000),
(7, 1, 1, 10, '2024-11-26', 20000),
(9, 1, 17, 20, '2024-11-27', 84000),
(10, 1, 17, 20, '2024-11-29', 84000),
(11, 1, 10, 10, '2024-12-05', 11220),
(12, 1, 1, 10, '2025-02-12', 20000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_producto`
--

CREATE TABLE `estado_producto` (
  `Id_Estado_Producto` int(11) NOT NULL,
  `Tipo_Estado_Producto` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_producto`
--

INSERT INTO `estado_producto` (`Id_Estado_Producto`, `Tipo_Estado_Producto`) VALUES
(1, 'Disponible'),
(2, 'Agotado'),
(3, 'Promocion'),
(4, 'No Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formula_medica`
--

CREATE TABLE `formula_medica` (
  `Id_Formula` int(11) NOT NULL,
  `Nombre_Paciente` varchar(45) DEFAULT NULL,
  `Identificacion_Paciente` int(11) DEFAULT NULL,
  `Fecha_Insercion` date DEFAULT NULL,
  `Id_Administrador` int(11) DEFAULT NULL,
  `Imagen` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `formula_medica`
--

INSERT INTO `formula_medica` (`Id_Formula`, `Nombre_Paciente`, `Identificacion_Paciente`, `Fecha_Insercion`, `Id_Administrador`, `Imagen`) VALUES
(3, 'pepe zalazar', 123456789, '2024-12-11', 1, 'Imagenes/Imagen de WhatsApp 2024-12-06 a las 20.55.12_48ac9388_b7162ce8-33ae-4a69-ac2a-3ec81234b183.jpg'),
(4, 'hola', 102938475, '2024-12-11', 1, 'Imagenes/Imagen de WhatsApp 2024-12-06 a las 20.55.12_48ac9388.jpg'),
(5, 'Sara Garciaa', 12345, '2025-02-12', 1, 'Imagenes/Imagen de WhatsApp 2024-12-06 a las 20.55.12_48ac9388_b7162ce8-33ae-4a69-ac2a-3ec81234b183.jpg'),
(6, 'erick', 123123, '2025-02-12', 1, 'Imagenes/Imagen de WhatsApp 2024-12-06 a las 20.55.12_48ac9388.jpg'),
(7, 'Categoria2', 12345988, '2025-02-14', 1, 'Imagenes/Imagen de WhatsApp 2024-12-06 a las 20.55.12_48ac9388.jpg'),
(8, 'sara', 32222, '2025-02-21', 1, 'Imagenes/Imagen de WhatsApp 2024-12-06 a las 20.55.12_48ac9388.jpg'),
(9, 'hola', 32222, '2025-02-13', 1, 'Imagenes/Imagen de WhatsApp 2024-12-06 a las 20.55.12_48ac9388.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `Id_Marca` int(11) NOT NULL,
  `Marca_Producto` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`Id_Marca`, `Marca_Producto`) VALUES
(3, 'Marca 1'),
(6, 'Marca2'),
(7, 'MARCA 3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `mensaje`, `fecha_creacion`) VALUES
(15, 'prueba de mensaje ', '2025-02-13 00:30:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `Id_Producto` int(11) NOT NULL,
  `Nombre_Producto` varchar(45) DEFAULT NULL,
  `Descripcion_Producto` varchar(45) DEFAULT NULL,
  `Costo_Adquisicion` double DEFAULT NULL,
  `Codigo_Barras` int(11) NOT NULL,
  `Peso` varchar(55) DEFAULT NULL,
  `Precio` double DEFAULT NULL,
  `Cantidad_Stock` int(11) DEFAULT NULL,
  `Id_Clasificacion` int(11) DEFAULT NULL,
  `Id_Categoria` int(11) DEFAULT NULL,
  `Id_Estado_Producto` int(11) DEFAULT NULL,
  `Id_Marca` int(11) DEFAULT NULL,
  `Id_Proveedor` int(11) DEFAULT NULL,
  `Fecha_Vencimiento` date DEFAULT NULL,
  `Cantidad_Minima` int(11) DEFAULT NULL,
  `Cantidad_Maxima` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`Id_Producto`, `Nombre_Producto`, `Descripcion_Producto`, `Costo_Adquisicion`, `Codigo_Barras`, `Peso`, `Precio`, `Cantidad_Stock`, `Id_Clasificacion`, `Id_Categoria`, `Id_Estado_Producto`, `Id_Marca`, `Id_Proveedor`, `Fecha_Vencimiento`, `Cantidad_Minima`, `Cantidad_Maxima`) VALUES
(1, 'Acetaminofen', 'Medicamento para el dolor de cabez', 500, 123456789, '500mg', 2000, 40, 1, 1, 3, 3, 1, '2025-02-12', 20, 70),
(6, 'amoxixilina', 'Medicamento para el dolor de cabeza', 500, 2147483647, '500mg', 1122, 55, 1, 4, 1, 3, 1, '2025-03-22', 10, 100),
(8, 'Sevedol', 'Medicamento para el dolor de cabeza', 500, 2147483647, '500mg', 1000, 65, 1, 4, 1, 3, 1, '2025-03-22', 10, 100),
(9, 'Paracetamol', 'Medicamento para el dolor de cabeza', 500, 979868647, '500mg', 2000, 70, 1, 1, 1, 3, 1, '2025-03-08', 20, 70),
(10, 'clotrimazol', 'Elefente', 400, 2147483647, '400g', 1122, 50, 1, 1, 1, 3, 1, '2025-02-22', 20, 80),
(11, 'Prueb2', 'Medicamento para el dolor de cabeza', 8000, 2147483647, '400g', 10000, 10, 1, 1, 1, 3, 1, '2025-02-28', 10, 24),
(12, 'Prueba', 'pepino', 500, 1234321, '500mg', 2000, 80, 1, 1, 1, 3, 1, '2024-11-27', 60, 100),
(13, 'betametasona', 'crema', 1000, 2147483647, '500mg', 5000, 40, 3, 1, 3, 3, 1, '2024-12-07', 10, 120),
(14, 'ProductoPP', 'Medicamento para el dolor de cabeza', 1000, 1234321234, '500mg', 6000, 60, 1, 1, 3, 3, 3, '2024-11-30', 20, 80),
(15, 'MECAFEM', 'DOLORES', 100, 19283745, '500', 5000, 60, 3, 1, 3, 3, 3, '2024-12-07', 10, 80),
(16, 'Acetaminofen', 'Medicamento para el dolor de cabeza', 1000, 1927345, '500mg', 5000, 50, 1, 1, 1, 3, 1, '2025-02-01', 10, 100),
(17, 'Dolex', 'para la gripa', 1000, 2147483647, '500mg', 6000, 40, 1, 1, 1, 3, 1, '2025-02-28', 20, 120);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `Id_Promocion` int(11) NOT NULL,
  `Id_Administrador` int(11) DEFAULT NULL,
  `Id_Producto` int(11) DEFAULT NULL,
  `Id_Tipo_Promocion` int(11) DEFAULT NULL,
  `Fecha_Inicio` date DEFAULT NULL,
  `Fecha_Fin` date DEFAULT NULL,
  `Descuento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`Id_Promocion`, `Id_Administrador`, `Id_Producto`, `Id_Tipo_Promocion`, `Fecha_Inicio`, `Fecha_Fin`, `Descuento`) VALUES
(2, 1, 16, 2, '2024-11-26', '2024-12-07', 40),
(3, 1, 17, 2, '2024-11-26', '2024-12-07', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `Id_Proveedor` int(11) NOT NULL,
  `Nombre_Proveedor` varchar(45) DEFAULT NULL,
  `Direccion_Proveedor` varchar(45) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` float DEFAULT NULL,
  `Id_Administrador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`Id_Proveedor`, `Nombre_Proveedor`, `Direccion_Proveedor`, `Correo`, `Telefono`, `Id_Administrador`) VALUES
(1, 'proveedora', 'calle 100', 'proveedor@gmail.com', 30182700000, 1),
(3, 'proveedor2', 'calle 1001', 'proveedorr@gmail.com', 30182700000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regente`
--

CREATE TABLE `regente` (
  `Id_Regente` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Apellido` varchar(45) DEFAULT NULL,
  `DNI` int(11) DEFAULT NULL,
  `Fecha_Contratacion` date DEFAULT NULL,
  `Licencia` int(11) DEFAULT NULL,
  `Correo` varchar(45) DEFAULT NULL,
  `Telefono` float DEFAULT NULL,
  `Contraseña_Encriptada` varbinary(255) NOT NULL,
  `Id_Turno` int(11) DEFAULT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `regente`
--

INSERT INTO `regente` (`Id_Regente`, `Nombre`, `Apellido`, `DNI`, `Fecha_Contratacion`, `Licencia`, `Correo`, `Telefono`, `Contraseña_Encriptada`, `Id_Turno`, `token_recuperacion`, `token_expiracion`) VALUES
(1, 'Reegente1', 'sakazar', 131221, '2024-11-24', 1213141516, 'gile01601@gmail.com', 3012350000, 0x24327924313024733642662e6d7a6751525a7830543769476961597775673275614d52356b306b5636483974646f396f4643474c4553575868666653, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_promocion`
--

CREATE TABLE `tipo_promocion` (
  `Id_Tipo_Promocion` int(11) NOT NULL,
  `Tipo_Promocion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_promocion`
--

INSERT INTO `tipo_promocion` (`Id_Tipo_Promocion`, `Tipo_Promocion`) VALUES
(1, '2 por 1'),
(2, 'Descuento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_transaccion`
--

CREATE TABLE `tipo_transaccion` (
  `Id_Tipo_Transaccion` int(11) NOT NULL,
  `Tipo_Transaccion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_transaccion`
--

INSERT INTO `tipo_transaccion` (`Id_Tipo_Transaccion`, `Tipo_Transaccion`) VALUES
(1, 'Entradas'),
(2, 'Salidas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `Id_Transacciones` int(11) NOT NULL,
  `Fecha_Transaccion` date DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Id_Administrador` int(11) DEFAULT NULL,
  `Id_Producto` int(11) DEFAULT NULL,
  `Id_Tipo_Transaccion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transacciones`
--

INSERT INTO `transacciones` (`Id_Transacciones`, `Fecha_Transaccion`, `Cantidad`, `Id_Administrador`, `Id_Producto`, `Id_Tipo_Transaccion`) VALUES
(1, '2024-11-22', 70, 1, 8, 1),
(4, '2024-11-23', 10, 1, 9, 1),
(5, '2024-11-24', 60, 1, 10, 1),
(6, '2024-11-24', 40, 1, 11, 1),
(7, '2024-11-25', 50, 1, 12, 1),
(8, '2024-11-25', 10, 1, 6, 2),
(9, '2024-11-25', 60, 1, 15, 1),
(17, '2024-11-25', 10, 1, 1, 2),
(18, '2024-11-26', 50, 1, 16, 1),
(19, '2024-11-26', 80, 1, 17, 1),
(20, '2024-11-27', 80, 1, 17, 2),
(21, '2024-11-27', 20, 1, 17, 2),
(22, '2024-11-29', 20, 1, 17, 2),
(23, '2024-12-05', 10, 1, 10, 2),
(24, '2025-02-12', 10, 1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno_regente`
--

CREATE TABLE `turno_regente` (
  `Id_Turno` int(11) NOT NULL,
  `turno` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turno_regente`
--

INSERT INTO `turno_regente` (`Id_Turno`, `turno`) VALUES
(1, 'mañana'),
(2, 'tarde'),
(3, 'noche');

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
-- Indices de la tabla `estado_producto`
--
ALTER TABLE `estado_producto`
  ADD PRIMARY KEY (`Id_Estado_Producto`);

--
-- Indices de la tabla `formula_medica`
--
ALTER TABLE `formula_medica`
  ADD PRIMARY KEY (`Id_Formula`),
  ADD KEY `Id_Administrador` (`Id_Administrador`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`Id_Marca`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`Id_Producto`),
  ADD KEY `Id_Clasificacion` (`Id_Clasificacion`),
  ADD KEY `Id_Categoria` (`Id_Categoria`),
  ADD KEY `Id_Estado_Producto` (`Id_Estado_Producto`),
  ADD KEY `Id_Marca` (`Id_Marca`),
  ADD KEY `Id_Proveedor` (`Id_Proveedor`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`Id_Promocion`),
  ADD KEY `Id_Administrador` (`Id_Administrador`),
  ADD KEY `Id_Producto` (`Id_Producto`),
  ADD KEY `Id_Tipo_Promocion` (`Id_Tipo_Promocion`);

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
  ADD KEY `fk_id_turno` (`Id_Turno`);

--
-- Indices de la tabla `tipo_promocion`
--
ALTER TABLE `tipo_promocion`
  ADD PRIMARY KEY (`Id_Tipo_Promocion`);

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
-- Indices de la tabla `turno_regente`
--
ALTER TABLE `turno_regente`
  ADD PRIMARY KEY (`Id_Turno`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `Id_Categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  MODIFY `Id_Clasificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  MODIFY `Id_Comprobante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `formula_medica`
--
ALTER TABLE `formula_medica`
  MODIFY `Id_Formula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `Id_Marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Id_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `Id_Promocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `Id_Proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `regente`
--
ALTER TABLE `regente`
  MODIFY `Id_Regente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `Id_Transacciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  ADD CONSTRAINT `formula_medica_ibfk_1` FOREIGN KEY (`Id_Administrador`) REFERENCES `administrador` (`Id_Administrador`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`Id_Clasificacion`) REFERENCES `clasificacion` (`Id_Clasificacion`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`Id_Categoria`) REFERENCES `categoria` (`Id_Categoria`),
  ADD CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`Id_Estado_Producto`) REFERENCES `estado_producto` (`Id_Estado_Producto`),
  ADD CONSTRAINT `producto_ibfk_4` FOREIGN KEY (`Id_Marca`) REFERENCES `marca` (`Id_Marca`),
  ADD CONSTRAINT `producto_ibfk_5` FOREIGN KEY (`Id_Proveedor`) REFERENCES `proveedor` (`Id_Proveedor`);

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `promocion_ibfk_1` FOREIGN KEY (`Id_Administrador`) REFERENCES `administrador` (`Id_Administrador`),
  ADD CONSTRAINT `promocion_ibfk_2` FOREIGN KEY (`Id_Producto`) REFERENCES `producto` (`Id_Producto`),
  ADD CONSTRAINT `promocion_ibfk_3` FOREIGN KEY (`Id_Tipo_Promocion`) REFERENCES `tipo_promocion` (`Id_Tipo_Promocion`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`Id_Administrador`) REFERENCES `administrador` (`Id_Administrador`);

--
-- Filtros para la tabla `regente`
--
ALTER TABLE `regente`
  ADD CONSTRAINT `fk_id_turno` FOREIGN KEY (`Id_Turno`) REFERENCES `turno_regente` (`Id_Turno`);

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
