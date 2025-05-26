-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 26-05-2025 a las 22:19:52
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
-- Base de datos: `restaurante`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_platillos`
--

CREATE TABLE `categorias_platillos` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_platillos`
--

INSERT INTO `categorias_platillos` (`id_categoria`, `nombre_categoria`, `usuario_id`) VALUES
(80, 'Comida', 59);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_estado_orden`
--

CREATE TABLE `historial_estado_orden` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) NOT NULL,
  `nuevo_estado` enum('pendiente','preparacion','listo','entregado','cancelado') NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_inventario`
--

CREATE TABLE `historial_inventario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `lote` varchar(50) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `accion` enum('Ingreso','Modificación','Eliminación') NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_inventario`
--

INSERT INTO `historial_inventario` (`id`, `nombre`, `cantidad`, `lote`, `fecha_vencimiento`, `accion`, `usuario_id`, `fecha_registro`) VALUES
(117, 'Salsa soja', 50.00, 'L-SOY-2025A', '2025-05-26', 'Ingreso', 57, '2025-05-23 21:35:26'),
(118, 'Arroz jazmín', 100.00, 'L-RZ-2025B', '2025-05-24', 'Ingreso', 57, '2025-05-23 21:39:53'),
(119, 'Queso', 50.00, 'RRR_1MM', '2025-05-31', 'Ingreso', 59, '2025-05-24 00:36:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_Ingrediente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT 0.00,
  `cantidad_minima` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unidad_medida` varchar(50) NOT NULL,
  `costo_unitario` decimal(10,2) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT curdate(),
  `fecha_vencimiento` date DEFAULT NULL,
  `lote` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `ubicacion_almacen` varchar(100) DEFAULT NULL,
  `estado` enum('activo','agotado','vencido','no disponible') DEFAULT 'activo',
  `proveedor` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_Ingrediente`, `nombre`, `cantidad`, `cantidad_minima`, `unidad_medida`, `costo_unitario`, `categoria`, `fecha_ingreso`, `fecha_vencimiento`, `lote`, `descripcion`, `ubicacion_almacen`, `estado`, `proveedor`, `foto`, `ultima_actualizacion`, `usuario_id`) VALUES
(71, 'Salsa soja', 50.00, 10.00, 'l', 10000.00, 'Especias', '2025-05-23', '2025-05-26', 'L-SOY-2025A', '', 'Estante 1 - Sección A', 'activo', 'Proveedora Wok', 'ingrediente_6830ea1edbabb.jpg', '2025-05-23 21:35:26', 57),
(72, 'Arroz jazmín', 100.00, 20.00, 'kg', 4000.00, 'Cereales y granos', '2025-05-23', '2025-05-24', 'L-RZ-2025B', 'El arroz (Oryza sativa) es una planta herbácea anual de la familia de las gramíneas (Poaceae), cuyo fruto es un grano ovalado y rico en almidón. Es la semilla de una planta cultivada, principal alimento de más de la mitad de la población mundial, especialmente en Asia y África. ', 'Saco 2 - Sección B', 'activo', 'Arroz Oriental SA', 'ingrediente_6830eb299423a.jpg', '2025-05-23 21:39:53', 57),
(73, 'Queso', 50.00, 10.00, 'kg', 5000.00, 'Lácteos', '2025-05-23', '2025-05-31', 'RRR_1MM', '🧀 ', 'Refrigerador 1', 'activo', 'Lácteos de el norte', 'ingrediente_6831147428267.jpg', '2025-05-24 00:36:04', 59);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_combos`
--

CREATE TABLE `menu_combos` (
  `id_combo` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre_combo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_combo` decimal(10,2) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado` enum('activo','inactivo','agotado') DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_combo_detalles`
--

