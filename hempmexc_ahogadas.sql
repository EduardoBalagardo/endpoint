-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-09-2021 a las 23:34:44
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hempmexc_ahogadas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_productos`
--

CREATE TABLE `categorias_productos` (
  `idcategoria` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categorias_productos`
--

INSERT INTO `categorias_productos` (`idcategoria`, `descripcion`) VALUES
(1, 'Alimentos'),
(2, 'Bebidas'),
(3, 'Postres');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `idempleado` int(11) NOT NULL,
  `idperfiles` int(11) NOT NULL,
  `nombres` varchar(200) DEFAULT NULL,
  `apellidos` varchar(200) DEFAULT NULL,
  `user` varchar(20) DEFAULT NULL,
  `pass` varchar(20) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `fecha_nacimiento` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`idempleado`, `idperfiles`, `nombres`, `apellidos`, `user`, `pass`, `telefono`, `fecha_nacimiento`) VALUES
(1, 3, 'Disponible', 'Disponible', '0', '12345678910', '', '1986-12-07 00:00:00'),
(2, 1, 'Administrador', 'Administrador', 'Sup Sup', '1970', '', '1990-12-28 00:00:00'),
(3, 2, 'AXEL', '', 'AXEL', 'AX8', '29268757', '2017-02-16 11:33:00'),
(6, 2, 'HUGO', '', 'HUGO', 'HG7', '', '2019-02-07 00:00:00'),
(9, 2, 'CH', '', 'CH', 'CH1', '', '2019-10-30 00:00:00'),
(10, 2, 'CRISTIAN\r\n', NULL, 'CH', 'CH1', NULL, NULL),
(11, 2, 'DIEGO', NULL, 'DI', 'EX3', NULL, NULL),
(12, 2, 'CINTHIA', NULL, 'CY', 'CR5', NULL, NULL),
(13, 2, 'MARITZA', NULL, 'MZ', 'TA3', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `indicadores`
--

CREATE TABLE `indicadores` (
  `idindicadores` int(11) NOT NULL,
  `opcion1` varchar(45) DEFAULT NULL,
  `opcion2` varchar(45) DEFAULT NULL,
  `opcion3` varchar(45) DEFAULT NULL,
  `opcion4` varchar(45) DEFAULT NULL,
  `opcion5` varchar(45) DEFAULT NULL,
  `opcion6` varchar(45) DEFAULT NULL,
  `opcion7` varchar(45) DEFAULT NULL,
  `opcion8` varchar(45) DEFAULT NULL,
  `opcion9` varchar(45) DEFAULT NULL,
  `opcion10` varchar(45) DEFAULT NULL,
  `idproducto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `indicadores`
--

INSERT INTO `indicadores` (`idindicadores`, `opcion1`, `opcion2`, `opcion3`, `opcion4`, `opcion5`, `opcion6`, `opcion7`, `opcion8`, `opcion9`, `opcion10`, `idproducto`) VALUES
(1, 'Bien ahogada', 'Tres cuartos ahogada', 'Medio ahogada', 'Un cuarto ahogada', 'Sin picante', 'Dany', '', NULL, NULL, NULL, 1),
(2, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 2),
(3, '', '', '', '', '', '', '', NULL, NULL, NULL, 3),
(4, '', '', '', '', '', '', NULL, NULL, NULL, NULL, 4),
(5, '', '', '', '', '', '', NULL, NULL, NULL, NULL, 5),
(6, '', '', '', '', '', '', NULL, NULL, NULL, NULL, 6),
(7, '', '', '', '', '', '', '', '', '', NULL, 7),
(8, '', '', '', '', '', '', '', '', NULL, NULL, 8),
(9, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 9),
(10, '', '', '', '', '', '', NULL, NULL, NULL, NULL, 10),
(11, 'Ahogada', 'Seca', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11),
(12, 'Ahogada', 'Seca', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12),
(13, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13),
(14, 'ingrediente 1', 'ingrediente 2', 'ingrediente 3', 'ingrediente 4', 'ingrediente 5', 'ingrediente 6', 'ingrediente 7', 'ingrediente 8', 'ingrediente 9', 'ingrediente 10', 14),
(15, 'Coca Cola', 'Coca Cola Light', 'Coca Zero', 'Fanta', 'Manzanita', 'Sprite', 'Boing Guayaba', 'Boing Mango', 'Sangria', '', 15),
(16, 'Horchata', 'Jamaica', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16),
(17, 'Horchata', 'Jamaica', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17),
(18, 'Horchata', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18),
(19, 'Horchata', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19),
(20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20),
(21, 'Tehuacan', 'Sangria', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21),
(22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22),
(23, 'Corona', 'Victoria', 'Bohemia Obscura', 'Bohemia Clara', NULL, NULL, NULL, NULL, NULL, NULL, 23),
(25, 'Victoria', 'Corona', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25),
(26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26),
(27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 27),
(28, 'Victoria', 'Corona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28),
(29, 'Victoria', 'Corona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 29),
(30, 'Victoria', 'Corona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30),
(31, 'Victoria', 'Corona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 31),
(32, 'Victoria', 'Corona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32),
(33, 'Victoria', 'Corona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33),
(34, 'Victoria', 'Corona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34),
(35, 'Victoria', 'Corona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35),
(36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36),
(37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37),
(38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 38),
(39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 39),
(40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40),
(41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 41),
(42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42),
(43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 43),
(44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44),
(45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 45),
(46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 46);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inorder`
--

CREATE TABLE `inorder` (
  `idinorder` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `estatus` int(11) DEFAULT NULL,
  `indicador` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `idorden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `inorder`
--

INSERT INTO `inorder` (`idinorder`, `idproducto`, `estatus`, `indicador`, `cantidad`, `fecha`, `idorden`) VALUES
(1, 1, 2, 'Torta Ahogada', 1, '2021-09-01 16:02:41', 4),
(2, 1, 2, 'Torta Ahogada', 1, '2021-09-01 16:02:41', 4),
(3, 1, 2, 'Torta Ahogada', 1, '2021-09-01 16:02:41', 4),
(4, 1, 1, 'Torta Ahogada', 1, '2021-09-01 16:13:43', 5),
(5, 1, 1, 'Torta Ahogada', 1, '2021-09-01 16:13:43', 5),
(6, 1, 1, 'Torta Ahogada', 1, '2021-09-01 16:13:43', 5),
(7, 20, 1, 'Agua Embotellada\r\n', 1, '2021-09-01 16:14:56', 6),
(8, 20, 1, 'Agua Embotellada\r\n', 1, '2021-09-01 16:14:56', 6),
(9, 20, 1, 'Agua Embotellada\r\n', 1, '2021-09-01 16:14:56', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `idmesa` int(11) NOT NULL,
  `idempleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`idmesa`, `idempleado`) VALUES
(1, 2),
(2, 2),
(3, 12),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_mesas`
--

CREATE TABLE `ordenes_mesas` (
  `idorden` int(11) NOT NULL,
  `idmesa` int(11) NOT NULL,
  `idempleado` int(11) NOT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ordenes_mesas`
--

INSERT INTO `ordenes_mesas` (`idorden`, `idmesa`, `idempleado`, `estatus`) VALUES
(1, 1, 2, 2),
(2, 1, 2, 2),
(3, 1, 2, 2),
(4, 1, 2, 2),
(5, 1, 2, 1),
(6, 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `idperfiles` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`idperfiles`, `descripcion`) VALUES
(1, 'super user'),
(2, 'user'),
(3, 'none user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idproducto` int(11) NOT NULL,
  `precio` float DEFAULT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `idcategoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idproducto`, `precio`, `descripcion`, `idcategoria`) VALUES
(1, 35, 'Torta Ahogada', 1),
(2, 26, 'Tacos Dorados (orden de 6)', 1),
(3, 36, 'Tacos Dorados (orden de 6) c/carnitas', 1),
(4, 15, 'Taco de Carnitas', 1),
(5, 10, 'Dorada de Papa', 1),
(6, 16, 'Dorada de Papa c/carnitas', 1),
(7, 40, 'Orden de Carnitas Picadas', 1),
(8, 45, 'Orden de Carnitas Picadas c/tomate', 1),
(9, 70, 'Carne en su Jugo (plato)', 1),
(10, 50, 'Carne en su Jugo (1/2 plato)', 1),
(11, 25, 'Quesadilla de queso (tortilla de harina)\r\n', 1),
(12, 40, 'Quesadilla de queso (tortilla de harina) c/carnitas', 1),
(13, 25, 'Tostada de Pata', 1),
(14, 5, 'Ingrediente Extra', 1),
(15, 17, 'Refrescos', 2),
(16, 18, 'Agua Fresca (vaso 1/2 litro)', 2),
(17, 28, 'Agua Fresca (vaso 1 litro)', 2),
(18, 28, 'Agua Fresca (vaso 1/2 litro) c/helado', 2),
(19, 40, 'Agua Fresca (vaso 1litro) c/helado', 2),
(20, 10, 'Agua Embotellada\r\n', 2),
(21, 25, 'Tehuacan o Sangria Preparada', 2),
(22, 35, 'Arandanito', 2),
(23, 28, 'Cerveza Embotellada\r\n', 2),
(25, 55, 'Cerveza Mega', 2),
(26, 7, 'Tarro Preparado', 2),
(27, 10, 'Cafe o Te', 2),
(28, 35, 'Michelada Ch', 2),
(29, 60, 'Michelada Gd\r\n', 2),
(30, 40, 'Cubana Ch\r\n', 2),
(31, 62, 'Cubana Gd', 2),
(32, 45, 'Clamato Ch', 2),
(33, 67, 'Clamato Gd', 2),
(34, 45, 'Piña Brava Ch', 2),
(35, 67, 'Piña Brava Gd', 2),
(36, 20, 'Jericaya', 3),
(37, 20, 'Arroz con Leche', 3),
(38, 25, 'Fresas Congeladas con Crema', 3),
(39, 25, 'Flan Napolitano (caramelo o cajeta)', 3),
(40, 0, 'Dorada Seca', 1),
(41, 0, 'Media Orden de Tacos Secos', 1),
(42, 0, 'Media Orden de Tacos con Carne', 1),
(43, 0, 'Media Orden de Tacos Sencillos', 1),
(44, 0, 'Media Orden de Carnitas', 1),
(45, 0, 'Media Orden de Carnitas con Tomate', 1),
(46, 0, 'Consome', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_productos`
--
ALTER TABLE `categorias_productos`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`idempleado`,`idperfiles`),
  ADD KEY `fk_empleados_perfiles1_idx` (`idperfiles`);

--
-- Indices de la tabla `indicadores`
--
ALTER TABLE `indicadores`
  ADD PRIMARY KEY (`idindicadores`);

--
-- Indices de la tabla `inorder`
--
ALTER TABLE `inorder`
  ADD PRIMARY KEY (`idinorder`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`idmesa`);

--
-- Indices de la tabla `ordenes_mesas`
--
ALTER TABLE `ordenes_mesas`
  ADD PRIMARY KEY (`idorden`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`idperfiles`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idproducto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_productos`
--
ALTER TABLE `categorias_productos`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `idempleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `indicadores`
--
ALTER TABLE `indicadores`
  MODIFY `idindicadores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `inorder`
--
ALTER TABLE `inorder`
  MODIFY `idinorder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `idmesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `ordenes_mesas`
--
ALTER TABLE `ordenes_mesas`
  MODIFY `idorden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `idperfiles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `fk_empleados_perfiles1` FOREIGN KEY (`idperfiles`) REFERENCES `perfiles` (`idperfiles`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
