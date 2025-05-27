-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 04:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reservas`
--

-- --------------------------------------------------------

--
-- Table structure for table `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` int(11) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `servicios` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `tipo`, `numero`, `descripcion`, `servicios`, `imagen`, `precio`) VALUES
(1, 'Simple', '101', 'Habitación simple con cama individual, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista a la ciudad.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80', 35.00),
(2, 'Simple', '102', 'Habitación simple con cama individual, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista a la ciudad.', 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80', 35.00),
(3, 'Simple', '103', 'Habitación simple con cama individual, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista a la ciudad.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80', 35.00),
(4, 'Simple', '104', 'Habitación simple con cama individual, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista a la ciudad.', 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80', 35.00),
(5, 'Simple', '105', 'Habitación simple con cama individual, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista a la ciudad.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80', 35.00),
(6, 'Doble', '201', 'Habitación doble con dos camas, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista al jardín.', 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=400&q=80', 60.00),
(7, 'Doble', '202', 'Habitación doble con dos camas, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista al jardín.', 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=400&q=80', 60.00),
(8, 'Doble', '203', 'Habitación doble con dos camas, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista al jardín.', 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=400&q=80', 60.00),
(9, 'Doble', '204', 'Habitación doble con dos camas, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista al jardín.', 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=400&q=80', 60.00),
(10, 'Doble', '205', 'Habitación doble con dos camas, baño privado, wifi, TV y escritorio.', 'Aire acondicionado, desayuno incluido, vista al jardín.', 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=400&q=80', 60.00),
(11, 'Suite', '301', 'Suite de lujo con cama king, sala de estar, jacuzzi, wifi, TV Smart.', 'Aire acondicionado, desayuno buffet, minibar, vista panorámica.', 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80', 120.00),
(12, 'Suite', '302', 'Suite de lujo con cama king, sala de estar, jacuzzi, wifi, TV Smart.', 'Aire acondicionado, desayuno buffet, minibar, vista panorámica.', 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80', 120.00),
(13, 'Suite', '303', 'Suite de lujo con cama king, sala de estar, jacuzzi, wifi, TV Smart.', 'Aire acondicionado, desayuno buffet, minibar, vista panorámica.', 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80', 120.00),
(14, 'Suite', '304', 'Suite de lujo con cama king, sala de estar, jacuzzi, wifi, TV Smart.', 'Aire acondicionado, desayuno buffet, minibar, vista panorámica.', 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80', 120.00),
(15, 'Suite', '305', 'Suite de lujo con cama king, sala de estar, jacuzzi, wifi, TV Smart.', 'Aire acondicionado, desayuno buffet, minibar, vista panorámica.', 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80', 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `habitacion` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `habitacion`, `fecha_inicio`, `fecha_fin`, `creado_en`) VALUES
(3, 6, 'Simple', '2025-04-30', '2025-05-08', '2025-05-21 21:20:11'),
(4, 6, 'Doble', '2025-05-01', '2025-05-15', '2025-05-21 21:45:28');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` varchar(20) DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contraseña`, `rol`) VALUES
(3, 'Carlos', 'cristianisaak1113@gmail.com', '$2y$10$zGqF1vP3p5a3xuqPQDePeer9bcmEf6KcQRa9Wnno0ivwfL6P9cdMu', 'usuario'),
(10, 'Cristian', 'cirobalino@espe.edu.ec', '$2y$10$DYPSUccukYwIpZf2bojL4esrO3AOiyykuLlLQiF3HkL.JkGq4urCa', 'usuario');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
