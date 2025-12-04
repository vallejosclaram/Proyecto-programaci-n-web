-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2025 a las 20:52:03
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
-- Base de datos: `upesport_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `id_rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_admin`, `id_usuario`, `nombre`, `apellido`, `id_rol`) VALUES
(1, 1, 'Admin', 'Uno', 1),
(2, 2, 'Admin', 'Dos', 1),
(3, 3, 'Admin', 'Tres', 1),
(4, 4, 'Admin', 'Cuatro', 1),
(5, 5, 'Admin', 'Cinco', 1),
(6, 6, 'Admin', 'Seis', 1),
(7, 7, 'Admin', 'Siete', 1),
(8, 8, 'Admin', 'Ocho', 1),
(9, 9, 'Admin', 'Nueve', 1),
(10, 10, 'Admin', 'Diez', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `id_comentario` int(11) NOT NULL,
  `id_autor` int(11) NOT NULL,
  `id_objetivo` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentario`
--

INSERT INTO `comentario` (`id_comentario`, `id_autor`, `id_objetivo`, `comentario`, `fecha`) VALUES
(1, 11, 1, 'Buen torneo, me gusto la organizacion', '2025-04-10 12:00:00'),
(2, 12, 2, 'Cuando hay la proxima fecha?', '2025-04-11 14:00:00'),
(3, 21, 1, 'Gracias por participar', '2025-04-12 16:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denuncias`
--

CREATE TABLE `denuncias` (
  `id_denuncia` int(11) NOT NULL,
  `id_reportador` int(11) NOT NULL,
  `id_reportado` int(11) DEFAULT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `id_torneo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `denuncias`
--

INSERT INTO `denuncias` (`id_denuncia`, `id_reportador`, `id_reportado`, `descripcion`, `fecha_creacion`, `id_torneo`) VALUES
(1, 11, 12, 'Uso de lenguaje ofensivo en chat', '2025-04-13 10:00:00', 1),
(2, 13, 14, 'Posible glitch en partida', '2025-04-14 11:00:00', 4),
(3, 15, 16, 'Comportamiento antideportivo', '2025-04-15 12:00:00', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id_equipo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_capitan_usuario` int(11) DEFAULT NULL,
  `id_juego` int(11) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id_equipo`, `nombre`, `id_capitan_usuario`, `id_juego`, `descripcion`, `estado`) VALUES
(1, 'Blue Falcons', 11, 1, 'Equipo casual Valorant', 1),
(2, 'Red Dragons', 12, 2, 'Equipo competitivo CS', 1),
(3, 'Night Owls', 13, 1, 'Equipo mixto', 1),
(4, 'Storm Riders', 14, 2, 'Equipo entrenado', 1),
(5, 'Lone Wolves', 15, 1, 'Equipo de amigos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `descripcion`) VALUES
(1, 'activo'),
(2, 'inactivo'),
(3, 'bloqueado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_equipo`
--

CREATE TABLE `estado_equipo` (
  `id_estado` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_equipo`
--

INSERT INTO `estado_equipo` (`id_estado`, `descripcion`) VALUES
(1, 'activo'),
(2, 'cerrado'),
(3, 'bloqueado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_inscripcion`
--

CREATE TABLE `estado_inscripcion` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_torneo`
--

CREATE TABLE `estado_torneo` (
  `id_estado` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_torneo`
--

INSERT INTO `estado_torneo` (`id_estado`, `descripcion`) VALUES
(1, 'Activo'),
(2, 'Cerrado'),
(3, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencia_reporte`
--

CREATE TABLE `evidencia_reporte` (
  `id_evidencia` int(11) NOT NULL,
  `id_reporte` int(11) NOT NULL,
  `archivo` varchar(300) NOT NULL,
  `tipo` enum('imagen','video','otro') DEFAULT 'imagen',
  `fecha_subida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juego`
--

CREATE TABLE `juego` (
  `id_juego` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juego`
--

INSERT INTO `juego` (`id_juego`, `nombre`) VALUES
(1, 'Valorant'),
(2, 'Counter Strike');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

CREATE TABLE `jugador` (
  `id_jugador` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `apellido` varchar(80) DEFAULT NULL,
  `pais` varchar(80) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `biografia` varchar(500) DEFAULT NULL,
  `puntaje` int(11) DEFAULT 0,
  `id_rol` int(11) DEFAULT NULL,
  `id_cuentajuego` varchar(100) DEFAULT NULL,
  `id_membresia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jugador`
--

INSERT INTO `jugador` (`id_jugador`, `id_usuario`, `nombre`, `apellido`, `pais`, `fecha_nacimiento`, `biografia`, `puntaje`, `id_rol`, `id_cuentajuego`, `id_membresia`) VALUES
(1, 11, 'Juan', 'Perez', 'Argentina', '1998-05-12', 'Jugador casual', 1200, 2, 'juan#001', 1),
(2, 12, 'Mateo', 'Gomez', 'Argentina', '1997-03-20', 'Loves FPS', 1520, 2, 'mateo#12', 1),
(3, 13, 'Lucia', 'Martinez', 'Chile', '1996-09-02', 'Support main', 980, 2, 'luciay', 1),
(4, 14, 'Sofia', 'Diaz', 'Uruguay', '2000-11-11', 'Entry fragger', 1400, 2, 'sofiag', 1),
(5, 15, 'Diego', 'Lopez', 'Peru', '1995-08-07', 'Strategist', 1300, 2, 'diego_7', 1),
(6, 16, 'Carla', 'Fernandez', 'Argentina', '1999-01-15', 'AWPer', 1600, 2, 'carlaAWP', 1),
(7, 17, 'Martin', 'Rojas', 'Chile', '1994-07-24', 'Flex player', 1100, 2, 'martinr', 1),
(8, 18, 'Alejandro', 'Vega', 'Argentina', '1993-10-03', 'Rifler', 1250, 2, 'alev', 1),
(9, 19, 'Camila', 'Santos', 'Brasil', '2001-06-30', 'Support', 900, 2, 'camis', 1),
(10, 20, 'Nicolas', 'Ruiz', 'Paraguay', '1992-12-12', 'Captain', 1550, 2, 'nicoR', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresia`
--

CREATE TABLE `membresia` (
  `id_membresia` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `membresia`
--

INSERT INTO `membresia` (`id_membresia`, `descripcion`) VALUES
(1, 'Estandar'),
(2, 'Premium'),
(3, 'Pro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros_equipo`
--

CREATE TABLE `miembros_equipo` (
  `id_miembro` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_union` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `miembros_equipo`
--

INSERT INTO `miembros_equipo` (`id_miembro`, `id_equipo`, `id_usuario`, `fecha_union`) VALUES
(1, 1, 11, '2025-04-01'),
(2, 1, 16, '2025-04-02'),
(3, 1, 17, '2025-04-03'),
(4, 2, 12, '2025-04-05'),
(5, 2, 18, '2025-04-06'),
(6, 2, 19, '2025-04-07'),
(7, 3, 13, '2025-04-08'),
(8, 3, 20, '2025-04-09'),
(9, 3, 11, '2025-04-10'),
(10, 4, 14, '2025-04-11'),
(11, 4, 16, '2025-04-12'),
(12, 4, 17, '2025-04-13'),
(13, 5, 15, '2025-04-14'),
(14, 5, 18, '2025-04-15'),
(15, 5, 19, '2025-04-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizador`
--

CREATE TABLE `organizador` (
  `id_organizador` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(30) DEFAULT NULL,
  `organizacion` varchar(50) DEFAULT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `pagina_web` varchar(200) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `organizador`
--

INSERT INTO `organizador` (`id_organizador`, `id_usuario`, `nombre`, `apellido`, `organizacion`, `pais`, `pagina_web`, `descripcion`, `id_estado`) VALUES
(1, 21, 'Maria', 'Uno', 'OrgOne', 'Argentina', 'https://org1.example', 'Organizador de torneos regionais', 1),
(2, 22, 'Pedro', 'Dos', 'OrgTwo', 'Chile', 'https://org2.example', 'Eventos semanales', 1),
(3, 23, 'Pablo', 'Tres', 'OrgThree', 'Uruguay', 'https://org3.example', 'Torneos casuales', 1),
(4, 24, 'Luis', 'Cuatro', 'OrgFour', 'Peru', 'https://org4.example', 'Competiciones', 1),
(5, 25, 'Clara', 'Cinco', 'OrgFive', 'Bolivia', 'https://org5.example', 'Torneos locales', 1),
(6, 26, 'Matias', 'Seis', 'OrgSix', 'Argentina', 'https://org6.example', 'Eventos online', 1),
(7, 27, 'Nicolas', 'Siete', 'OrgSeven', 'Chile', 'https://org7.example', 'Liga amateur', 1),
(8, 28, 'Maia', 'Ocho', 'OrgEight', 'Argentina', 'https://org8.example', 'Eventos LAN', 1),
(9, 29, 'Ferderico', 'Nueve', 'OrgNine', 'Paraguay', 'https://org9.example', 'Torneos regionales', 1),
(10, 30, 'Valentina', 'Diez', 'OrgTen', 'Uruguay', 'https://org10.example', 'Eventos mensuales', 1),
(11, 31, 'Dara', 'Jarjury', NULL, NULL, NULL, NULL, 1),
(12, 32, 'Sebastian', 'Celasco', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id_partida` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `ronda` int(11) NOT NULL,
  `id_jugador1` int(11) DEFAULT NULL,
  `id_jugador2` int(11) DEFAULT NULL,
  `id_equipo1` int(11) DEFAULT NULL,
  `id_equipo2` int(11) DEFAULT NULL,
  `tipo` enum('individual','equipo') NOT NULL,
  `estado` enum('pendiente','en_progreso','finalizada') DEFAULT 'pendiente',
  `fecha` date NOT NULL,
  `horario` time(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`) VALUES
(1, 'Bloquear usuario'),
(2, 'Bloquear torneo'),
(3, 'Visualizar perfil'),
(4, 'Visualizar torneo'),
(5, 'Denunciar torneo'),
(6, 'Crear equipo'),
(7, 'Crear torneo'),
(8, 'solicitar_equipo'),
(9, 'solicitar_torneo'),
(10, 'actualizar_ranking'),
(11, 'gestionar_puntaje'),
(12, 'subir_puntaje'),
(13, 'Denunciar jugador'),
(14, 'Editar perfil'),
(15, 'Cambiar contraseña'),
(16, 'Visualizar denuncia'),
(17, 'Crear ticket'),
(18, 'solicitar_creacion_torneo'),
(19, 'responder_tickets'),
(20, 'solicitar_membresia'),
(21, '⁠otorgar_membresia'),
(22, 'subir_puntaje');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntaje_torneo`
--

CREATE TABLE `puntaje_torneo` (
  `id_puntaje` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `id_jugador` int(11) DEFAULT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `puntaje_obtenido` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_validacion` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `id_partida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ranking`
--

CREATE TABLE `ranking` (
  `id_ranking` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `puntaje` int(11) DEFAULT 0,
  `posicion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_resultado`
--

CREATE TABLE `reporte_resultado` (
  `id_reporte` int(11) NOT NULL,
  `id_partida` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `resultado_b` int(11) NOT NULL,
  `resultado_a` int(11) NOT NULL,
  `fecha_reporte` datetime DEFAULT current_timestamp(),
  `estado` enum('reportado','coincidente','en_disputa','validado_admin') DEFAULT 'reportado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resolucion_admin`
--

CREATE TABLE `resolucion_admin` (
  `id_resolucion` int(11) NOT NULL,
  `id_partida` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `resultado_final_b` int(11) NOT NULL,
  `resultado_final_a` int(11) NOT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `fecha_resolucion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_ticket`
--

CREATE TABLE `respuesta_ticket` (
  `id_respuesta` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `respuesta` varchar(1000) NOT NULL,
  `fecha_respuesta` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(1, 'administrador'),
(2, 'jugador'),
(3, 'organizador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_permisos`
--

INSERT INTO `roles_permisos` (`id_rol`, `id_permiso`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 7),
(1, 10),
(1, 11),
(1, 14),
(1, 15),
(1, 16),
(1, 19),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 8),
(2, 9),
(2, 12),
(2, 13),
(2, 14),
(2, 15),
(2, 17),
(3, 3),
(3, 4),
(3, 7),
(3, 14),
(3, 15),
(3, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_creacion_torneo`
--

CREATE TABLE `solicitud_creacion_torneo` (
  `id_solicitud_creacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `id_juego` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `id_tipo` int(11) NOT NULL,
  `fecha_solicitud` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_creacion_torneo`
--

INSERT INTO `solicitud_creacion_torneo` (`id_solicitud_creacion`, `id_usuario`, `nombre`, `descripcion`, `id_juego`, `fecha_inicio`, `fecha_fin`, `id_tipo`, `fecha_solicitud`, `estado`) VALUES
(1, 21, 'Solicitud Org1 #1', 'Solicitud de prueba', 1, '2025-08-01', '2025-08-05', 1, '2025-04-01 10:00:00', 'pendiente'),
(2, 21, 'Solicitud Org1 #2', 'Solicitud de prueba', 2, '2025-08-10', '2025-08-15', 2, '2025-04-01 10:10:00', 'pendiente'),
(3, 21, 'Solicitud Org1 #3', 'Solicitud de prueba', 1, '2025-09-01', '2025-09-05', 1, '2025-04-01 10:20:00', 'pendiente'),
(4, 22, 'Solicitud Org2 #1', 'Solicitud de prueba', 2, '2025-08-02', '2025-08-06', 2, '2025-04-02 11:00:00', 'pendiente'),
(5, 22, 'Solicitud Org2 #2', 'Solicitud de prueba', 1, '2025-08-12', '2025-08-16', 1, '2025-04-02 11:10:00', 'pendiente'),
(6, 22, 'Solicitud Org2 #3', 'Solicitud de prueba', 2, '2025-09-02', '2025-09-06', 2, '2025-04-02 11:20:00', 'pendiente'),
(7, 23, 'Solicitud Org3 #1', 'Solicitud de prueba', 1, '2025-08-03', '2025-08-07', 1, '2025-04-03 12:00:00', 'pendiente'),
(8, 23, 'Solicitud Org3 #2', 'Solicitud de prueba', 2, '2025-08-13', '2025-08-17', 2, '2025-04-03 12:10:00', 'pendiente'),
(9, 23, 'Solicitud Org3 #3', 'Solicitud de prueba', 1, '2025-09-03', '2025-09-07', 1, '2025-04-03 12:20:00', 'pendiente'),
(10, 24, 'Solicitud Org4 #1', 'Solicitud de prueba', 2, '2025-08-04', '2025-08-08', 2, '2025-04-04 13:00:00', 'pendiente'),
(11, 24, 'Solicitud Org4 #2', 'Solicitud de prueba', 1, '2025-08-14', '2025-08-18', 1, '2025-04-04 13:10:00', 'pendiente'),
(12, 24, 'Solicitud Org4 #3', 'Solicitud de prueba', 2, '2025-09-04', '2025-09-08', 2, '2025-04-04 13:20:00', 'pendiente'),
(13, 25, 'Solicitud Org5 #1', 'Solicitud de prueba', 1, '2025-08-05', '2025-08-09', 1, '2025-04-05 14:00:00', 'pendiente'),
(14, 25, 'Solicitud Org5 #2', 'Solicitud de prueba', 2, '2025-08-15', '2025-08-19', 2, '2025-04-05 14:10:00', 'pendiente'),
(15, 25, 'Solicitud Org5 #3', 'Solicitud de prueba', 1, '2025-09-05', '2025-09-09', 1, '2025-04-05 14:20:00', 'pendiente'),
(16, 26, 'Solicitud Org6 #1', 'Solicitud de prueba', 2, '2025-08-06', '2025-08-10', 2, '2025-04-06 15:00:00', 'pendiente'),
(17, 26, 'Solicitud Org6 #2', 'Solicitud de prueba', 1, '2025-08-16', '2025-08-20', 1, '2025-04-06 15:10:00', 'pendiente'),
(18, 26, 'Solicitud Org6 #3', 'Solicitud de prueba', 2, '2025-09-06', '2025-09-10', 2, '2025-04-06 15:20:00', 'pendiente'),
(19, 27, 'Solicitud Org7 #1', 'Solicitud de prueba', 1, '2025-08-07', '2025-08-11', 1, '2025-04-07 16:00:00', 'pendiente'),
(20, 27, 'Solicitud Org7 #2', 'Solicitud de prueba', 2, '2025-08-17', '2025-08-21', 2, '2025-04-07 16:10:00', 'pendiente'),
(21, 27, 'Solicitud Org7 #3', 'Solicitud de prueba', 1, '2025-09-07', '2025-09-11', 1, '2025-04-07 16:20:00', 'pendiente'),
(22, 28, 'Solicitud Org8 #1', 'Solicitud de prueba', 2, '2025-08-08', '2025-08-12', 2, '2025-04-08 17:00:00', 'pendiente'),
(23, 28, 'Solicitud Org8 #2', 'Solicitud de prueba', 1, '2025-08-18', '2025-08-22', 1, '2025-04-08 17:10:00', 'pendiente'),
(24, 28, 'Solicitud Org8 #3', 'Solicitud de prueba', 2, '2025-09-08', '2025-09-12', 2, '2025-04-08 17:20:00', 'pendiente'),
(25, 29, 'Solicitud Org9 #1', 'Solicitud de prueba', 1, '2025-08-09', '2025-08-13', 1, '2025-04-09 18:00:00', 'pendiente'),
(26, 29, 'Solicitud Org9 #2', 'Solicitud de prueba', 2, '2025-08-19', '2025-08-23', 2, '2025-04-09 18:10:00', 'pendiente'),
(27, 29, 'Solicitud Org9 #3', 'Solicitud de prueba', 1, '2025-09-09', '2025-09-13', 1, '2025-04-09 18:20:00', 'pendiente'),
(28, 30, 'Solicitud Org10 #1', 'Solicitud de prueba', 2, '2025-08-10', '2025-08-14', 2, '2025-04-10 19:00:00', 'pendiente'),
(29, 30, 'Solicitud Org10 #2', 'Solicitud de prueba', 1, '2025-08-20', '2025-08-24', 1, '2025-04-10 19:10:00', 'pendiente'),
(30, 30, 'Solicitud Org10 #3', 'Solicitud de prueba', 2, '2025-09-10', '2025-09-14', 2, '2025-04-10 19:20:00', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_equipo`
--

CREATE TABLE `solicitud_equipo` (
  `id_solicitud` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `fecha_solicitud` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_equipo`
--

INSERT INTO `solicitud_equipo` (`id_solicitud`, `id_usuario`, `id_equipo`, `fecha_solicitud`) VALUES
(1, 11, 1, '2025-02-01'),
(2, 12, 1, '2025-02-02'),
(3, 13, 2, '2025-02-03'),
(4, 14, 2, '2025-02-04'),
(5, 15, 3, '2025-02-05'),
(6, 16, 3, '2025-02-06'),
(7, 17, 4, '2025-02-07'),
(8, 18, 5, '2025-02-08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_membresia`
--

CREATE TABLE `solicitud_membresia` (
  `id_membresia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `membresia` varchar(30) NOT NULL,
  `estado` int(11) NOT NULL,
  `comprobante` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_membresia`
--

INSERT INTO `solicitud_membresia` (`id_membresia`, `id_usuario`, `membresia`, `estado`, `comprobante`) VALUES
(2, 23, 'Premium', 0, 1764873288);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_torneo`
--

CREATE TABLE `solicitud_torneo` (
  `id_solicitud_torneo` int(11) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_torneo` int(11) NOT NULL,
  `fecha_solicitud` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_torneo`
--

INSERT INTO `solicitud_torneo` (`id_solicitud_torneo`, `id_equipo`, `id_usuario`, `id_torneo`, `fecha_solicitud`) VALUES
(1, NULL, 19, 1, '2025-02-01'),
(2, 3, NULL, 3, '2025-02-01'),
(3, NULL, 20, 5, '2025-02-02'),
(4, 2, NULL, 2, '2025-02-03'),
(5, 4, NULL, 6, '2025-02-03'),
(6, NULL, 17, 7, '2025-02-04'),
(7, NULL, 14, 9, '2025-02-05'),
(8, 2, NULL, 12, '2025-02-05'),
(9, 4, NULL, 14, '2025-02-06'),
(10, NULL, 11, 15, '2025-02-06'),
(11, 1, NULL, 22, '2025-12-04'),
(12, 5, NULL, 25, '2025-12-04'),
(13, NULL, 11, 21, '2025-12-04'),
(14, NULL, 12, 23, '2025-12-04'),
(15, NULL, 13, 24, '2025-12-04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE `ticket` (
  `id_ticket` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `asunto` varchar(150) NOT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ticket`
--

INSERT INTO `ticket` (`id_ticket`, `id_usuario`, `asunto`, `descripcion`, `fecha_creacion`) VALUES
(1, 11, '¿Cómo inscribirme?', 'No encuentro el botón de inscripcion', '2025-04-01 09:00:00'),
(2, 21, 'Solicitud de ayuda organizador', 'No puedo crear torneo', '2025-04-02 10:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_torneo`
--

CREATE TABLE `tipo_torneo` (
  `id_tipo` int(11) NOT NULL,
  `descripcion` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_torneo`
--

INSERT INTO `tipo_torneo` (`id_tipo`, `descripcion`) VALUES
(1, 'Individual'),
(2, 'Equipo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo`
--

CREATE TABLE `torneo` (
  `id_torneo` int(11) NOT NULL,
  `id_organizador` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `torneo`
--

INSERT INTO `torneo` (`id_torneo`, `id_organizador`, `id_juego`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `id_estado`, `id_tipo`) VALUES
(1, 1, 1, 'Valorant Open #1', 'Torneo apertura - individual', '2025-05-01', '2025-05-10', 1, 1),
(2, 1, 2, 'CS Cup #1', 'Formato equipos 5v5', '2025-05-15', '2025-05-25', 1, 2),
(3, 1, 1, 'Valorant Weekend #1', 'Torneo rapido - equipo', '2025-06-01', '2025-06-05', 3, 2),
(4, 1, 2, 'CS Solo #1', '1v1 cup', '2025-06-10', '2025-06-12', 1, 1),
(5, 1, 1, 'Valorant Spring #1', 'Competencia regional', '2025-07-01', '2025-07-10', 2, 1),
(6, 2, 2, 'CS Cup #2', 'Equipos amateurs', '2025-05-03', '2025-05-12', 1, 2),
(7, 2, 1, 'Valorant Solo #2', '1v1 campeonato', '2025-05-20', '2025-05-22', 1, 1),
(8, 2, 2, 'CS League #2', 'Liga mensual equipos', '2025-06-15', '2025-06-30', 1, 2),
(9, 2, 1, 'Valorant Night #2', 'Torneo nocturno individual', '2025-07-05', '2025-07-07', 3, 1),
(10, 2, 2, 'CS Quick #2', 'Torneo rapido equipos', '2025-07-20', '2025-07-23', 1, 2),
(11, 3, 1, 'Valorant Open #3', 'Torneo local', '2025-05-05', '2025-05-14', 1, 1),
(12, 3, 2, 'CS Spring #3', 'Equipos 5v5', '2025-05-25', '2025-06-04', 1, 2),
(13, 3, 1, 'Valorant Pro #3', '1v1 eliminatorias', '2025-06-06', '2025-06-10', 1, 1),
(14, 3, 2, 'CS Weekend #3', 'Torneo fin de semana', '2025-06-20', '2025-06-22', 2, 2),
(15, 3, 1, 'Valorant Summer #3', 'Competencia', '2025-07-10', '2025-07-18', 1, 1),
(16, 4, 2, 'CS Open #4', 'Equipos', '2025-05-07', '2025-05-17', 1, 2),
(17, 4, 1, 'Valorant Solo #4', '1v1 cup', '2025-05-30', '2025-06-02', 1, 1),
(18, 4, 2, 'CS Night #4', 'Nocturno equipos', '2025-06-12', '2025-06-14', 3, 2),
(19, 4, 1, 'Valorant Cup #4', 'Regional individual', '2025-07-01', '2025-07-05', 1, 1),
(20, 4, 2, 'CS Summer #4', 'Summer league', '2025-07-22', '2025-07-30', 1, 2),
(21, 5, 1, 'Valorant Open #5', 'Torneo local', '2025-05-09', '2025-05-18', 1, 1),
(22, 5, 2, 'CS Cup #5', 'Equipos amateurs', '2025-05-28', '2025-06-07', 1, 2),
(23, 5, 1, 'Valorant Night #5', 'Nocturno individual', '2025-06-08', '2025-06-10', 3, 1),
(24, 5, 2, 'CS Solo #5', '1v1 cup', '2025-06-25', '2025-06-27', 1, 1),
(25, 5, 1, 'Valorant Fest #5', 'Festival de juego', '2025-07-12', '2025-07-20', 2, 2),
(26, 6, 2, 'CS Open #6', 'Equipos 5v5', '2025-05-11', '2025-05-21', 1, 2),
(27, 6, 1, 'Valorant Pro #6', '1v1 eliminator', '2025-05-29', '2025-06-01', 1, 1),
(28, 6, 2, 'CS League #6', 'Liga local', '2025-06-15', '2025-06-25', 1, 2),
(29, 6, 1, 'Valorant Quick #6', 'Rápido individual', '2025-06-28', '2025-06-30', 1, 1),
(30, 6, 2, 'CS Fest #6', 'Equipos festival', '2025-07-02', '2025-07-10', 1, 2),
(31, 7, 1, 'Valorant Open #7', 'Torneo regional', '2025-05-13', '2025-05-22', 1, 1),
(32, 7, 2, 'CS Quick #7', 'Torneo rapido', '2025-05-30', '2025-06-02', 1, 2),
(33, 7, 1, 'Valorant Solo #7', '1v1 cup', '2025-06-11', '2025-06-13', 1, 1),
(34, 7, 2, 'CS Night #7', 'Nocturno equipos', '2025-06-24', '2025-06-26', 3, 2),
(35, 7, 1, 'Valorant Summer #7', 'Competicion', '2025-07-15', '2025-07-23', 1, 1),
(36, 8, 2, 'CS Open #8', 'Equipos locales', '2025-05-17', '2025-05-27', 1, 2),
(37, 8, 1, 'Valorant Pro #8', '1v1 eliminatorias', '2025-05-31', '2025-06-03', 1, 1),
(38, 8, 2, 'CS League #8', 'Liga mensual', '2025-06-16', '2025-06-29', 1, 2),
(39, 8, 1, 'Valorant Night #8', 'Nocturno individual', '2025-06-29', '2025-07-01', 3, 1),
(40, 8, 2, 'CS Cup #8', 'Torneo equipos', '2025-07-21', '2025-07-29', 1, 2),
(41, 9, 1, 'Valorant Open #9', 'Torneo semanal', '2025-05-19', '2025-05-28', 1, 1),
(42, 9, 2, 'CS Cup #9', 'Equipos 5v5', '2025-06-01', '2025-06-11', 1, 2),
(43, 9, 1, 'Valorant Solo #9', '1v1 cup', '2025-06-05', '2025-06-07', 1, 1),
(44, 9, 2, 'CS Night #9', 'Nocturno', '2025-06-21', '2025-06-23', 2, 2),
(45, 9, 1, 'Valorant Fest #9', 'Festival', '2025-07-11', '2025-07-19', 1, 1),
(46, 10, 2, 'CS Open #10', 'Equipos amateurs', '2025-05-21', '2025-05-31', 1, 2),
(47, 10, 1, 'Valorant Solo #10', '1v1 cup', '2025-06-02', '2025-06-04', 1, 1),
(48, 10, 2, 'CS Pro #10', 'Torneo competitivo', '2025-06-18', '2025-06-28', 1, 2),
(49, 10, 1, 'Valorant Night #10', 'Nocturno individual', '2025-07-06', '2025-07-08', 3, 1),
(50, 10, 2, 'CS Summer #10', 'Liga verano equipos', '2025-07-24', '2025-07-31', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo_equipo`
--

CREATE TABLE `torneo_equipo` (
  `id_torneo_equipo` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `fecha_inscripcion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `torneo_equipo`
--

INSERT INTO `torneo_equipo` (`id_torneo_equipo`, `id_torneo`, `id_equipo`, `fecha_inscripcion`) VALUES
(1, 2, 1, '2025-04-22 00:00:00'),
(2, 2, 2, '2025-04-22 00:00:00'),
(3, 2, 3, '2025-04-22 00:00:00'),
(4, 2, 4, '2025-04-22 00:00:00'),
(5, 6, 2, '2025-04-25 00:00:00'),
(6, 6, 1, '2025-04-25 00:00:00'),
(7, 6, 5, '2025-04-25 00:00:00'),
(8, 8, 1, '2025-05-10 00:00:00'),
(9, 8, 3, '2025-05-10 00:00:00'),
(10, 8, 2, '2025-05-10 00:00:00'),
(11, 12, 4, '2025-05-20 00:00:00'),
(12, 12, 5, '2025-05-20 00:00:00'),
(13, 12, 1, '2025-05-20 00:00:00'),
(14, 16, 2, '2025-05-22 00:00:00'),
(15, 16, 3, '2025-05-22 00:00:00'),
(16, 16, 4, '2025-05-22 00:00:00'),
(17, 22, 5, '2025-06-01 00:00:00'),
(18, 22, 1, '2025-06-01 00:00:00'),
(19, 22, 2, '2025-06-01 00:00:00'),
(20, 26, 3, '2025-06-05 00:00:00'),
(21, 26, 4, '2025-06-05 00:00:00'),
(22, 26, 5, '2025-06-05 00:00:00'),
(23, 28, 1, '2025-06-12 00:00:00'),
(24, 28, 2, '2025-06-12 00:00:00'),
(25, 28, 3, '2025-06-12 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo_jugador`
--

CREATE TABLE `torneo_jugador` (
  `id_torneo_jugador` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `id_jugador` int(11) NOT NULL,
  `fecha_inscripcion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `torneo_jugador`
--

INSERT INTO `torneo_jugador` (`id_torneo_jugador`, `id_torneo`, `id_jugador`, `fecha_inscripcion`) VALUES
(1, 1, 1, '2025-04-20 00:00:00'),
(2, 1, 2, '2025-04-20 00:00:00'),
(3, 1, 3, '2025-04-20 00:00:00'),
(4, 1, 4, '2025-04-20 00:00:00'),
(5, 1, 5, '2025-04-20 00:00:00'),
(6, 1, 6, '2025-04-20 00:00:00'),
(7, 4, 1, '2025-05-01 00:00:00'),
(8, 4, 7, '2025-05-01 00:00:00'),
(9, 4, 2, '2025-05-01 00:00:00'),
(10, 4, 3, '2025-05-01 00:00:00'),
(11, 4, 8, '2025-05-01 00:00:00'),
(12, 4, 9, '2025-05-01 00:00:00'),
(13, 7, 5, '2025-05-05 00:00:00'),
(14, 7, 6, '2025-05-05 00:00:00'),
(15, 7, 10, '2025-05-05 00:00:00'),
(16, 7, 1, '2025-05-05 00:00:00'),
(17, 7, 2, '2025-05-05 00:00:00'),
(18, 7, 3, '2025-05-05 00:00:00'),
(19, 11, 4, '2025-05-06 00:00:00'),
(20, 11, 5, '2025-05-06 00:00:00'),
(21, 11, 6, '2025-05-06 00:00:00'),
(22, 11, 7, '2025-05-06 00:00:00'),
(23, 11, 8, '2025-05-06 00:00:00'),
(24, 11, 9, '2025-05-06 00:00:00'),
(25, 13, 1, '2025-05-10 00:00:00'),
(26, 13, 2, '2025-05-10 00:00:00'),
(27, 13, 3, '2025-05-10 00:00:00'),
(28, 13, 4, '2025-05-10 00:00:00'),
(29, 13, 5, '2025-05-10 00:00:00'),
(30, 13, 6, '2025-05-10 00:00:00'),
(31, 17, 7, '2025-05-15 00:00:00'),
(32, 17, 8, '2025-05-15 00:00:00'),
(33, 17, 9, '2025-05-15 00:00:00'),
(34, 17, 10, '2025-05-15 00:00:00'),
(35, 17, 1, '2025-05-15 00:00:00'),
(36, 17, 2, '2025-05-15 00:00:00'),
(37, 19, 3, '2025-05-20 00:00:00'),
(38, 19, 4, '2025-05-20 00:00:00'),
(39, 19, 5, '2025-05-20 00:00:00'),
(40, 19, 6, '2025-05-20 00:00:00'),
(41, 19, 7, '2025-05-20 00:00:00'),
(42, 19, 8, '2025-05-20 00:00:00'),
(43, 21, 9, '2025-05-21 00:00:00'),
(44, 21, 10, '2025-05-21 00:00:00'),
(45, 21, 1, '2025-05-21 00:00:00'),
(46, 21, 2, '2025-05-21 00:00:00'),
(47, 21, 3, '2025-05-21 00:00:00'),
(48, 21, 4, '2025-05-21 00:00:00'),
(49, 23, 5, '2025-06-01 00:00:00'),
(50, 23, 6, '2025-06-01 00:00:00'),
(51, 23, 7, '2025-06-01 00:00:00'),
(52, 23, 8, '2025-06-01 00:00:00'),
(53, 23, 9, '2025-06-01 00:00:00'),
(54, 23, 10, '2025-06-01 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` date NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `contrasena`, `fecha_registro`, `id_estado`) VALUES
(1, 'admin1@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-01', 1),
(2, 'admin2@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-02', 1),
(3, 'admin3@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-03', 1),
(4, 'admin4@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-04', 1),
(5, 'admin5@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-05', 1),
(6, 'admin6@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-06', 1),
(7, 'admin7@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-07', 1),
(8, 'admin8@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-08', 1),
(9, 'admin9@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-09', 1),
(10, 'admin10@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-01-10', 1),
(11, 'player1@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-01', 1),
(12, 'player2@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-02', 1),
(13, 'player3@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-03', 1),
(14, 'player4@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-04', 1),
(15, 'player5@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-05', 1),
(16, 'player6@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-06', 1),
(17, 'player7@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-07', 1),
(18, 'player8@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-08', 1),
(19, 'player9@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-09', 1),
(20, 'player10@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-02-10', 1),
(21, 'org1@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-01', 1),
(22, 'org2@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-02', 1),
(23, 'org3@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-03', 1),
(24, 'org4@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-04', 1),
(25, 'org5@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-05', 1),
(26, 'org6@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-06', 1),
(27, 'org7@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-07', 1),
(28, 'org8@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-08', 1),
(29, 'org9@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-09', 1),
(30, 'org10@upesport.test', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-03-10', 1),
(31, 'daritajarjury@gmail.com', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-12-04', 1),
(32, 'Sebastian@gmail.com', '$2y$10$PSonsQXntC0W5ViqQvUG5.20avd9Z2S.mPgkUmEaVY8yGW4YGl42y', '2025-12-04', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id_usuario_rol` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`id_usuario_rol`, `id_usuario`, `id_rol`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 2),
(12, 12, 2),
(13, 13, 2),
(14, 14, 2),
(15, 15, 2),
(16, 16, 2),
(17, 17, 2),
(18, 18, 2),
(19, 19, 2),
(20, 20, 2),
(21, 21, 3),
(22, 22, 3),
(23, 23, 3),
(24, 24, 3),
(25, 25, 3),
(26, 26, 3),
(27, 27, 3),
(28, 28, 3),
(29, 29, 3),
(30, 30, 3),
(31, 31, 3),
(32, 32, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `ux_admin_usuario` (`id_usuario`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `fk_comentario_autor` (`id_autor`),
  ADD KEY `fk_comentario_objetivo` (`id_objetivo`);

--
-- Indices de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  ADD PRIMARY KEY (`id_denuncia`),
  ADD KEY `fk_denuncia_reportador` (`id_reportador`),
  ADD KEY `fk_denuncia_reportado` (`id_reportado`),
  ADD KEY `id_torneo` (`id_torneo`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `fk_equipo_juego` (`id_juego`),
  ADD KEY `fk_equipo_capitan` (`id_capitan_usuario`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `estado_equipo`
--
ALTER TABLE `estado_equipo`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `estado_inscripcion`
--
ALTER TABLE `estado_inscripcion`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `estado_torneo`
--
ALTER TABLE `estado_torneo`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `evidencia_reporte`
--
ALTER TABLE `evidencia_reporte`
  ADD PRIMARY KEY (`id_evidencia`),
  ADD KEY `id_reporte` (`id_reporte`);

--
-- Indices de la tabla `juego`
--
ALTER TABLE `juego`
  ADD PRIMARY KEY (`id_juego`);

--
-- Indices de la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD PRIMARY KEY (`id_jugador`),
  ADD UNIQUE KEY `ux_jugador_usuario` (`id_usuario`),
  ADD KEY `fk_jugador_rol` (`id_rol`),
  ADD KEY `id_membresia` (`id_membresia`);

--
-- Indices de la tabla `membresia`
--
ALTER TABLE `membresia`
  ADD PRIMARY KEY (`id_membresia`);

--
-- Indices de la tabla `miembros_equipo`
--
ALTER TABLE `miembros_equipo`
  ADD PRIMARY KEY (`id_miembro`),
  ADD UNIQUE KEY `ux_equipo_usuario` (`id_equipo`,`id_usuario`),
  ADD KEY `fk_miembros_equipo_usuario` (`id_usuario`);

--
-- Indices de la tabla `organizador`
--
ALTER TABLE `organizador`
  ADD PRIMARY KEY (`id_organizador`),
  ADD UNIQUE KEY `ux_organizador_usuario` (`id_usuario`),
  ADD KEY `fk_organizador_estado` (`id_estado`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_torneo` (`id_torneo`),
  ADD KEY `id_jugador1` (`id_jugador1`),
  ADD KEY `id_jugador2` (`id_jugador2`),
  ADD KEY `id_equipo1` (`id_equipo1`),
  ADD KEY `id_equipo2` (`id_equipo2`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `puntaje_torneo`
--
ALTER TABLE `puntaje_torneo`
  ADD PRIMARY KEY (`id_puntaje`),
  ADD UNIQUE KEY `id_partida` (`id_partida`),
  ADD KEY `fk_puntaje_torneo` (`id_torneo`),
  ADD KEY `fk_puntaje_jugador` (`id_jugador`),
  ADD KEY `fk_puntaje_equipo` (`id_equipo`);

--
-- Indices de la tabla `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id_ranking`),
  ADD KEY `fk_ranking_juego_eq` (`id_juego`);

--
-- Indices de la tabla `reporte_resultado`
--
ALTER TABLE `reporte_resultado`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `id_partida` (`id_partida`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `resolucion_admin`
--
ALTER TABLE `resolucion_admin`
  ADD PRIMARY KEY (`id_resolucion`),
  ADD KEY `fk_resolucion_partida` (`id_partida`),
  ADD KEY `fk_resolucion_admin` (`id_admin`);

--
-- Indices de la tabla `respuesta_ticket`
--
ALTER TABLE `respuesta_ticket`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `fk_respuesta_ticket` (`id_ticket`),
  ADD KEY `fk_respuesta_admin` (`id_admin`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `id_permiso` (`id_permiso`);

--
-- Indices de la tabla `solicitud_creacion_torneo`
--
ALTER TABLE `solicitud_creacion_torneo`
  ADD PRIMARY KEY (`id_solicitud_creacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_juego` (`id_juego`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indices de la tabla `solicitud_equipo`
--
ALTER TABLE `solicitud_equipo`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `fk_solicitud_equipo_usuario` (`id_usuario`),
  ADD KEY `fk_solicitud_equipo_equipo` (`id_equipo`);

--
-- Indices de la tabla `solicitud_membresia`
--
ALTER TABLE `solicitud_membresia`
  ADD PRIMARY KEY (`id_membresia`);

--
-- Indices de la tabla `solicitud_torneo`
--
ALTER TABLE `solicitud_torneo`
  ADD PRIMARY KEY (`id_solicitud_torneo`),
  ADD KEY `fk_solicitud_torneo_equipo` (`id_equipo`),
  ADD KEY `fk_solicitud_torneo_usuario` (`id_usuario`),
  ADD KEY `fk_solicitud_torneo_torneo` (`id_torneo`);

--
-- Indices de la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `fk_ticket_usuario` (`id_usuario`);

--
-- Indices de la tabla `tipo_torneo`
--
ALTER TABLE `tipo_torneo`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `torneo`
--
ALTER TABLE `torneo`
  ADD PRIMARY KEY (`id_torneo`),
  ADD KEY `fk_torneo_organizador` (`id_organizador`),
  ADD KEY `fk_torneo_juego` (`id_juego`),
  ADD KEY `fk_torneo_estado` (`id_estado`),
  ADD KEY `fk_torneo_tipo` (`id_tipo`);

--
-- Indices de la tabla `torneo_equipo`
--
ALTER TABLE `torneo_equipo`
  ADD PRIMARY KEY (`id_torneo_equipo`),
  ADD KEY `id_torneo` (`id_torneo`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `torneo_jugador`
--
ALTER TABLE `torneo_jugador`
  ADD PRIMARY KEY (`id_torneo_jugador`),
  ADD UNIQUE KEY `id_torneo_2` (`id_torneo`,`id_jugador`),
  ADD KEY `id_torneo` (`id_torneo`),
  ADD KEY `id_jugador` (`id_jugador`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuario_estado` (`id_estado`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id_usuario_rol`),
  ADD UNIQUE KEY `usuario_rol` (`id_usuario`,`id_rol`),
  ADD KEY `fk_usuario_rol_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `id_denuncia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_equipo`
--
ALTER TABLE `estado_equipo`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_torneo`
--
ALTER TABLE `estado_torneo`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `evidencia_reporte`
--
ALTER TABLE `evidencia_reporte`
  MODIFY `id_evidencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `juego`
--
ALTER TABLE `juego`
  MODIFY `id_juego` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `jugador`
--
ALTER TABLE `jugador`
  MODIFY `id_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `membresia`
--
ALTER TABLE `membresia`
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `miembros_equipo`
--
ALTER TABLE `miembros_equipo`
  MODIFY `id_miembro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `organizador`
--
ALTER TABLE `organizador`
  MODIFY `id_organizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `puntaje_torneo`
--
ALTER TABLE `puntaje_torneo`
  MODIFY `id_puntaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ranking`
--
ALTER TABLE `ranking`
  MODIFY `id_ranking` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reporte_resultado`
--
ALTER TABLE `reporte_resultado`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `resolucion_admin`
--
ALTER TABLE `resolucion_admin`
  MODIFY `id_resolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuesta_ticket`
--
ALTER TABLE `respuesta_ticket`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitud_creacion_torneo`
--
ALTER TABLE `solicitud_creacion_torneo`
  MODIFY `id_solicitud_creacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `solicitud_equipo`
--
ALTER TABLE `solicitud_equipo`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `solicitud_membresia`
--
ALTER TABLE `solicitud_membresia`
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `solicitud_torneo`
--
ALTER TABLE `solicitud_torneo`
  MODIFY `id_solicitud_torneo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_torneo`
--
ALTER TABLE `tipo_torneo`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `torneo`
--
ALTER TABLE `torneo`
  MODIFY `id_torneo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `torneo_equipo`
--
ALTER TABLE `torneo_equipo`
  MODIFY `id_torneo_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `torneo_jugador`
--
ALTER TABLE `torneo_jugador`
  MODIFY `id_torneo_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `id_usuario_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `fk_comentario_autor` FOREIGN KEY (`id_autor`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comentario_objetivo` FOREIGN KEY (`id_objetivo`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `denuncias`
--
ALTER TABLE `denuncias`
  ADD CONSTRAINT `fk_denuncia_reportado` FOREIGN KEY (`id_reportado`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_denuncia_reportador` FOREIGN KEY (`id_reportador`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `fk_equipo_capitan` FOREIGN KEY (`id_capitan_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_equipo_juego` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `evidencia_reporte`
--
ALTER TABLE `evidencia_reporte`
  ADD CONSTRAINT `evidencia_reporte_ibfk_1` FOREIGN KEY (`id_reporte`) REFERENCES `reporte_resultado` (`id_reporte`) ON DELETE CASCADE;

--
-- Filtros para la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD CONSTRAINT `fk_jugador_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jugador_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `miembros_equipo`
--
ALTER TABLE `miembros_equipo`
  ADD CONSTRAINT `fk_miembros_equipo_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_miembros_equipo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `organizador`
--
ALTER TABLE `organizador`
  ADD CONSTRAINT `fk_organizador_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_organizador_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`id_torneo`) REFERENCES `torneo` (`id_torneo`) ON DELETE CASCADE,
  ADD CONSTRAINT `partida_ibfk_2` FOREIGN KEY (`id_jugador1`) REFERENCES `jugador` (`id_jugador`) ON DELETE SET NULL,
  ADD CONSTRAINT `partida_ibfk_3` FOREIGN KEY (`id_jugador2`) REFERENCES `jugador` (`id_jugador`) ON DELETE SET NULL,
  ADD CONSTRAINT `partida_ibfk_4` FOREIGN KEY (`id_equipo1`) REFERENCES `equipo` (`id_equipo`) ON DELETE SET NULL,
  ADD CONSTRAINT `partida_ibfk_5` FOREIGN KEY (`id_equipo2`) REFERENCES `equipo` (`id_equipo`) ON DELETE SET NULL;

--
-- Filtros para la tabla `puntaje_torneo`
--
ALTER TABLE `puntaje_torneo`
  ADD CONSTRAINT `fk_puntaje_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_puntaje_jugador` FOREIGN KEY (`id_jugador`) REFERENCES `jugador` (`id_jugador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_puntaje_torneo` FOREIGN KEY (`id_torneo`) REFERENCES `torneo` (`id_torneo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reporte_resultado`
--
ALTER TABLE `reporte_resultado`
  ADD CONSTRAINT `reporte_resultado_ibfk_1` FOREIGN KEY (`id_partida`) REFERENCES `partida` (`id_partida`) ON DELETE CASCADE,
  ADD CONSTRAINT `reporte_resultado_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resolucion_admin`
--
ALTER TABLE `resolucion_admin`
  ADD CONSTRAINT `fk_resolucion_admin` FOREIGN KEY (`id_admin`) REFERENCES `administrador` (`id_admin`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resolucion_partida` FOREIGN KEY (`id_partida`) REFERENCES `partida` (`id_partida`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuesta_ticket`
--
ALTER TABLE `respuesta_ticket`
  ADD CONSTRAINT `fk_respuesta_admin` FOREIGN KEY (`id_admin`) REFERENCES `administrador` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respuesta_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `roles_permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_permisos_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitud_creacion_torneo`
--
ALTER TABLE `solicitud_creacion_torneo`
  ADD CONSTRAINT `solicitud_creacion_torneo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `solicitud_creacion_torneo_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`),
  ADD CONSTRAINT `solicitud_creacion_torneo_ibfk_3` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_torneo` (`id_tipo`);

--
-- Filtros para la tabla `solicitud_equipo`
--
ALTER TABLE `solicitud_equipo`
  ADD CONSTRAINT `fk_solicitud_equipo_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_solicitud_equipo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud_torneo`
--
ALTER TABLE `solicitud_torneo`
  ADD CONSTRAINT `fk_solicitud_torneo_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_solicitud_torneo_torneo` FOREIGN KEY (`id_torneo`) REFERENCES `torneo` (`id_torneo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_solicitud_torneo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_ticket_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `torneo`
--
ALTER TABLE `torneo`
  ADD CONSTRAINT `fk_torneo_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado_torneo` (`id_estado`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_torneo_juego` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_torneo_organizador` FOREIGN KEY (`id_organizador`) REFERENCES `organizador` (`id_organizador`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_torneo_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_torneo` (`id_tipo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `torneo_equipo`
--
ALTER TABLE `torneo_equipo`
  ADD CONSTRAINT `torneo_equipo_ibfk_1` FOREIGN KEY (`id_torneo`) REFERENCES `torneo` (`id_torneo`),
  ADD CONSTRAINT `torneo_equipo_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`);

--
-- Filtros para la tabla `torneo_jugador`
--
ALTER TABLE `torneo_jugador`
  ADD CONSTRAINT `torneo_jugador_ibfk_1` FOREIGN KEY (`id_torneo`) REFERENCES `torneo` (`id_torneo`),
  ADD CONSTRAINT `torneo_jugador_ibfk_2` FOREIGN KEY (`id_jugador`) REFERENCES `jugador` (`id_jugador`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `fk_usuario_rol_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_rol_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
