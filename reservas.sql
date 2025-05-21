-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-05-2025 a las 16:51:50
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `habitacion` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `habitacion`, `fecha_inicio`, `fecha_fin`, `creado_en`) VALUES
(3, 6, 'Simple', '2025-04-30', '2025-05-08', '2025-05-21 16:20:11'),
(4, 6, 'Doble', '2025-05-01', '2025-05-15', '2025-05-21 16:45:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contraseña`, `rol`) VALUES
(1, 'Cristian', 'cirobalino@espe.edu.ec', '$2y$10$B4CEKyaoAHNYu37a16UCAepcsV8H9ehIgwKRvcCGNtAWGNGBD0/pO', 'usuario'),
(3, 'Carlos', 'cristianisaak1113@gmail.com', '$2y$10$zGqF1vP3p5a3xuqPQDePeer9bcmEf6KcQRa9Wnno0ivwfL6P9cdMu', 'usuario'),
(4, 'Ronny Joel', 'ronnyibarra@gmail.com', '$2y$10$whm3SOn51zrTMXdfrdWNS.f6uvEmsKmI2wSAP3opQLtkccELGPim.', 'admin'),
(6, 'Ronn', 'rjibarra1@espe.edu.ec', '$2y$10$TrQ65/vir7wk9pmtkkEcSuXm.VeHV4ziB2p0BM48DEdhwE3qYMUX6', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