CREATE TABLE `menu_combo_detalles` (
  `combo_id` int(11) NOT NULL,
  `platillo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_platillo`
--

CREATE TABLE `menu_platillo` (
  `id_platillo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `estado` enum('disponible','no_disponible','agotado') DEFAULT 'disponible',
  `foto` varchar(255) DEFAULT NULL,
  `tiempo_preparacion` int(11) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_platillo_ingredientes`
--

CREATE TABLE `menu_platillo_ingredientes` (
  `id` int(11) NOT NULL,
  `platillo_id` int(11) NOT NULL,
  `ingrediente_id` int(11) NOT NULL,
  `cantidad_necesaria` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cliente_nombre` varchar(100) DEFAULT NULL,
  `cliente_contacto` varchar(50) DEFAULT NULL,
  `mesa` varchar(20) DEFAULT NULL,
  `nota` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `propina` decimal(10,2) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','preparacion','listo','entregado','cancelado') DEFAULT 'pendiente',
  `eliminada` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_detalles`
--

CREATE TABLE `orden_detalles` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) DEFAULT NULL,
  `platillo_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `nombre_restaurante` varchar(100) NOT NULL,
  `estado` enum('activo','bloqueado') DEFAULT 'activo',
  `rol` enum('admin','usuario') DEFAULT 'usuario',
  `token_menu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `correo`, `clave`, `nombre_restaurante`, `estado`, `rol`, `token_menu`) VALUES
(2, 'Administrador', 'admin@gmail.com', '$2y$10$1AoQbRVPOWFgq2IhAC4G2e6HOFh/ROLm.60FIQxLzFbL4sMW6EG92', 'Mi Restaurante', 'activo', 'admin', ''),
(57, 'digna', 'dignadelab@gmail.com', '$2y$10$95DBx1tpUn8Ql3GBGcquLOiw0pGHMY4Vji3.5Jnp1YpX8cSTQnoeC', 'Honk food', 'activo', 'usuario', '25f27a857199717681d611f7821380e1'),
(58, 'cami', 'Camiloelfloo6@gmail.com', '$2y$10$fX/B3oGxx2y7gnuyBuGeJOjUhYJVtRzilf8WL0ql/lZAVB4HXNXmW', 'La gran hoguera', 'activo', 'usuario', '7efcd00ad625460583e1aa456411021c'),
(59, 'Juan', 'apariciojuanesteban@gmail.com', '$2y$10$3nCb0m2BZEGnWneZWc5r2uyHHpeqlfVoj.zQX4MjxwkrlrIiITC3G', 'El punto de delicias ', 'activo', 'usuario', 'cfeae40d741a8e4b8d00a07b07b71c47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_temp`
--

CREATE TABLE `usuarios_temp` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `restaurante` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `codigo_verificacion` varchar(10) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_platillos`
--
ALTER TABLE `categorias_platillos`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `fk_categorias_usuario` (`usuario_id`);

--
-- Indices de la tabla `historial_estado_orden`
--
ALTER TABLE `historial_estado_orden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_id` (`orden_id`);

--
-- Indices de la tabla `historial_inventario`
--
ALTER TABLE `historial_inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_inventario_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_Ingrediente`),
  ADD KEY `fk_usuario` (`usuario_id`);

--
-- Indices de la tabla `menu_combos`
--
ALTER TABLE `menu_combos`
  ADD PRIMARY KEY (`id_combo`),
  ADD KEY `menu_combos_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `menu_combo_detalles`
--
ALTER TABLE `menu_combo_detalles`
  ADD PRIMARY KEY (`combo_id`,`platillo_id`),
  ADD KEY `menu_combo_detalles_ibfk_2` (`platillo_id`);

--
-- Indices de la tabla `menu_platillo`
--
ALTER TABLE `menu_platillo`
  ADD PRIMARY KEY (`id_platillo`),
  ADD KEY `fk_platillo_usuario` (`usuario_id`),
  ADD KEY `fk_platillo_categoria` (`id_categoria`);

--
-- Indices de la tabla `menu_platillo_ingredientes`
--
ALTER TABLE `menu_platillo_ingredientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ingrediente_platillo` (`platillo_id`),
  ADD KEY `fk_ingrediente_inventario` (`ingrediente_id`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `orden_detalles`
--
ALTER TABLE `orden_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_id` (`orden_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_platillos`
--
ALTER TABLE `categorias_platillos`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `historial_estado_orden`
--
ALTER TABLE `historial_estado_orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `historial_inventario`
--
ALTER TABLE `historial_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_Ingrediente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `menu_combos`
--
ALTER TABLE `menu_combos`
  MODIFY `id_combo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `menu_platillo`
--
ALTER TABLE `menu_platillo`
  MODIFY `id_platillo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `menu_platillo_ingredientes`
--
ALTER TABLE `menu_platillo_ingredientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `orden_detalles`
--
ALTER TABLE `orden_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `usuarios_temp`
--
ALTER TABLE `usuarios_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias_platillos`
--
ALTER TABLE `categorias_platillos`
  ADD CONSTRAINT `fk_categorias_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_estado_orden`
--
ALTER TABLE `historial_estado_orden`
  ADD CONSTRAINT `historial_estado_orden_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_inventario`
--
ALTER TABLE `historial_inventario`
  ADD CONSTRAINT `historial_inventario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu_combos`
--
ALTER TABLE `menu_combos`
  ADD CONSTRAINT `menu_combos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `menu_combo_detalles`
--
ALTER TABLE `menu_combo_detalles`
  ADD CONSTRAINT `menu_combo_detalles_ibfk_1` FOREIGN KEY (`combo_id`) REFERENCES `menu_combos` (`id_combo`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_combo_detalles_ibfk_2` FOREIGN KEY (`platillo_id`) REFERENCES `menu_platillo` (`id_platillo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `menu_platillo`
--
ALTER TABLE `menu_platillo`
  ADD CONSTRAINT `fk_platillo_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_platillos` (`id_categoria`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_platillo_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `menu_platillo_ingredientes`
--
ALTER TABLE `menu_platillo_ingredientes`
  ADD CONSTRAINT `fk_ingrediente_inventario` FOREIGN KEY (`ingrediente_id`) REFERENCES `inventario` (`id_Ingrediente`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ingrediente_platillo` FOREIGN KEY (`platillo_id`) REFERENCES `menu_platillo` (`id_platillo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `ordenes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `orden_detalles`
--
ALTER TABLE `orden_detalles`
  ADD CONSTRAINT `orden_detalles_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `eliminar_usuarios_temp` ON SCHEDULE EVERY 30 MINUTE STARTS '2025-04-20 21:58:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM usuarios_temp
  WHERE fecha_creacion < NOW() - INTERVAL 30 MINUTE$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
