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
(1, 1, 'Carlos', 'Benitez', NULL);

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
(1, 4, 1, 'blsbls', '2025-11-22 14:14:35', 0),
(2, 4, 1, 'comentarios', '2025-11-22 14:19:34', 0),
(3, 4, 2, 'bullying', '2025-11-22 14:20:00', 0),
(4, 4, 1, 'no se', '2025-11-22 14:20:26', 0),
(5, 4, 1, 'bullying', '2025-11-22 14:35:07', 0),
(6, 4, 1, 'no responsable', '2025-11-23 16:31:47', 0),
(7, 4, 2, 'dfgd', '2025-11-23 16:32:53', 0),
(8, 4, 2, 'bullying', '2025-11-23 16:36:17', 0),
(9, 4, 1, 'comentarios', '2025-11-23 19:24:45', 0),
(10, 4, 1, 'bullying', '2025-11-23 19:28:55', 0),
(11, 4, 1,'bullying', '2025-11-23 19:28:56', 0),
(12, 11, 1, 'comentarios', '2025-11-24 15:05:41', 0),
(13, 11, 1, 'comentarios', '2025-11-24 15:05:42', 0),
(14, 11, 1, 'comentarios', '2025-11-24 15:05:42', 0),
(15, 11, 1, 'comentarios', '2025-11-24 15:05:42', 0),
(16, 11, 1, 'comentarios', '2025-11-24 15:05:43', 0),
(17, 11, 1, 'comentarios', '2025-11-24 15:05:43', 0),
(18, 11, 1, 'comentarios', '2025-11-24 15:05:43', 0),
(19, 11, 1, 'comentarios', '2025-11-24 15:11:49', 0),
(20, 11, 1, 'comentarios', '2025-11-24 15:11:50', 0),
(21, 11, 1, 'no responsable', '2025-11-24 15:30:09', 0),
(22, 9, 1, 'no responsable', '2025-11-24 18:50:34', 0),
(23, 9, 1, 'no responsable', '2025-11-24 18:50:40', 0),
(24, 9, 1, 'no responsable', '2025-11-24 18:51:55', 0),
(25, 9, 1, 'bullying', '2025-11-24 18:52:32', 0),
(26, 9, 1, 'bullying', '2025-11-24 18:54:34', 0),
(27, 9, 1, 'comentarios', '2025-11-24 18:59:05', 0),
(28, 12, 1, 'comentarios', '2025-11-25 23:14:19', 0);

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
(6, 'espaciales', 1, 2, '', 1),
(7, 'gamers123', 1, 2, '', 1),
(11, 'Furia Latina', 11, 1, 'Equipo competitivo regional.', 1),
(12, 'Shadow Strikers', 8, 2, 'Especialistas en Valorant.', 1),
(13, 'Equipo LoL', 5, 2, 'Para divertirnos', 1),
(14, 'Equipo CS', 4, 2, '', 1),
(15, 'Equipo CS', 4, 2, '', 1),
(16, 'espaciales', 4, 2, '', 1),
(17, 'espaciales', 4, 2, '', 1),
(18, 'espaciales', 4, 2, '', 1),
(19, 'espaciales', 4, 2, '', 1),
(20, 'espaciales', 4, 2, '', 1);

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
(3, 'bloqueado');

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
(1, 4, '', '', NULL, NULL, NULL, 0, NULL, NULL, 0),
(2, 5, '', '', NULL, NULL, NULL, 0, NULL, NULL, 0),
(3, 8, 'Nicole', '', 'Argentina', '1998-08-15', '', 1450, 2, 'LMarte#887', 0),
(4, 9, 'María', 'Lopez', 'Chile', '1999-01-22', 'Experta en shooters tácticos.', 980, 2, 'MLopez#233', 0),
(5, 11, 'Leo', 'Suarez', 'Uruguay', '1997-12-02', 'Capitán de equipo competitivo.', 1500, 2, 'LeoS#991', 0),
(6, 12, 'nina', NULL, NULL, NULL, NULL, 0, NULL, NULL, 0),
(7, 13, 'Ana', 'Gutiérrez', 'Argentina', '2000-05-10', 'Amante del FPS competitivo.', 1200, 2, 'AnaG#445', 0);

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
(4, 13, 9, '2025-11-24'),
(5, 20, 8, '2025-11-26');

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
(1, 1, 'Juan', 'Pérez', 'Gamers Org', 'Argentina', 'https://gamers.org', 'Organización de torneos', 1);

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
(19, 'responder_tickets');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_torneo`
--

CREATE TABLE `solicitud_torneo` (
  `id_solicitud_torneo` int(11) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `fecha_solicitud` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_torneo`
