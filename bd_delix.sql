-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2025 at 09:17 PM
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
-- Database: `bd_delix`
--

-- --------------------------------------------------------

--
-- Table structure for table `dishes`
--

CREATE TABLE `dishes` (
  `id` int(255) NOT NULL,
  `name_dish` varchar(100) NOT NULL,
  `price` int(255) NOT NULL,
  `category` int(255) NOT NULL,
  `description` text DEFAULT NULL,
  `state` enum('Activo','Inactivo') DEFAULT 'Activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dish_ingredients`
--

CREATE TABLE `dish_ingredients` (
  `id` int(255) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `insumo_id` int(11) NOT NULL,
  `cantidad_necesaria` decimal(10,2) NOT NULL,
  `unidad` enum('Kg','Litro','Unidad') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insumos`
--

CREATE TABLE `insumos` (
  `id` int(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cantidad` int(255) NOT NULL,
  `cantidad_minima` int(255) NOT NULL,
  `unidad` enum('Kg','Litro','Unidad') NOT NULL,
  `costo_unitario` decimal(65,2) NOT NULL,
  `categoria` enum('Vegetal','Carne','Bebida','Otro') NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `lote` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ubicacion` varchar(100) NOT NULL,
  `estado` enum('Activo','Agotado','vencido') NOT NULL,
  `proveedor` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insumos`
--

INSERT INTO `insumos` (`id`, `nombre`, `cantidad`, `cantidad_minima`, `unidad`, `costo_unitario`, `categoria`, `fecha_ingreso`, `fecha_vencimiento`, `lote`, `descripcion`, `ubicacion`, `estado`, `proveedor`, `foto`) VALUES
(1, 'queso', 78, 12, 'Kg', 23000.00, 'Otro', '2025-07-01', '2025-09-26', 'sa-65-mantecados', 'queso', 'almacenamientos la voquilla', 'Activo', 'departamento de lacteos', '68acd80655198_queso.jpg'),
(2, '10', 4, 12, '', 0.00, 'Vegetal', '2025-07-01', '2025-09-26', 'sa', 'as', 'si', 'Activo', 'si', '68881152a3061_modelo med parqueadero.png'),
(3, '10', 4, 12, '', 0.00, 'Vegetal', '2025-07-01', '2025-09-26', 'sa', 'as', 'si', 'Activo', 'si', '6888117bb045f_modelo med parqueadero.png'),
(4, '10', 4, 12, '', 0.00, 'Vegetal', '2025-07-01', '2025-09-26', 'sa', 'as', 'si', 'Activo', 'si', '68881278df52a_modelo med parqueadero.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_restaurant` varchar(100) NOT NULL,
  `user_status` enum('active','blocked','pending') DEFAULT 'pending',
  `user_role` enum('admin','user') DEFAULT 'user',
  `verification_token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `verification_code` varchar(6) DEFAULT NULL,
  `code_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_restaurant`, `user_status`, `user_role`, `verification_token`, `created_at`, `reset_token`, `verification_code`, `code_expires_at`) VALUES
(63, 'camilo', 'camilodangaud21@gmail.com', '$2y$10$EgJ48q880rCaurC3ED9XI.PAgRR3o68Oov4eoNLgFfkv1sxiBcUW.', 'barabarabara', 'pending', 'user', NULL, '2025-08-22 19:52:40', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_temp`
--

CREATE TABLE `usuarios_temp` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `nombre_restaurante` varchar(100) NOT NULL,
  `codigo` varchar(6) NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dish_id` (`dish_id`),
  ADD KEY `insumo_id` (`insumo_id`);

--
-- Indexes for table `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- Indexes for table `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo_unique` (`correo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dishes`
--
ALTER TABLE `dishes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insumos`
--
ALTER TABLE `insumos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  ADD CONSTRAINT `dish_ingredients_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dish_ingredients_ibfk_2` FOREIGN KEY (`insumo_id`) REFERENCES `insumos` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `limpiar_usuarios_temp` ON SCHEDULE EVERY 30 MINUTE STARTS '2025-07-23 19:46:58' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM usuarios_temp WHERE creado_en < NOW() - INTERVAL 30 MINUTE$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
