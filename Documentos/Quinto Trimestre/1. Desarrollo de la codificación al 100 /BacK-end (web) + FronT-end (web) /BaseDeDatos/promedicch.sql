-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 21-04-2025 a las 21:47:37
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
(1, 'Erick', 'Zarto', 'saragarcia310328@gmail.com', 3014370000, 'c9bb2857cdaa4a38aaf7dcbca877fc5ccf4fd420e057b64b38a9bf3551c7cecc7132470a5a0189d41dd85b928270aecc767f', '2025-04-08 09:53:03', '$2y$10$Uqv3JihdlP0nDUc8K/EUNOZV8k5E6ZPBxIiS2n639yvsIGaFJHbWW');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `Id_Categoria` int(11) NOT NULL,
  `Nombre_Categoria` varchar(45) DEFAULT NULL,
  `Descripcion_Categoria` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`Id_Categoria`, `Nombre_Categoria`, `Descripcion_Categoria`) VALUES
(1, 'Medicamentos sin receta (OTC)', 'Los medicamentos sin receta (OTC) son aquellos que no requieren la autorización de un médico para ser adquiridos. Están destinados a tratar afecciones menores y comunes.'),
(4, 'Productos de Cuidado Personal', 'Los productos de cuidado personal están destinados a mantener la higiene y el bienestar personal.'),
(5, 'Antihistamínicos', 'Medicamentos que bloquean los efectos de la histamina, ayudando a controlar los síntomas de alergias, como estornudos, picazón o secreción nasal.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE `clasificacion` (
  `Id_Clasificacion` int(11) NOT NULL,
  `Nombre_Clasificacion` varchar(45) DEFAULT NULL,
  `Descripcion_Clasificacion` varchar(2000) DEFAULT NULL,
  `Id_Categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clasificacion`
--

INSERT INTO `clasificacion` (`Id_Clasificacion`, `Nombre_Clasificacion`, `Descripcion_Clasificacion`, `Id_Categoria`) VALUES
(1, 'Analgésicos y Antipiréticos', 'Productos diseñados para mantener el cabello limpio, saludable y en buen estado.', 1),
(3, 'Antigripales y Descongestionantes', 'Medicamentos que alivian los síntomas de la gripe, como congestión nasal y dolor de garganta.', 1),
(5, 'Antihistamínicos', 'Medicamentos utilizados para tratar síntomas de alergias, como estornudos, secreción nasal y picazón.', 1),
(6, 'Higiene Corporal', 'Productos utilizados para la limpieza diaria del cuerpo.', 4),
(7, 'Cuidado del Cabello', 'Productos diseñados para mantener el cabello limpio, saludable y en buen estado.', 4),
(8, 'Cuidado Bucal', 'Productos utilizados para la higiene oral y el cuidado de los dientes y encías.', 4);

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
(12, 1, 1, 10, '2025-02-12', 20000),
(13, 1, 1, 1, '2025-04-08', 2000),
(14, 1, 18, 2, '2025-04-08', 10000),
(15, 1, 13, 5, '2025-04-08', 25000);

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
(10, 'Sofia Valentina Rodrigez', 23966709, '2025-04-08', 1, 'Imagenes/-Recetas.-001-1.png'),
(11, 'Libardo Munera Gomez ', 98595826, '2025-04-09', 1, 'Imagenes/mceclip0.png'),
(12, 'Lina Marcela Jaramillo Jaramillo ', 30232233, '2025-04-09', 1, 'Imagenes/LJZUBDY5OFCJ3AKKMEO3EIOFD4.avif'),
(13, ' Nohemí Sierra Ramírez', 52291364, '2025-04-10', 1, 'Imagenes/bg1.webp'),
(14, 'Natalia Ruiz', 123, '2025-04-08', 1, 'Imagenes/bg1.webp');

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
-- Estructura de tabla para la tabla `mensajes_a_regente`
--

CREATE TABLE `mensajes_a_regente` (
  `id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_a_regente`
--

INSERT INTO `mensajes_a_regente` (`id`, `mensaje`, `fecha`) VALUES
(9, 'hola', '2025-04-21 19:47:05');

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
  `Descripcion_Producto` varchar(2000) DEFAULT NULL,
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
(1, 'Paracetamol', 'Es un analgésico y antipirético utilizado para aliviar dolores leves a moderados (como dolores de cabeza, musculares o menstruales) y para reducir la fiebre.', 500, 123456789, '500mg', 2000, 39, 1, 1, 1, 3, 1, '2025-04-14', 20, 70),
(6, 'Mascarilla capilar', 'Tratamiento intensivo que proporciona nutrición y reparación al cabello dañado.', 500, 2147483647, '500mg', 1122, 55, 7, 4, 1, 3, 1, '2028-03-09', 10, 100),
(8, 'Pasta de dientes', 'Producto para la limpieza de los dientes. Se pueden encontrar diferentes tipos como anticaries, blanqueadoras, o para dientes sensibles.', 500, 2147483647, '500mg', 1000, 65, 1, 1, 4, 3, 1, '2027-03-22', 10, 100),
(9, 'Ibuprofeno', ' Un medicamento antiinflamatorio no esteroideo (AINE) que reduce el dolor, la fiebre y la inflamación. Es utilizado para dolores de cabeza, musculares, dolor dental, y dolores articulares.', 500, 979868647, '500mg', 2000, 70, 1, 1, 1, 3, 1, '2029-03-08', 20, 70),
(10, 'Ácido Acetilsalicílico', 'Es un medicamento utilizado para reducir la fiebre, aliviar el dolor y como antiinflamatorio. También se usa para reducir el riesgo de eventos cardiovasculares.', 400, 2147483647, '400g', 1122, 50, 1, 1, 1, 3, 1, '2030-02-22', 20, 80),
(11, 'Paracetamol + Fenilefrina', 'Combinación comúnmente utilizada para aliviar el dolor y la fiebre, además de descongestionar la nariz.', 8000, 2147483647, '400g', 10000, 10, 3, 1, 1, 3, 1, '2026-02-28', 10, 24),
(12, 'Dextrometorfano', 'Utilizado para aliviar la tos seca, bloqueando el reflejo de la tos en el cerebro.', 500, 1234321, '500mg', 2000, 80, 3, 1, 1, 3, 1, '2025-04-03', 60, 100),
(13, 'Shampoo', 'Producto limpiador para el cabello. Hay diferentes tipos: hidratantes, anticaspa, y para cabellos grasos o secos.', 1000, 2147483647, '500mg', 5000, 35, 7, 4, 1, 3, 1, '2025-12-07', 10, 120),
(14, 'Pseudoefedrina', 'Descongestionante nasal que alivia la congestión causada por resfriados o alergias.', 1000, 1234321234, '500mg', 6000, 60, 3, 1, 1, 3, 1, '2034-11-30', 20, 80),
(15, 'Acondicionador', 'Producto utilizado después del shampoo para suavizar el cabello y proporcionar nutrientes.', 100, 19283745, '500', 5000, 60, 7, 4, 4, 3, 1, '2025-12-07', 10, 80),
(16, 'Loratadina', 'Antihistamínico utilizado para aliviar los síntomas de alergias estacionales como la rinitis alérgica y la urticaria.', 1000, 1927345, '500mg', 5000, 50, 5, 1, 1, 3, 1, '2025-09-07', 10, 100),
(17, 'Cetirizina', 'Otro antihistamínico utilizado para el tratamiento de alergias, especialmente en casos de rinitis alérgica o conjuntivitis alérgica.', 1000, 2147483647, '500mg', 6000, 40, 5, 1, 1, 3, 1, '2026-02-28', 20, 120),
(18, 'Jabón en barra', 'Producto para la limpieza diaria del cuerpo. Se encuentra en diferentes presentaciones: antibacteriano, hidratante, y para piel sensible.', 2000, 2147483647, '500mg', 5000, 498, 1, 1, 1, 3, 1, '2025-11-14', 110, 700),
(19, 'Gel de baño', 'Formulación líquida utilizada para la limpieza corporal, también disponible con propiedades hidratantes y de diferentes aromas.', 20, 102301250, '12mg', 125, 10, 1, 1, 4, 3, 1, '2025-04-30', 1, 20);

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
(4, 1, 18, 1, '2025-04-07', '2025-04-30', 0),
(5, 1, 1, 1, '2025-04-10', '2025-04-24', 0),
(7, 1, 11, 2, '2025-04-28', '2025-04-23', 80),
(8, 1, 9, 2, '2025-04-29', '2025-04-30', 80);

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
(1, 'Samara ', 'Diaz', 52025545, '2024-11-24', 259523, 'gile01601@gmail.com', 3203530000, 0x2432792431302456315170702f734c303542484d5770446d764447512e464b494232595955694a42356a68794679426943374f6c4f2f7561596e6936, 2, NULL, NULL);

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
(24, '2025-02-12', 10, 1, 1, 2),
(25, '2025-04-07', 1, 1, 1, 2),
(26, '2025-04-07', 500, 1, 18, 1),
(27, '2025-04-07', 10, 1, 19, 1),
(28, '2025-04-07', 2, 1, 18, 2),
(29, '2025-04-07', 5, 1, 13, 2);

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
-- Indices de la tabla `mensajes_a_regente`
--
ALTER TABLE `mensajes_a_regente`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `Id_Clasificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  MODIFY `Id_Comprobante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `formula_medica`
--
ALTER TABLE `formula_medica`
  MODIFY `Id_Formula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `Id_Marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mensajes_a_regente`
--
ALTER TABLE `mensajes_a_regente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Id_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `Id_Promocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `Id_Transacciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
