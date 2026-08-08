-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-08-2026 a las 05:02:31
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
-- Base de datos: `audiolibro_english`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos_modulos`
--

CREATE TABLE `accesos_modulos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `numero_modulo` int(11) NOT NULL,
  `estado` varchar(20) DEFAULT 'pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `numero_modulo` int(11) NOT NULL,
  `metodo_pago` enum('airtm','apollo_pay','paypal','pago_movil') NOT NULL,
  `monto` decimal(5,2) DEFAULT 0.50,
  `referencia_transaccion` varchar(255) NOT NULL,
  `nivel_solicitado` varchar(50) DEFAULT '1',
  `estatus` enum('pendiente','completado','rechazado') DEFAULT 'pendiente',
  `fecha_pago` datetime DEFAULT current_timestamp(),
  `estado` varchar(50) DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `suscripcion_activa` tinyint(1) DEFAULT 0,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `rol` varchar(20) DEFAULT 'cliente',
  `es_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `suscripcion_activa`, `fecha_registro`, `rol`, `es_admin`) VALUES
(2, 'nerbelis valdeblanques', 'valdeblanques@gmail.com', '$2y$10$zWnCbvCj4YzVV6XeuoJgrOtYvLRR5eeUX/RI/MOHrCKk9cocSwJJu', 1, '2026-08-04 08:33:46', 'admin', 1),
(9, 'brisneida fernandez', 'bri11283989@gmail.com', '$2y$10$LKjeO7lAiG6Xy7i6ulxYzO8rscLwSJX2sD2hC1RhltrDTGxdKwRkO', 0, '2026-08-05 20:17:02', 'cliente', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos_modulos`
--
ALTER TABLE `accesos_modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesos_modulos`
--
ALTER TABLE `accesos_modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
