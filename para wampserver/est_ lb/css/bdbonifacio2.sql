-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-12-2013 a las 09:42:33
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `bdbonifacio2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE IF NOT EXISTS `estados` (
  `ESTAID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ESTANOMB` varchar(50) NOT NULL COMMENT 'NOMBRE',
  `ESTDCOLO` varchar(50) NOT NULL COMMENT 'COLOR',
  `ESTAFLAG` varchar(2) NOT NULL COMMENT 'ACTIVO "A" INACTIVO "I""',
  PRIMARY KEY (`ESTAID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`ESTAID`, `ESTANOMB`, `ESTDCOLO`, `ESTAFLAG`) VALUES
(1, 'EN ESPERA', '', 'A'),
(2, 'FINALIZADO', '', 'A'),
(3, 'LLAMANDO', '', 'A'),
(4, 'NO ATENDIDO', '', 'A'),
(5, 'sincronizado', '', 'A'),
(6, 'ATENDIENDO', '', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE IF NOT EXISTS `modulos` (
  `MODUID` int(11) NOT NULL AUTO_INCREMENT,
  `MODUNOMB` varchar(50) NOT NULL,
  `MODUTIPO` int(1) NOT NULL DEFAULT '1' COMMENT '1 CITAS MEDICAS 2 LABORATORIOS',
  `MODUTUMA` int(3) NOT NULL DEFAULT '25' COMMENT 'NUMERO DE TURNOS EN LA MAÑANA',
  `MODUTUTA` int(3) NOT NULL DEFAULT '25' COMMENT 'NUMERO DE TURNOS EN LA TARDE',
  `MODUCOLO` varchar(10) NOT NULL COMMENT 'COLOR DEL MODULO',
  PRIMARY KEY (`MODUID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`MODUID`, `MODUNOMB`, `MODUTIPO`, `MODUTUMA`, `MODUTUTA`, `MODUCOLO`) VALUES
(1, 'PROMOCION Y PREVENCION', 1, 25, 25, '#7BEF5B'),
(2, 'ATENCION CLIENTE', 1, 100, 100, '#E3EA59'),
(3, 'CITAS MEDICAS', 1, 100, 100, '#1343F2'),
(4, 'ODONTOLOGIA', 1, 50, 50, '#FF6600'),
(5, 'LABORATORIOS 1', 2, 50, 50, ''),
(6, 'LABORATORIOS 2', 2, 50, 50, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
  `PARACONS` int(11) NOT NULL AUTO_INCREMENT COMMENT 'CONSECUTIVO',
  `PARANOMB` varchar(100) NOT NULL COMMENT 'NOMBRE DEL PARAMETRO',
  `PARAVALO` varchar(50) NOT NULL COMMENT 'VALOR',
  `PARAFECH` datetime NOT NULL COMMENT 'FECHA',
  `PARAIDUS` int(11) NOT NULL COMMENT 'USUARIO',
  `PARAIDMO` int(11) NOT NULL COMMENT 'MODULO',
  PRIMARY KEY (`PARACONS`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`PARACONS`, `PARANOMB`, `PARAVALO`, `PARAFECH`, `PARAIDUS`, `PARAIDMO`) VALUES
(1, 'sincronizar', '1', '2013-12-30 00:16:09', 3, 4),
(2, 'sincronizar', '4', '2013-12-30 00:26:56', 3, 4),
(3, 'sincronizar', '51', '2013-12-30 00:30:56', 3, 4),
(4, 'sincronizar', '11', '2013-12-30 00:42:06', 1, 1),
(5, 'sincronizar', '', '2013-12-30 02:15:04', 3, 4),
(6, 'sincronizar', '', '2013-12-30 02:15:11', 3, 4),
(7, 'sincronizar', '', '2013-12-30 02:15:56', 3, 4),
(8, 'sincronizar', '5', '2013-12-30 02:19:08', 3, 4),
(9, 'sincronizar', '4', '2013-12-30 02:23:25', 3, 4),
(10, 'sincronizar', '3', '2013-12-30 02:34:39', 3, 4),
(11, 'sincronizar', '13', '2013-12-30 02:35:01', 3, 4),
(12, 'sincronizar', '23', '2013-12-30 02:37:01', 3, 4),
(13, 'sincronizar', '54', '2013-12-30 02:37:52', 3, 4),
(14, 'sincronizar', '12', '2013-12-30 02:48:41', 3, 4),
(15, 'sincronizar', '12', '2013-12-30 02:48:43', 3, 4),
(16, 'sincronizar', '12', '2013-12-30 02:48:45', 3, 4),
(17, 'sincronizar', '12', '2013-12-30 02:48:46', 3, 4),
(18, 'sincronizar', '4', '2013-12-30 02:48:55', 3, 4),
(19, 'sincronizar', '3', '2013-12-30 02:50:16', 3, 4),
(20, 'sincronizar', '3', '2013-12-30 02:52:19', 3, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE IF NOT EXISTS `personas` (
  `PERSCONS` int(11) NOT NULL AUTO_INCREMENT COMMENT 'CONSECUTIVO',
  `PERSCEDU` varchar(20) NOT NULL COMMENT 'CEDULA',
  `PERSNOMB` varchar(50) NOT NULL COMMENT 'NOMBRE',
  `PERSNUCE` varchar(50) NOT NULL COMMENT 'CELULAR',
  PRIMARY KEY (`PERSCONS`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`PERSCONS`, `PERSCEDU`, `PERSNOMB`, `PERSNUCE`) VALUES
(1, '123456789', 'JUAN CAMILO DIAZ', '3152347658'),
(3, '1110465734', 'PABLO NERUDA', '3218765432'),
(4, '12345867', 'DIEGO YAMID', '3214658793'),
(5, '32654789', 'gabriel marquez', '3125467893'),
(6, '13456789', 'tim berners', '3210096847'),
(7, '20205040', 'Auxiliar Administrativo', '312324329');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `ROLEID` int(11) NOT NULL,
  `ROLENOMB` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`ROLEID`, `ROLENOMB`) VALUES
(1, 'AUXILIAR LABORATORIOS'),
(2, 'AUXILIAR CENTRAL CITAS'),
(3, 'orientador'),
(4, 'ADMINISTRADOR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE IF NOT EXISTS `turnos` (
  `TURNCONS` int(11) NOT NULL AUTO_INCREMENT COMMENT 'CONSECUTIVO',
  `TURNCOAS` varchar(4) NOT NULL COMMENT 'CODIGO ASIGNADO',
  `TURNMODU` varchar(5) NOT NULL COMMENT 'NUMERO MODULO',
  `TURNIDUS` int(11) NOT NULL COMMENT 'ID USUARIO',
  `TURNFECH` datetime NOT NULL COMMENT 'FECHA Y HORA de inicio',
  `TURNFEFI` datetime NOT NULL COMMENT 'FECHA Y HORA FIN',
  `TURNTURN` int(1) NOT NULL DEFAULT '1' COMMENT '1 MAÑANA, 2 TARDE',
  `TURNIDES` int(11) NOT NULL COMMENT 'ID ESTADO TURNO',
  `TURNPARA` varchar(50) NOT NULL COMMENT 'PARAMETRO VALOR',
  `TURNFELL` datetime NOT NULL COMMENT 'HORA Y FECHA DE LLAMADO',
  PRIMARY KEY (`TURNCONS`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`TURNCONS`, `TURNCOAS`, `TURNMODU`, `TURNIDUS`, `TURNFECH`, `TURNFEFI`, `TURNTURN`, `TURNIDES`, `TURNPARA`, `TURNFELL`) VALUES
(1, '1', '4', 3, '2013-12-30 00:18:36', '2013-12-30 00:20:05', 1, 2, '1', '2013-12-30 00:16:15'),
(2, '2', '4', 3, '2013-12-30 00:22:19', '2013-12-30 00:22:22', 1, 2, '1', '2013-12-30 00:21:18'),
(3, '3', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '1', '2013-12-30 00:23:04'),
(4, '4', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '1', '2013-12-30 00:23:37'),
(5, '4', '4', 3, '2013-12-30 00:29:25', '0000-00-00 00:00:00', 1, 6, '2', '2013-12-30 00:28:54'),
(6, '51', '4', 3, '2013-12-30 00:39:11', '2013-12-30 00:39:13', 1, 2, '3', '2013-12-30 00:33:03'),
(7, '52', '4', 3, '2013-12-30 00:39:16', '2013-12-30 00:39:17', 1, 2, '3', '2013-12-30 00:39:14'),
(8, '53', '4', 3, '2013-12-30 00:39:20', '2013-12-30 00:39:22', 1, 2, '3', '2013-12-30 00:39:18'),
(9, '54', '4', 3, '2013-12-30 00:39:25', '2013-12-30 00:39:27', 1, 2, '3', '2013-12-30 00:39:23'),
(10, '55', '4', 3, '2013-12-30 00:39:29', '2013-12-30 00:39:31', 1, 2, '3', '2013-12-30 00:39:27'),
(11, '56', '4', 3, '2013-12-30 00:39:36', '2013-12-30 00:39:38', 1, 2, '3', '2013-12-30 00:39:32'),
(12, '57', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '3', '2013-12-30 00:39:41'),
(13, '11', '1', 1, '2013-12-30 00:42:13', '2013-12-30 00:42:15', 1, 2, '4', '2013-12-30 00:42:08'),
(14, '12', '1', 1, '2013-12-30 00:42:18', '2013-12-30 00:42:19', 1, 2, '4', '2013-12-30 00:42:16'),
(15, '13', '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '4', '2013-12-30 00:42:20'),
(16, '', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 5, '5', '0000-00-00 00:00:00'),
(17, '', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '6', '2013-12-30 02:15:12'),
(18, '', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 5, '7', '0000-00-00 00:00:00'),
(19, '5', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '8', '2013-12-30 02:19:12'),
(20, '4', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '9', '2013-12-30 02:23:28'),
(21, '1', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '9', '2013-12-30 02:23:30'),
(22, '2', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '9', '2013-12-30 02:23:36'),
(23, '3', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '9', '2013-12-30 02:23:37'),
(24, '3', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '10', '2013-12-30 02:34:41'),
(25, '1', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '10', '2013-12-30 02:34:43'),
(26, '2', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '10', '2013-12-30 02:34:51'),
(27, '3', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '10', '2013-12-30 02:34:51'),
(28, '4', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '10', '2013-12-30 02:34:52'),
(29, '13', '4', 3, '2013-12-30 02:35:05', '2013-12-30 02:35:06', 1, 2, '11', '2013-12-30 02:35:03'),
(30, '14', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '11', '2013-12-30 02:35:07'),
(31, '15', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '11', '2013-12-30 02:35:09'),
(32, '16', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '11', '2013-12-30 02:35:10'),
(33, '17', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '11', '2013-12-30 02:35:11'),
(34, '23', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:03'),
(35, '24', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:05'),
(36, '25', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:06'),
(37, '26', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:07'),
(38, '27', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:08'),
(39, '28', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:09'),
(40, '29', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:10'),
(41, '30', '4', 3, '2013-12-30 02:37:17', '2013-12-30 02:37:19', 1, 2, '12', '2013-12-30 02:37:11'),
(42, '31', '4', 3, '2013-12-30 02:37:23', '2013-12-30 02:37:24', 1, 2, '12', '2013-12-30 02:37:20'),
(43, '32', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:25'),
(44, '33', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:27'),
(45, '34', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:28'),
(46, '35', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:29'),
(47, '36', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:29'),
(48, '37', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:30'),
(49, '38', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '12', '2013-12-30 02:37:31'),
(50, '39', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '12', '2013-12-30 02:37:32'),
(51, '54', '4', 3, '2013-12-30 02:38:15', '2013-12-30 02:38:18', 1, 2, '13', '2013-12-30 02:37:55'),
(52, '55', '4', 3, '2013-12-30 02:39:06', '2013-12-30 02:39:08', 1, 2, '13', '2013-12-30 02:38:24'),
(53, '56', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '13', '2013-12-30 02:39:10'),
(54, '57', '4', 3, '2013-12-30 02:39:14', '2013-12-30 02:39:15', 1, 2, '13', '2013-12-30 02:39:12'),
(55, '58', '4', 3, '2013-12-30 02:44:22', '2013-12-30 02:44:24', 1, 2, '13', '2013-12-30 02:39:16'),
(56, '59', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '13', '2013-12-30 02:44:25'),
(57, '12', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 5, '14', '0000-00-00 00:00:00'),
(58, '12', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 5, '15', '0000-00-00 00:00:00'),
(59, '12', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 5, '16', '0000-00-00 00:00:00'),
(60, '12', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 5, '17', '0000-00-00 00:00:00'),
(61, '4', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 5, '18', '0000-00-00 00:00:00'),
(62, '3', '4', 3, '2013-12-30 02:50:32', '2013-12-30 02:50:34', 1, 2, '19', '2013-12-30 02:50:20'),
(63, '4', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '19', '2013-12-30 02:50:36'),
(64, '5', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '19', '2013-12-30 02:50:38'),
(65, '6', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 4, '19', '2013-12-30 02:50:40'),
(66, '7', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '19', '2013-12-30 02:50:41'),
(67, '3', '4', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3, '20', '2013-12-30 02:52:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `USUAID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID DEL USUARIO',
  `USUANOMB` varchar(50) NOT NULL COMMENT 'NOMBRE USUARIO',
  `USUACONT` varchar(16) NOT NULL COMMENT 'CONTRASEÑA USUARIO',
  `USUAIDPE` varchar(50) NOT NULL COMMENT 'ID PERSONAS',
  `USUAIDMO` int(11) NOT NULL COMMENT 'ID MODULOS',
  `USUAIDRO` int(11) NOT NULL COMMENT 'ID ROLES',
  PRIMARY KEY (`USUAID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`USUAID`, `USUANOMB`, `USUACONT`, `USUAIDPE`, `USUAIDMO`, `USUAIDRO`) VALUES
(1, 'usupreve', 'ABC123', '1', 3, 2),
(3, 'usuodont', '12345', '3', 6, 2),
(4, 'usuatcl', 'gatuelo', '4', 8, 2),
(5, 'usucime', 'gmar', '5', 7, 2),
(6, 'usulab1', 'timb', '6', 7, 1),
(7, 'usulab2', 'abc123', '7', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
