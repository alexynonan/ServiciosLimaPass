-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2021 a las 01:59:46
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `metropolitano`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigo`
--

CREATE TABLE `codigo` (
  `id` int(11) NOT NULL,
  `dni` varchar(12) NOT NULL,
  `codigo` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `codigo`
--

INSERT INTO `codigo` (`id`, `dni`, `codigo`) VALUES
(1, '73125325', 'ih36'),
(2, '12345678', '85yg'),
(3, '11111111', ''),
(4, '22222222', ''),
(19, '73125321', ''),
(20, '73125326', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correos`
--

CREATE TABLE `correos` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `correos`
--

INSERT INTO `correos` (`id`, `correo`) VALUES
(1, 'alexander@gmail.com'),
(2, 'jean@gmail.com'),
(4, 'martin@gmail.com'),
(5, 'roxana@gmail.com'),
(19, 'tomas@gmail.com'),
(20, 'torreo@gmail.com'),
(3, 'valeria@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeros`
--

CREATE TABLE `numeros` (
  `id` int(11) NOT NULL,
  `numero` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `numeros`
--

INSERT INTO `numeros` (`id`, `numero`) VALUES
(1, '987677106'),
(2, '987654321'),
(3, '987654322'),
(4, '987654312'),
(5, '945361220'),
(19, '987677100'),
(20, '012345678');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas`
--

CREATE TABLE `tarjetas` (
  `id` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `nombre` varchar(300) NOT NULL,
  `imagen` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tarjetas`
--

INSERT INTO `tarjetas` (`id`, `tipo`, `nombre`, `imagen`) VALUES
(1, 0, 'Tarjeta General', 'https://narrow-minded-democ.000webhostapp.com/servicios/rc/tarjetaGeneral.png'),
(2, 1, 'Tarjeta Discapacitado', 'https://narrow-minded-democ.000webhostapp.com/servicios/rc/tarjetaDiscapacitado.png'),
(3, 2, 'Tarjeta Universitario', 'https://narrow-minded-democ.000webhostapp.com/servicios/rc/tarjetaUniversitario.png'),
(4, 3, 'Tarjeta Escolar', 'https://narrow-minded-democ.000webhostapp.com/servicios/rc/tarjetaEscolar.png'),
(5, 4, 'Tarjeta Personalizada', 'https://narrow-minded-democ.000webhostapp.com/servicios/rc/tarjetaPersonalizada.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas_usuarios`
--

CREATE TABLE `tarjetas_usuarios` (
  `id_usuario` varchar(12) NOT NULL,
  `id_tarjeta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tarjetas_usuarios`
--

INSERT INTO `tarjetas_usuarios` (`id_usuario`, `id_tarjeta`) VALUES
('09995515', 0),
('09995515', 3),
('11111111', 0),
('11111111', 4),
('12345678', 0),
('22222222', 0),
('73125321', 0),
('73125325', 0),
('73125325', 2),
('73125325', 4),
('73125326', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `dni` varchar(12) NOT NULL,
  `nombre` varchar(300) NOT NULL,
  `apellido` varchar(300) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `numero` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `dni`, `nombre`, `apellido`, `correo`, `numero`) VALUES
(1, '73125325', 'alexander', 'ynonan', 'alexander@gmail.com', '987677106'),
(2, '12345678', 'jean', 'zea', 'jean@gmail.com', '987654321'),
(3, '11111111', 'Valeria', 'valeria', 'valeria@gmail.com', '987654322'),
(4, '22222222', 'martin', 'martin', 'martin@gmail.com', '987654312'),
(5, '09995515', 'roxana', 'huayllapuma', 'roxana@gmail.com', '945361220'),
(19, '73125321', 'tomas', 'tomas', 'tomas@gmail.com', '987677100'),
(20, '73125326', 'leonardo', 'torreo', 'torreo@gmail.com', '012345678');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `codigo`
--
ALTER TABLE `codigo`
  ADD PRIMARY KEY (`id`,`dni`);

--
-- Indices de la tabla `correos`
--
ALTER TABLE `correos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `correo` (`correo`);

--
-- Indices de la tabla `numeros`
--
ALTER TABLE `numeros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD PRIMARY KEY (`tipo`);

--
-- Indices de la tabla `tarjetas_usuarios`
--
ALTER TABLE `tarjetas_usuarios`
  ADD KEY `id_usuario` (`id_usuario`,`id_tarjeta`),
  ADD KEY `id_usuario_2` (`id_usuario`,`id_tarjeta`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `correo` (`correo`,`numero`),
  ADD KEY `dni` (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `codigo`
--
ALTER TABLE `codigo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `correos`
--
ALTER TABLE `correos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `numeros`
--
ALTER TABLE `numeros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
