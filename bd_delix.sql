-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-08-2025 a las 02:03:47
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
-- Base de datos: `bd_delix`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos`
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
-- Volcado de datos para la tabla `insumos`
--

INSERT INTO `insumos` (`id`, `nombre`, `cantidad`, `cantidad_minima`, `unidad`, `costo_unitario`, `categoria`, `fecha_ingreso`, `fecha_vencimiento`, `lote`, `descripcion`, `ubicacion`, `estado`, `proveedor`, `foto`) VALUES
(1, '10', 4, 12, '', 0.00, 'Vegetal', '2025-07-01', '2025-09-26', 'sa', 'as', 'si', 'Activo', 'si', '6888108722bd7_modelo med parqueadero.png'),
(2, '10', 4, 12, '', 0.00, 'Vegetal', '2025-07-01', '2025-09-26', 'sa', 'as', 'si', 'Activo', 'si', '68881152a3061_modelo med parqueadero.png'),
(3, '10', 4, 12, '', 0.00, 'Vegetal', '2025-07-01', '2025-09-26', 'sa', 'as', 'si', 'Activo', 'si', '6888117bb045f_modelo med parqueadero.png'),
(4, '10', 4, 12, '', 0.00, 'Vegetal', '2025-07-01', '2025-09-26', 'sa', 'as', 'si', 'Activo', 'si', '68881278df52a_modelo med parqueadero.png'),
(0, 'Queseso', 23, 12, 'Kg', 12.00, 'Vegetal', '2025-08-12', '2025-08-29', 'LOTE-BLOQ-33', 'juan121212', 'porno', 'Activo', 'ZENU', '6894d8e192e3e_descarga.jpg'),
(0, 'Queseso', 23, 12, 'Kg', 12.00, 'Vegetal', '2025-08-12', '2025-08-29', 'LOTE-BLOQ-33', 'juan121212', 'porno', 'Activo', 'ZENU', '6894d91e33695_descarga.jpg'),
(0, 'Queseso', 23, 12, 'Kg', 12.00, 'Vegetal', '2025-08-12', '2025-08-29', 'LOTE-BLOQ-33', 'juan121212', 'porno', 'Activo', 'ZENU', '6894d92661d24_descarga.jpg'),
(0, 'Queseso', 23, 12, 'Kg', 12.00, 'Vegetal', '2025-08-12', '2025-08-29', 'LOTE-BLOQ-33', 'juan121212', 'porno', 'Activo', 'ZENU', '6894da65af3bc_descarga.jpg'),
(0, 'Queseso', 23, 12, 'Kg', 12.00, 'Vegetal', '2025-08-12', '2025-08-29', 'LOTE-BLOQ-33', 'juan121212', 'porno', 'Activo', 'ZENU', '6894daedb40cf_descarga.jpg'),
(0, 'Queseso', 323, 2, 'Kg', 23.00, 'Vegetal', '2025-08-13', '2025-08-14', 'QA_R100', 'juasaas', 'porno', 'Activo', 'ZENU', '6894e148134d1_descarga.jpg'),
(0, 'Queseso', 323, 2, 'Kg', 23.00, 'Vegetal', '2025-08-13', '2025-08-14', 'QA_R100', 'juasaas', 'porno', 'Activo', 'ZENU', '6894e1b1eaea3_descarga.jpg'),
(0, 'Queseso', 12, 3333, 'Kg', 12.00, 'Carne', '2025-08-19', '2025-08-20', 'LOTE-BLOQ-33', 'qweqwqe', 'porno', 'Activo', 'fruco', '6894e3b115a80_descarga.jpg'),
(0, 'Queseso', 12, 3333, 'Kg', 12.00, 'Carne', '2025-08-19', '2025-08-20', 'LOTE-BLOQ-33', 'qweqwqe', 'porno', 'Activo', 'fruco', NULL),
(0, 'Queseso', 12, 3333, 'Kg', 12.00, 'Carne', '2025-08-19', '2025-08-20', 'LOTE-BLOQ-33', 'qweqwqe', 'porno', 'Activo', 'fruco', NULL),
(0, 'Queseso', 23, 12, 'Kg', 12.00, 'Vegetal', '2025-08-20', '2025-08-27', 'QA_R100', 'wqqeqqweweqeqe', 'porno', 'Activo', 'FAVI PAN', NULL),
(0, 'Chocolate', 12, 12, 'Unidad', 12.00, 'Bebida', '2025-08-13', '2025-08-15', 'LOTE-BLOQ-33', 'werewrwrewr', 'porno', 'Activo', 'FAVI PAN', NULL),
(0, 'Queseso', 23, 1, 'Kg', 12.00, 'Vegetal', '2025-08-12', '2025-08-14', 'QP-001', '121sddewewqeqqe', 'porno', 'Activo', 'FAVI PAN', NULL),
(0, 'Carne en bistec', 23, 12, 'Kg', 12.00, 'Vegetal', '2025-08-21', '2025-08-29', '12', '1WQEQEQEQ', 'porno', 'Activo', 'fruco', NULL),
(0, 'Lechuguita', 34, 12, 'Kg', 12000.00, 'Vegetal', '2025-08-10', '2025-08-13', 'LOTE-BLOQ-33', 'lengua de res ', 'almacen 1', 'Activo', 'Carniceria del norte ', NULL),
(0, 'Lechuguita', 54, 12, 'Kg', 3000.00, 'Vegetal', '2025-08-12', '2025-08-20', 'QA_R100', 'TOMATE', 'almacen 1', 'Activo', 'FAVI PAN', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_temp`
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
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- Indices de la tabla `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo_unique` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `limpiar_usuarios_temp` ON SCHEDULE EVERY 30 MINUTE STARTS '2025-07-23 19:46:58' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM usuarios_temp WHERE creado_en < NOW() - INTERVAL 30 MINUTE$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
