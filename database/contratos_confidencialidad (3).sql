-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2025 a las 19:27:18
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
-- Base de datos: `contratos_confidencialidad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contract_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  `contract_content` text NOT NULL,
  `signature_image_path` varchar(255) NOT NULL,
  `pdf_file_path` varchar(255) NOT NULL,
  `status` enum('active','expired','renewed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `contracts`
--

INSERT INTO `contracts` (`id`, `user_id`, `contract_date`, `expiration_date`, `contract_content`, `signature_image_path`, `pdf_file_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-06-17', '2026-06-17', 'Contrato de confidencialidad estándar generado el 2025-06-17 para el usuario con ID: 1.', '', '', 'active', '2025-06-17 16:46:16', '2025-06-17 16:46:16'),
(2, 1, '2025-06-17', '2026-06-17', 'Contrato de confidencialidad estándar generado el 2025-06-17 para el usuario con ID: 1.', '', '', 'active', '2025-06-17 18:02:53', '2025-06-17 18:02:53'),
(3, 1, '2025-06-17', '2026-06-17', 'Contrato de confidencialidad estándar generado el 2025-06-17 para el usuario con ID: 1.', '', '', 'active', '2019-06-17 18:02:53', '2019-12-17 18:02:53'),
(4, 1, '2019-06-18', '2026-06-18', 'Contrato de confidencialidad estándar generado el 2025-06-18 para el usuario con ID: 1.', '', '', 'active', '2025-06-18 11:38:12', '2025-06-18 12:11:20'),
(5, 1, '2020-06-18', '2026-06-18', 'Contrato de confidencialidad estándar generado el 2025-06-18 para el usuario con ID: 1.', '', '', 'active', '2025-06-18 12:23:05', '2025-06-18 12:23:42'),
(6, 2, '2025-06-18', '2026-06-18', 'Contrato de confidencialidad estándar generado el 2025-06-18 para el usuario con ID: 2.', '', '', 'active', '2025-06-18 16:55:30', '2025-06-18 16:55:30'),
(7, 2, '2019-06-18', '2026-06-18', 'Contrato de confidencialidad estándar generado el 2025-06-18 para el usuario con ID: 2.', '', '', 'active', '2025-06-18 16:57:39', '2025-06-18 16:59:28'),
(8, 4, '2025-06-19', '2026-06-19', 'Contrato de confidencialidad estándar generado el 2025-06-19 para el usuario con ID: 4.', '', '', 'active', '2025-06-19 15:56:12', '2025-06-19 15:56:12'),
(9, 5, '2025-06-19', '2026-06-19', 'Contrato de confidencialidad estándar generado el 2025-06-19 para el usuario con ID: 5.', '', '', 'active', '2025-06-19 17:16:21', '2025-06-19 17:16:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `huella_dactilar` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `dependencia` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `cedula`, `username`, `password_hash`, `nombre`, `apellido`, `telefono`, `correo`, `foto_perfil`, `huella_dactilar`, `cargo`, `dependencia`, `created_at`, `updated_at`, `status`) VALUES
(1, '16075795', 'Ascor83', '$2y$10$OKZIbfuvtiMHFaMsQ0LpTOCDWsrpQlDWWP0JRIiFrtr6noNuu7YPC', 'Asdrubal Corales', 'Corales Pérez', '04124811679', 'acorales@apn.gob.ve', 'uploads/6853fed82db4f_fotocarnet.jpg', 'uploads/68515b21eb9c8_hueladactilar.jpg', 'Administrador Base de Datos', 'Oficina de Tecnología de la Información', '2025-06-17 11:59:45', '2025-06-19 12:13:12', 'active'),
(2, '16075796', 'jPerez', '$2y$10$VWAUiNdg0qmzfCVn5kfDQ.WXst2os8JYfB1iHvR97n0QgqY73JVQC', 'José ', 'Pérez', '04124811654', 'jperez@gmail.com', 'uploads/6852efe995bdd_fotocarnet02.jpg', 'uploads/6852ef50e40be_hueladactilar.jpg', 'Apoyo Profesional', 'Recursos Humanos', '2025-06-18 16:53:12', '2025-06-18 16:57:13', 'active'),
(3, '16123456', 'mavallejo', '$2y$10$ocOxyOHxkmjpkQKzuhE3FuAgHwIbRt.Gt5ZmZpdIXkj.SCmGdLfSm', 'María Vallejo', '', NULL, 'mavallejo@gmail.com', NULL, NULL, NULL, NULL, '2025-06-18 19:11:03', '2025-06-18 19:11:03', 'active'),
(4, '6998415', 'lexposito', '$2y$10$ocOxyOHxkmjpkQKzuhE3FuAgHwIbRt.Gt5ZmZpdIXkj.SCmGdLfSm', 'Lizbeth María', 'Expósito', '04166225236', 'lexposito@ine.gob.ve', 'uploads/685432dacaac3_fotocarnet03.jpg', 'uploads/685432dacad86_hueladactilar.jpg', 'Gerente (E)', 'Oficina de Tecnología de la Información', '2025-06-19 15:45:08', '2025-06-19 17:07:31', 'active'),
(5, '13987654', 'jcorales', '$2y$10$x7/Mt3HFzp.6qPYYQ1oYlehUhgC.XClz9vY1s3/HicM9BtUYvUDFC', 'Juan Manuel', 'Corales Pérez', '04124811672', 'jcorales@gmail.com', 'uploads/685445c7dafec_fotocarnet02.jpg', 'uploads/685445c7dc973_hueladactilar.jpg', 'Programador', 'Oficina de Tecnología de la Información', '2025-06-19 17:14:41', '2025-06-19 17:15:51', 'active');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
