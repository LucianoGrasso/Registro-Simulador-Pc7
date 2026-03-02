-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2026 a las 12:41:55
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
-- Base de datos: `registro_simulador`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `rut_dni` varchar(255) NOT NULL,
  `npi` varchar(255) NOT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `qr_code` text DEFAULT NULL,
  `qr_image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre_completo`, `rut_dni`, `npi`, `correo`, `qr_code`, `qr_image_path`, `is_active`, `metadata`, `created_at`, `updated_at`) VALUES
(6, 'Jose Ignacio Rodriguez Sepulveda', '19.809.561-3', '002420-6', NULL, '002420-6', 'qr-codes/qr_002420-6.svg', 1, NULL, '2025-09-25 18:36:35', '2025-09-25 18:36:37'),
(7, 'Pablo Ignacio Brinnkman Balocci', '20.180.742-5', '005821-6', NULL, '005821-6', 'qr-codes/qr_005821-6.svg', 1, NULL, '2025-09-25 18:37:47', '2025-09-25 18:37:47'),
(8, 'Agustin Arturo Muñoz Anguita', '19.940.491-1', '000421-9', NULL, '000421-9', 'qr-codes/qr_000421-9.svg', 1, NULL, '2025-09-25 18:38:47', '2025-09-25 18:38:47'),
(9, 'Fransisco Tomas Fernandez Obeso', '19.962.367-2', '002121-4', NULL, '002121-4', 'qr-codes/qr_002121-4.svg', 1, NULL, '2025-09-25 18:39:45', '2025-09-25 18:39:45'),
(10, 'Jorge Andres Urban Asenjo', '19.994.469-K', '001421-5', NULL, '001421-5', 'qr-codes/qr_001421-5.svg', 1, NULL, '2025-09-25 18:40:41', '2025-09-25 18:40:41'),
(11, 'Martin De La Fuente De La Horra', '20.012.418-9', '001321-3', NULL, '001321-3', 'qr-codes/qr_001321-3.svg', 1, NULL, '2025-09-25 18:42:22', '2025-09-25 18:42:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('registro-simulador-cache-administrador@simulador.local|127.0.0.1', 'i:1;', 1771620747),
('registro-simulador-cache-administrador@simulador.local|127.0.0.1:timer', 'i:1771620747;', 1771620747);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_16_172829_create_alumnos_table', 1),
(5, '2025_09_16_172838_create_sesiones_table', 1),
(6, '2025_09_16_172844_add_role_to_users_table', 1),
(7, '2025_09_23_123058_remove_unique_constraint_from_sesiones_table', 1),
(8, '2025_09_29_105700_create_soportes_table', 2),
(9, '2025_12_01_104445_add_archivo_vuelo_to_sesiones_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alumno_id` bigint(20) UNSIGNED NOT NULL,
  `npi` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_fin` datetime DEFAULT NULL,
  `duracion_minutos` int(11) DEFAULT NULL,
  `actividad` text NOT NULL,
  `estado` enum('activa','finalizada','cancelada') NOT NULL DEFAULT 'activa',
  `usuario_inicio_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_fin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `detalles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles`)),
  `observaciones` text DEFAULT NULL,
  `archivo_vuelo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`id`, `alumno_id`, `npi`, `fecha`, `hora_inicio`, `hora_fin`, `duracion_minutos`, `actividad`, `estado`, `usuario_inicio_id`, `usuario_fin_id`, `detalles`, `observaciones`, `archivo_vuelo`, `created_at`, `updated_at`) VALUES
(12, 8, '0004219', '2025-10-02', '2025-10-02 10:35:17', '2025-10-02 10:47:58', 12, 'Practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-02 13:35:17', '2025-10-02 13:47:58'),
(15, 7, '005821-6', '2025-10-06', '2025-10-06 10:55:48', '2025-10-06 12:01:09', 65, 'Emergencia - Rodelillo', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-06 13:55:48', '2025-10-06 15:01:09'),
(17, 7, '005821-6', '2025-10-08', '2025-10-08 10:11:55', '2025-10-08 11:40:31', 100, 'Practicando emergencia', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-08 13:11:55', '2025-10-08 18:40:31'),
(20, 9, '002121-4', '2025-10-09', '2025-10-09 12:21:14', '2025-10-09 12:22:15', 1, 'Practica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-09 15:21:14', '2025-10-09 15:22:15'),
(21, 6, '002420-6', '2025-10-11', '2025-10-11 10:40:55', '2025-10-11 12:31:02', 110, 'práctica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-11 13:40:55', '2025-10-11 15:31:02'),
(22, 10, '001421-5', '2025-10-11', '2025-10-11 12:31:24', '2025-10-11 13:22:13', 50, 'práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-11 15:31:24', '2025-10-11 16:22:13'),
(23, 6, '002420-6', '2025-10-11', '2025-10-11 19:27:21', '2025-10-11 20:45:35', 78, 'práctica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-11 22:27:21', '2025-10-11 23:45:35'),
(24, 10, '001421-5', '2025-10-12', '2025-10-12 11:31:56', '2025-10-12 12:11:57', 40, 'práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-12 14:31:56', '2025-10-12 15:11:57'),
(25, 11, '001321-3', '2025-10-12', '2025-10-12 19:26:38', '2025-10-12 21:03:09', 96, 'Práctica en seco y practica normaln simulador', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-12 22:26:38', '2025-10-13 00:03:09'),
(26, 9, '0021214', '2025-10-12', '2025-10-12 21:11:03', '2025-10-12 21:53:22', 42, 'Práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-13 00:11:03', '2025-10-13 00:53:22'),
(27, 7, '005821-6', '2025-10-13', '2025-10-13 10:12:42', '2025-10-13 11:49:59', 97, 'practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-13 13:12:42', '2025-10-13 14:49:59'),
(28, 8, '000421-9', '2025-10-13', '2025-10-13 11:51:21', '2025-10-13 12:17:04', 25, 'practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-13 14:51:21', '2025-10-13 15:17:04'),
(29, 11, '001321-3', '2025-10-13', '2025-10-13 18:13:25', '2025-10-13 19:14:04', 60, 'Práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-13 21:13:25', '2025-10-13 22:14:04'),
(30, 6, '002420-6', '2025-10-13', '2025-10-13 19:19:13', '2025-10-13 19:51:55', 32, 'práctica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-13 22:19:13', '2025-10-13 22:51:55'),
(31, 7, '005821-6', '2025-10-13', '2025-10-13 20:26:27', '2025-10-13 21:33:55', 67, 'practica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-13 23:26:27', '2025-10-14 00:33:55'),
(32, 6, '002420-6', '2025-10-14', '2025-10-14 10:54:05', '2025-10-14 11:30:10', 36, 'practica en seco y normal', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-14 13:54:05', '2025-10-14 14:30:10'),
(33, 10, '001421-5', '2025-10-14', '2025-10-14 14:44:40', '2025-10-14 15:03:53', 19, 'Practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-14 17:44:40', '2025-10-14 18:03:53'),
(34, 8, '000421-9', '2025-10-14', '2025-10-14 15:24:50', '2025-10-14 16:30:59', 66, 'Practica', 'finalizada', 1, 2, NULL, NULL, NULL, '2025-10-14 18:24:50', '2025-10-14 19:30:59'),
(35, 9, '0021214', '2025-10-14', '2025-10-14 16:42:05', '2025-10-14 18:35:28', 113, 'Práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-14 19:42:05', '2025-10-14 21:35:28'),
(36, 11, '001321-3', '2025-10-14', '2025-10-14 17:28:49', '2025-10-14 18:35:32', 66, 'práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-14 20:28:49', '2025-10-14 21:35:32'),
(37, 8, '000421-9', '2025-10-14', '2025-10-14 18:38:09', '2025-10-14 19:27:54', 49, 'Práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-14 21:38:09', '2025-10-14 22:27:54'),
(38, 7, '005821-6', '2025-10-14', '2025-10-14 20:17:34', '2025-10-14 22:11:10', 113, 'practica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-14 23:17:34', '2025-10-15 01:11:10'),
(39, 7, '005821-6', '2025-10-15', '2025-10-15 09:43:34', '2025-10-15 10:30:02', 46, 'Practica roedillo', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-15 12:43:34', '2025-10-15 13:30:02'),
(40, 6, '002420-6', '2025-10-15', '2025-10-15 10:32:19', '2025-10-15 10:59:18', 26, 'practica casa blanca', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-15 13:32:19', '2025-10-15 13:59:18'),
(41, 8, '000421-9', '2025-10-15', '2025-10-15 11:31:22', '2025-10-15 12:15:54', 44, 'emergencia - boco', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-15 14:31:22', '2025-10-15 15:15:54'),
(42, 11, '001321-3', '2025-10-15', '2025-10-15 14:44:51', '2025-10-15 15:36:26', 51, 'Práctica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-15 17:44:51', '2025-10-15 18:36:26'),
(43, 8, '000421-9', '2025-10-15', '2025-10-15 18:59:58', '2025-10-15 19:53:02', 53, 'práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-15 21:59:58', '2025-10-15 22:53:02'),
(44, 9, '0021214', '2025-10-15', '2025-10-15 20:40:23', '2025-10-15 21:50:48', 70, 'PRÁCTICA', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-15 23:40:23', '2025-10-16 00:50:48'),
(45, 8, '000421-9', '2025-10-16', '2025-10-16 08:53:02', '2025-10-16 09:50:47', 57, 'Practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-16 11:53:02', '2025-10-16 12:50:47'),
(46, 10, '001421-5', '2025-10-16', '2025-10-16 09:03:55', '2025-10-16 09:50:55', 47, 'Practica apoyo', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-16 12:03:55', '2025-10-16 12:50:55'),
(47, 8, '000421-9', '2025-10-16', '2025-10-16 11:28:18', '2025-10-16 11:55:44', 27, 'practica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-16 14:28:18', '2025-10-16 14:55:44'),
(48, 11, '001321-3', '2025-10-16', '2025-10-16 17:01:13', '2025-10-16 17:43:05', 41, 'práctica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-16 20:01:13', '2025-10-16 20:43:05'),
(49, 7, '005821-6', '2025-10-16', '2025-10-16 17:53:43', '2025-10-16 19:07:33', 73, 'practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-16 20:53:43', '2025-10-16 22:07:33'),
(50, 9, '0021214', '2025-10-16', '2025-10-16 19:11:40', '2025-10-16 20:18:44', 67, 'Práctica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-16 22:11:40', '2025-10-16 23:18:44'),
(51, 9, '0021214', '2025-10-19', '2025-10-19 15:32:51', '2025-10-19 16:39:04', 66, 'Práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-19 18:32:51', '2025-10-19 19:39:04'),
(52, 9, '0021214', '2025-10-19', '2025-10-19 19:18:20', '2025-10-19 20:18:44', 60, 'Práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-19 22:18:20', '2025-10-19 23:18:44'),
(53, 7, '005821-6', '2025-10-20', '2025-10-20 10:21:26', '2025-10-20 11:12:19', 50, 'Practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-20 13:21:26', '2025-10-20 14:12:19'),
(54, 8, '000421-9', '2025-10-20', '2025-10-20 11:14:35', '2025-10-20 11:44:58', 30, 'Practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-20 14:14:35', '2025-10-20 14:44:58'),
(55, 6, '002420-6', '2025-10-20', '2025-10-20 11:46:07', '2025-10-20 12:17:03', 30, 'Practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-20 14:46:07', '2025-10-20 15:17:03'),
(56, 11, '001321-3', '2025-10-20', '2025-10-20 13:52:45', '2025-10-20 13:54:20', 1, 'práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-20 16:52:45', '2025-10-20 16:54:20'),
(57, 11, '001321-3', '2025-10-20', '2025-10-20 13:54:50', '2025-10-20 15:12:28', 77, 'práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-20 16:54:50', '2025-10-20 18:12:28'),
(58, 10, '001421-5', '2025-10-20', '2025-10-20 16:24:22', '2025-10-20 17:12:33', 48, 'práctica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-20 19:24:22', '2025-10-20 20:12:33'),
(59, 8, '0004219', '2025-10-20', '2025-10-20 17:13:28', '2025-10-20 18:14:23', 60, 'practica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-20 20:13:28', '2025-10-20 21:14:23'),
(60, 6, '002420-6', '2025-10-20', '2025-10-20 20:26:20', '2025-10-20 21:00:11', 33, 'práctica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-20 23:26:20', '2025-10-21 00:00:11'),
(61, 7, '005821-6', '2025-10-20', '2025-10-20 21:01:36', '2025-10-20 21:52:52', 51, 'practica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-21 00:01:36', '2025-10-21 00:52:52'),
(62, 10, '001421-5', '2025-10-21', '2025-10-21 09:55:53', '2025-10-21 10:49:32', 53, 'Practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-21 12:55:53', '2025-10-21 13:49:32'),
(63, 8, '000421-9', '2025-10-21', '2025-10-21 10:53:15', '2025-10-21 11:41:44', 48, 'Practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-21 13:53:15', '2025-10-21 14:41:44'),
(64, 8, '000421-9', '2025-10-21', '2025-10-21 15:12:35', '2025-10-21 15:57:49', 45, 'Practica', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-21 18:12:35', '2025-10-21 18:57:49'),
(65, 7, '005821-6', '2025-10-22', '2025-10-22 11:00:52', '2025-10-22 12:20:49', 79, 'practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-22 14:00:52', '2025-10-22 15:20:49'),
(66, 6, '002420-6', '2025-10-22', '2025-10-22 13:43:30', '2025-10-22 14:39:32', 56, 'practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-22 16:43:30', '2025-10-22 17:39:32'),
(67, 11, '001321-3', '2025-10-22', '2025-10-22 15:10:54', '2025-10-22 16:41:44', 90, 'practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-22 18:10:54', '2025-10-22 19:41:44'),
(68, 9, '002121-4', '2025-10-22', '2025-10-22 15:11:23', '2025-10-22 16:41:47', 90, 'practica', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-22 18:11:23', '2025-10-22 19:41:47'),
(69, 8, '000421-9', '2025-10-22', '2025-10-22 17:44:48', '2025-10-22 18:03:57', 19, 'PRÁCTICA', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-22 20:44:48', '2025-10-22 21:03:57'),
(70, 10, '001421-5', '2025-10-22', '2025-10-22 20:16:53', '2025-10-22 21:27:21', 70, 'PRÁCTICA', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-22 23:16:53', '2025-10-23 00:27:21'),
(71, 11, '001321-3', '2025-10-22', '2025-10-22 21:37:28', '2025-10-22 22:16:32', 39, 'PRÁCTICA', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-23 00:37:28', '2025-10-23 01:16:32'),
(72, 8, '000421-9', '2025-10-23', '2025-10-23 10:11:55', '2025-10-23 11:26:25', 74, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-23 13:11:55', '2025-10-23 14:26:25'),
(73, 10, '001421-5', '2025-10-23', '2025-10-23 11:27:22', '2025-10-23 11:40:09', 12, 'Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-23 14:27:22', '2025-10-23 14:40:09'),
(74, 7, '005821-6', '2025-10-23', '2025-10-23 11:27:40', '2025-10-23 11:40:07', 12, 'Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-23 14:27:40', '2025-10-23 14:40:07'),
(75, 6, '002420-6', '2025-10-23', '2025-10-23 14:31:53', '2025-10-23 16:05:54', 94, 'Emergencia en vuelo', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-23 17:31:53', '2025-10-23 19:05:54'),
(76, 10, '001421-5', '2025-10-23', '2025-10-23 17:00:13', '2025-10-23 18:56:10', 115, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-23 20:00:13', '2025-10-23 21:56:10'),
(77, 9, '0021214', '2025-10-23', '2025-10-23 17:57:54', '2025-10-23 18:56:13', 58, 'Trabajo en pista, Acrobacias, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-23 20:57:54', '2025-10-23 21:56:13'),
(78, 11, '001321-3', '2025-10-23', '2025-10-23 17:59:08', '2025-10-23 18:56:11', 57, 'Trabajo en pista, Acrobacias', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-23 20:59:08', '2025-10-23 21:56:11'),
(79, 7, '005821-6', '2025-10-24', '2025-10-24 10:53:30', '2025-10-24 12:53:50', 120, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-24 13:53:30', '2025-10-24 15:53:50'),
(80, 6, '002420-6', '2025-10-24', '2025-10-24 10:54:03', '2025-10-24 11:13:12', 19, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-24 13:54:03', '2025-10-24 14:13:12'),
(81, 9, '002121-4', '2025-10-24', '2025-10-24 14:24:44', '2025-10-24 15:43:36', 78, 'Emergencia en vuelo, Trabajo en pista, Acrobacias', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-24 17:24:44', '2025-10-24 18:43:36'),
(82, 8, '000421-9', '2025-10-25', '2025-10-25 15:03:05', '2025-10-25 16:13:20', 70, 'Práctica en seco, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-25 18:03:05', '2025-10-25 19:13:20'),
(83, 8, '000421-9', '2025-10-26', '2025-10-26 08:53:40', '2025-10-26 09:55:28', 61, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-26 11:53:40', '2025-10-26 12:55:28'),
(84, 6, '002420-6', '2025-10-26', '2025-10-26 09:15:23', '2025-10-26 10:52:26', 97, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-26 12:15:23', '2025-10-26 13:52:26'),
(85, 11, '001321-3', '2025-10-26', '2025-10-26 18:13:56', '2025-10-26 19:19:28', 65, 'Práctica en seco, Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-26 21:13:56', '2025-10-26 22:19:28'),
(86, 11, '001321-3', '2025-10-26', '2025-10-26 19:33:36', '2025-10-26 19:52:17', 18, 'Práctica en seco, Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-26 22:33:36', '2025-10-26 22:52:17'),
(87, 10, '001421-5', '2025-10-27', '2025-10-27 10:39:10', '2025-10-27 12:20:51', 101, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-27 13:39:10', '2025-10-27 15:20:51'),
(88, 6, '002420-6', '2025-10-27', '2025-10-27 11:02:50', '2025-10-27 12:20:53', 78, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-27 14:02:50', '2025-10-27 15:20:53'),
(89, 9, '0021214', '2025-10-27', '2025-10-27 14:35:07', '2025-10-27 15:43:45', 68, 'Emergencia en vuelo, Trabajo en pista, Acrobacias', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-27 17:35:07', '2025-10-27 18:43:45'),
(90, 6, '002420-6', '2025-10-27', '2025-10-27 16:41:40', '2025-10-27 17:27:49', 46, 'Emergencia en vuelo', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-27 19:41:40', '2025-10-27 20:27:49'),
(91, 8, '000421-9', '2025-10-27', '2025-10-27 18:03:18', '2025-10-27 18:55:23', 52, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-27 21:03:18', '2025-10-27 21:55:23'),
(92, 6, '002420-6', '2025-10-28', '2025-10-28 13:29:54', '2025-10-28 14:17:57', 48, 'Emergencia en vuelo', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-28 16:29:54', '2025-10-28 17:17:57'),
(93, 7, '005821-6', '2025-10-28', '2025-10-28 15:51:32', '2025-10-28 18:21:41', 150, 'Emergencia en vuelo, Trabajo en pista, Acrobacias', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-28 18:51:32', '2025-10-28 21:21:41'),
(94, 6, '002420-6', '2025-10-29', '2025-10-29 07:23:41', '2025-10-29 08:18:36', 54, 'Práctica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-29 10:23:41', '2025-10-29 11:18:36'),
(95, 11, '001321-3', '2025-10-29', '2025-10-29 11:26:54', '2025-10-29 12:10:12', 43, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-29 14:26:54', '2025-10-29 15:10:12'),
(96, 8, '000421-9', '2025-10-29', '2025-10-29 14:03:19', '2025-10-29 14:51:29', 48, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-29 17:03:19', '2025-10-29 17:51:29'),
(97, 10, '001421-5', '2025-10-29', '2025-10-29 16:20:39', '2025-10-29 17:39:53', 79, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-29 19:20:39', '2025-10-29 20:39:53'),
(98, 8, '000421-9', '2025-10-29', '2025-10-29 17:52:22', '2025-10-29 18:55:39', 63, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-29 20:52:22', '2025-10-29 21:55:39'),
(99, 11, '001321-3', '2025-10-29', '2025-10-29 20:21:44', '2025-10-29 21:12:23', 50, 'Práctica en seco, Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-29 23:21:44', '2025-10-30 00:12:23'),
(100, 10, '001421-5', '2025-10-30', '2025-10-30 07:35:44', '2025-10-30 08:21:13', 45, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-30 10:35:44', '2025-10-30 11:21:13'),
(101, 8, '000421-9', '2025-10-30', '2025-10-30 09:33:40', '2025-10-30 10:08:11', 34, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-30 12:33:40', '2025-10-30 13:08:11'),
(102, 10, '001421-5', '2025-10-30', '2025-10-30 10:10:00', '2025-10-30 10:49:01', 39, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-10-30 13:10:00', '2025-10-30 13:49:01'),
(103, 10, '001421-5', '2025-10-31', '2025-10-31 10:42:25', '2025-10-31 11:22:55', 40, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-31 13:42:25', '2025-10-31 14:22:55'),
(104, 7, '005821-6', '2025-10-31', '2025-10-31 18:04:19', '2025-10-31 20:07:54', 123, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-10-31 21:04:19', '2025-10-31 23:07:54'),
(105, 9, '0021214', '2025-11-01', '2025-11-01 18:34:22', '2025-11-01 19:40:14', 65, 'Práctica en seco, Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-01 21:34:22', '2025-11-01 22:40:14'),
(106, 9, '0021214', '2025-11-01', '2025-11-01 20:27:08', '2025-11-01 21:31:10', 64, 'Práctica en seco, Emergencia en vuelo, Acrobacias', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-01 23:27:08', '2025-11-02 00:31:10'),
(107, 10, '001421-5', '2025-11-02', '2025-11-02 11:46:02', '2025-11-02 12:45:08', 59, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-02 14:46:02', '2025-11-02 15:45:08'),
(108, 8, '000421-9', '2025-11-02', '2025-11-02 15:56:43', '2025-11-02 16:53:35', 56, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-02 18:56:43', '2025-11-02 19:53:35'),
(109, 8, '000421-9', '2025-11-03', '2025-11-03 18:02:55', '2025-11-03 18:41:13', 38, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-03 21:02:55', '2025-11-03 21:41:13'),
(110, 10, '001421-5', '2025-11-03', '2025-11-03 20:03:00', '2025-11-03 21:04:26', 61, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-03 23:03:00', '2025-11-04 00:04:26'),
(111, 6, '002420-6', '2025-11-04', '2025-11-04 07:25:58', '2025-11-04 08:12:51', 46, 'Práctica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-04 10:25:58', '2025-11-04 11:12:51'),
(112, 9, '0021214', '2025-11-04', '2025-11-04 09:23:34', '2025-11-04 10:05:23', 41, 'Acrobacias', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-04 12:23:34', '2025-11-04 13:05:23'),
(113, 7, '005821-6', '2025-11-04', '2025-11-04 09:30:40', '2025-11-04 10:05:20', 34, 'Acrobacias', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-04 12:30:40', '2025-11-04 13:05:20'),
(114, 7, '005821-6', '2025-11-04', '2025-11-04 11:26:47', '2025-11-04 12:05:37', 38, 'Acrobacias', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-04 14:26:47', '2025-11-04 15:05:37'),
(115, 6, '002420-6', '2025-11-04', '2025-11-04 14:10:28', '2025-11-04 14:40:47', 30, 'Emergencia en vuelo', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-04 17:10:28', '2025-11-04 17:40:47'),
(116, 10, '001421-5', '2025-11-05', '2025-11-05 15:17:45', '2025-11-05 16:07:40', 49, 'Acrobacias', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-05 18:17:45', '2025-11-05 19:07:40'),
(117, 11, '001321-3', '2025-11-05', '2025-11-05 19:04:48', '2025-11-05 20:54:20', 109, 'Práctica en seco, Emergencia en vuelo, Acrobacias', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-05 22:04:48', '2025-11-05 23:54:20'),
(118, 11, '001321-3', '2025-11-05', '2025-11-05 20:54:29', '2025-11-05 21:25:03', 30, 'Práctica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-05 23:54:29', '2025-11-06 00:25:03'),
(119, 7, '005821-6', '2025-11-06', '2025-11-06 11:16:59', '2025-11-06 12:17:49', 60, 'Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-06 14:16:59', '2025-11-06 15:17:49'),
(120, 10, '001421-5', '2025-11-06', '2025-11-06 15:32:45', '2025-11-06 16:05:13', 32, 'Acrobacias', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-06 18:32:45', '2025-11-06 19:05:13'),
(121, 9, '002121-4', '2025-11-07', '2025-11-07 09:46:26', '2025-11-07 10:24:48', 38, 'Práctica en seco, Emergencia en vuelo, Trabajo en pista, Acrobacias', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-07 12:46:26', '2025-11-07 13:24:48'),
(122, 6, '002420-6', '2025-11-09', '2025-11-09 19:05:43', '2025-11-09 20:08:00', 62, 'Emergencia en vuelo', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-09 22:05:43', '2025-11-09 23:08:00'),
(123, 10, '001421-5', '2025-11-09', '2025-11-09 20:26:03', '2025-11-09 21:22:32', 56, 'Práctica en seco, Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-09 23:26:03', '2025-11-10 00:22:32'),
(124, 6, '002420-6', '2025-11-10', '2025-11-10 08:52:07', '2025-11-10 09:26:44', 34, 'Emergencia en vuelo', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-10 11:52:07', '2025-11-10 12:26:44'),
(125, 10, '001421-5', '2025-11-10', '2025-11-10 10:17:35', '2025-11-10 10:55:21', 37, 'Emergencia en vuelo, Trabajo en pista, Acrobacias', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-10 13:17:35', '2025-11-10 13:55:21'),
(126, 10, '001421-5', '2025-11-10', '2025-11-10 16:27:51', '2025-11-10 18:15:59', 108, 'Emergencia en vuelo, Trabajo en pista, Acrobacias', 'finalizada', 1, 2, NULL, NULL, NULL, '2025-11-10 19:27:51', '2025-11-10 21:15:59'),
(127, 8, '000421-9', '2025-11-10', '2025-11-10 18:17:08', '2025-11-10 19:12:09', 55, 'Práctica en seco, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-10 21:17:08', '2025-11-10 22:12:09'),
(128, 11, '001321-3', '2025-11-10', '2025-11-10 18:17:22', '2025-11-10 19:12:12', 54, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-10 21:17:23', '2025-11-10 22:12:12'),
(129, 10, '001421-5', '2025-11-11', '2025-11-11 08:57:32', '2025-11-11 09:35:55', 38, 'Práctica en seco', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-11 11:57:32', '2025-11-11 12:35:55'),
(130, 8, '000421-9', '2025-11-11', '2025-11-11 15:33:54', '2025-11-11 16:21:53', 47, 'Acrobacias', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-11 18:33:54', '2025-11-11 19:21:53'),
(131, 6, '002420-6', '2025-11-12', '2025-11-12 17:18:10', '2025-11-12 18:34:51', 76, 'Práctica en seco', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-12 20:18:10', '2025-11-12 21:34:51'),
(132, 8, '000421-9', '2025-11-12', '2025-11-12 17:18:20', '2025-11-12 18:34:48', 76, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-12 20:18:20', '2025-11-12 21:34:48'),
(133, 8, '000421-9', '2025-11-19', '2025-11-19 18:09:06', '2025-11-19 18:50:28', 41, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-19 21:09:06', '2025-11-19 21:50:28'),
(134, 11, '001321-3', '2025-11-19', '2025-11-19 18:09:15', '2025-11-19 18:50:25', 41, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-19 21:09:15', '2025-11-19 21:50:25'),
(135, 9, '002121-4', '2025-11-19', '2025-11-19 20:48:40', '2025-11-19 21:38:45', 50, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-19 23:48:40', '2025-11-20 00:38:45'),
(136, 11, '001321-3', '2025-11-20', '2025-11-20 19:43:49', '2025-11-20 19:44:11', 0, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-20 22:43:49', '2025-11-20 22:44:11'),
(137, 11, '001321-3', '2025-11-20', '2025-11-20 19:45:35', '2025-11-20 21:50:50', 125, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-20 22:45:35', '2025-11-21 00:50:50'),
(138, 9, '002121-4', '2025-11-20', '2025-11-20 19:45:52', '2025-11-20 21:50:52', 125, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-20 22:45:52', '2025-11-21 00:50:52'),
(139, 8, '000421-9', '2025-11-20', '2025-11-20 19:46:06', '2025-11-20 21:50:58', 124, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-20 22:46:06', '2025-11-21 00:50:58'),
(140, 10, '001421-5', '2025-11-20', '2025-11-20 19:46:21', '2025-11-20 21:50:55', 124, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-20 22:46:21', '2025-11-21 00:50:55'),
(141, 8, '000421-9', '2025-11-23', '2025-11-23 17:29:54', '2025-11-23 20:03:03', 153, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-23 20:29:54', '2025-11-23 23:03:03'),
(142, 11, '001321-3', '2025-11-23', '2025-11-23 20:04:10', '2025-11-23 20:39:35', 35, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-23 23:04:10', '2025-11-23 23:39:35'),
(143, 11, '001321-3', '2025-11-23', '2025-11-23 20:39:50', '2025-11-23 21:44:21', 64, 'Práctica en seco, Trabajo en pista, Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-23 23:39:50', '2025-11-24 00:44:21'),
(144, 11, '001321-3', '2025-11-25', '2025-11-25 18:49:14', '2025-11-25 19:06:05', 16, 'Trabajo en pista, Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-25 21:49:14', '2025-11-25 22:06:05'),
(145, 6, '002420-6', '2025-11-27', '2025-11-27 11:12:09', '2025-11-27 11:29:27', 17, 'Navegación', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-11-27 14:12:09', '2025-11-27 14:29:27'),
(146, 6, '002420-6', '2025-11-27', '2025-11-27 18:24:22', '2025-11-27 19:29:26', 65, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-27 21:24:22', '2025-11-27 22:29:26'),
(147, 10, '001421-5', '2025-11-27', '2025-11-27 18:24:40', '2025-11-27 19:29:24', 64, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-27 21:24:40', '2025-11-27 22:29:24'),
(148, 9, '002121-4', '2025-11-27', '2025-11-27 20:20:37', '2025-11-27 22:17:38', 117, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-27 23:20:37', '2025-11-28 01:17:38'),
(149, 7, '005821-6', '2025-11-27', '2025-11-27 20:20:53', '2025-11-27 22:17:36', 116, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-27 23:20:53', '2025-11-28 01:17:36'),
(150, 6, '002420-6', '2025-11-30', '2025-11-30 11:15:01', '2025-11-30 13:05:56', 110, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-30 14:15:01', '2025-11-30 16:05:56'),
(151, 10, '001421-5', '2025-11-30', '2025-11-30 11:15:37', '2025-11-30 13:05:54', 110, 'Navegación', 'finalizada', 2, 2, NULL, NULL, NULL, '2025-11-30 14:15:37', '2025-11-30 16:05:54'),
(152, 8, '000421-9', '2025-11-30', '2025-11-30 14:37:14', '2025-11-30 16:40:34', 123, 'Navegación', 'finalizada', 2, 1, NULL, NULL, NULL, '2025-11-30 17:37:14', '2025-12-01 14:05:34'),
(153, 11, '001321-3', '2025-12-01', '2025-12-01 11:36:47', '2025-12-01 12:04:42', 27, 'Navegación', 'finalizada', 1, 1, NULL, NULL, NULL, '2025-12-01 14:36:47', '2025-12-01 15:04:42'),
(162, 9, '002121-4', '2025-12-02', '2025-12-02 10:55:59', '2025-12-02 11:22:51', 26, 'Navegación', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_162.json', '2025-12-02 13:55:59', '2025-12-02 14:22:51'),
(165, 7, '005821-6', '2025-12-03', '2025-12-03 08:43:30', '2025-12-03 08:56:45', 13, 'Emergencia en vuelo', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_165.json', '2025-12-03 11:43:30', '2025-12-03 11:56:45'),
(166, 6, '002420-6', '2025-12-03', '2025-12-03 11:12:27', '2025-12-03 12:59:17', 106, 'Navegación', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_166.json', '2025-12-03 14:12:27', '2025-12-03 15:59:17'),
(167, 6, '002420-6', '2025-12-03', '2025-12-03 17:02:13', '2025-12-04 08:39:56', 120, 'Navegación', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_167.json', '2025-12-03 20:02:13', '2025-12-04 11:39:56'),
(168, 7, '005821-6', '2025-12-16', '2025-12-16 15:48:37', '2025-12-16 16:11:26', 22, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_168.json', '2025-12-16 18:48:37', '2025-12-16 19:11:26'),
(169, 9, '002121-4', '2025-12-16', '2025-12-16 16:11:45', '2025-12-16 16:23:56', 12, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_169.json', '2025-12-16 19:11:45', '2025-12-16 19:23:56'),
(170, 10, '001421-5', '2025-12-16', '2025-12-16 16:24:15', '2025-12-16 17:00:26', 36, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_170.json', '2025-12-16 19:24:15', '2025-12-16 20:00:26'),
(171, 8, '000421-9', '2025-12-16', '2025-12-16 17:00:37', '2025-12-16 19:20:53', 140, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_171.json', '2025-12-16 20:00:37', '2025-12-16 22:20:53'),
(172, 11, '001321-3', '2025-12-16', '2025-12-16 17:00:49', '2025-12-16 19:20:50', 140, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_172.json', '2025-12-16 20:00:49', '2025-12-16 22:20:50'),
(177, 8, '000421-9', '2025-12-17', '2025-12-17 13:05:42', '2025-12-17 13:48:59', 43, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_177.json', '2025-12-17 16:05:42', '2025-12-17 16:48:59'),
(178, 10, '001421-5', '2025-12-17', '2025-12-17 13:05:51', '2025-12-17 13:48:57', 43, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_178.json', '2025-12-17 16:05:51', '2025-12-17 16:48:57'),
(179, 11, '001321-3', '2025-12-17', '2025-12-17 15:56:32', '2025-12-17 18:00:30', 123, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_179.json', '2025-12-17 18:56:32', '2025-12-17 21:00:30'),
(180, 6, '002420-6', '2025-12-17', '2025-12-17 15:57:00', '2025-12-17 18:00:35', 123, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_180.json', '2025-12-17 18:57:00', '2025-12-17 21:00:35'),
(181, 7, '005821-6', '2025-12-17', '2025-12-17 16:58:18', '2025-12-17 18:00:37', 62, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_181.json', '2025-12-17 19:58:18', '2025-12-18 11:42:24'),
(182, 9, '002121-4', '2025-12-17', '2025-12-17 16:58:34', '2025-12-17 18:00:37', 62, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_182.json', '2025-12-17 19:58:34', '2025-12-17 21:00:37'),
(183, 9, '002121-4', '2025-12-18', '2025-12-18 11:41:22', '2025-12-18 12:05:29', 24, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_183.json', '2025-12-18 14:41:22', '2025-12-18 15:05:29'),
(184, 7, '005821-6', '2025-12-18', '2025-12-18 12:05:50', '2025-12-18 12:13:21', 7, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_184.json', '2025-12-18 15:05:50', '2025-12-18 15:13:21'),
(185, 7, '005821-6', '2025-12-18', '2025-12-18 14:17:42', '2025-12-18 15:00:18', 42, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_185.json', '2025-12-18 17:17:42', '2025-12-18 18:00:18'),
(186, 9, '002121-4', '2025-12-18', '2025-12-18 15:02:12', '2025-12-18 15:36:00', 33, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_186.json', '2025-12-18 18:02:12', '2025-12-18 18:36:00'),
(187, 8, '000421-9', '2025-12-18', '2025-12-18 15:39:11', '2025-12-18 16:11:49', 32, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_187.json', '2025-12-18 18:39:11', '2025-12-18 19:11:49'),
(188, 11, '001321-3', '2025-12-18', '2025-12-18 16:12:54', '2025-12-18 16:45:35', 32, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_188.json', '2025-12-18 19:12:54', '2025-12-18 19:45:35'),
(189, 10, '001421-5', '2025-12-18', '2025-12-18 16:46:09', '2025-12-18 18:02:21', 76, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_189.json', '2025-12-18 19:46:09', '2025-12-18 21:02:21'),
(190, 6, '002420-6', '2025-12-18', '2025-12-18 16:46:21', '2025-12-18 18:02:23', 76, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_190.json', '2025-12-18 19:46:21', '2025-12-18 21:02:23'),
(191, 7, '005821-6', '2025-12-19', '2025-12-19 19:55:57', '2025-12-19 21:17:42', 81, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_191.json', '2025-12-19 22:55:57', '2025-12-20 00:17:42'),
(192, 7, '005821-6', '2025-12-20', '2025-12-20 09:17:24', '2025-12-20 10:37:02', 79, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_192.json', '2025-12-20 12:17:24', '2025-12-20 13:37:02'),
(193, 9, '002121-4', '2025-12-20', '2025-12-20 09:17:34', '2025-12-20 10:37:00', 79, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_193.json', '2025-12-20 12:17:34', '2025-12-20 13:37:00'),
(194, 11, '001321-3', '2025-12-20', '2025-12-20 11:48:07', '2025-12-20 13:19:01', 90, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_194.json', '2025-12-20 14:48:07', '2025-12-20 16:19:01'),
(195, 8, '000421-9', '2025-12-20', '2025-12-20 11:48:16', '2025-12-20 13:19:03', 90, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_195.json', '2025-12-20 14:48:16', '2025-12-20 16:19:03'),
(196, 10, '001421-5', '2025-12-20', '2025-12-20 15:34:27', '2025-12-20 17:33:50', 119, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_196.json', '2025-12-20 18:34:27', '2025-12-20 20:33:50'),
(197, 6, '002420-6', '2025-12-20', '2025-12-20 16:14:02', '2025-12-20 17:33:52', 79, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_197.json', '2025-12-20 19:14:02', '2025-12-20 20:33:52'),
(198, 9, '0021214', '2025-12-20', '2025-12-20 19:01:18', '2025-12-20 21:01:18', 120, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_198.json', '2025-12-20 22:01:18', '2025-12-21 11:45:57'),
(199, 9, '0021214', '2025-12-21', '2025-12-21 08:46:14', '2025-12-21 09:59:47', 73, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_199.json', '2025-12-21 11:46:14', '2025-12-21 12:59:47'),
(200, 10, '001421-5', '2025-12-21', '2025-12-21 10:59:53', '2025-12-21 12:52:05', 112, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_200.json', '2025-12-21 13:59:53', '2025-12-21 15:52:05'),
(201, 6, '002420-6', '2025-12-21', '2025-12-21 11:00:02', '2025-12-21 12:52:03', 112, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_201.json', '2025-12-21 14:00:02', '2025-12-21 15:52:03'),
(202, 8, '000421-9', '2025-12-21', '2025-12-21 13:11:55', '2025-12-21 15:48:03', 156, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_202.json', '2025-12-21 16:11:55', '2025-12-21 18:48:03'),
(203, 8, '000421-9', '2025-12-21', '2025-12-21 15:48:17', '2025-12-21 16:57:02', 68, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_203.json', '2025-12-21 18:48:17', '2025-12-21 19:57:02'),
(204, 8, '000421-9', '2025-12-22', '2025-12-22 16:06:19', '2025-12-22 18:36:32', 150, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_204.json', '2025-12-22 19:06:19', '2025-12-22 21:36:32'),
(205, 11, '001321-3', '2025-12-22', '2025-12-22 16:44:42', '2025-12-22 18:36:34', 111, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_205.json', '2025-12-22 19:44:42', '2025-12-22 21:36:34'),
(206, 7, '005821-6', '2025-12-22', '2025-12-22 18:36:43', '2025-12-22 19:21:57', 45, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_206.json', '2025-12-22 21:36:43', '2025-12-22 22:21:57'),
(207, 9, '002121-4', '2025-12-22', '2025-12-22 18:36:49', '2025-12-22 19:21:55', 45, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_207.json', '2025-12-22 21:36:49', '2025-12-22 22:21:55'),
(208, 10, '001421-5', '2025-12-22', '2025-12-22 19:22:06', '2025-12-22 20:16:41', 54, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_208.json', '2025-12-22 22:22:06', '2025-12-22 23:16:41'),
(209, 9, '002121-4', '2025-12-23', '2025-12-23 14:48:51', '2025-12-23 15:24:04', 35, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_209.json', '2025-12-23 17:48:51', '2025-12-23 18:24:04'),
(210, 7, '005821-6', '2025-12-23', '2025-12-23 15:24:23', '2025-12-23 15:57:00', 32, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_210.json', '2025-12-23 18:24:23', '2025-12-23 18:57:00'),
(211, 10, '001421-5', '2025-12-23', '2025-12-23 15:59:04', '2025-12-23 17:34:05', 95, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_211.json', '2025-12-23 18:59:04', '2025-12-23 20:34:05'),
(212, 6, '002420-6', '2025-12-23', '2025-12-23 16:01:38', '2025-12-23 17:34:07', 92, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_212.json', '2025-12-23 19:01:38', '2025-12-23 20:34:07'),
(213, 8, '000421-9', '2025-12-23', '2025-12-23 17:34:25', '2025-12-23 18:47:44', 73, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_213.json', '2025-12-23 20:34:25', '2025-12-23 21:47:44'),
(214, 6, '002420-6', '2025-12-23', '2025-12-23 18:59:33', '2025-12-23 20:24:23', 84, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_214.json', '2025-12-23 21:59:33', '2025-12-23 23:24:23'),
(215, 8, '0004219', '2025-12-23', '2025-12-23 20:35:49', '2025-12-23 22:35:00', 120, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_215.json', '2025-12-23 23:35:49', '2025-12-24 11:33:21'),
(216, 10, '001421-5', '2025-12-24', '2025-12-24 10:37:06', '2025-12-24 11:18:58', 41, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_216.json', '2025-12-24 13:37:06', '2025-12-24 14:18:58'),
(217, 7, '005821-6', '2025-12-24', '2025-12-24 13:39:00', '2025-12-24 15:28:20', 109, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_217.json', '2025-12-24 16:39:00', '2025-12-24 18:28:20'),
(218, 9, '002121-4', '2025-12-24', '2025-12-24 13:39:09', '2025-12-24 15:28:18', 109, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_218.json', '2025-12-24 16:39:09', '2025-12-24 18:28:18'),
(219, 10, '001421-5', '2025-12-25', '2025-12-25 08:43:56', '2025-12-25 09:45:21', 61, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_219.json', '2025-12-25 11:43:56', '2025-12-25 12:45:21'),
(220, 11, '001321-3', '2025-12-25', '2025-12-25 12:59:58', '2025-12-25 16:22:37', 202, 'Práctica en seco, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_220.json', '2025-12-25 15:59:58', '2025-12-25 19:22:37'),
(221, 11, '001321-3', '2025-12-25', '2025-12-25 16:23:29', '2025-12-25 17:17:04', 53, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_221.json', '2025-12-25 19:23:29', '2025-12-25 20:17:04'),
(222, 11, '001321-3', '2025-12-25', '2025-12-25 20:33:30', '2025-12-25 22:02:19', 88, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_222.json', '2025-12-25 23:33:30', '2025-12-26 01:02:19'),
(223, 11, '001321-3', '2025-12-25', '2025-12-25 22:02:28', '2025-12-25 00:02:37', 120, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_223.json', '2025-12-26 01:02:28', '2025-12-26 09:47:37'),
(224, 7, '005821-6', '2025-12-26', '2025-12-26 06:51:22', '2025-12-26 07:53:32', 62, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_224.json', '2025-12-26 09:51:22', '2025-12-26 10:53:32'),
(225, 7, '005821-6', '2025-12-26', '2025-12-26 07:57:06', '2025-12-26 08:51:33', 54, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_225.json', '2025-12-26 10:57:06', '2025-12-26 11:51:33'),
(226, 11, '001321-3', '2025-12-26', '2025-12-26 08:51:59', '2025-12-26 10:21:47', 89, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_226.json', '2025-12-26 11:51:59', '2025-12-26 13:21:47'),
(227, 10, '001421-5', '2025-12-26', '2025-12-26 10:40:14', '2025-12-26 11:33:58', 53, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_227.json', '2025-12-26 13:40:14', '2025-12-26 14:33:58'),
(228, 11, '001321-3', '2025-12-26', '2025-12-26 12:37:09', '2025-12-26 13:40:20', 63, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_228.json', '2025-12-26 15:37:09', '2025-12-26 16:40:20'),
(229, 7, '005821-6', '2025-12-26', '2025-12-26 13:44:23', '2025-12-26 15:06:18', 81, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_229.json', '2025-12-26 16:44:23', '2025-12-26 18:06:18'),
(230, 8, '000421-9', '2025-12-26', '2025-12-26 15:06:27', '2025-12-26 16:35:43', 89, 'Emergencia en vuelo, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_230.json', '2025-12-26 18:06:27', '2025-12-26 19:35:43'),
(231, 6, '002420-6', '2025-12-26', '2025-12-26 17:31:14', '2025-12-26 19:30:00', 118, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_231.json', '2025-12-26 20:31:14', '2025-12-26 22:30:00'),
(232, 10, '001421-5', '2025-12-26', '2025-12-26 17:44:53', '2025-12-26 19:29:55', 105, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_232.json', '2025-12-26 20:44:53', '2025-12-26 22:29:55'),
(233, 6, '002420-6', '2025-12-26', '2025-12-26 19:30:16', '2025-12-26 20:25:33', 55, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_233.json', '2025-12-26 22:30:16', '2025-12-26 23:25:33'),
(234, 7, '005821-6', '2025-12-26', '2025-12-26 20:42:22', '2025-12-26 21:57:08', 74, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_234.json', '2025-12-26 23:42:22', '2025-12-27 00:57:08'),
(235, 7, '005821-6', '2025-12-27', '2025-12-27 07:35:22', '2025-12-27 09:55:11', 139, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_235.json', '2025-12-27 10:35:22', '2025-12-27 12:55:11'),
(236, 6, '002420-6', '2025-12-27', '2025-12-27 07:36:21', '2025-12-27 09:55:08', 138, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_236.json', '2025-12-27 10:36:21', '2025-12-27 12:55:08'),
(237, 7, '005821-6', '2025-12-27', '2025-12-27 09:55:21', '2025-12-27 11:32:40', 97, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_237.json', '2025-12-27 12:55:21', '2025-12-27 14:32:40'),
(238, 6, '002420-6', '2025-12-27', '2025-12-27 09:55:28', '2025-12-27 11:32:38', 97, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_238.json', '2025-12-27 12:55:28', '2025-12-27 14:32:38'),
(239, 9, '002121-4', '2025-12-27', '2025-12-27 11:32:51', '2025-12-27 13:24:26', 111, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_239.json', '2025-12-27 14:32:51', '2025-12-27 16:24:26'),
(240, 9, '002121-4', '2025-12-27', '2025-12-27 15:30:46', '2025-12-27 17:03:11', 92, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_240.json', '2025-12-27 18:30:46', '2025-12-27 20:03:11'),
(241, 6, '002420-6', '2025-12-27', '2025-12-27 16:53:16', '2025-12-27 17:51:12', 57, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_241.json', '2025-12-27 19:53:16', '2025-12-27 20:51:12'),
(242, 9, '002121-4', '2025-12-27', '2025-12-27 18:21:36', '2025-12-27 20:40:54', 139, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_242.json', '2025-12-27 21:21:36', '2025-12-27 23:40:54'),
(243, 9, '002121-4', '2025-12-28', '2025-12-28 08:12:40', '2025-12-28 08:58:04', 45, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_243.json', '2025-12-28 11:12:40', '2025-12-28 11:58:04'),
(244, 6, '002420-6', '2025-12-28', '2025-12-28 11:25:06', '2025-12-28 12:39:44', 74, 'Práctica en seco', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_244.json', '2025-12-28 14:25:06', '2025-12-28 15:39:44'),
(245, 11, '001321-3', '2025-12-28', '2025-12-28 13:21:54', '2025-12-28 13:22:32', 0, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_245.json', '2025-12-28 16:21:54', '2025-12-28 16:22:32'),
(246, 11, '001321-3', '2025-12-28', '2025-12-28 13:22:41', '2025-12-28 15:25:50', 120, 'Práctica en seco', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_246.json', '2025-12-28 16:22:41', '2025-12-29 14:25:50'),
(247, 6, '002420-6', '2025-12-29', '2025-12-29 12:53:01', '2025-12-29 13:40:30', 47, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_247.json', '2025-12-29 15:53:01', '2025-12-29 16:40:30'),
(248, 9, '002121-4', '2025-12-29', '2025-12-29 13:43:12', '2025-12-29 14:48:05', 64, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_248.json', '2025-12-29 16:43:12', '2025-12-29 17:48:05'),
(249, 7, '005821-6', '2025-12-29', '2025-12-29 14:48:12', '2025-12-29 15:49:12', 61, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_249.json', '2025-12-29 17:48:12', '2025-12-29 18:49:12'),
(250, 11, '001321-3', '2025-12-29', '2025-12-29 15:57:13', '2025-12-29 17:03:26', 66, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_250.json', '2025-12-29 18:57:13', '2025-12-29 20:03:26'),
(251, 8, '000421-9', '2025-12-29', '2025-12-29 17:03:32', '2025-12-29 18:32:30', 88, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_251.json', '2025-12-29 20:03:32', '2025-12-29 21:32:30'),
(252, 6, '002420-6', '2025-12-29', '2025-12-29 18:38:58', '2025-12-29 19:53:46', 74, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_252.json', '2025-12-29 21:38:58', '2025-12-29 22:53:46'),
(253, 8, '000421-9', '2025-12-29', '2025-12-29 19:55:57', '2025-12-29 20:56:08', 60, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_253.json', '2025-12-29 22:55:57', '2025-12-29 23:56:08'),
(254, 11, '001321-3', '2025-12-29', '2025-12-29 20:17:41', '2025-12-29 20:56:10', 38, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_254.json', '2025-12-29 23:17:41', '2025-12-29 23:56:10'),
(255, 9, '002121-4', '2025-12-29', '2025-12-29 20:56:19', '2025-12-29 22:02:35', 66, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_255.json', '2025-12-29 23:56:19', '2025-12-30 01:02:35'),
(256, 7, '005821-6', '2025-12-29', '2025-12-29 20:56:48', '2025-12-29 22:02:32', 65, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_256.json', '2025-12-29 23:56:48', '2025-12-30 01:02:32'),
(257, 10, '001421-5', '2025-12-29', '2025-12-29 22:02:52', '2025-12-29 23:14:58', 72, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_257.json', '2025-12-30 01:02:52', '2025-12-30 02:14:58'),
(258, 6, '002420-6', '2025-12-29', '2025-12-29 22:02:59', '2025-12-29 23:14:55', 71, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_258.json', '2025-12-30 01:02:59', '2025-12-30 02:14:55'),
(262, 11, '001321-3', '2025-12-30', '2025-12-30 10:09:19', '2025-12-30 10:22:04', 12, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_262.json', '2025-12-30 13:09:19', '2025-12-30 13:22:04'),
(263, 11, '001321-3', '2025-12-30', '2025-12-30 11:05:37', '2025-12-30 11:43:55', 38, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_263.json', '2025-12-30 14:05:37', '2025-12-30 14:43:55'),
(264, 9, '002121-4', '2025-12-30', '2025-12-30 11:45:05', '2025-12-30 13:07:35', 82, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_264.json', '2025-12-30 14:45:05', '2025-12-30 16:07:35'),
(265, 6, '002420-6', '2025-12-30', '2025-12-30 13:07:42', '2025-12-30 14:54:42', 107, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_265.json', '2025-12-30 16:07:42', '2025-12-30 17:54:42'),
(266, 8, '000421-9', '2025-12-30', '2025-12-30 15:15:34', '2025-12-30 16:12:18', 56, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_266.json', '2025-12-30 18:15:34', '2025-12-30 19:12:18'),
(267, 10, '001421-5', '2025-12-30', '2025-12-30 15:23:09', '2025-12-30 16:13:34', 50, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_267.json', '2025-12-30 18:23:09', '2025-12-30 19:13:34'),
(268, 6, '002420-6', '2025-12-30', '2025-12-30 16:19:26', '2025-12-30 17:32:08', 72, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_268.json', '2025-12-30 19:19:26', '2025-12-30 20:32:08'),
(269, 10, '001421-5', '2025-12-30', '2025-12-30 16:19:33', '2025-12-30 17:32:06', 72, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_269.json', '2025-12-30 19:19:33', '2025-12-30 20:32:06'),
(270, 11, '001321-3', '2025-12-30', '2025-12-30 17:32:03', '2025-12-30 17:52:56', 20, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_270.json', '2025-12-30 20:32:03', '2025-12-30 20:52:56'),
(271, 6, '002420-6', '2025-12-30', '2025-12-30 17:53:02', '2025-12-30 18:45:17', 52, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_271.json', '2025-12-30 20:53:02', '2025-12-30 21:45:17'),
(272, 10, '001421-5', '2025-12-30', '2025-12-30 17:53:18', '2025-12-30 18:45:15', 51, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_272.json', '2025-12-30 20:53:18', '2025-12-30 21:45:15'),
(273, 8, '000421-9', '2025-12-30', '2025-12-30 18:51:41', '2025-12-30 20:52:44', 121, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_273.json', '2025-12-30 21:51:41', '2025-12-30 23:52:44'),
(274, 11, '001321-3', '2025-12-30', '2025-12-30 20:21:47', '2025-12-30 21:10:13', 48, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_274.json', '2025-12-30 23:21:47', '2025-12-31 00:10:13'),
(275, 8, '000421-9', '2025-12-30', '2025-12-30 20:53:56', '2025-12-30 22:24:18', 90, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_275.json', '2025-12-30 23:53:56', '2025-12-31 01:24:18'),
(276, 7, '005821-6', '2025-12-30', '2025-12-30 21:09:49', '2025-12-30 22:37:48', 87, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_276.json', '2025-12-31 00:09:49', '2025-12-31 01:37:48'),
(277, 9, '002121-4', '2025-12-30', '2025-12-30 21:09:58', '2025-12-30 22:37:45', 87, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_277.json', '2025-12-31 00:09:58', '2025-12-31 01:37:45'),
(278, 8, '000421-9', '2025-12-30', '2025-12-30 22:26:06', '2025-12-30 22:53:07', 27, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_278.json', '2025-12-31 01:26:06', '2025-12-31 01:53:07'),
(279, 11, '001321-3', '2025-12-31', '2025-12-31 06:53:39', '2025-12-31 07:42:05', 48, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_279.json', '2025-12-31 09:53:39', '2025-12-31 10:42:05'),
(280, 9, '002121-4', '2025-12-31', '2025-12-31 07:35:10', '2025-12-31 09:01:42', 86, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_280.json', '2025-12-31 10:35:10', '2025-12-31 12:01:42'),
(281, 11, '001321-3', '2025-12-31', '2025-12-31 07:42:15', '2025-12-31 08:00:38', 18, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_281.json', '2025-12-31 10:42:15', '2025-12-31 11:00:38'),
(282, 8, '000421-9', '2025-12-31', '2025-12-31 09:04:37', '2025-12-31 10:10:30', 65, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_282.json', '2025-12-31 12:04:37', '2025-12-31 13:10:30');
INSERT INTO `sesiones` (`id`, `alumno_id`, `npi`, `fecha`, `hora_inicio`, `hora_fin`, `duracion_minutos`, `actividad`, `estado`, `usuario_inicio_id`, `usuario_fin_id`, `detalles`, `observaciones`, `archivo_vuelo`, `created_at`, `updated_at`) VALUES
(283, 7, '005821-6', '2025-12-31', '2025-12-31 10:11:41', '2025-12-31 11:04:38', 52, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_283.json', '2025-12-31 13:11:41', '2025-12-31 14:04:38'),
(284, 8, '000421-9', '2025-12-31', '2025-12-31 11:04:46', '2025-12-31 11:42:15', 37, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_284.json', '2025-12-31 14:04:46', '2025-12-31 14:42:15'),
(285, 11, '001321-3', '2025-12-31', '2025-12-31 11:04:52', '2025-12-31 11:42:17', 37, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_285.json', '2025-12-31 14:04:52', '2025-12-31 14:42:17'),
(286, 7, '005821-6', '2025-12-31', '2025-12-31 12:16:35', '2025-12-31 13:07:06', 50, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_286.json', '2025-12-31 15:16:35', '2025-12-31 16:07:07'),
(287, 11, '001321-3', '2025-12-31', '2025-12-31 12:16:57', '2025-12-31 13:07:06', 50, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_287.json', '2025-12-31 15:16:57', '2025-12-31 16:07:07'),
(288, 10, '001421-5', '2025-12-31', '2025-12-31 13:08:45', '2025-12-31 14:30:27', 81, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_288.json', '2025-12-31 16:08:45', '2025-12-31 17:30:27'),
(289, 6, '002420-6', '2025-12-31', '2025-12-31 13:08:51', '2025-12-31 14:30:25', 81, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_289.json', '2025-12-31 16:08:51', '2025-12-31 17:30:25'),
(290, 7, '005821-6', '2025-12-31', '2025-12-31 14:43:49', '2025-12-31 15:37:21', 53, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_290.json', '2025-12-31 17:43:49', '2025-12-31 18:37:21'),
(291, 8, '000421-9', '2025-12-31', '2025-12-31 16:16:28', '2025-12-31 17:54:48', 98, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_291.json', '2025-12-31 19:16:28', '2025-12-31 20:54:48'),
(292, 9, '002121-4', '2025-12-31', '2025-12-31 16:16:34', '2025-12-31 17:54:33', 97, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_292.json', '2025-12-31 19:16:34', '2025-12-31 20:54:33'),
(293, 8, '000421-9', '2025-12-31', '2025-12-31 18:54:17', '2025-12-31 19:59:46', 65, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_293.json', '2025-12-31 21:54:17', '2025-12-31 22:59:46'),
(294, 9, '002121-4', '2025-12-31', '2025-12-31 18:54:39', '2025-12-31 19:59:48', 65, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_294.json', '2025-12-31 21:54:39', '2025-12-31 22:59:48'),
(295, 9, '002121-4', '2025-12-31', '2025-12-31 21:59:11', '2025-12-31 23:18:06', 78, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_295.json', '2026-01-01 00:59:11', '2026-01-01 02:18:06'),
(296, 8, '000421-9', '2025-12-31', '2025-12-31 22:00:09', '2025-12-31 23:18:03', 77, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_296.json', '2026-01-01 01:00:09', '2026-01-01 02:18:03'),
(297, 8, '000421-9', '2026-01-01', '2026-01-01 08:16:44', '2026-01-01 10:05:56', 109, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_297.json', '2026-01-01 11:16:44', '2026-01-01 13:05:56'),
(298, 9, '002121-4', '2026-01-01', '2026-01-01 08:17:36', '2026-01-01 10:05:58', 108, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_298.json', '2026-01-01 11:17:36', '2026-01-01 13:05:58'),
(299, 8, '000421-9', '2026-01-01', '2026-01-01 11:39:23', '2026-01-01 12:51:58', 72, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_299.json', '2026-01-01 14:39:23', '2026-01-01 15:51:58'),
(300, 8, '000421-9', '2026-01-01', '2026-01-01 14:33:37', '2026-01-01 15:55:36', 81, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_300.json', '2026-01-01 17:33:37', '2026-01-01 18:55:36'),
(301, 8, '000421-9', '2026-01-01', '2026-01-01 16:06:09', '2026-01-01 16:42:57', 36, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_301.json', '2026-01-01 19:06:09', '2026-01-01 19:42:57'),
(302, 6, '002420-6', '2026-01-01', '2026-01-01 16:43:15', '2026-01-01 18:28:07', 104, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_302.json', '2026-01-01 19:43:15', '2026-01-01 21:28:07'),
(303, 8, '000421-9', '2026-01-01', '2026-01-01 20:06:05', '2026-01-01 21:49:24', 103, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_303.json', '2026-01-01 23:06:05', '2026-01-02 00:49:24'),
(304, 11, '001321-3', '2026-01-01', '2026-01-01 20:07:27', '2026-01-01 21:49:26', 101, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_304.json', '2026-01-01 23:07:27', '2026-01-02 00:49:26'),
(305, 11, '001321-3', '2026-01-01', '2026-01-01 22:07:44', '2026-01-01 22:38:09', 30, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_305.json', '2026-01-02 01:07:44', '2026-01-02 01:38:09'),
(306, 6, '002420-6', '2026-01-02', '2026-01-02 07:05:39', '2026-01-02 08:21:05', 75, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_306.json', '2026-01-02 10:05:39', '2026-01-02 11:21:05'),
(307, 11, '001321-3', '2026-01-02', '2026-01-02 07:05:50', '2026-01-02 07:48:16', 42, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_307.json', '2026-01-02 10:05:50', '2026-01-02 10:48:16'),
(308, 10, '001421-5', '2026-01-02', '2026-01-02 07:48:07', '2026-01-02 08:21:03', 32, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_308.json', '2026-01-02 10:48:07', '2026-01-02 11:21:03'),
(309, 11, '001321-3', '2026-01-02', '2026-01-02 08:44:10', '2026-01-02 10:06:24', 82, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_309.json', '2026-01-02 11:44:10', '2026-01-02 13:06:24'),
(310, 10, '001421-5', '2026-01-02', '2026-01-02 10:08:03', '2026-01-02 10:57:06', 49, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_310.json', '2026-01-02 13:08:03', '2026-01-02 13:57:06'),
(311, 6, '002420-6', '2026-01-02', '2026-01-02 10:08:17', '2026-01-02 11:56:03', 107, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_311.json', '2026-01-02 13:08:17', '2026-01-02 14:56:03'),
(312, 8, '000421-9', '2026-01-02', '2026-01-02 12:11:18', '2026-01-02 13:31:39', 80, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_312.json', '2026-01-02 15:11:18', '2026-01-02 16:31:39'),
(313, 6, '002420-6', '2026-01-02', '2026-01-02 12:11:24', '2026-01-02 13:31:37', 80, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_313.json', '2026-01-02 15:11:24', '2026-01-02 16:31:37'),
(314, 7, '005821-6', '2026-01-02', '2026-01-02 13:41:03', '2026-01-02 15:59:38', 138, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_314.json', '2026-01-02 16:41:03', '2026-01-02 18:59:38'),
(315, 9, '002121-4', '2026-01-02', '2026-01-02 13:41:13', '2026-01-02 15:59:36', 138, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_315.json', '2026-01-02 16:41:13', '2026-01-02 18:59:36'),
(316, 11, '001321-3', '2026-01-02', '2026-01-02 16:29:51', '2026-01-02 17:55:49', 85, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_316.json', '2026-01-02 19:29:51', '2026-01-02 20:55:49'),
(317, 10, '001421-5', '2026-01-02', '2026-01-02 16:31:13', '2026-01-02 17:55:49', 84, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_317.json', '2026-01-02 19:31:13', '2026-01-02 20:55:49'),
(318, 10, '001421-5', '2026-01-02', '2026-01-02 19:15:06', '2026-01-02 20:32:57', 77, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_318.json', '2026-01-02 22:15:06', '2026-01-02 23:32:57'),
(319, 11, '001321-3', '2026-01-02', '2026-01-02 19:15:12', '2026-01-02 20:32:59', 77, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_319.json', '2026-01-02 22:15:12', '2026-01-02 23:32:59'),
(320, 10, '001421-5', '2026-01-03', '2026-01-03 08:11:51', '2026-01-03 10:02:06', 110, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_320.json', '2026-01-03 11:11:51', '2026-01-03 13:02:06'),
(321, 11, '001321-3', '2026-01-03', '2026-01-03 08:13:41', '2026-01-03 08:28:07', 14, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_321.json', '2026-01-03 11:13:41', '2026-01-03 11:28:07'),
(322, 11, '001321-3', '2026-01-03', '2026-01-03 08:28:36', '2026-01-03 10:02:03', 93, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_322.json', '2026-01-03 11:28:36', '2026-01-03 13:02:03'),
(323, 8, '000421-9', '2026-01-03', '2026-01-03 10:40:01', '2026-01-03 12:52:41', 132, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_323.json', '2026-01-03 13:40:01', '2026-01-03 15:52:41'),
(324, 6, '002420-6', '2026-01-03', '2026-01-03 10:40:33', '2026-01-03 12:52:39', 132, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_324.json', '2026-01-03 13:40:33', '2026-01-03 15:52:39'),
(325, 7, '005821-6', '2026-01-03', '2026-01-03 19:37:17', '2026-01-03 21:19:36', 102, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_325.json', '2026-01-03 22:37:17', '2026-01-04 00:19:36'),
(326, 9, '002121-4', '2026-01-03', '2026-01-03 19:37:55', '2026-01-03 21:19:33', 101, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_326.json', '2026-01-03 22:37:55', '2026-01-04 00:19:33'),
(327, 7, '005821-6', '2026-01-04', '2026-01-04 09:09:06', '2026-01-04 10:21:57', 72, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_327.json', '2026-01-04 12:09:06', '2026-01-04 13:21:57'),
(328, 9, '002121-4', '2026-01-04', '2026-01-04 09:09:21', '2026-01-04 10:22:00', 72, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_328.json', '2026-01-04 12:09:21', '2026-01-04 13:22:00'),
(329, 6, '002420-6', '2026-01-04', '2026-01-04 09:30:07', '2026-01-04 10:22:02', 51, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_329.json', '2026-01-04 12:30:07', '2026-01-04 13:22:02'),
(330, 7, '005821-6', '2026-01-04', '2026-01-04 10:31:59', '2026-01-04 12:02:08', 90, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_330.json', '2026-01-04 13:31:59', '2026-01-04 15:02:08'),
(331, 9, '002121-4', '2026-01-04', '2026-01-04 10:32:06', '2026-01-04 12:02:38', 90, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_331.json', '2026-01-04 13:32:06', '2026-01-04 15:02:38'),
(332, 10, '001421-5', '2026-01-04', '2026-01-04 12:01:33', '2026-01-04 13:17:56', 76, 'Emergencia en vuelo, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_332.json', '2026-01-04 15:01:33', '2026-01-04 16:17:56'),
(333, 6, '002420-6', '2026-01-04', '2026-01-04 14:20:55', '2026-01-04 15:06:23', 45, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_333.json', '2026-01-04 17:20:55', '2026-01-04 18:06:23'),
(334, 9, '002121-4', '2026-01-04', '2026-01-04 15:01:46', '2026-01-04 16:08:47', 67, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_334.json', '2026-01-04 18:01:46', '2026-01-04 19:08:47'),
(335, 7, '005821-6', '2026-01-04', '2026-01-04 15:59:10', '2026-01-04 17:39:36', 100, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_335.json', '2026-01-04 18:59:10', '2026-01-04 20:39:36'),
(336, 6, '002420-6', '2026-01-04', '2026-01-04 16:08:57', '2026-01-04 17:39:34', 90, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_336.json', '2026-01-04 19:08:57', '2026-01-04 20:39:34'),
(337, 8, '000421-9', '2026-01-04', '2026-01-04 18:09:36', '2026-01-04 19:54:45', 105, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_337.json', '2026-01-04 21:09:36', '2026-01-04 22:54:45'),
(338, 11, '001321-3', '2026-01-04', '2026-01-04 18:09:44', '2026-01-04 19:54:47', 105, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_338.json', '2026-01-04 21:09:44', '2026-01-04 22:54:47'),
(339, 7, '005821-6', '2026-01-04', '2026-01-04 20:52:19', '2026-01-04 22:17:33', 85, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_339.json', '2026-01-04 23:52:19', '2026-01-05 01:17:33'),
(340, 10, '001421-5', '2026-01-05', '2026-01-05 09:30:57', '2026-01-05 11:28:34', 117, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_340.json', '2026-01-05 12:30:57', '2026-01-05 14:28:34'),
(341, 11, '001321-3', '2026-01-05', '2026-01-05 09:53:53', '2026-01-05 11:28:36', 94, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_341.json', '2026-01-05 12:53:53', '2026-01-05 14:28:36'),
(342, 6, '002420-6', '2026-01-05', '2026-01-05 11:29:37', '2026-01-05 11:49:57', 20, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_342.json', '2026-01-05 14:29:37', '2026-01-05 14:49:57'),
(343, 9, '002121-4', '2026-01-05', '2026-01-05 11:30:02', '2026-01-05 13:33:16', 123, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_343.json', '2026-01-05 14:30:02', '2026-01-05 16:33:16'),
(344, 8, '000421-9', '2026-01-05', '2026-01-05 11:38:03', '2026-01-05 13:33:19', 115, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_344.json', '2026-01-05 14:38:03', '2026-01-05 16:33:19'),
(345, 7, '005821-6', '2026-01-05', '2026-01-05 13:45:10', '2026-01-05 15:04:24', 79, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_345.json', '2026-01-05 16:45:10', '2026-01-05 18:04:24'),
(346, 6, '002420-6', '2026-01-05', '2026-01-05 14:41:14', '2026-01-05 15:39:25', 58, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_346.json', '2026-01-05 17:41:14', '2026-01-05 18:39:25'),
(347, 8, '000421-9', '2026-01-05', '2026-01-05 15:04:20', '2026-01-05 16:07:29', 63, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_347.json', '2026-01-05 18:04:20', '2026-01-05 19:07:29'),
(348, 6, '002420-6', '2026-01-05', '2026-01-05 15:51:55', '2026-01-05 16:44:48', 52, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_348.json', '2026-01-05 18:51:55', '2026-01-05 19:44:48'),
(349, 8, '000421-9', '2026-01-05', '2026-01-05 16:41:14', '2026-01-05 18:31:59', 110, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_349.json', '2026-01-05 19:41:14', '2026-01-05 21:31:59'),
(350, 11, '001321-3', '2026-01-05', '2026-01-05 18:32:06', '2026-01-05 18:34:43', 2, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_350.json', '2026-01-05 21:32:06', '2026-01-05 21:34:43'),
(351, 6, '002420-6', '2026-01-05', '2026-01-05 18:32:14', '2026-01-05 18:34:40', 2, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_351.json', '2026-01-05 21:32:14', '2026-01-05 21:34:40'),
(352, 7, '005821-6', '2026-01-05', '2026-01-05 18:35:16', '2026-01-05 19:35:21', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_352.json', '2026-01-05 21:35:16', '2026-01-05 22:35:21'),
(353, 10, '001421-5', '2026-01-05', '2026-01-05 18:35:38', '2026-01-05 19:35:42', 60, 'Navegación, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_353.json', '2026-01-05 21:35:38', '2026-01-05 22:35:42'),
(354, 6, '002420-6', '2026-01-05', '2026-01-05 19:31:14', '2026-01-05 20:54:45', 83, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_354.json', '2026-01-05 22:31:14', '2026-01-05 23:54:45'),
(355, 9, '002121-4', '2026-01-05', '2026-01-05 19:35:15', '2026-01-05 20:54:48', 79, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_355.json', '2026-01-05 22:35:15', '2026-01-05 23:54:48'),
(356, 9, '002121-4', '2026-01-05', '2026-01-05 20:55:04', '2026-01-05 21:45:59', 50, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_356.json', '2026-01-05 23:55:04', '2026-01-06 00:45:59'),
(357, 11, '001321-3', '2026-01-06', '2026-01-06 07:08:09', '2026-01-06 07:54:57', 46, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_357.json', '2026-01-06 10:08:09', '2026-01-06 10:54:57'),
(358, 10, '001421-5', '2026-01-06', '2026-01-06 07:08:18', '2026-01-06 08:51:48', 103, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_358.json', '2026-01-06 10:08:18', '2026-01-06 11:51:48'),
(359, 7, '005821-6', '2026-01-06', '2026-01-06 08:55:46', '2026-01-06 09:49:22', 53, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_359.json', '2026-01-06 11:55:46', '2026-01-06 12:49:22'),
(360, 11, '001321-3', '2026-01-06', '2026-01-06 09:49:49', '2026-01-06 10:47:43', 57, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_360.json', '2026-01-06 12:49:49', '2026-01-06 13:47:43'),
(361, 9, '002121-4', '2026-01-06', '2026-01-06 10:48:30', '2026-01-06 11:54:11', 65, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_361.json', '2026-01-06 13:48:30', '2026-01-06 14:54:12'),
(362, 11, '001321-3', '2026-01-06', '2026-01-06 12:05:47', '2026-01-06 13:44:31', 98, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_362.json', '2026-01-06 15:05:47', '2026-01-06 16:44:31'),
(363, 6, '002420-6', '2026-01-06', '2026-01-06 13:44:49', '2026-01-06 15:17:52', 93, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_363.json', '2026-01-06 16:44:49', '2026-01-06 18:17:52'),
(364, 11, '001321-3', '2026-01-06', '2026-01-06 13:44:53', '2026-01-06 15:10:28', 85, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_364.json', '2026-01-06 16:44:53', '2026-01-06 18:10:28'),
(365, 8, '000421-9', '2026-01-06', '2026-01-06 14:46:00', '2026-01-06 16:02:25', 76, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_365.json', '2026-01-06 17:46:00', '2026-01-06 19:02:25'),
(366, 10, '001421-5', '2026-01-06', '2026-01-06 15:10:35', '2026-01-06 16:18:46', 68, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_366.json', '2026-01-06 18:10:35', '2026-01-06 19:18:46'),
(367, 7, '005821-6', '2026-01-06', '2026-01-06 16:19:33', '2026-01-06 17:10:31', 50, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_367.json', '2026-01-06 19:19:33', '2026-01-06 20:10:31'),
(368, 10, '001421-5', '2026-01-06', '2026-01-06 16:20:07', '2026-01-06 17:10:28', 50, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_368.json', '2026-01-06 19:20:07', '2026-01-06 20:10:28'),
(369, 9, '002121-4', '2026-01-06', '2026-01-06 19:02:39', '2026-01-06 20:11:02', 68, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_369.json', '2026-01-06 22:02:39', '2026-01-06 23:11:02'),
(370, 7, '005821-6', '2026-01-06', '2026-01-06 19:02:49', '2026-01-06 20:11:00', 68, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_370.json', '2026-01-06 22:02:49', '2026-01-06 23:11:00'),
(371, 8, '000421-9', '2026-01-06', '2026-01-06 20:21:23', '2026-01-06 21:33:01', 71, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_371.json', '2026-01-06 23:21:23', '2026-01-07 00:33:01'),
(372, 10, '001421-5', '2026-01-07', '2026-01-07 07:16:47', '2026-01-07 08:08:57', 52, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_372.json', '2026-01-07 10:16:47', '2026-01-07 11:08:57'),
(373, 7, '005821-6', '2026-01-07', '2026-01-07 07:37:33', '2026-01-07 08:08:59', 31, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_373.json', '2026-01-07 10:37:33', '2026-01-07 11:08:59'),
(374, 7, '005821-6', '2026-01-07', '2026-01-07 08:09:13', '2026-01-07 09:12:07', 62, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_374.json', '2026-01-07 11:09:13', '2026-01-07 12:12:07'),
(375, 10, '001421-5', '2026-01-07', '2026-01-07 09:17:00', '2026-01-07 10:09:03', 52, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_375.json', '2026-01-07 12:17:00', '2026-01-07 13:09:03'),
(376, 8, '000421-9', '2026-01-07', '2026-01-07 10:47:23', '2026-01-07 12:04:41', 77, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_376.json', '2026-01-07 13:47:23', '2026-01-07 15:04:41'),
(377, 10, '001421-5', '2026-01-07', '2026-01-07 10:47:44', '2026-01-07 11:02:54', 15, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_377.json', '2026-01-07 13:47:44', '2026-01-07 14:02:54'),
(378, 9, '002121-4', '2026-01-07', '2026-01-07 10:58:28', '2026-01-07 11:00:26', 1, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_378.json', '2026-01-07 13:58:28', '2026-01-07 14:00:26'),
(379, 9, '002121-4', '2026-01-07', '2026-01-07 12:06:08', '2026-01-07 13:14:43', 68, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_379.json', '2026-01-07 15:06:08', '2026-01-07 16:14:43'),
(380, 6, '002420-6', '2026-01-07', '2026-01-07 13:14:51', '2026-01-07 15:08:05', 113, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_380.json', '2026-01-07 16:14:51', '2026-01-07 18:08:05'),
(381, 7, '005821-6', '2026-01-07', '2026-01-07 13:29:56', '2026-01-07 15:08:08', 98, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_381.json', '2026-01-07 16:29:56', '2026-01-07 18:08:08'),
(382, 11, '001321-3', '2026-01-07', '2026-01-07 14:02:41', '2026-01-07 15:08:12', 65, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_382.json', '2026-01-07 17:02:41', '2026-01-07 18:08:12'),
(383, 6, '002420-6', '2026-01-07', '2026-01-07 16:16:14', '2026-01-07 17:06:36', 50, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_383.json', '2026-01-07 19:16:14', '2026-01-07 20:06:36'),
(384, 6, '002420-6', '2026-01-07', '2026-01-07 17:13:47', '2026-01-07 18:27:46', 73, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_384.json', '2026-01-07 20:13:47', '2026-01-07 21:27:46'),
(385, 11, '001321-3', '2026-01-07', '2026-01-07 17:14:08', '2026-01-07 18:27:44', 73, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_385.json', '2026-01-07 20:14:08', '2026-01-07 21:27:44'),
(386, 9, '002121-4', '2026-01-07', '2026-01-07 18:34:16', '2026-01-07 20:30:26', 116, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_386.json', '2026-01-07 21:34:16', '2026-01-07 23:30:26'),
(387, 7, '005821-6', '2026-01-07', '2026-01-07 18:34:26', '2026-01-07 20:30:28', 116, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_387.json', '2026-01-07 21:34:26', '2026-01-07 23:30:28'),
(388, 10, '001421-5', '2026-01-07', '2026-01-07 19:40:41', '2026-01-07 21:20:47', 100, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_388.json', '2026-01-07 22:40:41', '2026-01-08 00:20:47'),
(389, 8, '000421-9', '2026-01-07', '2026-01-07 19:40:49', '2026-01-07 20:30:40', 49, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_389.json', '2026-01-07 22:40:49', '2026-01-07 23:30:40'),
(390, 6, '002420-6', '2026-01-08', '2026-01-08 06:56:07', '2026-01-08 07:56:11', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_390.json', '2026-01-08 09:56:07', '2026-01-08 10:56:11'),
(391, 11, '001321-3', '2026-01-08', '2026-01-08 06:56:36', '2026-01-08 07:56:14', 59, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_391.json', '2026-01-08 09:56:36', '2026-01-08 10:56:14'),
(392, 10, '001421-5', '2026-01-08', '2026-01-08 07:17:04', '2026-01-08 07:55:57', 38, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_392.json', '2026-01-08 10:17:04', '2026-01-08 10:55:57'),
(393, 11, '001321-3', '2026-01-08', '2026-01-08 07:56:19', '2026-01-08 09:03:55', 67, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_393.json', '2026-01-08 10:56:19', '2026-01-08 12:03:55'),
(394, 10, '001421-5', '2026-01-08', '2026-01-08 09:06:49', '2026-01-08 10:09:06', 62, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_394.json', '2026-01-08 12:06:49', '2026-01-08 13:09:06'),
(395, 7, '005821-6', '2026-01-08', '2026-01-08 10:11:21', '2026-01-08 10:58:21', 47, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_395.json', '2026-01-08 13:11:21', '2026-01-08 13:58:21'),
(396, 9, '002121-4', '2026-01-08', '2026-01-08 11:02:17', '2026-01-08 12:44:32', 102, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_396.json', '2026-01-08 14:02:17', '2026-01-08 15:44:32'),
(397, 11, '001321-3', '2026-01-08', '2026-01-08 12:45:55', '2026-01-08 13:27:37', 41, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_397.json', '2026-01-08 15:45:55', '2026-01-08 16:27:37'),
(398, 8, '000421-9', '2026-01-08', '2026-01-08 13:29:01', '2026-01-08 15:03:37', 94, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_398.json', '2026-01-08 16:29:01', '2026-01-08 18:03:37'),
(399, 10, '001421-5', '2026-01-08', '2026-01-08 13:29:16', '2026-01-08 13:46:59', 17, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_399.json', '2026-01-08 16:29:16', '2026-01-08 16:46:59'),
(400, 9, '002121-4', '2026-01-08', '2026-01-08 15:02:14', '2026-01-08 16:18:16', 76, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_400.json', '2026-01-08 18:02:14', '2026-01-08 19:18:16'),
(401, 7, '005821-6', '2026-01-08', '2026-01-08 15:02:34', '2026-01-08 16:37:47', 95, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_401.json', '2026-01-08 18:02:34', '2026-01-08 19:37:47'),
(402, 10, '001421-5', '2026-01-08', '2026-01-08 15:02:49', '2026-01-08 16:37:50', 95, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_402.json', '2026-01-08 18:02:49', '2026-01-08 19:37:50'),
(403, 8, '000421-9', '2026-01-08', '2026-01-08 16:18:05', '2026-01-08 17:33:40', 75, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_403.json', '2026-01-08 19:18:05', '2026-01-08 20:33:40'),
(404, 11, '001321-3', '2026-01-08', '2026-01-08 16:47:40', '2026-01-08 17:33:38', 45, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_404.json', '2026-01-08 19:47:40', '2026-01-08 20:33:38'),
(405, 9, '002121-4', '2026-01-08', '2026-01-08 17:42:01', '2026-01-08 18:16:44', 34, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_405.json', '2026-01-08 20:42:01', '2026-01-08 21:16:44'),
(406, 6, '002420-6', '2026-01-08', '2026-01-08 19:29:16', '2026-01-08 20:45:49', 76, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_406.json', '2026-01-08 22:29:16', '2026-01-08 23:45:49'),
(407, 11, '001321-3', '2026-01-08', '2026-01-08 20:53:59', '2026-01-08 22:00:36', 66, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_407.json', '2026-01-08 23:53:59', '2026-01-09 01:00:36'),
(408, 6, '002420-6', '2026-01-08', '2026-01-08 21:02:42', '2026-01-08 22:15:09', 72, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_408.json', '2026-01-09 00:02:42', '2026-01-09 01:15:09'),
(409, 6, '002420-6', '2026-01-09', '2026-01-09 07:13:51', '2026-01-09 07:54:39', 40, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_409.json', '2026-01-09 10:13:51', '2026-01-09 10:54:39'),
(410, 6, '002420-6', '2026-01-09', '2026-01-09 09:01:01', '2026-01-09 10:31:04', 90, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_410.json', '2026-01-09 12:01:01', '2026-01-09 13:31:04'),
(414, 7, '005821-6', '2026-01-09', '2026-01-09 18:15:34', '2026-01-09 20:07:25', 111, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_414.json', '2026-01-09 21:15:34', '2026-01-09 23:07:25'),
(415, 7, '005821-6', '2026-01-10', '2026-01-10 08:11:06', '2026-01-10 09:57:10', 106, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_415.json', '2026-01-10 11:11:06', '2026-01-10 12:57:10'),
(416, 6, '002420-6', '2026-01-10', '2026-01-10 11:07:46', '2026-01-10 12:20:28', 72, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_416.json', '2026-01-10 14:07:46', '2026-01-10 15:20:28'),
(417, 11, '001321-3', '2026-01-10', '2026-01-10 12:31:25', '2026-01-10 13:35:12', 63, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_417.json', '2026-01-10 15:31:25', '2026-01-10 16:35:12'),
(418, 10, '001421-5', '2026-01-10', '2026-01-10 13:43:36', '2026-01-10 15:14:08', 90, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_418.json', '2026-01-10 16:43:36', '2026-01-10 18:14:08'),
(419, 9, '002121-4', '2026-01-10', '2026-01-10 13:58:19', '2026-01-10 15:14:10', 75, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_419.json', '2026-01-10 16:58:19', '2026-01-10 18:14:10'),
(420, 9, '002121-4', '2026-01-10', '2026-01-10 16:24:24', '2026-01-10 17:40:24', 76, 'Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_420.json', '2026-01-10 19:24:24', '2026-01-10 20:40:24'),
(421, 9, '002121-4', '2026-01-10', '2026-01-10 20:45:50', '2026-01-10 21:53:13', 67, 'Trabajo en pista, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_421.json', '2026-01-10 23:45:50', '2026-01-11 00:53:13'),
(422, 9, '002121-4', '2026-01-11', '2026-01-11 09:01:31', '2026-01-11 10:44:56', 103, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_422.json', '2026-01-11 12:01:31', '2026-01-11 13:44:56'),
(423, 10, '001421-5', '2026-01-11', '2026-01-11 09:01:41', '2026-01-11 10:44:58', 103, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_423.json', '2026-01-11 12:01:41', '2026-01-11 13:44:58'),
(424, 8, '000421-9', '2026-01-11', '2026-01-11 10:57:02', '2026-01-11 11:56:18', 59, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_424.json', '2026-01-11 13:57:02', '2026-01-11 14:56:18'),
(425, 11, '001321-3', '2026-01-11', '2026-01-11 12:21:03', '2026-01-11 13:25:05', 64, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_425.json', '2026-01-11 15:21:03', '2026-01-11 16:25:05'),
(426, 8, '000421-9', '2026-01-11', '2026-01-11 13:49:25', '2026-01-11 14:52:59', 63, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_426.json', '2026-01-11 16:49:25', '2026-01-11 17:52:59'),
(427, 6, '002420-6', '2026-01-11', '2026-01-11 15:52:50', '2026-01-11 17:08:26', 75, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_427.json', '2026-01-11 18:52:50', '2026-01-11 20:08:26'),
(428, 8, '000421-9', '2026-01-11', '2026-01-11 17:34:35', '2026-01-11 18:52:39', 78, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_428.json', '2026-01-11 20:34:35', '2026-01-11 21:52:39'),
(429, 8, '000421-9', '2026-01-11', '2026-01-11 21:05:51', '2026-01-11 21:46:03', 40, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_429.json', '2026-01-12 00:05:51', '2026-01-12 00:46:03'),
(430, 6, '002420-6', '2026-01-12', '2026-01-12 07:05:53', '2026-01-12 08:01:00', 55, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_430.json', '2026-01-12 10:05:53', '2026-01-12 11:01:00'),
(431, 11, '001321-3', '2026-01-12', '2026-01-12 08:59:02', '2026-01-12 10:05:50', 66, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_431.json', '2026-01-12 11:59:02', '2026-01-12 13:05:50'),
(432, 6, '002420-6', '2026-01-12', '2026-01-12 08:59:13', '2026-01-12 10:05:47', 66, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_432.json', '2026-01-12 11:59:13', '2026-01-12 13:05:47'),
(433, 11, '001321-3', '2026-01-12', '2026-01-12 11:17:45', '2026-01-12 11:47:02', 29, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_433.json', '2026-01-12 14:17:45', '2026-01-12 14:47:02'),
(434, 11, '001321-3', '2026-01-12', '2026-01-12 13:38:27', '2026-01-12 14:23:31', 45, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_434.json', '2026-01-12 16:38:27', '2026-01-12 17:23:31'),
(435, 7, '005821-6', '2026-01-12', '2026-01-12 14:35:18', '2026-01-12 16:57:58', 142, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_435.json', '2026-01-12 17:35:18', '2026-01-12 19:57:59'),
(436, 10, '001421-5', '2026-01-12', '2026-01-12 15:15:08', '2026-01-12 16:58:01', 102, 'Trabajo en pista, Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_436.json', '2026-01-12 18:15:08', '2026-01-12 19:58:01'),
(437, 8, '000421-9', '2026-01-12', '2026-01-12 17:00:06', '2026-01-12 18:47:44', 107, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_437.json', '2026-01-12 20:00:06', '2026-01-12 21:47:44'),
(438, 9, '002121-4', '2026-01-12', '2026-01-12 17:01:56', '2026-01-12 18:47:47', 105, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_438.json', '2026-01-12 20:01:56', '2026-01-12 21:47:47'),
(439, 6, '002420-6', '2026-01-12', '2026-01-12 19:06:05', '2026-01-12 19:57:04', 50, 'Acrobacias, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_439.json', '2026-01-12 22:06:05', '2026-01-12 22:57:04'),
(440, 6, '002420-6', '2026-01-12', '2026-01-12 22:36:32', '2026-01-12 23:20:25', 43, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_440.json', '2026-01-13 01:36:32', '2026-01-13 02:20:25'),
(441, 10, '001421-5', '2026-01-13', '2026-01-13 11:04:51', '2026-01-13 12:31:18', 86, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_441.json', '2026-01-13 14:04:51', '2026-01-13 15:31:18'),
(442, 7, '005821-6', '2026-01-13', '2026-01-13 11:57:52', '2026-01-13 12:31:15', 33, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_442.json', '2026-01-13 14:57:52', '2026-01-13 15:31:15'),
(443, 9, '002121-4', '2026-01-13', '2026-01-13 12:31:33', '2026-01-13 12:57:07', 25, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_443.json', '2026-01-13 15:31:33', '2026-01-13 15:57:07'),
(444, 6, '002420-6', '2026-01-13', '2026-01-13 13:07:43', '2026-01-13 14:49:13', 101, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_444.json', '2026-01-13 16:07:43', '2026-01-13 17:49:13'),
(445, 7, '005821-6', '2026-01-13', '2026-01-13 14:49:26', '2026-01-13 15:39:23', 49, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_445.json', '2026-01-13 17:49:26', '2026-01-13 18:39:23'),
(446, 8, '000421-9', '2026-01-13', '2026-01-13 15:39:31', '2026-01-13 17:02:56', 83, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_446.json', '2026-01-13 18:39:31', '2026-01-13 20:02:56'),
(447, 6, '002420-6', '2026-01-13', '2026-01-13 17:09:29', '2026-01-13 17:47:36', 38, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_447.json', '2026-01-13 20:09:29', '2026-01-13 20:47:36'),
(448, 11, '001321-3', '2026-01-13', '2026-01-13 18:03:56', '2026-01-13 18:38:31', 34, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_448.json', '2026-01-13 21:03:56', '2026-01-13 21:38:31'),
(449, 11, '001321-3', '2026-01-13', '2026-01-13 19:22:32', '2026-01-13 19:43:38', 21, 'Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_449.json', '2026-01-13 22:22:32', '2026-01-13 22:43:38'),
(450, 10, '001421-5', '2026-01-13', '2026-01-13 21:57:33', '2026-01-13 23:25:51', 88, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_450.json', '2026-01-14 00:57:33', '2026-01-14 02:25:51'),
(451, 9, '002121-4', '2026-01-13', '2026-01-13 21:57:43', '2026-01-13 23:25:49', 88, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_451.json', '2026-01-14 00:57:43', '2026-01-14 02:25:49'),
(453, 7, '005821-6', '2026-01-14', '2026-01-14 13:11:21', '2026-01-14 13:38:44', 27, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_453.json', '2026-01-14 16:11:21', '2026-01-14 16:38:44'),
(454, 10, '001421-5', '2026-01-14', '2026-01-14 13:12:03', '2026-01-14 13:38:42', 26, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_454.json', '2026-01-14 16:12:03', '2026-01-14 16:38:42'),
(455, 6, '002420-6', '2026-01-14', '2026-01-14 13:12:13', '2026-01-14 13:59:57', 47, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_455.json', '2026-01-14 16:12:13', '2026-01-14 16:59:57'),
(456, 8, '000421-9', '2026-01-14', '2026-01-14 13:57:35', '2026-01-14 14:51:33', 53, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_456.json', '2026-01-14 16:57:35', '2026-01-14 17:51:33'),
(457, 11, '001321-3', '2026-01-14', '2026-01-14 13:59:32', '2026-01-14 14:51:35', 52, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_457.json', '2026-01-14 16:59:32', '2026-01-14 17:51:35'),
(458, 9, '002121-4', '2026-01-14', '2026-01-14 15:00:53', '2026-01-14 16:55:46', 114, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_458.json', '2026-01-14 18:00:53', '2026-01-14 19:55:46'),
(459, 10, '001421-5', '2026-01-14', '2026-01-14 15:01:02', '2026-01-14 16:55:48', 114, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_459.json', '2026-01-14 18:01:02', '2026-01-14 19:55:48'),
(460, 6, '002420-6', '2026-01-14', '2026-01-14 16:57:58', '2026-01-14 18:05:20', 67, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_460.json', '2026-01-14 19:57:58', '2026-01-14 21:05:20'),
(461, 6, '002420-6', '2026-01-15', '2026-01-15 13:15:18', '2026-01-15 13:21:18', 6, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_461.json', '2026-01-15 16:15:18', '2026-01-15 16:21:18'),
(462, 6, '002420-6', '2026-01-15', '2026-01-15 13:22:12', '2026-01-15 14:00:27', 38, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_462.json', '2026-01-15 16:22:12', '2026-01-15 17:00:27'),
(463, 10, '001421-5', '2026-01-15', '2026-01-15 14:14:59', '2026-01-15 15:06:32', 51, 'Emergencia en vuelo, Trabajo en pista, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_463.json', '2026-01-15 17:14:59', '2026-01-15 18:06:32'),
(464, 9, '002121-4', '2026-01-15', '2026-01-15 15:32:19', '2026-01-15 15:50:11', 17, 'Trabajo en pista, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_464.json', '2026-01-15 18:32:19', '2026-01-15 18:50:11'),
(465, 11, '001321-3', '2026-01-15', '2026-01-15 15:51:16', '2026-01-15 16:52:46', 61, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_465.json', '2026-01-15 18:51:16', '2026-01-15 19:52:46'),
(466, 7, '005821-6', '2026-01-15', '2026-01-15 16:53:08', '2026-01-15 17:29:03', 35, 'Emergencia en vuelo, Trabajo en pista, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_466.json', '2026-01-15 19:53:08', '2026-01-15 20:29:03'),
(467, 10, '001421-5', '2026-01-15', '2026-01-15 16:53:27', '2026-01-15 17:29:05', 35, 'Emergencia en vuelo, Trabajo en pista, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_467.json', '2026-01-15 19:53:27', '2026-01-15 20:29:05'),
(468, 6, '002420-6', '2026-01-15', '2026-01-15 17:28:05', '2026-01-15 18:08:49', 40, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_468.json', '2026-01-15 20:28:05', '2026-01-15 21:08:49'),
(469, 9, '002121-4', '2026-01-15', '2026-01-15 18:14:44', '2026-01-15 19:27:53', 73, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_469.json', '2026-01-15 21:14:44', '2026-01-15 22:27:53'),
(470, 6, '002420-6', '2026-01-15', '2026-01-15 20:28:33', '2026-01-15 20:58:38', 30, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_470.json', '2026-01-15 23:28:33', '2026-01-15 23:58:38'),
(471, 9, '002121-4', '2026-01-15', '2026-01-15 23:53:27', '2026-01-16 00:27:39', 34, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_471.json', '2026-01-16 02:53:27', '2026-01-16 03:27:39'),
(472, 6, '002420-6', '2026-01-16', '2026-01-16 09:21:35', '2026-01-16 10:09:00', 47, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_472.json', '2026-01-16 12:21:35', '2026-01-16 13:09:00'),
(473, 9, '002121-4', '2026-01-16', '2026-01-16 12:30:33', '2026-01-16 13:21:17', 50, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_473.json', '2026-01-16 15:30:33', '2026-01-16 16:21:17'),
(474, 10, '001421-5', '2026-01-16', '2026-01-16 13:27:51', '2026-01-16 13:57:03', 29, 'Emergencia en vuelo, Trabajo en pista, Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_474.json', '2026-01-16 16:27:51', '2026-01-16 16:57:03'),
(475, 7, '005821-6', '2026-01-17', '2026-01-17 17:40:52', '2026-01-17 19:21:15', 100, 'Emergencia en vuelo, Trabajo en pista, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_475.json', '2026-01-17 20:40:52', '2026-01-17 22:21:15'),
(476, 11, '001321-3', '2026-01-18', '2026-01-18 18:09:31', '2026-01-18 19:12:47', 63, 'Trabajo en pista', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_476.json', '2026-01-18 21:09:31', '2026-01-18 22:12:47'),
(477, 7, '005821-6', '2026-01-19', '2026-01-19 09:44:18', '2026-01-19 10:29:44', 45, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_477.json', '2026-01-19 12:44:18', '2026-01-19 13:29:44'),
(478, 9, '002121-4', '2026-01-19', '2026-01-19 09:48:23', '2026-01-19 10:29:46', 41, 'Emergencia en vuelo, Trabajo en pista, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_478.json', '2026-01-19 12:48:23', '2026-01-19 13:29:46'),
(479, 10, '001421-5', '2026-01-19', '2026-01-19 11:54:11', '2026-01-19 12:29:17', 35, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_479.json', '2026-01-19 14:54:11', '2026-01-19 15:29:17'),
(480, 7, '005821-6', '2026-01-19', '2026-01-19 15:10:31', '2026-01-19 16:15:17', 64, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_480.json', '2026-01-19 18:10:31', '2026-01-19 19:15:17'),
(481, 9, '002121-4', '2026-01-19', '2026-01-19 16:15:54', '2026-01-19 17:21:41', 65, 'Emergencia en vuelo, Trabajo en pista, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_481.json', '2026-01-19 19:15:54', '2026-01-19 20:21:41'),
(482, 7, '005821-6', '2026-01-19', '2026-01-19 16:16:26', '2026-01-19 17:21:45', 65, 'Emergencia en vuelo, Trabajo en pista, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_482.json', '2026-01-19 19:16:26', '2026-01-19 20:21:45'),
(483, 10, '001421-5', '2026-01-19', '2026-01-19 16:48:00', '2026-01-19 17:22:29', 34, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_483.json', '2026-01-19 19:48:00', '2026-01-19 20:22:29'),
(484, 7, '005821-6', '2026-01-20', '2026-01-20 15:29:33', '2026-01-20 17:09:04', 99, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_484.json', '2026-01-20 18:29:33', '2026-01-20 20:09:04'),
(485, 9, '002121-4', '2026-01-20', '2026-01-20 15:30:51', '2026-01-20 17:09:07', 98, 'Emergencia en vuelo, Trabajo en pista', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_485.json', '2026-01-20 18:30:51', '2026-01-20 20:09:07'),
(486, 11, '001321-3', '2026-01-26', '2026-01-26 15:55:45', '2026-01-26 16:36:46', 41, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_486.json', '2026-01-26 18:55:45', '2026-01-26 19:36:46'),
(487, 8, '000421-9', '2026-01-26', '2026-01-26 15:55:54', '2026-01-26 16:36:49', 40, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_487.json', '2026-01-26 18:55:54', '2026-01-26 19:36:49'),
(488, 9, '002121-4', '2026-01-26', '2026-01-26 17:15:54', '2026-01-26 17:43:22', 27, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_488.json', '2026-01-26 20:15:54', '2026-01-26 20:43:22'),
(489, 7, '005821-6', '2026-01-26', '2026-01-26 17:16:03', '2026-01-26 17:43:20', 27, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_489.json', '2026-01-26 20:16:03', '2026-01-26 20:43:20'),
(490, 9, '002121-4', '2026-01-28', '2026-01-28 15:34:37', '2026-01-28 16:50:41', 76, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_490.json', '2026-01-28 18:34:37', '2026-01-28 19:50:41'),
(491, 7, '005821-6', '2026-01-28', '2026-01-28 15:34:50', '2026-01-28 16:50:44', 75, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_491.json', '2026-01-28 18:34:50', '2026-01-28 19:50:44'),
(492, 10, '001421-5', '2026-01-28', '2026-01-28 15:35:00', '2026-01-28 16:50:47', 75, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_492.json', '2026-01-28 18:35:00', '2026-01-28 19:50:47'),
(493, 6, '002420-6', '2026-01-28', '2026-01-28 15:35:08', '2026-01-28 16:50:51', 75, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_493.json', '2026-01-28 18:35:08', '2026-01-28 19:50:51'),
(494, 10, '001421-5', '2026-01-28', '2026-01-28 16:51:19', '2026-01-28 17:51:20', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_494.json', '2026-01-28 19:51:19', '2026-01-28 20:51:21'),
(495, 6, '002420-6', '2026-01-28', '2026-01-28 16:51:29', '2026-01-28 17:51:31', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_495.json', '2026-01-28 19:51:29', '2026-01-28 20:51:31'),
(496, 10, '001421-5', '2026-01-28', '2026-01-28 18:04:04', '2026-01-28 19:04:48', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_496.json', '2026-01-28 21:04:04', '2026-01-28 22:04:48'),
(497, 6, '002420-6', '2026-01-28', '2026-01-28 18:04:09', '2026-01-28 19:04:46', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_497.json', '2026-01-28 21:04:09', '2026-01-28 22:04:46'),
(498, 10, '001421-5', '2026-01-28', '2026-01-28 20:33:12', '2026-01-28 21:35:02', 61, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_498.json', '2026-01-28 23:33:12', '2026-01-29 00:35:02'),
(499, 11, '001321-3', '2026-01-29', '2026-01-29 07:19:58', '2026-01-29 08:22:20', 62, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_499.json', '2026-01-29 10:19:58', '2026-01-29 11:22:20'),
(500, 6, '002420-6', '2026-01-29', '2026-01-29 07:20:18', '2026-01-29 08:22:22', 62, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_500.json', '2026-01-29 10:20:18', '2026-01-29 11:22:22'),
(501, 10, '001421-5', '2026-01-29', '2026-01-29 07:37:23', '2026-01-29 08:22:25', 45, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_501.json', '2026-01-29 10:37:23', '2026-01-29 11:22:25'),
(502, 8, '000421-9', '2026-01-29', '2026-01-29 15:50:04', '2026-01-29 18:14:22', 144, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_502.json', '2026-01-29 18:50:04', '2026-01-29 21:14:22'),
(503, 10, '001421-5', '2026-01-29', '2026-01-29 15:50:30', '2026-01-29 18:14:24', 143, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_503.json', '2026-01-29 18:50:30', '2026-01-29 21:14:24'),
(504, 7, '005821-6', '2026-01-29', '2026-01-29 17:29:12', '2026-01-29 19:06:35', 97, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_504.json', '2026-01-29 20:29:12', '2026-01-29 22:06:35'),
(505, 11, '001321-3', '2026-01-29', '2026-01-29 17:31:24', '2026-01-29 19:06:37', 95, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_505.json', '2026-01-29 20:31:24', '2026-01-29 22:06:37'),
(506, 6, '002420-6', '2026-01-29', '2026-01-29 19:06:47', '2026-01-29 20:33:57', 87, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_506.json', '2026-01-29 22:06:47', '2026-01-29 23:33:57'),
(507, 9, '002121-4', '2026-01-29', '2026-01-29 19:06:55', '2026-01-29 20:33:58', 87, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_507.json', '2026-01-29 22:06:55', '2026-01-29 23:33:58'),
(508, 6, '002420-6', '2026-01-29', '2026-01-29 21:18:21', '2026-01-29 21:58:19', 39, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_508.json', '2026-01-30 00:18:21', '2026-01-30 00:58:19'),
(509, 10, '001421-5', '2026-01-30', '2026-01-30 15:37:23', '2026-01-30 17:05:46', 88, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_509.json', '2026-01-30 18:37:23', '2026-01-30 20:05:46'),
(510, 9, '002121-4', '2026-01-30', '2026-01-30 15:37:34', '2026-01-30 16:18:25', 40, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_510.json', '2026-01-30 18:37:34', '2026-01-30 19:18:25'),
(511, 6, '002420-6', '2026-01-30', '2026-01-30 15:38:22', '2026-01-30 17:05:50', 87, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_511.json', '2026-01-30 18:38:22', '2026-01-30 20:05:50'),
(512, 7, '005821-6', '2026-01-30', '2026-01-30 15:39:21', '2026-01-30 16:18:28', 39, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_512.json', '2026-01-30 18:39:21', '2026-01-30 19:18:28'),
(513, 8, '000421-9', '2026-01-30', '2026-01-30 15:39:41', '2026-01-30 17:05:40', 85, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_513.json', '2026-01-30 18:39:41', '2026-01-30 20:05:40'),
(514, 9, '002121-4', '2026-01-30', '2026-01-30 17:02:51', '2026-01-30 20:05:45', 182, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_514.json', '2026-01-30 20:02:51', '2026-01-30 23:05:46'),
(515, 7, '005821-6', '2026-01-30', '2026-01-30 17:03:03', '2026-01-30 20:05:47', 182, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_515.json', '2026-01-30 20:03:03', '2026-01-30 23:05:47'),
(516, 11, '001321-3', '2026-01-30', '2026-01-30 17:08:59', '2026-01-30 18:23:09', 74, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_516.json', '2026-01-30 20:08:59', '2026-01-30 21:23:09'),
(517, 11, '001321-3', '2026-01-30', '2026-01-30 20:06:01', '2026-01-30 21:06:46', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_517.json', '2026-01-30 23:06:01', '2026-01-31 00:06:46'),
(518, 11, '001321-3', '2026-01-30', '2026-01-30 21:16:47', '2026-01-30 22:04:29', 47, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_518.json', '2026-01-31 00:16:47', '2026-01-31 01:04:29'),
(519, 11, '001321-3', '2026-01-31', '2026-01-31 09:03:23', '2026-01-31 10:29:50', 86, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_519.json', '2026-01-31 12:03:23', '2026-01-31 13:29:50'),
(520, 8, '000421-9', '2026-01-31', '2026-01-31 09:04:20', '2026-01-31 10:29:52', 85, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_520.json', '2026-01-31 12:04:20', '2026-01-31 13:29:52'),
(521, 9, '002121-4', '2026-01-31', '2026-01-31 10:37:26', '2026-01-31 12:18:11', 100, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_521.json', '2026-01-31 13:37:26', '2026-01-31 15:18:11');
INSERT INTO `sesiones` (`id`, `alumno_id`, `npi`, `fecha`, `hora_inicio`, `hora_fin`, `duracion_minutos`, `actividad`, `estado`, `usuario_inicio_id`, `usuario_fin_id`, `detalles`, `observaciones`, `archivo_vuelo`, `created_at`, `updated_at`) VALUES
(522, 7, '005821-6', '2026-01-31', '2026-01-31 10:37:34', '2026-01-31 12:18:08', 100, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_522.json', '2026-01-31 13:37:34', '2026-01-31 15:18:08'),
(523, 10, '001421-5', '2026-01-31', '2026-01-31 12:17:23', '2026-01-31 13:43:13', 85, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_523.json', '2026-01-31 15:17:23', '2026-01-31 16:43:13'),
(524, 6, '002420-6', '2026-01-31', '2026-01-31 12:56:49', '2026-01-31 13:43:10', 46, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_524.json', '2026-01-31 15:56:49', '2026-01-31 16:43:10'),
(525, 8, '000421-9', '2026-01-31', '2026-01-31 14:03:26', '2026-01-31 15:03:37', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_525.json', '2026-01-31 17:03:26', '2026-01-31 18:03:37'),
(526, 8, '000421-9', '2026-01-31', '2026-01-31 15:53:55', '2026-01-31 16:35:01', 41, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_526.json', '2026-01-31 18:53:55', '2026-01-31 19:35:01'),
(527, 8, '000421-9', '2026-01-31', '2026-01-31 17:55:45', '2026-01-31 19:04:23', 68, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_527.json', '2026-01-31 20:55:45', '2026-01-31 22:04:23'),
(528, 8, '000421-9', '2026-01-31', '2026-01-31 21:40:31', '2026-01-31 22:37:14', 56, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_528.json', '2026-02-01 00:40:31', '2026-02-01 01:37:14'),
(529, 8, '000421-9', '2026-02-01', '2026-02-01 09:01:20', '2026-02-01 10:00:33', 59, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_529.json', '2026-02-01 12:01:20', '2026-02-01 13:00:33'),
(530, 9, '002121-4', '2026-02-01', '2026-02-01 10:59:52', '2026-02-01 12:10:17', 70, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_530.json', '2026-02-01 13:59:52', '2026-02-01 15:10:17'),
(531, 10, '001421-5', '2026-02-01', '2026-02-01 12:06:32', '2026-02-01 13:14:08', 67, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_531.json', '2026-02-01 15:06:32', '2026-02-01 16:14:08'),
(532, 9, '002121-4', '2026-02-01', '2026-02-01 16:45:07', '2026-02-01 17:41:34', 56, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_532.json', '2026-02-01 19:45:07', '2026-02-01 20:41:34'),
(533, 6, '002420-6', '2026-02-01', '2026-02-01 18:02:53', '2026-02-01 19:31:51', 88, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_533.json', '2026-02-01 21:02:53', '2026-02-01 22:31:51'),
(534, 11, '001321-3', '2026-02-01', '2026-02-01 18:32:28', '2026-02-01 19:46:49', 74, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_534.json', '2026-02-01 21:32:28', '2026-02-01 22:46:49'),
(535, 9, '002121-4', '2026-02-01', '2026-02-01 20:43:28', '2026-02-01 21:42:46', 59, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_535.json', '2026-02-01 23:43:28', '2026-02-02 00:42:46'),
(536, 7, '005821-6', '2026-02-02', '2026-02-02 07:14:20', '2026-02-02 08:14:27', 60, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_536.json', '2026-02-02 10:14:20', '2026-02-02 11:14:27'),
(537, 9, '002121-4', '2026-02-02', '2026-02-02 15:47:01', '2026-02-02 17:11:31', 84, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_537.json', '2026-02-02 18:47:01', '2026-02-02 20:11:31'),
(538, 7, '005821-6', '2026-02-02', '2026-02-02 15:47:08', '2026-02-02 17:11:33', 84, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_538.json', '2026-02-02 18:47:08', '2026-02-02 20:11:33'),
(539, 11, '001321-3', '2026-02-02', '2026-02-02 17:11:40', '2026-02-02 18:29:33', 77, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_539.json', '2026-02-02 20:11:40', '2026-02-02 21:29:33'),
(540, 6, '002420-6', '2026-02-02', '2026-02-02 17:11:52', '2026-02-02 18:29:31', 77, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_540.json', '2026-02-02 20:11:52', '2026-02-02 21:29:31'),
(541, 10, '001421-5', '2026-02-02', '2026-02-02 18:36:18', '2026-02-02 20:07:48', 91, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_541.json', '2026-02-02 21:36:18', '2026-02-02 23:07:48'),
(542, 8, '000421-9', '2026-02-02', '2026-02-02 18:36:27', '2026-02-02 20:07:50', 91, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_542.json', '2026-02-02 21:36:27', '2026-02-02 23:07:50'),
(543, 8, '000421-9', '2026-02-02', '2026-02-02 20:38:24', '2026-02-02 22:13:55', 95, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_543.json', '2026-02-02 23:38:24', '2026-02-03 01:13:55'),
(544, 6, '002420-6', '2026-02-02', '2026-02-02 21:06:31', '2026-02-02 22:13:58', 67, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_544.json', '2026-02-03 00:06:31', '2026-02-03 01:13:58'),
(545, 11, '001321-3', '2026-02-03', '2026-02-03 07:18:59', '2026-02-03 08:25:12', 66, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_545.json', '2026-02-03 10:18:59', '2026-02-03 11:25:12'),
(546, 6, '002420-6', '2026-02-03', '2026-02-03 07:19:07', '2026-02-03 08:25:10', 66, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_546.json', '2026-02-03 10:19:07', '2026-02-03 11:25:10'),
(547, 7, '005821-6', '2026-02-03', '2026-02-03 16:04:14', '2026-02-03 17:36:35', 92, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_547.json', '2026-02-03 19:04:14', '2026-02-03 20:36:35'),
(548, 9, '002121-4', '2026-02-03', '2026-02-03 16:04:22', '2026-02-03 17:36:37', 92, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_548.json', '2026-02-03 19:04:22', '2026-02-03 20:36:37'),
(549, 10, '001421-5', '2026-02-03', '2026-02-03 17:36:53', '2026-02-03 19:06:29', 89, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_549.json', '2026-02-03 20:36:53', '2026-02-03 22:06:30'),
(550, 8, '000421-9', '2026-02-03', '2026-02-03 17:37:01', '2026-02-03 19:06:38', 89, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_550.json', '2026-02-03 20:37:01', '2026-02-03 22:06:38'),
(551, 11, '001321-3', '2026-02-03', '2026-02-03 19:14:06', '2026-02-03 20:43:34', 89, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_551.json', '2026-02-03 22:14:06', '2026-02-03 23:43:34'),
(552, 6, '002420-6', '2026-02-03', '2026-02-03 19:14:33', '2026-02-03 20:43:36', 89, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_552.json', '2026-02-03 22:14:33', '2026-02-03 23:43:36'),
(553, 10, '001421-5', '2026-02-04', '2026-02-04 15:42:49', '2026-02-04 17:06:55', 84, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_553.json', '2026-02-04 18:42:49', '2026-02-04 20:06:55'),
(554, 8, '000421-9', '2026-02-04', '2026-02-04 15:42:57', '2026-02-04 17:06:53', 83, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_554.json', '2026-02-04 18:42:57', '2026-02-04 20:06:53'),
(555, 11, '001321-3', '2026-02-04', '2026-02-04 17:07:04', '2026-02-04 18:39:26', 92, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_555.json', '2026-02-04 20:07:04', '2026-02-04 21:39:26'),
(556, 6, '002420-6', '2026-02-04', '2026-02-04 17:07:13', '2026-02-04 18:39:28', 92, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_556.json', '2026-02-04 20:07:13', '2026-02-04 21:39:28'),
(557, 9, '002121-4', '2026-02-04', '2026-02-04 18:35:41', '2026-02-04 20:13:50', 98, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_557.json', '2026-02-04 21:35:41', '2026-02-04 23:13:50'),
(558, 7, '005821-6', '2026-02-04', '2026-02-04 18:36:15', '2026-02-04 20:13:48', 97, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_558.json', '2026-02-04 21:36:15', '2026-02-04 23:13:48'),
(559, 6, '002420-6', '2026-02-05', '2026-02-05 15:18:09', '2026-02-05 17:07:41', 109, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_559.json', '2026-02-05 18:18:09', '2026-02-05 20:07:41'),
(560, 11, '001321-3', '2026-02-05', '2026-02-05 15:18:17', '2026-02-05 17:07:43', 109, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_560.json', '2026-02-05 18:18:17', '2026-02-05 20:07:43'),
(561, 9, '002121-4', '2026-02-05', '2026-02-05 17:22:10', '2026-02-05 17:43:08', 20, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_561.json', '2026-02-05 20:22:10', '2026-02-05 20:43:08'),
(562, 7, '005821-6', '2026-02-05', '2026-02-05 17:22:25', '2026-02-05 17:43:09', 20, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_562.json', '2026-02-05 20:22:25', '2026-02-05 20:43:09'),
(563, 9, '002121-4', '2026-02-06', '2026-02-06 11:03:23', '2026-02-06 15:40:39', 277, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_563.json', '2026-02-06 14:03:23', '2026-02-06 18:40:39'),
(564, 7, '005821-6', '2026-02-06', '2026-02-06 11:03:31', '2026-02-06 15:40:41', 277, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_564.json', '2026-02-06 14:03:31', '2026-02-06 18:40:41'),
(565, 9, '002121-4', '2026-02-06', '2026-02-06 15:40:51', '2026-02-06 17:09:41', 88, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_565.json', '2026-02-06 18:40:51', '2026-02-06 20:09:41'),
(566, 7, '005821-6', '2026-02-06', '2026-02-06 15:41:08', '2026-02-06 17:09:43', 88, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_566.json', '2026-02-06 18:41:08', '2026-02-06 20:09:43'),
(567, 6, '002420-6', '2026-02-07', '2026-02-07 09:03:26', '2026-02-07 11:01:05', 117, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_567.json', '2026-02-07 12:03:26', '2026-02-07 14:01:05'),
(568, 11, '001321-3', '2026-02-07', '2026-02-07 09:03:37', '2026-02-07 11:01:07', 117, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_568.json', '2026-02-07 12:03:37', '2026-02-07 14:01:07'),
(569, 10, '001421-5', '2026-02-07', '2026-02-07 11:16:56', '2026-02-07 13:08:34', 111, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_569.json', '2026-02-07 14:16:56', '2026-02-07 16:08:34'),
(570, 8, '000421-9', '2026-02-07', '2026-02-07 11:17:02', '2026-02-07 13:08:31', 111, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_570.json', '2026-02-07 14:17:02', '2026-02-07 16:08:31'),
(571, 9, '002121-4', '2026-02-07', '2026-02-07 18:24:38', '2026-02-07 20:08:44', 104, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_571.json', '2026-02-07 21:24:38', '2026-02-07 23:08:44'),
(572, 7, '005821-6', '2026-02-07', '2026-02-07 18:24:48', '2026-02-07 20:08:51', 104, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_572.json', '2026-02-07 21:24:48', '2026-02-07 23:08:51'),
(573, 9, '002121-4', '2026-02-08', '2026-02-08 09:19:57', '2026-02-08 11:10:40', 110, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_573.json', '2026-02-08 12:19:57', '2026-02-08 14:10:40'),
(574, 7, '005821-6', '2026-02-08', '2026-02-08 09:20:09', '2026-02-08 11:10:42', 110, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_574.json', '2026-02-08 12:20:09', '2026-02-08 14:10:42'),
(575, 10, '001421-5', '2026-02-08', '2026-02-08 11:14:58', '2026-02-08 12:53:40', 98, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_575.json', '2026-02-08 14:14:58', '2026-02-08 15:53:40'),
(576, 8, '000421-9', '2026-02-08', '2026-02-08 11:15:05', '2026-02-08 12:53:38', 98, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_576.json', '2026-02-08 14:15:05', '2026-02-08 15:53:38'),
(577, 6, '002420-6', '2026-02-08', '2026-02-08 17:32:03', '2026-02-08 20:25:53', 173, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_577.json', '2026-02-08 20:32:03', '2026-02-08 23:25:53'),
(578, 11, '001321-3', '2026-02-08', '2026-02-08 18:20:34', '2026-02-08 20:25:55', 125, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_578.json', '2026-02-08 21:20:34', '2026-02-08 23:25:55'),
(579, 6, '002420-6', '2026-02-09', '2026-02-09 08:41:22', '2026-02-09 09:55:18', 73, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_579.json', '2026-02-09 11:41:22', '2026-02-09 12:55:18'),
(580, 10, '001421-5', '2026-02-09', '2026-02-09 10:08:22', '2026-02-09 11:11:24', 63, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_580.json', '2026-02-09 13:08:22', '2026-02-09 14:11:24'),
(581, 11, '001321-3', '2026-02-09', '2026-02-09 11:19:22', '2026-02-09 12:23:20', 63, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_581.json', '2026-02-09 14:19:22', '2026-02-09 15:23:20'),
(582, 8, '000421-9', '2026-02-09', '2026-02-09 11:59:43', '2026-02-09 12:23:23', 23, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_582.json', '2026-02-09 14:59:43', '2026-02-09 15:23:23'),
(583, 8, '000421-9', '2026-02-09', '2026-02-09 13:52:23', '2026-02-09 15:28:11', 95, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_583.json', '2026-02-09 16:52:23', '2026-02-09 18:28:11'),
(584, 7, '005821-6', '2026-02-09', '2026-02-09 15:28:24', '2026-02-09 17:03:28', 95, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_584.json', '2026-02-09 18:28:24', '2026-02-09 20:03:28'),
(585, 9, '002121-4', '2026-02-09', '2026-02-09 15:28:31', '2026-02-09 17:03:33', 95, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_585.json', '2026-02-09 18:28:31', '2026-02-09 20:03:33'),
(586, 10, '001421-5', '2026-02-09', '2026-02-09 17:03:58', '2026-02-09 18:37:57', 93, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_586.json', '2026-02-09 20:03:58', '2026-02-09 21:37:57'),
(587, 8, '000421-9', '2026-02-09', '2026-02-09 17:04:04', '2026-02-09 18:37:59', 93, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_587.json', '2026-02-09 20:04:04', '2026-02-09 21:37:59'),
(588, 6, '002420-6', '2026-02-09', '2026-02-09 18:41:21', '2026-02-09 20:28:56', 107, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_588.json', '2026-02-09 21:41:21', '2026-02-09 23:28:56'),
(589, 11, '001321-3', '2026-02-09', '2026-02-09 18:41:27', '2026-02-09 20:28:54', 107, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_589.json', '2026-02-09 21:41:27', '2026-02-09 23:28:54'),
(590, 11, '001321-3', '2026-02-09', '2026-02-09 20:49:26', '2026-02-09 21:29:59', 40, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_590.json', '2026-02-09 23:49:26', '2026-02-10 00:29:59'),
(591, 11, '001321-3', '2026-02-09', '2026-02-09 21:30:14', '2026-02-09 22:16:42', 46, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_591.json', '2026-02-10 00:30:14', '2026-02-10 01:16:42'),
(592, 7, '005821-6', '2026-02-10', '2026-02-10 07:14:12', '2026-02-10 08:42:41', 88, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_592.json', '2026-02-10 10:14:12', '2026-02-10 11:42:41'),
(593, 9, '002121-4', '2026-02-10', '2026-02-10 07:14:27', '2026-02-10 10:04:09', 169, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_593.json', '2026-02-10 10:14:27', '2026-02-10 13:04:09'),
(594, 11, '001321-3', '2026-02-10', '2026-02-10 10:23:34', '2026-02-10 11:55:48', 92, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_594.json', '2026-02-10 13:23:34', '2026-02-10 14:55:48'),
(595, 6, '002420-6', '2026-02-10', '2026-02-10 10:23:42', '2026-02-10 11:55:50', 92, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_595.json', '2026-02-10 13:23:42', '2026-02-10 14:55:50'),
(596, 7, '005821-6', '2026-02-10', '2026-02-10 11:56:27', '2026-02-10 12:27:04', 30, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_596.json', '2026-02-10 14:56:27', '2026-02-10 15:27:04'),
(597, 7, '005821-6', '2026-02-10', '2026-02-10 13:39:17', '2026-02-10 15:52:26', 133, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_597.json', '2026-02-10 16:39:17', '2026-02-10 18:52:26'),
(598, 10, '001421-5', '2026-02-10', '2026-02-10 15:52:35', '2026-02-10 17:38:51', 106, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_598.json', '2026-02-10 18:52:35', '2026-02-10 20:38:51'),
(599, 8, '000421-9', '2026-02-10', '2026-02-10 15:52:54', '2026-02-10 17:38:54', 106, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_599.json', '2026-02-10 18:52:54', '2026-02-10 20:38:54'),
(600, 11, '001321-3', '2026-02-10', '2026-02-10 17:39:04', '2026-02-10 19:09:45', 90, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_600.json', '2026-02-10 20:39:04', '2026-02-10 22:09:45'),
(601, 6, '002420-6', '2026-02-10', '2026-02-10 17:39:18', '2026-02-10 19:09:48', 90, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_601.json', '2026-02-10 20:39:18', '2026-02-10 22:09:48'),
(602, 9, '002121-4', '2026-02-10', '2026-02-10 19:16:34', '2026-02-10 21:46:58', 150, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_602.json', '2026-02-10 22:16:34', '2026-02-11 00:46:58'),
(603, 7, '005821-6', '2026-02-10', '2026-02-10 19:16:41', '2026-02-10 21:47:00', 150, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_603.json', '2026-02-10 22:16:41', '2026-02-11 00:47:00'),
(604, 8, '000421-9', '2026-02-10', '2026-02-10 19:36:51', '2026-02-10 21:47:03', 130, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_604.json', '2026-02-10 22:36:51', '2026-02-11 00:47:03'),
(605, 11, '001321-3', '2026-02-11', '2026-02-11 07:10:33', '2026-02-11 08:14:52', 64, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_605.json', '2026-02-11 10:10:33', '2026-02-11 11:14:52'),
(606, 6, '002420-6', '2026-02-11', '2026-02-11 07:10:43', '2026-02-11 08:14:55', 64, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_606.json', '2026-02-11 10:10:43', '2026-02-11 11:14:55'),
(607, 11, '001321-3', '2026-02-11', '2026-02-11 08:42:20', '2026-02-11 10:02:23', 80, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_607.json', '2026-02-11 11:42:20', '2026-02-11 13:02:23'),
(608, 8, '000421-9', '2026-02-11', '2026-02-11 10:20:06', '2026-02-11 12:08:29', 108, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_608.json', '2026-02-11 13:20:06', '2026-02-11 15:08:29'),
(609, 10, '001421-5', '2026-02-11', '2026-02-11 10:20:28', '2026-02-11 12:08:31', 108, 'Navegación, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_609.json', '2026-02-11 13:20:28', '2026-02-11 15:08:31'),
(610, 6, '002420-6', '2026-02-11', '2026-02-11 11:29:27', '2026-02-11 11:42:45', 13, 'Navegación, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_610.json', '2026-02-11 14:29:27', '2026-02-11 14:42:45'),
(611, 11, '001321-3', '2026-02-11', '2026-02-11 12:08:41', '2026-02-11 12:41:00', 32, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_611.json', '2026-02-11 15:08:41', '2026-02-11 15:41:00'),
(612, 6, '002420-6', '2026-02-11', '2026-02-11 12:08:48', '2026-02-11 12:41:02', 32, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_612.json', '2026-02-11 15:08:48', '2026-02-11 15:41:02'),
(613, 9, '002121-4', '2026-02-11', '2026-02-11 13:49:55', '2026-02-11 15:43:56', 114, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_613.json', '2026-02-11 16:49:55', '2026-02-11 18:43:56'),
(614, 7, '005821-6', '2026-02-11', '2026-02-11 13:50:07', '2026-02-11 15:43:58', 113, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_614.json', '2026-02-11 16:50:07', '2026-02-11 18:43:58'),
(615, 11, '001321-3', '2026-02-11', '2026-02-11 15:49:17', '2026-02-11 17:22:24', 93, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_615.json', '2026-02-11 18:49:17', '2026-02-11 20:22:24'),
(616, 6, '002420-6', '2026-02-11', '2026-02-11 15:49:49', '2026-02-11 17:22:26', 92, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_616.json', '2026-02-11 18:49:49', '2026-02-11 20:22:26'),
(617, 10, '001421-5', '2026-02-11', '2026-02-11 17:22:34', '2026-02-11 18:56:06', 93, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_617.json', '2026-02-11 20:22:34', '2026-02-11 21:56:06'),
(618, 8, '000421-9', '2026-02-11', '2026-02-11 17:22:42', '2026-02-11 18:56:15', 93, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_618.json', '2026-02-11 20:22:42', '2026-02-11 21:56:15'),
(619, 9, '002121-4', '2026-02-11', '2026-02-11 19:03:50', '2026-02-11 21:09:30', 125, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_619.json', '2026-02-11 22:03:50', '2026-02-12 00:09:30'),
(620, 7, '005821-6', '2026-02-11', '2026-02-11 19:04:04', '2026-02-11 21:09:33', 125, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_620.json', '2026-02-11 22:04:04', '2026-02-12 00:09:33'),
(621, 9, '002121-4', '2026-02-11', '2026-02-11 21:09:45', '2026-02-11 21:55:43', 45, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_621.json', '2026-02-12 00:09:45', '2026-02-12 00:55:43'),
(622, 11, '001321-3', '2026-02-12', '2026-02-12 07:10:17', '2026-02-12 07:58:41', 48, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_622.json', '2026-02-12 10:10:17', '2026-02-12 10:58:41'),
(623, 6, '002420-6', '2026-02-12', '2026-02-12 07:10:30', '2026-02-12 07:58:43', 48, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_623.json', '2026-02-12 10:10:30', '2026-02-12 10:58:43'),
(624, 6, '002420-6', '2026-02-12', '2026-02-12 08:42:47', '2026-02-12 10:16:52', 94, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_624.json', '2026-02-12 11:42:47', '2026-02-12 13:16:53'),
(625, 10, '001421-5', '2026-02-12', '2026-02-12 10:19:25', '2026-02-12 12:08:45', 109, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_625.json', '2026-02-12 13:19:25', '2026-02-12 15:08:45'),
(626, 8, '000421-9', '2026-02-12', '2026-02-12 10:19:33', '2026-02-12 12:08:48', 109, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_626.json', '2026-02-12 13:19:33', '2026-02-12 15:08:48'),
(627, 9, '002121-4', '2026-02-12', '2026-02-12 10:20:40', '2026-02-12 11:47:58', 87, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_627.json', '2026-02-12 13:20:40', '2026-02-12 14:47:58'),
(628, 7, '005821-6', '2026-02-12', '2026-02-12 12:10:59', '2026-02-12 13:07:01', 56, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_628.json', '2026-02-12 15:10:59', '2026-02-12 16:07:01'),
(629, 11, '001321-3', '2026-02-12', '2026-02-12 12:11:06', '2026-02-12 13:07:03', 55, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_629.json', '2026-02-12 15:11:06', '2026-02-12 16:07:03'),
(630, 10, '001421-5', '2026-02-12', '2026-02-12 13:51:43', '2026-02-12 15:28:27', 96, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_630.json', '2026-02-12 16:51:43', '2026-02-12 18:28:27'),
(631, 9, '002121-4', '2026-02-12', '2026-02-12 15:28:34', '2026-02-12 17:04:15', 95, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_631.json', '2026-02-12 18:28:34', '2026-02-12 20:04:15'),
(632, 7, '005821-6', '2026-02-12', '2026-02-12 15:28:46', '2026-02-12 17:04:18', 95, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_632.json', '2026-02-12 18:28:46', '2026-02-12 20:04:18'),
(633, 10, '001421-5', '2026-02-12', '2026-02-12 17:04:32', '2026-02-12 18:34:08', 89, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_633.json', '2026-02-12 20:04:32', '2026-02-12 21:34:08'),
(634, 8, '000421-9', '2026-02-12', '2026-02-12 17:04:49', '2026-02-12 18:34:09', 89, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_634.json', '2026-02-12 20:04:49', '2026-02-12 21:34:09'),
(635, 11, '001321-3', '2026-02-12', '2026-02-12 18:35:42', '2026-02-12 19:51:44', 76, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_635.json', '2026-02-12 21:35:42', '2026-02-12 22:51:44'),
(636, 6, '002420-6', '2026-02-12', '2026-02-12 18:35:47', '2026-02-12 19:51:46', 75, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_636.json', '2026-02-12 21:35:47', '2026-02-12 22:51:46'),
(637, 8, '000421-9', '2026-02-13', '2026-02-13 07:18:18', '2026-02-13 08:09:37', 51, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_637.json', '2026-02-13 10:18:18', '2026-02-13 11:09:37'),
(638, 9, '002121-4', '2026-02-13', '2026-02-13 08:50:01', '2026-02-13 10:30:46', 100, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_638.json', '2026-02-13 11:50:01', '2026-02-13 13:30:46'),
(639, 10, '001421-5', '2026-02-13', '2026-02-13 08:50:35', '2026-02-13 10:30:49', 100, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_639.json', '2026-02-13 11:50:35', '2026-02-13 13:30:49'),
(640, 8, '000421-9', '2026-02-13', '2026-02-13 10:31:58', '2026-02-13 12:09:42', 97, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_640.json', '2026-02-13 13:31:58', '2026-02-13 15:09:42'),
(641, 7, '005821-6', '2026-02-13', '2026-02-13 10:32:52', '2026-02-13 12:09:45', 96, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_641.json', '2026-02-13 13:32:52', '2026-02-13 15:09:45'),
(642, 11, '001321-3', '2026-02-13', '2026-02-13 12:16:04', '2026-02-14 08:45:18', 1229, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_642.json', '2026-02-13 15:16:04', '2026-02-14 11:45:18'),
(643, 6, '002420-6', '2026-02-13', '2026-02-13 12:16:10', '2026-02-14 08:45:16', 1229, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_643.json', '2026-02-13 15:16:10', '2026-02-14 11:45:16'),
(644, 10, '001421-5', '2026-02-14', '2026-02-14 08:45:25', '2026-02-14 10:47:23', 121, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_644.json', '2026-02-14 11:45:25', '2026-02-14 13:47:23'),
(645, 11, '001321-3', '2026-02-14', '2026-02-14 09:26:21', '2026-02-14 10:47:28', 81, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_645.json', '2026-02-14 12:26:21', '2026-02-14 13:47:28'),
(646, 6, '002420-6', '2026-02-14', '2026-02-14 10:48:14', '2026-02-14 12:51:12', 122, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_646.json', '2026-02-14 13:48:14', '2026-02-14 15:51:13'),
(647, 8, '000421-9', '2026-02-14', '2026-02-14 11:06:01', '2026-02-14 12:51:17', 105, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_647.json', '2026-02-14 14:06:01', '2026-02-14 15:51:17'),
(648, 7, '005821-6', '2026-02-14', '2026-02-14 17:41:35', '2026-02-14 19:21:10', 99, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_648.json', '2026-02-14 20:41:35', '2026-02-14 22:21:10'),
(649, 9, '002121-4', '2026-02-14', '2026-02-14 17:41:45', '2026-02-14 19:21:09', 99, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_649.json', '2026-02-14 20:41:45', '2026-02-14 22:21:09'),
(650, 6, '002420-6', '2026-02-14', '2026-02-14 20:53:18', '2026-02-14 21:48:43', 55, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_650.json', '2026-02-14 23:53:18', '2026-02-15 00:48:43'),
(651, 6, '002420-6', '2026-02-15', '2026-02-15 08:59:19', '2026-02-15 10:21:05', 81, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_651.json', '2026-02-15 11:59:19', '2026-02-15 13:21:05'),
(652, 8, '000421-9', '2026-02-15', '2026-02-15 08:59:37', '2026-02-15 10:21:08', 81, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_652.json', '2026-02-15 11:59:37', '2026-02-15 13:21:08'),
(653, 9, '002121-4', '2026-02-15', '2026-02-15 10:42:39', '2026-02-15 12:45:12', 122, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_653.json', '2026-02-15 13:42:39', '2026-02-15 15:45:12'),
(654, 7, '005821-6', '2026-02-15', '2026-02-15 10:42:50', '2026-02-15 12:45:09', 122, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_654.json', '2026-02-15 13:42:50', '2026-02-15 15:45:09'),
(655, 10, '001421-5', '2026-02-15', '2026-02-15 12:38:56', '2026-02-15 13:39:08', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_655.json', '2026-02-15 15:38:56', '2026-02-15 16:39:08'),
(656, 11, '001321-3', '2026-02-15', '2026-02-15 18:21:38', '2026-02-15 19:38:20', 76, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_656.json', '2026-02-15 21:21:38', '2026-02-15 22:38:20'),
(657, 7, '005821-6', '2026-02-16', '2026-02-16 07:17:34', '2026-02-16 08:33:21', 75, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_657.json', '2026-02-16 10:17:34', '2026-02-16 11:33:21'),
(658, 9, '002121-4', '2026-02-16', '2026-02-16 07:17:42', '2026-02-16 08:33:23', 75, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_658.json', '2026-02-16 10:17:42', '2026-02-16 11:33:23'),
(659, 9, '002121-4', '2026-02-16', '2026-02-16 08:49:19', '2026-02-16 10:12:42', 83, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_659.json', '2026-02-16 11:49:19', '2026-02-16 13:12:42'),
(660, 11, '001321-3', '2026-02-16', '2026-02-16 10:15:36', '2026-02-16 10:22:09', 6, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_660.json', '2026-02-16 13:15:36', '2026-02-16 13:22:09'),
(661, 10, '001421-5', '2026-02-16', '2026-02-16 10:15:51', '2026-02-16 10:22:11', 6, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_661.json', '2026-02-16 13:15:51', '2026-02-16 13:22:11'),
(662, 7, '005821-6', '2026-02-16', '2026-02-16 10:36:54', '2026-02-16 12:07:13', 90, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_662.json', '2026-02-16 13:36:54', '2026-02-16 15:07:13'),
(663, 6, '002420-6', '2026-02-16', '2026-02-16 13:00:23', '2026-02-16 13:45:25', 45, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_663.json', '2026-02-16 16:00:23', '2026-02-16 16:45:25'),
(664, 11, '001321-3', '2026-02-16', '2026-02-16 13:53:38', '2026-02-16 15:07:48', 74, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_664.json', '2026-02-16 16:53:38', '2026-02-16 18:07:48'),
(665, 10, '001421-5', '2026-02-16', '2026-02-16 13:53:55', '2026-02-16 15:07:50', 73, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_665.json', '2026-02-16 16:53:55', '2026-02-16 18:07:50'),
(666, 7, '005821-6', '2026-02-16', '2026-02-16 14:05:37', '2026-02-16 15:07:53', 62, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_666.json', '2026-02-16 17:05:37', '2026-02-16 18:07:53'),
(667, 11, '001321-3', '2026-02-16', '2026-02-16 15:11:02', '2026-02-16 16:27:35', 76, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_667.json', '2026-02-16 18:11:02', '2026-02-16 19:27:35'),
(668, 9, '002121-4', '2026-02-16', '2026-02-16 15:11:10', '2026-02-16 16:27:38', 76, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_668.json', '2026-02-16 18:11:10', '2026-02-16 19:27:38'),
(669, 7, '005821-6', '2026-02-16', '2026-02-16 16:27:48', '2026-02-16 17:15:47', 47, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_669.json', '2026-02-16 19:27:48', '2026-02-16 20:15:47'),
(670, 9, '002121-4', '2026-02-16', '2026-02-16 17:16:05', '2026-02-16 18:54:29', 98, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_670.json', '2026-02-16 20:16:05', '2026-02-16 21:54:29'),
(671, 8, '000421-9', '2026-02-16', '2026-02-16 17:18:24', '2026-02-16 19:40:55', 142, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_671.json', '2026-02-16 20:18:24', '2026-02-16 22:40:55'),
(672, 10, '001421-5', '2026-02-16', '2026-02-16 17:18:31', '2026-02-16 19:29:06', 130, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_672.json', '2026-02-16 20:18:31', '2026-02-16 22:29:06'),
(673, 6, '002420-6', '2026-02-16', '2026-02-16 17:18:44', '2026-02-16 19:40:53', 142, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_673.json', '2026-02-16 20:18:44', '2026-02-16 22:40:53'),
(674, 8, '000421-9', '2026-02-16', '2026-02-16 20:18:15', '2026-02-16 20:21:05', 2, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_674.json', '2026-02-16 23:18:15', '2026-02-16 23:21:05'),
(675, 8, '000421-9', '2026-02-16', '2026-02-16 20:21:50', '2026-02-16 21:17:09', 55, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_675.json', '2026-02-16 23:21:50', '2026-02-17 00:17:09'),
(676, 8, '000421-9', '2026-02-16', '2026-02-16 21:37:10', '2026-02-16 22:30:46', 53, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_676.json', '2026-02-17 00:37:10', '2026-02-17 01:30:46'),
(677, 11, '001321-3', '2026-02-17', '2026-02-17 07:32:59', '2026-02-17 08:27:07', 54, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_677.json', '2026-02-17 10:32:59', '2026-02-17 11:27:07'),
(678, 11, '001321-3', '2026-02-17', '2026-02-17 08:53:14', '2026-02-17 10:14:05', 80, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_678.json', '2026-02-17 11:53:14', '2026-02-17 13:14:06'),
(679, 8, '000421-9', '2026-02-17', '2026-02-17 10:39:22', '2026-02-17 11:06:14', 26, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_679.json', '2026-02-17 13:39:22', '2026-02-17 14:06:14'),
(680, 10, '001421-5', '2026-02-17', '2026-02-17 10:39:41', '2026-02-17 11:40:31', 60, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_680.json', '2026-02-17 13:39:41', '2026-02-17 14:40:31'),
(681, 7, '005821-6', '2026-02-17', '2026-02-17 12:09:27', '2026-02-17 12:26:10', 16, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_681.json', '2026-02-17 15:09:27', '2026-02-17 15:26:10'),
(682, 6, '002420-6', '2026-02-17', '2026-02-17 13:53:22', '2026-02-17 15:52:56', 119, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_682.json', '2026-02-17 16:53:22', '2026-02-17 18:52:56'),
(683, 7, '005821-6', '2026-02-17', '2026-02-17 16:14:57', '2026-02-17 18:00:44', 105, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_683.json', '2026-02-17 19:14:57', '2026-02-17 21:00:44'),
(684, 11, '001321-3', '2026-02-17', '2026-02-17 16:15:18', '2026-02-17 18:00:46', 105, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_684.json', '2026-02-17 19:15:18', '2026-02-17 21:00:46'),
(685, 10, '001421-5', '2026-02-17', '2026-02-17 18:01:26', '2026-02-17 19:43:50', 102, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_685.json', '2026-02-17 21:01:26', '2026-02-17 22:43:50'),
(686, 8, '000421-9', '2026-02-17', '2026-02-17 18:01:34', '2026-02-17 19:43:52', 102, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_686.json', '2026-02-17 21:01:34', '2026-02-17 22:43:52'),
(687, 6, '002420-6', '2026-02-17', '2026-02-17 19:44:04', '2026-02-17 21:27:17', 103, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_687.json', '2026-02-17 22:44:04', '2026-02-18 00:27:17'),
(688, 9, '002121-4', '2026-02-17', '2026-02-17 19:44:33', '2026-02-17 21:27:19', 102, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_688.json', '2026-02-17 22:44:33', '2026-02-18 00:27:19'),
(689, 10, '001421-5', '2026-02-18', '2026-02-18 07:12:39', '2026-02-18 08:40:44', 88, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_689.json', '2026-02-18 10:12:39', '2026-02-18 11:40:44'),
(690, 9, '002121-4', '2026-02-18', '2026-02-18 07:13:47', '2026-02-18 08:40:42', 86, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_690.json', '2026-02-18 10:13:47', '2026-02-18 11:40:42'),
(691, 10, '001421-5', '2026-02-18', '2026-02-18 08:56:06', '2026-02-18 10:25:38', 89, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_691.json', '2026-02-18 11:56:06', '2026-02-18 13:25:38'),
(692, 11, '001321-3', '2026-02-18', '2026-02-18 10:37:15', '2026-02-18 11:29:53', 52, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_692.json', '2026-02-18 13:37:15', '2026-02-18 14:29:53'),
(693, 8, '000421-9', '2026-02-18', '2026-02-18 11:32:27', '2026-02-18 12:20:25', 47, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_693.json', '2026-02-18 14:32:27', '2026-02-18 15:20:25'),
(694, 10, '001421-5', '2026-02-18', '2026-02-18 13:10:45', '2026-02-18 13:43:20', 32, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_694.json', '2026-02-18 16:10:45', '2026-02-18 16:43:20'),
(695, 8, '000421-9', '2026-02-18', '2026-02-18 13:44:22', '2026-02-18 15:51:39', 127, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_695.json', '2026-02-18 16:44:22', '2026-02-18 18:51:39'),
(696, 9, '002121-4', '2026-02-18', '2026-02-18 15:51:46', '2026-02-18 17:33:39', 101, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_696.json', '2026-02-18 18:51:46', '2026-02-18 20:33:39'),
(697, 7, '005821-6', '2026-02-18', '2026-02-18 15:51:55', '2026-02-18 17:33:45', 101, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_697.json', '2026-02-18 18:51:55', '2026-02-18 20:33:45'),
(698, 10, '001421-5', '2026-02-18', '2026-02-18 17:36:15', '2026-02-18 19:07:37', 91, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_698.json', '2026-02-18 20:36:15', '2026-02-18 22:07:37'),
(699, 6, '002420-6', '2026-02-18', '2026-02-18 17:36:31', '2026-02-18 19:07:35', 91, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_699.json', '2026-02-18 20:36:31', '2026-02-18 22:07:35'),
(700, 8, '000421-9', '2026-02-18', '2026-02-18 19:05:58', '2026-02-18 20:39:12', 93, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_700.json', '2026-02-18 22:05:58', '2026-02-18 23:39:12'),
(701, 11, '001321-3', '2026-02-18', '2026-02-18 19:07:32', '2026-02-18 20:39:16', 91, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_701.json', '2026-02-18 22:07:32', '2026-02-18 23:39:16'),
(702, 11, '001321-3', '2026-02-18', '2026-02-18 21:34:21', '2026-02-18 22:05:49', 31, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_702.json', '2026-02-19 00:34:21', '2026-02-19 01:05:49'),
(703, 7, '005821-6', '2026-02-19', '2026-02-19 07:12:35', '2026-02-19 08:37:17', 84, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_703.json', '2026-02-19 10:12:35', '2026-02-19 11:37:17'),
(704, 9, '002121-4', '2026-02-19', '2026-02-19 07:12:45', '2026-02-19 08:37:20', 84, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_704.json', '2026-02-19 10:12:45', '2026-02-19 11:37:20'),
(705, 9, '002121-4', '2026-02-19', '2026-02-19 08:51:55', '2026-02-19 10:31:16', 99, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_705.json', '2026-02-19 11:51:55', '2026-02-19 13:31:16'),
(706, 11, '001321-3', '2026-02-19', '2026-02-19 11:07:54', '2026-02-19 12:44:41', 96, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_706.json', '2026-02-19 14:07:54', '2026-02-19 15:44:41'),
(707, 6, '002420-6', '2026-02-19', '2026-02-19 11:08:41', '2026-02-19 12:44:43', 96, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_707.json', '2026-02-19 14:08:41', '2026-02-19 15:44:43'),
(708, 7, '005821-6', '2026-02-19', '2026-02-19 11:09:28', '2026-02-19 12:08:46', 59, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_708.json', '2026-02-19 14:09:28', '2026-02-19 15:08:46'),
(709, 7, '005821-6', '2026-02-19', '2026-02-19 12:47:59', '2026-02-19 12:49:03', 1, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_709.json', '2026-02-19 15:47:59', '2026-02-19 15:49:03'),
(710, 6, '002420-6', '2026-02-19', '2026-02-19 13:17:12', '2026-02-19 13:45:53', 28, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_710.json', '2026-02-19 16:17:12', '2026-02-19 16:45:53'),
(711, 11, '001321-3', '2026-02-19', '2026-02-19 13:52:06', '2026-02-19 16:00:21', 128, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_711.json', '2026-02-19 16:52:06', '2026-02-19 19:00:21'),
(712, 10, '001421-5', '2026-02-19', '2026-02-19 16:00:28', '2026-02-19 17:30:32', 90, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_712.json', '2026-02-19 19:00:28', '2026-02-19 20:30:32'),
(713, 6, '002420-6', '2026-02-19', '2026-02-19 16:00:43', '2026-02-19 17:30:47', 90, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_713.json', '2026-02-19 19:00:43', '2026-02-19 20:30:47'),
(714, 11, '001321-3', '2026-02-19', '2026-02-19 17:31:00', '2026-02-19 19:30:28', 119, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_714.json', '2026-02-19 20:31:00', '2026-02-19 22:30:28'),
(715, 9, '002121-4', '2026-02-19', '2026-02-19 17:31:34', '2026-02-19 18:20:03', 48, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_715.json', '2026-02-19 20:31:34', '2026-02-19 21:20:03'),
(716, 8, '000421-9', '2026-02-19', '2026-02-19 17:31:43', '2026-02-19 19:30:26', 118, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_716.json', '2026-02-19 20:31:43', '2026-02-19 22:30:26'),
(717, 7, '005821-6', '2026-02-19', '2026-02-19 19:47:20', '2026-02-19 22:46:40', 179, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_717.json', '2026-02-19 22:47:20', '2026-02-20 01:46:40'),
(718, 10, '001421-5', '2026-02-20', '2026-02-20 07:07:39', '2026-02-20 07:53:57', 46, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_718.json', '2026-02-20 10:07:39', '2026-02-20 10:53:57'),
(719, 6, '002420-6', '2026-02-20', '2026-02-20 07:07:52', '2026-02-20 07:50:27', 42, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_719.json', '2026-02-20 10:07:52', '2026-02-20 10:50:27'),
(720, 11, '001321-3', '2026-02-20', '2026-02-20 07:54:33', '2026-02-20 08:19:44', 25, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_720.json', '2026-02-20 10:54:33', '2026-02-20 11:19:44'),
(721, 7, '005821-6', '2026-02-20', '2026-02-20 08:19:56', '2026-02-20 10:30:56', 131, 'Vuelo instrumental', 'finalizada', 2, 1, NULL, NULL, 'vuelo_sesion_721.json', '2026-02-20 11:19:56', '2026-02-20 13:30:56'),
(722, 8, '000421-9', '2026-02-20', '2026-02-20 10:42:42', '2026-02-20 12:15:22', 92, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_722.json', '2026-02-20 13:42:42', '2026-02-20 15:15:22'),
(723, 10, '001421-5', '2026-02-20', '2026-02-20 11:08:59', '2026-02-20 12:15:20', 66, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_723.json', '2026-02-20 14:08:59', '2026-02-20 15:15:20'),
(724, 9, '002121-4', '2026-02-20', '2026-02-20 13:11:03', '2026-02-20 13:43:03', 32, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_724.json', '2026-02-20 16:11:03', '2026-02-20 16:43:03'),
(725, 10, '001421-5', '2026-02-20', '2026-02-20 13:52:52', '2026-02-20 14:12:14', 19, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_725.json', '2026-02-20 16:52:52', '2026-02-20 17:12:14'),
(726, 10, '001421-5', '2026-02-20', '2026-02-20 14:30:48', '2026-02-20 16:04:31', 93, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_726.json', '2026-02-20 17:30:48', '2026-02-20 19:04:31'),
(727, 8, '000421-9', '2026-02-20', '2026-02-20 16:06:28', '2026-02-20 17:38:07', 91, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_727.json', '2026-02-20 19:06:28', '2026-02-20 20:38:07'),
(728, 7, '005821-6', '2026-02-20', '2026-02-20 16:06:48', '2026-02-20 17:38:09', 91, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_728.json', '2026-02-20 19:06:48', '2026-02-20 20:38:09'),
(729, 10, '001421-5', '2026-02-20', '2026-02-20 17:38:16', '2026-02-20 19:07:39', 89, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_729.json', '2026-02-20 20:38:16', '2026-02-20 22:07:39'),
(730, 11, '001321-3', '2026-02-20', '2026-02-20 17:38:31', '2026-02-20 19:07:37', 89, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_730.json', '2026-02-20 20:38:31', '2026-02-20 22:07:37'),
(731, 9, '002121-4', '2026-02-20', '2026-02-20 19:29:57', '2026-02-20 21:29:39', 119, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_731.json', '2026-02-20 22:29:57', '2026-02-21 00:29:39'),
(732, 9, '002121-4', '2026-02-21', '2026-02-21 08:10:46', '2026-02-21 10:49:11', 158, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_732.json', '2026-02-21 11:10:46', '2026-02-21 13:49:11'),
(733, 7, '005821-6', '2026-02-21', '2026-02-21 08:50:01', '2026-02-21 10:49:29', 119, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_733.json', '2026-02-21 11:50:01', '2026-02-21 13:49:29'),
(734, 10, '001421-5', '2026-02-21', '2026-02-21 10:54:56', '2026-02-21 12:20:38', 85, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_734.json', '2026-02-21 13:54:56', '2026-02-21 15:20:38'),
(735, 8, '000421-9', '2026-02-21', '2026-02-21 10:55:09', '2026-02-21 12:20:35', 85, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_735.json', '2026-02-21 13:55:09', '2026-02-21 15:20:35'),
(736, 11, '001321-3', '2026-02-21', '2026-02-21 12:20:56', '2026-02-21 14:18:47', 117, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_736.json', '2026-02-21 15:20:56', '2026-02-21 17:18:47'),
(737, 6, '002420-6', '2026-02-21', '2026-02-21 12:21:03', '2026-02-21 14:18:45', 117, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_737.json', '2026-02-21 15:21:03', '2026-02-21 17:18:45'),
(738, 8, '000421-9', '2026-02-21', '2026-02-21 14:22:58', '2026-02-21 15:48:41', 85, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_738.json', '2026-02-21 17:22:58', '2026-02-21 18:48:41'),
(739, 8, '000421-9', '2026-02-21', '2026-02-21 17:13:35', '2026-02-21 18:26:00', 72, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_739.json', '2026-02-21 20:13:35', '2026-02-21 21:26:00'),
(740, 8, '000421-9', '2026-02-21', '2026-02-21 19:52:30', '2026-02-21 21:02:39', 70, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_740.json', '2026-02-21 22:52:30', '2026-02-22 00:02:39'),
(741, 8, '000421-9', '2026-02-22', '2026-02-22 08:36:28', '2026-02-22 10:40:45', 124, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_741.json', '2026-02-22 11:36:28', '2026-02-22 13:40:45'),
(742, 10, '001421-5', '2026-02-22', '2026-02-22 09:04:01', '2026-02-22 10:40:47', 96, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_742.json', '2026-02-22 12:04:01', '2026-02-22 13:40:47'),
(743, 9, '002121-4', '2026-02-22', '2026-02-22 10:40:57', '2026-02-22 12:26:11', 105, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_743.json', '2026-02-22 13:40:57', '2026-02-22 15:26:11'),
(744, 7, '005821-6', '2026-02-22', '2026-02-22 10:41:32', '2026-02-22 12:26:09', 104, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_744.json', '2026-02-22 13:41:32', '2026-02-22 15:26:09'),
(745, 6, '002420-6', '2026-02-22', '2026-02-22 12:23:46', '2026-02-22 14:00:41', 96, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_745.json', '2026-02-22 15:23:46', '2026-02-22 17:00:41'),
(746, 11, '001321-3', '2026-02-22', '2026-02-22 12:26:48', '2026-02-22 14:00:34', 93, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_746.json', '2026-02-22 15:26:48', '2026-02-22 17:00:34'),
(747, 10, '001421-5', '2026-02-22', '2026-02-22 15:04:17', '2026-02-22 16:07:22', 63, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_747.json', '2026-02-22 18:04:17', '2026-02-22 19:07:22'),
(748, 10, '001421-5', '2026-02-22', '2026-02-22 17:24:40', '2026-02-22 18:37:58', 73, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_748.json', '2026-02-22 20:24:40', '2026-02-22 21:37:58'),
(749, 10, '001421-5', '2026-02-22', '2026-02-22 20:26:54', '2026-02-22 21:08:26', 41, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_749.json', '2026-02-22 23:26:54', '2026-02-23 00:08:26'),
(750, 8, '000421-9', '2026-02-23', '2026-02-23 07:40:29', '2026-02-23 08:40:53', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_750.json', '2026-02-23 10:40:29', '2026-02-23 11:40:53'),
(751, 7, '005821-6', '2026-02-23', '2026-02-23 07:40:37', '2026-02-23 08:40:55', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_751.json', '2026-02-23 10:40:37', '2026-02-23 11:40:55'),
(752, 7, '005821-6', '2026-02-23', '2026-02-23 08:41:12', '2026-02-23 10:21:54', 100, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_752.json', '2026-02-23 11:41:12', '2026-02-23 13:21:54'),
(753, 8, '000421-9', '2026-02-23', '2026-02-23 10:40:24', '2026-02-23 12:15:22', 94, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_753.json', '2026-02-23 13:40:24', '2026-02-23 15:15:22'),
(754, 6, '002420-6', '2026-02-23', '2026-02-23 12:15:32', '2026-02-23 12:45:34', 30, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_754.json', '2026-02-23 15:15:32', '2026-02-23 15:45:34'),
(755, 6, '002420-6', '2026-02-23', '2026-02-23 13:02:41', '2026-02-23 13:40:45', 38, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_755.json', '2026-02-23 16:02:41', '2026-02-23 16:40:45'),
(756, 9, '002121-4', '2026-02-23', '2026-02-23 13:42:57', '2026-02-23 14:05:45', 22, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_756.json', '2026-02-23 16:42:57', '2026-02-23 17:05:45'),
(757, 6, '002420-6', '2026-02-23', '2026-02-23 14:05:42', '2026-02-23 15:26:50', 81, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_757.json', '2026-02-23 17:05:42', '2026-02-23 18:26:50'),
(758, 10, '001421-5', '2026-02-23', '2026-02-23 16:58:17', '2026-02-23 18:50:39', 112, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_758.json', '2026-02-23 19:58:17', '2026-02-23 21:50:39');
INSERT INTO `sesiones` (`id`, `alumno_id`, `npi`, `fecha`, `hora_inicio`, `hora_fin`, `duracion_minutos`, `actividad`, `estado`, `usuario_inicio_id`, `usuario_fin_id`, `detalles`, `observaciones`, `archivo_vuelo`, `created_at`, `updated_at`) VALUES
(759, 11, '001321-3', '2026-02-23', '2026-02-23 17:00:00', '2026-02-23 18:50:37', 110, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_759.json', '2026-02-23 20:00:00', '2026-02-23 21:50:37'),
(760, 6, '002420-6', '2026-02-23', '2026-02-23 18:53:15', '2026-02-23 20:34:46', 101, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_760.json', '2026-02-23 21:53:15', '2026-02-23 23:34:46'),
(761, 8, '000421-9', '2026-02-23', '2026-02-23 18:53:22', '2026-02-23 20:34:44', 101, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_761.json', '2026-02-23 21:53:22', '2026-02-23 23:34:44'),
(762, 7, '005821-6', '2026-02-23', '2026-02-23 20:35:01', '2026-02-23 22:18:43', 103, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_762.json', '2026-02-23 23:35:01', '2026-02-24 01:18:43'),
(763, 9, '002121-4', '2026-02-23', '2026-02-23 20:35:07', '2026-02-23 22:18:45', 103, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_763.json', '2026-02-23 23:35:07', '2026-02-24 01:18:45'),
(764, 6, '002420-6', '2026-02-24', '2026-02-24 06:55:54', '2026-02-24 08:23:48', 87, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_764.json', '2026-02-24 09:55:54', '2026-02-24 11:23:48'),
(765, 11, '001321-3', '2026-02-24', '2026-02-24 06:56:26', '2026-02-24 08:23:50', 87, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_765.json', '2026-02-24 09:56:26', '2026-02-24 11:23:50'),
(766, 11, '001321-3', '2026-02-24', '2026-02-24 08:44:45', '2026-02-24 10:12:57', 88, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_766.json', '2026-02-24 11:44:45', '2026-02-24 13:12:57'),
(767, 9, '002121-4', '2026-02-24', '2026-02-24 10:44:52', '2026-02-24 12:51:18', 126, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_767.json', '2026-02-24 13:44:52', '2026-02-24 15:51:18'),
(768, 6, '002420-6', '2026-02-24', '2026-02-24 12:51:29', '2026-02-24 13:58:34', 67, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_768.json', '2026-02-24 15:51:29', '2026-02-24 16:58:34'),
(769, 10, '001421-5', '2026-02-24', '2026-02-24 13:58:58', '2026-02-24 14:44:48', 45, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_769.json', '2026-02-24 16:58:58', '2026-02-24 17:44:48'),
(770, 11, '001321-3', '2026-02-24', '2026-02-24 16:11:45', '2026-02-24 18:07:26', 115, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_770.json', '2026-02-24 19:11:45', '2026-02-24 21:07:26'),
(771, 6, '002420-6', '2026-02-24', '2026-02-24 16:11:58', '2026-02-24 18:07:24', 115, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_771.json', '2026-02-24 19:11:58', '2026-02-24 21:07:24'),
(772, 7, '005821-6', '2026-02-24', '2026-02-24 18:11:18', '2026-02-24 20:21:43', 130, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_772.json', '2026-02-24 21:11:18', '2026-02-24 23:21:43'),
(773, 9, '002121-4', '2026-02-24', '2026-02-24 18:11:25', '2026-02-24 20:54:02', 162, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_773.json', '2026-02-24 21:11:25', '2026-02-24 23:54:02'),
(774, 10, '001421-5', '2026-02-24', '2026-02-24 20:21:37', '2026-02-24 21:19:39', 58, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_774.json', '2026-02-24 23:21:37', '2026-02-25 00:19:39'),
(775, 8, '000421-9', '2026-02-24', '2026-02-24 20:21:51', '2026-02-24 21:19:42', 57, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_775.json', '2026-02-24 23:21:51', '2026-02-25 00:19:42'),
(776, 9, '002121-4', '2026-02-24', '2026-02-24 21:19:48', '2026-02-24 22:39:52', 80, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_776.json', '2026-02-25 00:19:48', '2026-02-25 01:39:52'),
(777, 8, '000421-9', '2026-02-25', '2026-02-25 07:06:52', '2026-02-25 08:06:49', 59, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_777.json', '2026-02-25 10:06:52', '2026-02-25 11:06:49'),
(778, 6, '002420-6', '2026-02-25', '2026-02-25 07:08:05', '2026-02-25 08:08:09', 60, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_778.json', '2026-02-25 10:08:05', '2026-02-25 11:08:09'),
(779, 8, '000421-9', '2026-02-25', '2026-02-25 08:47:58', '2026-02-25 10:17:48', 89, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_779.json', '2026-02-25 11:47:58', '2026-02-25 13:17:48'),
(780, 6, '002420-6', '2026-02-25', '2026-02-25 10:24:04', '2026-02-25 12:24:50', 120, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_780.json', '2026-02-25 13:24:04', '2026-02-25 15:24:50'),
(781, 7, '005821-6', '2026-02-25', '2026-02-25 13:50:47', '2026-02-25 14:56:30', 65, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_781.json', '2026-02-25 16:50:47', '2026-02-25 17:56:30'),
(782, 9, '002121-4', '2026-02-25', '2026-02-25 13:50:58', '2026-02-25 17:03:50', 192, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_782.json', '2026-02-25 16:50:58', '2026-02-25 20:03:50'),
(783, 11, '001321-3', '2026-02-25', '2026-02-25 14:45:34', '2026-02-25 17:03:52', 138, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_783.json', '2026-02-25 17:45:34', '2026-02-25 20:03:52'),
(784, 10, '001421-5', '2026-02-25', '2026-02-25 15:33:39', '2026-02-25 16:12:24', 38, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_784.json', '2026-02-25 18:33:39', '2026-02-25 19:12:24'),
(785, 10, '001421-5', '2026-02-25', '2026-02-25 17:05:43', '2026-02-25 18:53:51', 108, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_785.json', '2026-02-25 20:05:43', '2026-02-25 21:53:51'),
(786, 6, '002420-6', '2026-02-25', '2026-02-25 17:05:56', '2026-02-25 18:53:54', 107, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_786.json', '2026-02-25 20:05:56', '2026-02-25 21:53:54'),
(787, 11, '001321-3', '2026-02-25', '2026-02-25 18:58:39', '2026-02-25 20:04:53', 66, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_787.json', '2026-02-25 21:58:39', '2026-02-25 23:04:53'),
(788, 9, '002121-4', '2026-02-25', '2026-02-25 18:59:56', '2026-02-25 20:04:50', 64, 'Práctica en seco, Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_788.json', '2026-02-25 21:59:56', '2026-02-25 23:04:50'),
(789, 8, '000421-9', '2026-02-25', '2026-02-25 19:57:36', '2026-02-25 20:34:11', 36, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_789.json', '2026-02-25 22:57:36', '2026-02-25 23:34:11'),
(790, 11, '001321-3', '2026-02-25', '2026-02-25 20:05:05', '2026-02-25 20:34:07', 29, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_790.json', '2026-02-25 23:05:05', '2026-02-25 23:34:07'),
(791, 7, '005821-6', '2026-02-25', '2026-02-25 20:38:35', '2026-02-25 21:19:25', 40, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_791.json', '2026-02-25 23:38:35', '2026-02-26 00:19:25'),
(792, 9, '002121-4', '2026-02-25', '2026-02-25 21:59:30', '2026-02-25 23:00:08', 60, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_792.json', '2026-02-26 00:59:30', '2026-02-26 02:00:08'),
(793, 11, '001321-3', '2026-02-26', '2026-02-26 07:07:46', '2026-02-26 08:13:07', 65, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_793.json', '2026-02-26 10:07:46', '2026-02-26 11:13:07'),
(794, 9, '002121-4', '2026-02-26', '2026-02-26 07:07:54', '2026-02-26 08:11:43', 63, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_794.json', '2026-02-26 10:07:54', '2026-02-26 11:11:43'),
(795, 11, '001321-3', '2026-02-26', '2026-02-26 08:42:14', '2026-02-26 10:17:36', 95, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_795.json', '2026-02-26 11:42:14', '2026-02-26 13:17:36'),
(796, 9, '002121-4', '2026-02-26', '2026-02-26 10:40:34', '2026-02-26 12:28:54', 108, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_796.json', '2026-02-26 13:40:34', '2026-02-26 15:28:54'),
(797, 10, '001421-5', '2026-02-26', '2026-02-26 12:59:57', '2026-02-26 14:20:45', 80, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_797.json', '2026-02-26 15:59:57', '2026-02-26 17:20:45'),
(798, 7, '005821-6', '2026-02-26', '2026-02-26 13:59:36', '2026-02-26 16:15:45', 136, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_798.json', '2026-02-26 16:59:36', '2026-02-26 19:15:45'),
(799, 8, '000421-9', '2026-02-26', '2026-02-26 13:59:43', '2026-02-26 16:15:46', 136, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_799.json', '2026-02-26 16:59:43', '2026-02-26 19:15:46'),
(800, 10, '001421-5', '2026-02-26', '2026-02-26 16:15:57', '2026-02-26 18:21:50', 125, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_800.json', '2026-02-26 19:15:57', '2026-02-26 21:21:50'),
(801, 6, '002420-6', '2026-02-26', '2026-02-26 16:16:22', '2026-02-26 18:21:48', 125, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_801.json', '2026-02-26 19:16:22', '2026-02-26 21:21:48'),
(802, 8, '000421-9', '2026-02-26', '2026-02-26 18:10:12', '2026-02-26 19:36:18', 86, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_802.json', '2026-02-26 21:10:12', '2026-02-26 22:36:18'),
(803, 7, '005821-6', '2026-02-26', '2026-02-26 18:10:26', '2026-02-26 19:36:20', 85, 'Vuelo instrumental', 'finalizada', 1, 1, NULL, NULL, 'vuelo_sesion_803.json', '2026-02-26 21:10:26', '2026-02-26 22:36:20'),
(804, 11, '001321-3', '2026-02-26', '2026-02-26 20:37:33', '2026-02-26 23:16:20', 158, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_804.json', '2026-02-26 23:37:33', '2026-02-27 02:16:20'),
(805, 9, '002121-4', '2026-02-26', '2026-02-26 20:37:39', '2026-02-26 23:16:36', 158, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_805.json', '2026-02-26 23:37:39', '2026-02-27 02:16:36'),
(806, 6, '002420-6', '2026-02-26', '2026-02-26 20:37:44', '2026-02-26 23:16:53', 159, 'Vuelo instrumental', 'finalizada', 1, 2, NULL, NULL, 'vuelo_sesion_806.json', '2026-02-26 23:37:44', '2026-02-27 02:16:53'),
(807, 10, '001421-5', '2026-02-27', '2026-02-27 07:42:26', '2026-02-27 08:26:19', 43, 'Vuelo instrumental', 'finalizada', 2, 2, NULL, NULL, 'vuelo_sesion_807.json', '2026-02-27 10:42:26', '2026-02-27 11:26:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('gK8Bd74aTMkByVlDrHB5JQQCt9mk8Dumn9KJbG3N', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS2swVDFEeTMzUm5iVkFjTnVzRkV5WExIdDh1T1JzTjl3TzAwZUJrWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9zaW11bGFkb3ItcGM3LmxvY2FsL3Nlc2lvbmVzL2FjdGl2YXMtYWpheCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1772192508);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soporte`
--