--

INSERT INTO `solicitud_torneo` (`id_solicitud_torneo`, `id_equipo`, `id_usuario`, `id_torneo`, `fecha_solicitud`) VALUES
(10, NULL, 9, 2, '2025-11-24'),
(11, NULL, 9, 2, '2025-11-24'),
(12, 13, 9, 1, '2025-11-24'),
(13, 13, 9, 1, '2025-11-24');

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
(1, 4, 'cómo sumarme a un torneo', 'no entcuentro el boton', '2025-11-24 02:28:48'),
(2, 4, 'cómo sumarme a un torneo', 'entro y no veo el boton', '2025-11-24 02:36:03');

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
(1, 1, 1, 'Torneo de Valorant', 'Torneo amistoso', '2025-12-01', '2025-12-10', 1, 2),
(2, 1, 2, 'LoL Championship', 'Torneo competitivo', '2025-12-05', '2025-12-15', 1, 1);

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
(0, '', '1234', '2024-01-11', 1),
(1, 'admin@email.com', 'pass123', '2025-10-31', 1),
(2, 'mail@upe.com', 'pass123', '2025-10-31', 1),
(3, 'progamer@example.com', 'pass123', '2025-11-11', 1),
(4, 'game123@mail.com', '$2y$10$gdj2t/YIV7Z3G', '2025-11-17', 1),
(5, 'vik09@email.com', '$2y$10$LNeY2VulE40Yo', '2025-11-17', 1),
(6, 'admin@upe.com', '1234', '2024-01-10', 1),
(8, 'm4ail@upe.com', '1234', '2024-02-20', 1),
(9, 'maria.player@upe.com', '1234', '2024-03-05', 1),
(10, 'organizer@upe.com', '1234', '2024-03-10', 1),
(11, 'teamlead@upe.com', '1234', '2024-04-01', 1),
(12, 'jugado@mail.com', 'pass123', '2025-11-25', 1);

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
(6, 0, 2),
(1, 1, 1),
(2, 2, 2),
(3, 4, 2),
(4, 5, 2),
(5, 6, 1),
(7, 8, 2),
(8, 9, 2),
(9, 10, 3),
(10, 11, 2),
(13, 12, 2);

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `id_denuncia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `membresia`
--
ALTER TABLE `membresia`
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `miembros_equipo`
--
ALTER TABLE `miembros_equipo`
  MODIFY `id_miembro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `organizador`
--
ALTER TABLE `organizador`
  MODIFY `id_organizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_solicitud_creacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_equipo`
--
ALTER TABLE `solicitud_equipo`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_torneo`
--
ALTER TABLE `solicitud_torneo`
  MODIFY `id_solicitud_torneo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id_torneo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `torneo_equipo`
--
ALTER TABLE `torneo_equipo`
  MODIFY `id_torneo_equipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `torneo_jugador`
--
ALTER TABLE `torneo_jugador`
  MODIFY `id_torneo_jugador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `id_usuario_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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

--
-- Evita duplicado en el torneo (para que no se inscriba dos veces el mismo jugador o equipo)
--

ALTER TABLE torneo_jugador
ADD UNIQUE (id_torneo, id_jugador);