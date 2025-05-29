-- Borrar la base de datos si existe (para una instalación limpia)
DROP DATABASE IF EXISTS `reservas`;

-- Crear la base de datos
CREATE DATABASE `reservas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar la base de datos recién creada
USE `reservas`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` varchar(20) DEFAULT 'usuario',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--
INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contraseña`, `rol`) VALUES
(1, 'Carlos Rodriguez', 'carlos.r@example.com', '$2y$10$zGqF1vP3p5a3xuqPQDePeer9bcmEf6KcQRa9Wnno0ivwfL6P9cdMu', 'usuario'), -- Contraseña: password123
(2, 'Cristian Obalino', 'cristian.o@example.com', '$2y$10$DYPSUccukYwIpZf2bojL4esrO3AOiyykuLlLQiF3HkL.JkGq4urCa', 'usuario'), -- Contraseña: password456
(3, 'Ana Lopez (Admin)', 'ana.admin@example.com', '$2y$10$R4XjK8x.kP3s0.R6w9U8I.VqLwE9f.X2qYtU5oB7nZcG1jH3kL5mO', 'admin');    -- Contraseña: adminpass

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--
CREATE TABLE `habitaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `servicios` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_tipo_numero` (`tipo`,`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--
INSERT INTO `habitaciones` (`id`, `tipo`, `numero`, `descripcion`, `servicios`, `imagen`, `precio`) VALUES
(1, 'Simple', '101', 'Acogedora habitación individual perfecta para viajeros solos. Cuenta con una cómoda cama, baño privado completo, Wi-Fi de alta velocidad, TV de pantalla plana y un práctico escritorio.', 'Aire acondicionado, Calefacción, Desayuno continental incluido, Limpieza diaria, Artículos de aseo gratuitos, Secador de pelo, Vista a la ciudad.', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=500&q=60', 45.00),
(2, 'Simple', '102', 'Habitación individual funcional y luminosa, ideal para una estancia confortable. Equipada con baño privado, Wi-Fi, TV y todas las comodidades necesarias.', 'Aire acondicionado, Calefacción, Limpieza diaria, Escritorio, Teléfono, Servicio de despertador, Vista al patio interior.', 'https://images.unsplash.com/photo-1590490359854-dfba59ee83c8?auto=format&fit=crop&w=500&q=60', 48.00),
(3, 'Simple', '103', 'Moderna habitación individual con diseño minimalista. Perfecta para concentrarse o descansar. Incluye smart TV y cafetera.', 'Smart TV, Cafetera, Wi-Fi Premium, Artículos de aseo ecológicos, Ducha efecto lluvia.', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=500&q=60', 52.00),
(4, 'Doble', '201', 'Espaciosa habitación doble con dos camas individuales o una cama matrimonial (según disponibilidad). Perfecta para parejas o amigos. Ofrece baño privado, Wi-Fi, TV y un ambiente relajante.', 'Aire acondicionado, Calefacción, Desayuno buffet opcional, Minibar, Caja fuerte, Limpieza diaria, Plancha y tabla de planchar (bajo petición), Vista al jardín o exterior.', 'https://images.unsplash.com/photo-1560185007-c5ca91ba2960?auto=format&fit=crop&w=500&q=60', 75.00),
(5, 'Doble', '202', 'Confortable habitación doble diseñada para el descanso. Dispone de todas las comodidades modernas, incluyendo baño completo, Wi-Fi rápido, TV y una cuidada decoración.', 'Aire acondicionado, Calefacción, Servicio de habitaciones (horario limitado), Cafetera/tetera, Limpieza diaria, Balcón (en algunas habitaciones).', 'https://images.unsplash.com/photo-1611892440504-4cb0296a1a19?auto=format&fit=crop&w=500&q=60', 80.00),
(6, 'Doble', '203', 'Habitación doble superior con un toque elegante. Ofrece más espacio y detalles de confort como albornoces y zapatillas.', 'Albornoces y zapatillas, Minibar premium, Smart TV 4K, Ducha de hidromasaje, Insonorización.', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=500&q=60', 95.00),
(7, 'Suite', '301', 'Lujosa suite con área de estar separada, cama king-size y vistas panorámicas. Ideal para una experiencia inolvidable. Incluye jacuzzi privado, minibar completo y acceso a servicios premium.', 'Aire acondicionado central, Calefacción por losa radiante, Desayuno VIP en la habitación, Acceso al lounge ejecutivo, Smart TV 55", Sistema de sonido, Albornoz y zapatillas, Cafetera Nespresso, Jacuzzi.', 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=500&q=60', 150.00),
(8, 'Suite', '302', 'Elegante suite que combina diseño y confort. Ofrece un amplio espacio, cama extragrande, baño de mármol con bañera y ducha separadas, y una zona de trabajo.', 'Climatización individual, Insonorización, Prensa diaria, Amenidades de lujo, Room service 24h, Carta de almohadas, Vista privilegiada, Bañera de hidromasaje.', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=500&q=60', 165.00),
(9, 'Familiar', '401', 'Amplia habitación familiar con capacidad para 4 personas (2 adultos y 2 niños). Cuenta con una cama matrimonial y dos camas individuales o literas, además de una pequeña zona de juegos.', 'Cama matrimonial, Dos camas individuales/literas, TV con canales infantiles, Microondas (bajo petición), Baño adaptado para niños, Desayuno familiar incluido.', 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=500&q=60', 110.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--
CREATE TABLE `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `habitacion_id` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_usuario_id` (`usuario_id`),
  KEY `idx_habitacion_id` (`habitacion_id`),
  KEY `idx_fechas_habitacion` (`habitacion_id`,`fecha_inicio`,`fecha_fin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--
INSERT INTO `reservas` (`id`, `usuario_id`, `habitacion_id`, `fecha_inicio`, `fecha_fin`, `creado_en`) VALUES
(1, 1, 1, '2025-06-10', '2025-06-12', '2025-05-28 10:15:00'),
(2, 2, 5, '2025-07-01', '2025-07-05', '2025-05-28 11:20:00'),
(3, 1, 7, '2025-08-15', '2025-08-20', '2025-05-29 09:00:00'),
(4, 3, 9, '2025-07-20', '2025-07-27', '2025-05-29 14:00:00');


--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reservas_habitacion` FOREIGN KEY (`habitacion_id`) REFERENCES `habitaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reservas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;