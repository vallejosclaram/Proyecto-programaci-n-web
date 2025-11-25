CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_admin`, `id_usuario`, `nombre`, `apellido`) VALUES
(1, 1, 'admin', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_juego`
--

CREATE TABLE `categoria_juego` (
  `id_categoria` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `id_juego` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `id_organizador` int(11) DEFAULT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `denuncias`
--

INSERT INTO `denuncias` (`id_denuncia`, `id_reportador`, `id_reportado`, `id_organizador`, `descripcion`, `fecha_creacion`) VALUES
(1, 2, 4, NULL, 'No cumple con las reglas del juego', '2025-11-23 23:02:23'),
(2, 4, 8, 3, 'No cumple con la edad minima', '2025-11-17 23:07:00'),
(3, 22, 24, NULL, 'Abuso de chat en partida clasificatoria.', '2025-11-24 12:10:00'),
(4, 23, 4, NULL, 'Cuenta compartida detectada en torneo valorant.', '2025-11-24 12:45:00'),
(5, 22, NULL, 1, 'Reglamento no publicado con anticipación.', '2025-11-24 13:30:00'),
(6, 23, NULL, 2, 'Premios no entregados al finalizar el torneo.', '2025-11-24 14:05:00');

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
(1, 'espaciales', 1, 2, '', 1),
(2, 'espaciales', 1, 2, '', 1),
(3, 'espaciales', 1, 2, '', 1),
(4, 'espaciales', 1, 2, '', 1),
(5, 'espaciales', 1, 2, '', 1),
(6, 'espaciales', 1, 2, '', 1),
(7, 'gamers123', 1, 2, '', 1),
(8, 'marcianos', 1, 2, '', 1),
(9, 'equipo9', 1, 1, '', 1),
(10, 'equipo4', 1, 2, '', 1),
(11, 'Team Valorant Elite', 11, 1, 'Equipo profesional de Valorant', 1),
(12, 'LoL Champions', 12, 2, 'Equipo competitivo de League of Legends', 1),
(13, 'Valorant Warriors', 13, 1, 'Equipo en ascenso de Valorant', 1),
(14, 'LoL Legends', 14, 2, 'Equipo experimentado de LoL', 1);

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
(2, 'cerrado');

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
-- Estructura de tabla para la tabla `inscripcion_torneo`
--

CREATE TABLE `inscripcion_torneo` (
  `id_inscripcion` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `id_jugador` int(11) DEFAULT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `fecha_inscripcion` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','aceptado','rechazado') DEFAULT 'pendiente'
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
(2, 'League of Legends');

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
  `id_cuentajuego` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jugador`
--

INSERT INTO `jugador` (`id_jugador`, `id_usuario`, `nombre`, `apellido`, `pais`, `fecha_nacimiento`, `biografia`, `puntaje`, `id_rol`, `id_cuentajuego`) VALUES
(1, 4, '', '', NULL, NULL, NULL, 0, NULL, NULL),
(2, 5, '', '', NULL, NULL, NULL, 0, NULL, NULL),
(3, 11, 'Carlos', 'Rodriguez', 'Argentina', '1998-05-15', 'Jugador profesional de Valorant', 0, NULL, 'CarlosPro#1234'),
(4, 12, 'Ana', 'Martinez', 'Chile', '2000-03-22', 'Especialista en League of Legends', 0, NULL, 'AnaGamer#5678'),
(5, 13, 'Luis', 'Garcia', 'México', '1999-07-10', 'Jugador competitivo', 0, NULL, 'LuisPro#9012'),
(6, 14, 'Maria', 'Lopez', 'Colombia', '2001-11-30', 'Rising star en Valorant', 0, NULL, 'MariaStar#3456'),
(7, 15, 'Diego', 'Fernandez', 'Argentina', '1997-12-05', 'Veterano en LoL', 0, NULL, 'DiegoVet#7890');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresia`
--

CREATE TABLE `membresia` (
  `id_membresia` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros_equipo`
--

CREATE TABLE `miembros_equipo` (
  `id_miembro` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `rol_en_equipo` varchar(50) DEFAULT NULL,
  `fecha_union` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `miembros_equipo`
--

INSERT INTO `miembros_equipo` (`id_miembro`, `id_equipo`, `id_usuario`, `rol_en_equipo`, `fecha_union`) VALUES
(1, 8, 4, 'capitana', '2025-11-22'),
(2, 9, 4, 'capitana', '2025-11-22'),
(3, 10, 4, 'capitana', '2025-11-22');

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
(1, 1, 'Juan', 'Pérez', 'Gamers Org', 'Argentina', 'https://gamers.org', 'Organización de torneos', 1),
(2, 7, 'juan', 'gomez', 'G.O', 'Argentina', NULL, NULL, 1),
(3, 8, 'maria', 'Martinez', 'G.O', 'Argentina', NULL, NULL, 1),
(4, 16, 'Roberto', 'Silva', 'Gaming Events SA', 'Argentina', 'https://gamingevents.com', 'Organizamos los mejores torneos de eSports', 1),
(5, 17, 'Patricia', 'Morales', 'Esports Pro', 'Chile', 'https://esportspro.cl', 'Especialistas en torneos competitivos', 1),
(6, 18, 'Fernando', 'Castro', 'Tournament Masters', 'México', 'https://tournamentmasters.mx', 'Masters en organización de torneos', 1);

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
  `estado_validacion` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `puntaje_torneo`
--

INSERT INTO `puntaje_torneo` (`id_puntaje`, `id_torneo`, `id_jugador`, `id_equipo`, `puntaje_obtenido`, `fecha_registro`, `estado_validacion`) VALUES
(1, 4, 1, NULL, 15, '2025-11-24 02:12:56', 'aprobado'),
(2, 4, 2, NULL, 10, '2025-11-24 02:12:56', 'rechazado'),
(3, 2, 2, NULL, 8, '2025-11-24 02:14:05', 'pendiente'),
(4, 4, 2, NULL, 20, '2025-11-24 03:06:35', 'aprobado'),
(5, 7, 3, NULL, 25, '2025-11-21 10:00:00', 'pendiente'),
(6, 7, 4, NULL, 30, '2025-11-21 11:30:00', 'aprobado'),
(7, 7, 3, NULL, 18, '2025-11-22 09:15:00', 'rechazado'),
(8, 7, 5, NULL, 35, '2025-11-22 14:20:00', 'aprobado'),
(9, 7, 6, NULL, 22, '2025-11-23 16:45:00', 'pendiente'),
(12, 8, 3, NULL, 40, '2025-12-11 08:00:00', 'aprobado'),
(13, 8, 4, NULL, 38, '2025-12-11 09:30:00', 'aprobado'),
(14, 8, 5, NULL, 32, '2025-12-12 10:15:00', 'aprobado'),
(15, 8, 6, NULL, 28, '2025-12-12 11:00:00', 'rechazado'),
(16, 8, 7, NULL, 45, '2025-12-13 13:20:00', 'aprobado'),
(19, 9, 4, NULL, 20, '2025-11-16 10:00:00', 'aprobado'),
(20, 9, 5, NULL, 15, '2025-11-17 11:00:00', 'aprobado'),
(21, 9, 6, NULL, 12, '2025-11-18 12:00:00', 'pendiente'),
(22, 9, 7, NULL, 18, '2025-11-19 13:00:00', 'rechazado'),
(26, 6, NULL, 11, 120, '2025-12-02 10:00:00', 'aprobado'),
(27, 6, NULL, 13, 95, '2025-12-02 11:00:00', 'aprobado'),
(28, 6, NULL, 11, 110, '2025-12-03 14:00:00', 'pendiente'),
(29, 6, NULL, 13, 85, '2025-12-04 15:00:00', 'rechazado'),
(33, 11, 4, NULL, 15, '2025-11-26 09:00:00', 'aprobado'),
(34, 11, 5, NULL, 12, '2025-11-27 10:00:00', 'aprobado'),
(35, 11, 6, NULL, 18, '2025-11-28 11:00:00', 'pendiente'),
(36, 11, 7, NULL, 20, '2025-11-29 12:00:00', 'aprobado'),
(37, 11, 3, NULL, 10, '2025-11-30 13:00:00', 'rechazado'),
(40, 13, 4, NULL, 8, '2025-11-11 10:00:00', 'aprobado'),
(41, 13, 5, NULL, 6, '2025-11-12 11:00:00', 'aprobado'),
(42, 13, 6, NULL, 9, '2025-11-13 12:00:00', 'pendiente'),
(43, 13, 7, NULL, 7, '2025-11-14 13:00:00', 'rechazado'),
(47, 10, NULL, 12, 150, '2025-12-06 10:00:00', 'aprobado'),
(48, 10, NULL, 14, 130, '2025-12-07 11:00:00', 'aprobado'),
(49, 10, NULL, 12, 140, '2025-12-08 12:00:00', 'pendiente'),
(50, 10, NULL, 14, 125, '2025-12-09 13:00:00', 'rechazado'),
(54, 12, NULL, 12, 180, '2025-12-02 10:00:00', 'aprobado'),
(55, 12, NULL, 14, 165, '2025-12-03 11:00:00', 'aprobado'),
(56, 12, NULL, 12, 170, '2025-12-04 12:00:00', 'pendiente'),
(57, 12, NULL, 14, 160, '2025-12-05 13:00:00', 'rechazado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ranking_equipo`
--

CREATE TABLE `ranking_equipo` (
  `id_ranking_eq` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `puntaje` int(11) DEFAULT 0,
  `posicion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ranking_equipo`
--

INSERT INTO `ranking_equipo` (`id_ranking_eq`, `id_equipo`, `id_juego`, `puntaje`, `posicion`) VALUES
(1, 7, 1, 200, 2),
(2, 6, 1, 150, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ranking_individual`
--

CREATE TABLE `ranking_individual` (
  `id_ranking_indiv` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `puntaje` int(11) DEFAULT 0,
  `posicion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ranking_individual`
--

INSERT INTO `ranking_individual` (`id_ranking_indiv`, `id_usuario`, `id_juego`, `puntaje`, `posicion`) VALUES
(1, 7, 1, 100, 5),
(2, 10, 2, 80, 7);

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

--
-- Volcado de datos para la tabla `respuesta_ticket`
--

INSERT INTO `respuesta_ticket` (`id_respuesta`, `id_ticket`, `id_admin`, `respuesta`, `fecha_respuesta`) VALUES
(1, 1, 1, 'Tu cuenta estaba bloqueada temporalmente. Ya fue desbloqueada.', '2025-11-24 12:04:26'),
(2, 2, 1, 'Se corrigió el error del formulario, vuelve a intentarlo por favor.', '2025-11-24 12:04:26'),
(3, 3, 1, 'Tu correo fue actualizado en el sistema.', '2025-11-24 12:04:26'),
(4, 4, 1, 'Gracias por el aviso. El usuario fue advertido.', '2025-11-24 12:04:26'),
(5, 5, 1, 'El puntaje ya fue validado y debería aparecer en tu perfil.', '2025-11-24 12:04:26'),
(6, 7, 1, 'hol! no te deja ya que no hay mas cupos disponibles', '2025-11-24 12:08:06'),
(7, 6, 1, 'recarga la pagina', '2025-11-24 13:11:35');

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
(1, 18),
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
(3, 17);

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
(1, 7, 'Valorant Cup', 'Torneo amistoso de Valorant con equipos de 5 jugadores', 1, '2025-12-01', '2025-12-05', 2, '2025-11-24 11:16:38', 'aprobado'),
(2, 10, 'LoL Championship Solo', 'Competencia individual de League of Legends', 2, '2025-12-10', '2025-12-15', 1, '2025-11-24 11:16:38', 'rechazado'),
(3, 12, 'LoL Team Clash', 'Torneo competitivo por equipos de LoL', 2, '2025-12-20', '2025-12-25', 2, '2025-11-24 11:16:38', 'pendiente'),
(5, 19, 'Valorant Clash Series', 'Formato suizo con premio a los tres mejores equipos.', 1, '2026-01-10', '2026-01-15', 2, '2025-11-24 11:23:37', 'rechazado'),
(6, 20, 'LoL Solo Masters', 'Torneo 1vs1 enfocado en mid-laners.', 2, '2026-01-20', '2026-01-23', 1, '2025-11-24 11:23:37', 'pendiente'),
(7, 21, 'Valorant Rookie Cup', 'Para jugadores con menos de 6 meses de experiencia.', 1, '2025-12-05', '2025-12-07', 2, '2025-11-24 11:23:37', 'aprobado');

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
  `id_equipo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `fecha_solicitud` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 7, 'No puedo acceder a mi cuenta', 'Intento iniciar sesión pero me dice usuario bloqueado.', '2025-11-24 12:04:09'),
(2, 10, 'Error al inscribirme a un torneo', 'Me aparece mensaje de error al enviar la solicitud.', '2025-11-24 12:04:09'),
(3, 13, 'Cambiar correo electrónico', 'Quiero actualizar mi email de registro.', '2025-11-24 12:04:09'),
(4, 22, 'Reporte de comportamiento tóxico', 'Jugador con ID 24 insultó durante un partido.', '2025-11-24 12:04:09'),
(5, 19, 'Problema con verificación de puntaje', 'Mi puntaje del torneo no aparece actualizado.', '2025-11-24 12:04:09'),
(6, 7, 'No se carga mi perfil', 'Cuando entro a mi perfil aparece en blanco.', '2025-11-24 12:07:28'),
(7, 10, 'Problema con un torneo', 'Intento inscribirme al torneo LoL Championship y no me deja.', '2025-11-24 12:07:28'),
(8, 13, 'Error al actualizar biografía', 'Al guardar la bio me sale un error 500.', '2025-11-24 12:07:28'),
(9, 22, 'Reporte de jugador', 'Quiero reportar comportamiento pero el formulario no funciona.', '2025-11-24 12:07:28'),
(10, 19, 'No puedo ver mis puntajes', 'Los puntajes más recientes no aparecen en mi ranking.', '2025-11-24 12:07:28');

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
  `id_tipo` int(11) DEFAULT NULL,
  `bloqueado_hasta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `torneo`
--

INSERT INTO `torneo` (`id_torneo`, `id_organizador`, `id_juego`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `id_estado`, `id_tipo`, `bloqueado_hasta`) VALUES
(1, 1, 1, 'Torneo de Valorant', 'Torneo amistoso', '2025-12-01', '2025-12-10', 1, 2, NULL),
(2, 1, 2, 'LoL Championship', 'Torneo competitivo', '2025-12-05', '2025-12-15', 3, 1, '2025-12-09'),
(3, 2, 2, 'Torneo LOL', NULL, NULL, NULL, 3, 2, '2025-12-09'),
(4, 3, 1, 'Odyssey', NULL, NULL, NULL, 1, 1, NULL),
(6, 1, 1, 'Valorant Championship 2025', 'Torneo principal de Valorant con los mejores equipos', '2025-12-01', '2025-12-15', 1, 2, NULL),
(7, 1, 1, 'Valorant Open Cup', 'Torneo abierto para todos los niveles', '2025-11-20', '2025-11-30', 1, 1, NULL),
(8, 2, 1, 'Valorant Masters', 'Torneo de élite para jugadores profesionales', '2025-12-10', '2025-12-20', 1, 1, NULL),
(9, 3, 1, 'Valorant Rookie Tournament', 'Torneo para nuevos jugadores', '2025-11-15', '2025-11-25', 1, 1, NULL),
(10, 1, 2, 'LoL Summer Championship', 'Campeonato de verano de League of Legends', '2025-12-05', '2025-12-18', 1, 2, NULL),
(11, 2, 2, 'LoL Solo Queue Tournament', 'Torneo individual de LoL', '2025-11-25', '2025-12-05', 1, 1, NULL),
(12, 3, 2, 'LoL Pro League', 'Liga profesional de League of Legends', '2025-12-01', '2025-12-20', 1, 2, NULL),
(13, 1, 2, 'LoL Rookie Challenge', 'Desafío para nuevos jugadores de LoL', '2025-11-10', '2025-11-20', 1, 1, NULL);

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
  `contrasena` varchar(20) NOT NULL,
  `fecha_registro` date NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `bloqueado_hasta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `contrasena`, `fecha_registro`, `id_estado`, `bloqueado_hasta`) VALUES
(1, 'admin@email.com', 'pass123', '2025-10-31', 1, NULL),
(2, 'mail@upe.com', 'pass123', '2025-10-31', 1, NULL),
(3, 'progamer@example.com', 'pass123', '2025-11-11', 1, NULL),
(4, 'game123@mail.com', '$2y$10$gdj2t/YIV7Z3G', '2025-11-17', 3, '2025-12-09'),
(5, 'vik09@email.com', '$2y$10$LNeY2VulE40Yo', '2025-11-17', 1, NULL),
(6, 'admin@upesport.com', '1234', '0000-00-00', 1, NULL),
(7, 'juan@upesport.com', '1234', '0000-00-00', 1, NULL),
(8, 'maria@upesport.com', '1234', '0000-00-00', 3, '2025-12-09'),
(9, 'pedro@upesport.com', '1234', '0000-00-00', 1, NULL),
(10, 'sofia@upesport.com', '1234', '0000-00-00', 1, NULL),
(11, 'jugador1@test.com', '$2y$10$test123456789', '2025-10-15', 1, NULL),
(12, 'jugador2@test.com', '$2y$10$test123456789', '2025-10-20', 1, NULL),
(13, 'jugador3@test.com', '$2y$10$test123456789', '2025-10-25', 1, NULL),
(14, 'jugador4@test.com', '$2y$10$test123456789', '2025-11-01', 1, NULL),
(15, 'jugador5@test.com', '$2y$10$test123456789', '2025-11-05', 1, NULL),
(16, 'organizador1@test.com', '$2y$10$test123456789', '2025-09-10', 1, NULL),
(17, 'organizador2@test.com', '$2y$10$test123456789', '2025-09-15', 1, NULL),
(18, 'organizador3@test.com', '$2y$10$test123456789', '2025-09-20', 1, NULL),
(19, 'postulante1@upesport.com', '1234', '2025-11-10', 1, NULL),
(20, 'postulante2@upesport.com', '1234', '2025-11-11', 1, NULL),
(21, 'postulante3@upesport.com', '1234', '2025-11-12', 1, NULL),
(22, 'denunciante1@mail.com', '1234', '2025-11-05', 1, NULL),
(23, 'denunciante2@mail.com', '1234', '2025-11-06', 1, NULL),
(24, 'denunciadoextra@mail.com', '1234', '2025-11-07', 3, '2025-12-09');

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
(2, 2, 2),
(3, 4, 2),
(10, 5, 1),
(4, 5, 2),
(11, 6, 2),
(12, 7, 2),
(13, 8, 2),
(14, 9, 2),
(15, 11, 2),
(16, 12, 2),
(17, 13, 2),
(18, 14, 2),
(19, 15, 2),
(20, 16, 3),
(21, 17, 3),
(22, 18, 3),
(30, 19, 2),
(31, 20, 2),
(32, 21, 2),
(34, 22, 2),
(35, 23, 2),
(33, 24, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `validacion_puntaje`
--

CREATE TABLE `validacion_puntaje` (
  `id_validacion` int(11) NOT NULL,
  `id_puntaje` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `fecha_validacion` datetime NOT NULL DEFAULT current_timestamp(),
  `resultado` enum('aprobado','rechazado') NOT NULL,
  `observaciones` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `validacion_puntaje`
--

INSERT INTO `validacion_puntaje` (`id_validacion`, `id_puntaje`, `id_admin`, `fecha_validacion`, `resultado`, `observaciones`) VALUES
(1, 1, 1, '2025-11-24 02:24:53', 'rechazado', 'El puntaje no coincide con el acta oficial'),
(2, 1, 1, '2025-11-24 02:54:32', 'aprobado', NULL),
(3, 1, 1, '2025-11-24 02:55:16', 'rechazado', NULL),
(4, 1, 1, '2025-11-24 02:55:21', 'aprobado', NULL),
(5, 1, 1, '2025-11-24 02:55:24', 'aprobado', NULL),
(6, 2, 1, '2025-11-24 03:05:20', 'rechazado', NULL),
(7, 4, 1, '2025-11-24 03:07:29', 'aprobado', NULL),
(8, 14, 1, '2025-11-24 03:30:26', 'aprobado', NULL);

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
-- Indices de la tabla `categoria_juego`
--
ALTER TABLE `categoria_juego`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `fk_categoria_juego` (`id_juego`);

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
  ADD KEY `fk_denuncia_organizador` (`id_organizador`);

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
-- Indices de la tabla `estado_torneo`
--
ALTER TABLE `estado_torneo`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `inscripcion_torneo`
--
ALTER TABLE `inscripcion_torneo`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD KEY `fk_inscripcion_torneo` (`id_torneo`),
  ADD KEY `fk_inscripcion_jugador` (`id_jugador`),
  ADD KEY `fk_inscripcion_equipo` (`id_equipo`);

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
  ADD KEY `fk_jugador_rol` (`id_rol`);

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
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `puntaje_torneo`
--
ALTER TABLE `puntaje_torneo`
  ADD PRIMARY KEY (`id_puntaje`),
  ADD KEY `fk_puntaje_torneo` (`id_torneo`),
  ADD KEY `fk_puntaje_jugador` (`id_jugador`),
  ADD KEY `fk_puntaje_equipo` (`id_equipo`);

--
-- Indices de la tabla `ranking_equipo`
--
ALTER TABLE `ranking_equipo`
  ADD PRIMARY KEY (`id_ranking_eq`),
  ADD KEY `fk_ranking_equipo` (`id_equipo`),
  ADD KEY `fk_ranking_juego_eq` (`id_juego`);

--
-- Indices de la tabla `ranking_individual`
--
ALTER TABLE `ranking_individual`
  ADD PRIMARY KEY (`id_ranking_indiv`),
  ADD KEY `fk_ranking_usuario` (`id_usuario`),
  ADD KEY `fk_ranking_juego` (`id_juego`);

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
-- Indices de la tabla `validacion_puntaje`
--
ALTER TABLE `validacion_puntaje`
  ADD PRIMARY KEY (`id_validacion`),
  ADD KEY `fk_validacion_puntaje` (`id_puntaje`),
  ADD KEY `fk_validacion_admin` (`id_admin`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categoria_juego`
--
ALTER TABLE `categoria_juego`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `id_denuncia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_equipo`
--
ALTER TABLE `estado_equipo`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_torneo`
--
ALTER TABLE `estado_torneo`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inscripcion_torneo`
--
ALTER TABLE `inscripcion_torneo`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `juego`
--
ALTER TABLE `juego`
  MODIFY `id_juego` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `jugador`
--
ALTER TABLE `jugador`
  MODIFY `id_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `membresia`
--
ALTER TABLE `membresia`
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `miembros_equipo`
--
ALTER TABLE `miembros_equipo`
  MODIFY `id_miembro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `organizador`
--
ALTER TABLE `organizador`
  MODIFY `id_organizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `puntaje_torneo`
--
ALTER TABLE `puntaje_torneo`
  MODIFY `id_puntaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `ranking_equipo`
--
ALTER TABLE `ranking_equipo`
  MODIFY `id_ranking_eq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ranking_individual`
--
ALTER TABLE `ranking_individual`
  MODIFY `id_ranking_indiv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `respuesta_ticket`
--
ALTER TABLE `respuesta_ticket`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitud_creacion_torneo`
--
ALTER TABLE `solicitud_creacion_torneo`
  MODIFY `id_solicitud_creacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `solicitud_equipo`
--
ALTER TABLE `solicitud_equipo`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_torneo`
--
ALTER TABLE `solicitud_torneo`
  MODIFY `id_solicitud_torneo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipo_torneo`
--
ALTER TABLE `tipo_torneo`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `torneo`
--
ALTER TABLE `torneo`
  MODIFY `id_torneo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `id_usuario_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `validacion_puntaje`
--
ALTER TABLE `validacion_puntaje`
  MODIFY `id_validacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `categoria_juego`
--
ALTER TABLE `categoria_juego`
  ADD CONSTRAINT `fk_categoria_juego` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_denuncia_organizador` FOREIGN KEY (`id_organizador`) REFERENCES `organizador` (`id_organizador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_denuncia_reportado` FOREIGN KEY (`id_reportado`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_denuncia_reportador` FOREIGN KEY (`id_reportador`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `fk_equipo_capitan` FOREIGN KEY (`id_capitan_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_equipo_juego` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripcion_torneo`
--
ALTER TABLE `inscripcion_torneo`
  ADD CONSTRAINT `fk_inscripcion_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscripcion_jugador` FOREIGN KEY (`id_jugador`) REFERENCES `jugador` (`id_jugador`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscripcion_torneo` FOREIGN KEY (`id_torneo`) REFERENCES `torneo` (`id_torneo`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `puntaje_torneo`
--
ALTER TABLE `puntaje_torneo`
  ADD CONSTRAINT `fk_puntaje_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_puntaje_jugador` FOREIGN KEY (`id_jugador`) REFERENCES `jugador` (`id_jugador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_puntaje_torneo` FOREIGN KEY (`id_torneo`) REFERENCES `torneo` (`id_torneo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ranking_equipo`
--
ALTER TABLE `ranking_equipo`
  ADD CONSTRAINT `fk_ranking_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ranking_juego_eq` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ranking_individual`
--
ALTER TABLE `ranking_individual`
  ADD CONSTRAINT `fk_ranking_juego` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ranking_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Filtros para la tabla `validacion_puntaje`
--
ALTER TABLE `validacion_puntaje`
  ADD CONSTRAINT `fk_validacion_admin` FOREIGN KEY (`id_admin`) REFERENCES `administrador` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_validacion_puntaje` FOREIGN KEY (`id_puntaje`) REFERENCES `puntaje_torneo` (`id_puntaje`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;