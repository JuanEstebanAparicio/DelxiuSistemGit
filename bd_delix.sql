-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-07-2025 a las 23:19:59
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
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `usuario_nombre` varchar(100) NOT NULL,
  `usuario_correo` varchar(150) NOT NULL,
  `usuario_clave` varchar(255) NOT NULL,
  `usuario_restaurante` varchar(100) NOT NULL,
  `usuario_estado` enum('activo','bloqueado') DEFAULT 'activo',
  `usuario_rol` enum('admin','usuario') DEFAULT 'usuario',
  `usuario_token` varchar(255) DEFAULT NULL,
  `usuario_creacion` datetime DEFAULT current_timestamp(),
  `recuperar_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario_nombre`, `usuario_correo`, `usuario_clave`, `usuario_restaurante`, `usuario_estado`, `usuario_rol`, `usuario_token`, `usuario_creacion`, `recuperar_token`) VALUES
(42, 'Juan', 'apariciojuanesteban@gmail.com', '$2y$10$kQsBWc9MpL3o6DELuE7oPe8zLEqliUTpLm0pNdwUQd8AZd4NBif3q', 'la rica braza', 'activo', 'usuario', '4bb17055e8a7ecba3f3f5f5603c6916b74e87bfc3464a659535ece64adb27c33', '2025-07-28 12:32:05', NULL);

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `usuario_correo_unique` (`usuario_correo`);

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
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