CREATE TABLE `soporte` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` enum('falla','sugerencia') NOT NULL DEFAULT 'falla',
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('baja','media','alta') NOT NULL DEFAULT 'media',
  `estado` enum('pendiente','en_revision','resuelto','rechazado') NOT NULL DEFAULT 'pendiente',
  `respuesta_admin` text DEFAULT NULL,
  `fecha_resolucion` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','invitado') NOT NULL DEFAULT 'invitado',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador Principal', 'admin@simulador.local', 'admin', 1, '2025-09-23 15:03:18', '$2y$12$oGtckG/lewWBHaVS1slm5eOFlGKFK5MwfTLgJuEnBfEGRiWTCYiDe', NULL, '2025-09-23 15:03:18', '2025-09-23 15:03:18'),
(2, 'Operador Sala', 'operador@simulador.local', 'invitado', 1, '2025-09-23 15:03:19', '$2y$12$dVLgWhHfQDeJUNf/36Cky.eOedgSZrFmo5nGJo0EMC5v2l8J7qaqS', NULL, '2025-09-23 15:03:19', '2025-09-23 15:03:19'),
(3, 'Operador Turno', 'turno@simulador.local', 'invitado', 1, '2025-09-23 15:03:19', '$2y$12$zoH4fnVyO58zdcos9jvf.OKYmQ9rNNw4npzI379Z5aCSYpvjF1GJa', NULL, '2025-09-23 15:03:19', '2025-09-23 15:03:19');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alumnos_rut_dni_unique` (`rut_dni`),
  ADD UNIQUE KEY `alumnos_npi_unique` (`npi`),
  ADD UNIQUE KEY `alumnos_correo_unique` (`correo`),
  ADD KEY `alumnos_npi_index` (`npi`),
  ADD KEY `alumnos_rut_dni_index` (`rut_dni`),
  ADD KEY `alumnos_is_active_index` (`is_active`),
  ADD KEY `alumnos_is_active_npi_index` (`is_active`,`npi`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sesiones_usuario_inicio_id_foreign` (`usuario_inicio_id`),
  ADD KEY `sesiones_usuario_fin_id_foreign` (`usuario_fin_id`),
  ADD KEY `sesiones_npi_index` (`npi`),
  ADD KEY `sesiones_fecha_index` (`fecha`),
  ADD KEY `sesiones_estado_index` (`estado`),
  ADD KEY `sesiones_fecha_estado_index` (`fecha`,`estado`),
  ADD KEY `sesiones_alumno_id_fecha_index` (`alumno_id`,`fecha`),
  ADD KEY `sesiones_hora_inicio_index` (`hora_inicio`),
  ADD KEY `idx_alumno_estado` (`alumno_id`,`estado`),
  ADD KEY `idx_alumno_fecha` (`alumno_id`,`fecha`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `soporte`
--
ALTER TABLE `soporte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `soporte_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=808;

--
-- AUTO_INCREMENT de la tabla `soporte`
--
ALTER TABLE `soporte`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_alumno_id_foreign` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sesiones_usuario_fin_id_foreign` FOREIGN KEY (`usuario_fin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sesiones_usuario_inicio_id_foreign` FOREIGN KEY (`usuario_inicio_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `soporte`
--
ALTER TABLE `soporte`
  ADD CONSTRAINT `soporte_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
