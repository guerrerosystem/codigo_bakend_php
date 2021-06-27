-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 26-06-2021 a las 19:09:11
-- Versión del servidor: 5.7.23-23
-- Versión de PHP: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `guerre13_demo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(28) NOT NULL,
  `permissions` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `role`, `permissions`, `created_by`, `date_created`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'guerrerosystemsac@gmail.com', 'super admin', '{\"orders\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"categories\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"subcategories\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"products\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"products_order\":{\"read\":\"1\",\"update\":\"1\"},\"featured\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"customers\":{\"read\":\"1\"},\"payment\":{\"read\":\"1\",\"update\":\"1\"},\"notifications\":{\"create\":\"1\",\"read\":\"1\",\"delete\":\"1\"},\"transactions\":{\"read\":\"1\"},\"settings\":{\"read\":\"1\",\"update\":\"1\"},\"locations\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"reports\":{\"create\":\"1\",\"read\":\"1\"},\"faqs\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"home_sliders\":{\"create\":\"1\",\"read\":\"1\",\"delete\":\"1\"},\"new_offers\":{\"create\":\"1\",\"read\":\"1\",\"delete\":\"1\"},\"promo_codes\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"delivery_boys\":{\"create\":\"1\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"return_requests\":{\"read\":\"1\",\"update\":\"1\",\"delete\":\"1\"}}', 0, '2020-06-22 16:48:25'),
(36, 'demo', 'e10adc3949ba59abbe56e057f20f883e', 'demo@gmail.com', 'admin', '{\"orders\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"0\"},\"categories\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"0\",\"delete\":\"0\"},\"subcategories\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"0\",\"delete\":\"0\"},\"products\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"0\",\"delete\":\"0\"},\"products_order\":{\"read\":\"1\",\"update\":\"1\"},\"featured\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"0\",\"delete\":\"0\"},\"customers\":{\"read\":\"1\"},\"payment\":{\"read\":\"1\",\"update\":\"0\"},\"notifications\":{\"create\":\"0\",\"read\":\"1\",\"delete\":\"0\"},\"transactions\":{\"read\":\"1\"},\"settings\":{\"read\":\"1\",\"update\":\"0\"},\"locations\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"0\"},\"reports\":{\"create\":\"0\",\"read\":\"1\"},\"faqs\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"0\"},\"home_sliders\":{\"create\":\"0\",\"read\":\"1\",\"delete\":\"0\"},\"new_offers\":{\"create\":\"0\",\"read\":\"1\",\"delete\":\"0\"},\"promo_codes\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"1\",\"delete\":\"0\"},\"delivery_boys\":{\"create\":\"0\",\"read\":\"1\",\"update\":\"0\",\"delete\":\"0\"},\"return_requests\":{\"read\":\"1\",\"update\":\"0\",\"delete\":\"0\"}}', 1, '2021-01-30 14:33:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `city_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id`, `name`, `city_id`) VALUES
(1, 'lince', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `subtitle` text NOT NULL,
  `image` text NOT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `name`, `subtitle`, `image`, `status`) VALUES
(33, 'demo1', 'demo1', 'upload/images/5128-2021-06-27.png', NULL),
(34, 'demo2', 'demo2', 'upload/images/5994-2021-06-27.png', NULL),
(35, 'demo3', 'demo3', 'upload/images/9846-2021-06-27.png', NULL),
(36, 'demo4', 'demo4', 'upload/images/6718-2021-06-27.jpg', NULL),
(37, 'demo5', 'demo5', 'upload/images/8364-2021-06-27.jpg', NULL),
(38, 'demo6', 'demo6', 'upload/images/7615-2021-06-27.png', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `city`
--

INSERT INTO `city` (`id`, `name`) VALUES
(1, 'lima');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` text NOT NULL,
  `address` text NOT NULL,
  `bonus` int(11) NOT NULL,
  `balance` double DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fcm_id` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `delivery_boys`
--

INSERT INTO `delivery_boys` (`id`, `name`, `mobile`, `password`, `address`, `bonus`, `balance`, `status`, `date_created`, `fcm_id`) VALUES
(23, 'millera', '123456789', '202cb962ac59075b964b07152d234b70', '123', 10, 14, 1, '2021-01-07 06:58:55', 'f-ArOrRCQxKn5FPQpbO3U3:APA91bEN5gqEeagKlHp2y__gmT81OJJvMwoEGwzeHELNEnGNvoWKJMsquXIHYahKXtTheV7fJitg7WZysXkWWKFXI-w31FDSqcf8YzE_f3Ecp79qODUW_Z7gpF-SXqmu6Ip8yT1M1XtZ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delivery_boy_notifications`
--

CREATE TABLE `delivery_boy_notifications` (
  `id` int(11) NOT NULL,
  `delivery_boy_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(56) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `delivery_boy_notifications`
--

INSERT INTO `delivery_boy_notifications` (`id`, `delivery_boy_id`, `order_id`, `title`, `message`, `type`, `date_created`) VALUES
(331, 20, 154, 'Su comisión 0 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #154.Su comisión de0 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-15 16:40:41'),
(330, 20, 154, 'Tu nuevo pedido ha sido Entregado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #154. Por favor toma nota de ello.', 'order_reward', '2020-10-15 16:40:41'),
(329, 20, 154, 'Tu nuevo pedido ha sido Enviado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #154. Por favor toma nota de ello.', 'order_reward', '2020-10-15 16:38:42'),
(328, 20, 154, 'Tu nuevo pedido ha sido Procesado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #154. Por favor toma nota de ello.', 'order_reward', '2020-10-15 16:37:27'),
(327, 20, 154, 'Su nuevo pedido con ID: #154  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #154. Por favor toma nota de ello.', 'order_reward', '2020-10-15 16:35:51'),
(326, 20, 149, 'Su comisión 0 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #149.Su comisión de0 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-15 13:22:26'),
(325, 20, 149, 'Tu nuevo pedido ha sido Entregado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #149. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:22:25'),
(324, 20, 148, 'Su comisión 0 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #148.Su comisión de0 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-15 13:22:15'),
(323, 20, 148, 'Tu nuevo pedido ha sido Entregado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #148. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:22:14'),
(322, 20, 147, 'Tu nuevo pedido ha sido Enviado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #147. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:22:01'),
(321, 20, 145, 'Tu nuevo pedido ha sido Procesado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #145. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:21:51'),
(320, 20, 144, 'Su comisión 0 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #144.Su comisión de0 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-15 13:21:41'),
(319, 20, 144, 'Tu nuevo pedido ha sido Entregado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #144. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:21:41'),
(318, 20, 140, 'Su comisión 0 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #140.Su comisión de0 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-15 13:21:30'),
(316, 20, 150, 'Su nuevo pedido con ID: #150  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #150. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:21:02'),
(317, 20, 140, 'Tu nuevo pedido ha sido Entregado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #140. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:21:30'),
(315, 20, 149, 'Su nuevo pedido con ID: #149  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #149. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:20:51'),
(314, 20, 148, 'Su nuevo pedido con ID: #148  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #148. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:20:31'),
(313, 20, 147, 'Su nuevo pedido con ID: #147  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #147. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:20:13'),
(312, 20, 145, 'Su nuevo pedido con ID: #145  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #145. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:20:00'),
(310, 20, 140, 'Su nuevo pedido con ID: #140  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #140. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:19:38'),
(311, 20, 144, 'Su nuevo pedido con ID: #144  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #144. Por favor toma nota de ello.', 'order_reward', '2020-10-15 13:19:51'),
(309, 20, 138, 'Su pedido ha sido Entregado', 'Hola Demo, Aquí está la nueva actualización de su pedido. ID : #138. Su pedido ha sido Entregado. Por favor toma nota de ello.', 'order_status', '2020-10-15 12:22:48'),
(308, 20, 138, 'Su comisión 0 S/. ha sido acreditado', 'Hola Demo, Aquí está la nueva actualización de su pedido para el ID de pedido: #138. Su comisión de0 es acreditado Por favor toma nota de ello.', 'order_reward', '2020-10-15 12:22:47'),
(307, 20, 138, 'Su nuevo pedido con ID: #138  ha sido Entregado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #138. Por favor toma nota de ello.', 'order_reward', '2020-10-15 12:22:47'),
(305, 20, 137, 'Tu nuevo pedido ha sido Entregado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #137. Por favor toma nota de ello.', 'order_reward', '2020-10-15 12:18:05'),
(306, 20, 137, 'Su comisión 0 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #137.Su comisión de0 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-15 12:18:05'),
(304, 20, 137, 'Su nuevo pedido con ID: #137  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #137. Por favor toma nota de ello.', 'order_reward', '2020-10-15 12:17:53'),
(332, 20, 155, 'Su nuevo pedido con ID: #155  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #155. Por favor toma nota de ello.', 'order_reward', '2020-10-16 22:45:09'),
(333, 20, 155, 'Tu nuevo pedido ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #155. Por favor toma nota de ello.', 'order_reward', '2020-10-16 22:47:42'),
(334, 20, 155, 'Tu nuevo pedido ha sido Enviado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #155. Por favor toma nota de ello.', 'order_reward', '2020-10-16 22:48:02'),
(335, 20, 150, 'Tu nuevo pedido ha sido Entregado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #150. Por favor toma nota de ello.', 'order_reward', '2020-10-18 05:14:29'),
(336, 20, 150, 'Su comisión 0 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #150.Su comisión de0 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-18 05:14:49'),
(337, 20, 156, 'Su nuevo pedido con ID: #156  ha sido Recibido', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #156. Por favor toma nota de ello.', 'order_reward', '2020-10-18 05:15:31'),
(338, 20, 156, 'Tu nuevo pedido ha sido Procesado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #156. Por favor toma nota de ello.', 'order_reward', '2020-10-18 05:17:03'),
(339, 21, 158, 'Su nuevo pedido con ID: #158  ha sido Recibido', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #158. Por favor toma nota de ello.', 'order_reward', '2020-10-18 06:44:49'),
(340, 21, 158, 'Tu nuevo pedido ha sido Entregado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #158. Por favor toma nota de ello.', 'order_reward', '2020-10-18 06:45:09'),
(341, 21, 158, 'Su comisión 0.59 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #158.Su comisión de0.59 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-18 06:45:09'),
(342, 20, 146, 'Su nuevo pedido con ID: #146  ha sido Enviado', 'Hola Demo, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #146. Por favor toma nota de ello.', 'order_reward', '2020-10-25 23:52:58'),
(343, 20, 146, 'Su pedido ha sido Enviado', 'Hola Demo, Aquí está la nueva actualización de su pedido. ID : #146. Su pedido ha sido Enviado. Por favor toma nota de ello.', 'order_status', '2020-10-25 23:52:59'),
(344, 21, 160, 'Su nuevo pedido con ID: #160  ha sido Recibido', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #160. Por favor toma nota de ello.', 'order_reward', '2020-10-26 19:05:36'),
(345, 21, 164, 'Su nuevo pedido con ID: #164  ha sido Procesado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #164. Por favor toma nota de ello.', 'order_reward', '2020-10-26 20:33:05'),
(346, 21, 164, 'Su pedido ha sido Procesado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #164. Su pedido ha sido Procesado. Por favor toma nota de ello.', 'order_status', '2020-10-26 20:33:05'),
(347, 21, 164, 'Tu nuevo pedido ha sido Entregado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #164. Por favor toma nota de ello.', 'order_reward', '2020-10-26 20:33:29'),
(348, 21, 164, 'Su comisión 0.3 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #164.Su comisión de0.3 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-26 20:33:29'),
(349, 21, 165, 'Su nuevo pedido con ID: #165  ha sido Procesado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #165. Por favor toma nota de ello.', 'order_reward', '2020-10-26 21:32:00'),
(350, 21, 165, 'Su pedido ha sido Procesado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #165. Su pedido ha sido Procesado. Por favor toma nota de ello.', 'order_status', '2020-10-26 21:32:01'),
(351, 21, 165, 'Su pedido ha sido Enviado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #165. Su pedido ha sido Enviado. Por favor toma nota de ello.', 'order_status', '2020-10-26 21:32:47'),
(352, 21, 165, 'Tu nuevo pedido ha sido Entregado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #165. Por favor toma nota de ello.', 'order_reward', '2020-10-26 21:33:23'),
(353, 21, 165, 'Su comisión 17.4 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #165.Su comisión de17.4 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-26 21:33:23'),
(354, 21, 163, 'Su nuevo pedido con ID: #163  ha sido Procesado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #163. Por favor toma nota de ello.', 'order_reward', '2020-10-26 23:52:50'),
(355, 21, 163, 'Su pedido ha sido Procesado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #163. Su pedido ha sido Procesado. Por favor toma nota de ello.', 'order_status', '2020-10-26 23:52:50'),
(356, 21, 163, 'Su pedido ha sido Enviado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #163. Su pedido ha sido Enviado. Por favor toma nota de ello.', 'order_status', '2020-10-26 23:53:13'),
(357, 21, 163, 'Tu nuevo pedido ha sido Entregado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #163. Por favor toma nota de ello.', 'order_reward', '2020-10-26 23:59:57'),
(358, 21, 163, 'Su comisión 10.6 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #163.Su comisión de10.6 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-26 23:59:57'),
(359, 21, 166, 'Su nuevo pedido con ID: #166  ha sido Enviado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #166. Por favor toma nota de ello.', 'order_reward', '2020-10-27 01:11:03'),
(360, 21, 166, 'Su pedido ha sido Enviado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #166. Su pedido ha sido Enviado. Por favor toma nota de ello.', 'order_status', '2020-10-27 01:11:03'),
(361, 21, 166, 'Tu nuevo pedido ha sido Entregado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #166. Por favor toma nota de ello.', 'order_reward', '2020-10-27 01:23:48'),
(362, 21, 166, 'Tu nuevo pedido ha sido Entregado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #166. Por favor toma nota de ello.', 'order_reward', '2020-10-27 01:23:59'),
(363, 21, 167, 'Su nuevo pedido con ID: #167  ha sido Procesado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #167. Por favor toma nota de ello.', 'order_reward', '2020-10-27 01:27:17'),
(364, 21, 167, 'Su pedido ha sido Procesado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #167. Su pedido ha sido Procesado. Por favor toma nota de ello.', 'order_status', '2020-10-27 01:27:17'),
(365, 21, 167, 'Su pedido ha sido Enviado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #167. Su pedido ha sido Enviado. Por favor toma nota de ello.', 'order_status', '2020-10-27 01:27:54'),
(366, 21, 167, 'Tu nuevo pedido ha sido Entregado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #167. Por favor toma nota de ello.', 'order_reward', '2020-10-27 01:28:16'),
(367, 21, 167, 'Su comisión 0.2 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #167.Su comisión de0.2 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-27 01:28:17'),
(368, 21, 168, 'Su nuevo pedido con ID: #168  ha sido Procesado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #168. Por favor toma nota de ello.', 'order_reward', '2020-10-27 13:57:05'),
(369, 21, 168, 'Su pedido ha sido Procesado', 'Hola Guerrero, Aquí está la nueva actualización de su pedido. ID : #168. Su pedido ha sido Procesado. Por favor toma nota de ello.', 'order_status', '2020-10-27 13:57:05'),
(370, 21, 168, 'Tu nuevo pedido ha sido Enviado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #168. Por favor toma nota de ello.', 'order_reward', '2020-10-27 14:05:23'),
(371, 21, 168, 'Tu nuevo pedido ha sido Entregado', 'Hola Guerrero, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #168. Por favor toma nota de ello.', 'order_reward', '2020-10-27 14:11:56'),
(372, 21, 168, 'Su comisión 3.7 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #168.Su comisión de3.7 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2020-10-27 14:11:56'),
(373, 23, 176, 'Su nuevo pedido con ID: #176  ha sido Procesado', 'Hola Miller, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #176. Por favor toma nota de ello.', 'order_reward', '2021-01-07 07:00:14'),
(374, 23, 176, 'Tu nuevo pedido ha sido Recibido', 'Hola Millera, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #176. Por favor toma nota de ello.', 'order_reward', '2021-02-04 03:45:40'),
(375, 23, 176, 'Tu nuevo pedido ha sido Entregado', 'Hola Millera, Tienes un nuevo pedido para entregar. Aquí está su ID de pedido: #176. Por favor toma nota de ello.', 'order_reward', '2021-02-04 03:51:39'),
(376, 23, 176, 'Su comisión 0.472 S/. ha sido acreditado ', 'Hola ,Aquí está la nueva actualización de su pedido.ID : #176.Su comisión de0.472 se le atribuye. Por favor tome nota de ello.', 'order_reward', '2021-02-04 03:51:39'),
(377, 23, 179, 'Su nuevo pedido con ID: #179  ha sido Recibido', 'Hola Millera, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #179. Por favor toma nota de ello.', 'order_reward', '2021-02-25 18:19:09'),
(378, 23, 180, 'Su nuevo pedido con ID: #180  ha sido Recibido', 'Hola Millera, Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #180. Por favor toma nota de ello.', 'order_reward', '2021-02-25 18:27:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `status` char(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fund_transfers`
--

CREATE TABLE `fund_transfers` (
  `id` int(11) NOT NULL,
  `delivery_boy_id` int(11) NOT NULL,
  `type` varchar(8) NOT NULL COMMENT 'credit | debit',
  `opening_balance` double NOT NULL,
  `closing_balance` double NOT NULL,
  `amount` double NOT NULL,
  `status` varchar(28) NOT NULL,
  `message` varchar(512) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `order_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `order_date` datetime NOT NULL,
  `phone_number` varchar(16) NOT NULL,
  `order_list` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `discount` varchar(6) NOT NULL,
  `total_sale` varchar(10) NOT NULL,
  `shipping_charge` varchar(100) NOT NULL,
  `payment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `message` varchar(512) NOT NULL,
  `type` varchar(12) NOT NULL,
  `type_id` int(11) NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `image` varchar(256) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `delivery_boy_id` int(11) DEFAULT '0',
  `mobile` varchar(15) NOT NULL,
  `total` float NOT NULL,
  `delivery_charge` float NOT NULL,
  `tax_amount` float NOT NULL DEFAULT '0',
  `tax_percentage` float NOT NULL DEFAULT '0',
  `wallet_balance` float NOT NULL,
  `discount` float NOT NULL,
  `promo_code` varchar(28) DEFAULT NULL,
  `promo_discount` float NOT NULL DEFAULT '0',
  `final_total` float DEFAULT NULL,
  `payment_method` varchar(16) NOT NULL,
  `address` text NOT NULL,
  `latitude` varchar(256) NOT NULL,
  `longitude` varchar(256) NOT NULL,
  `delivery_time` varchar(128) NOT NULL,
  `status` varchar(1024) NOT NULL,
  `active_status` varchar(16) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `delivery_boy_id`, `mobile`, `total`, `delivery_charge`, `tax_amount`, `tax_percentage`, `wallet_balance`, `discount`, `promo_code`, `promo_discount`, `final_total`, `payment_method`, `address`, `latitude`, `longitude`, `delivery_time`, `status`, `active_status`, `date_added`) VALUES
(190, 33, 0, '972805092', 1, 0, 0.18, 18, 0, 0, '-', 0, 1.18, 'Efectivo', 'lima, , , 001, entregar a esta direccion ', '-12.080082519093398', '-76.97351362556218', 'Hoy - hoy de 6:am a 9 pm', '[[\"recibido\",\"26-06-2021 06:02:19pm\"],[\"procesado\",\"26-06-2021 06:22:38pm\"],[\"enviado\",\"26-06-2021 06:26:47pm\"]]', 'enviado', '2021-06-26 23:02:19'),
(191, 33, 0, '972805092', 1, 0, 0.18, 18, 0, 0, '-', 0, 1.18, 'Efectivo', 'lima, lince, lima, 001, entregar a esta direccion ', '-12.080082519093398', '-76.97351362556218', 'Hoy - hoy de 6:am a 9 pm', '[[\"recibido\",\"26-06-2021 06:50:37pm\"]]', 'recibido', '2021-06-26 23:50:37'),
(192, 33, 0, '972805092', 1, 0, 0.18, 18, 0, 0, '-', 0, 1.18, 'Efectivo', 'lima, lince, lima, 001, entregar a esta direccion ', '-12.080082519093398', '-76.97351362556218', 'Hoy - hoy de 6:am a 9 pm', '[[\"recibido\",\"26-06-2021 06:52:13pm\"]]', 'recibido', '2021-06-26 23:52:13'),
(193, 33, 0, '972805092', 1, 0, 0.18, 18, 0, 0, '-', 0, 1.18, 'Efectivo', 'lima, lince, lima, 001, entregar a esta direccion ', '-12.080082519093398', '-76.97351362556218', 'Hoy - hoy de 6:am a 9 pm', '[[\"recibido\",\"26-06-2021 06:55:29pm\"]]', 'recibido', '2021-06-26 23:55:29'),
(194, 33, 0, '972805092', 1, 0, 0.18, 18, 0, 0, '-', 0, 1.18, 'Efectivo', 'lima, lince, lima, 001, entregar a esta direccion ', '-12.080082519093398', '-76.97351362556218', 'Hoy - hoy de 6:am a 9 pm', '[[\"recibido\",\"26-06-2021 06:56:54pm\"]]', 'recibido', '2021-06-26 23:56:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_variant_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` float NOT NULL,
  `discounted_price` double NOT NULL,
  `discount` float NOT NULL,
  `sub_total` float NOT NULL,
  `deliver_by` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `status` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `active_status` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `order_items`
--

INSERT INTO `order_items` (`id`, `user_id`, `order_id`, `product_variant_id`, `quantity`, `price`, `discounted_price`, `discount`, `sub_total`, `deliver_by`, `status`, `active_status`, `date_added`) VALUES
(179, 33, 190, 130, 1, 12, 1, 0, 1, NULL, '[[\"recibido\",\"26-06-2021 06:02:19pm\"],[\"procesado\",\"26-06-2021 06:22:38pm\"],[\"enviado\",\"26-06-2021 06:26:47pm\"]]', 'enviado', '2021-06-26 23:02:19'),
(180, 33, 191, 130, 1, 12, 1, 0, 1, NULL, '[[\"recibido\",\"26-06-2021 06:50:37pm\"]]', 'recibido', '2021-06-26 23:50:37'),
(181, 33, 192, 130, 1, 12, 1, 0, 1, NULL, '[[\"recibido\",\"26-06-2021 06:52:13pm\"]]', 'recibido', '2021-06-26 23:52:13'),
(182, 33, 193, 130, 1, 12, 1, 0, 1, NULL, '[[\"recibido\",\"26-06-2021 06:55:29pm\"]]', 'recibido', '2021-06-26 23:55:29'),
(183, 33, 194, 130, 1, 12, 1, 0, 1, NULL, '[[\"recibido\",\"26-06-2021 06:56:54pm\"]]', 'recibido', '2021-06-26 23:56:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_requests`
--

CREATE TABLE `payment_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_type` varchar(56) NOT NULL,
  `payment_address` varchar(1024) NOT NULL,
  `amount_requested` int(11) NOT NULL,
  `remarks` varchar(512) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `row_order` int(11) NOT NULL DEFAULT '0',
  `name` varchar(256) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `indicator` tinyint(4) DEFAULT NULL COMMENT '0 - none | 1 - veg | 2 - non-veg',
  `image` text NOT NULL,
  `other_images` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `status` int(2) DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `row_order`, `name`, `slug`, `category_id`, `subcategory_id`, `indicator`, `image`, `other_images`, `description`, `status`, `date_added`) VALUES
(125, 0, 'demo1', 'demo1-1', 33, 64, NULL, 'upload/images/8280-2021-06-27.png', '[\"upload/other_images/1624750966.712.png\",\"upload/other_images/1624751002.9486.png\",\"upload/other_images/1624751017.3498.jpg\"]', '<p>gg</p>\r\n', 1, '2021-06-26 02:11:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_variant`
--

CREATE TABLE `product_variant` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` varchar(16) NOT NULL,
  `measurement` int(11) NOT NULL,
  `measurement_unit_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `discounted_price` int(11) NOT NULL,
  `serve_for` varchar(16) NOT NULL,
  `stock` float NOT NULL,
  `stock_unit_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `product_variant`
--

INSERT INTO `product_variant` (`id`, `product_id`, `type`, `measurement`, `measurement_unit_id`, `price`, `discounted_price`, `serve_for`, `stock`, `stock_unit_id`) VALUES
(130, 125, 'packet', 1, 1, 12, 1, 'disponible', 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(11) NOT NULL,
  `promo_code` varchar(28) NOT NULL,
  `message` varchar(512) NOT NULL,
  `start_date` varchar(28) NOT NULL,
  `end_date` varchar(28) NOT NULL,
  `no_of_users` int(11) NOT NULL,
  `minimum_order_amount` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `discount_type` varchar(28) NOT NULL,
  `max_discount_amount` int(11) NOT NULL,
  `repeat_usage` tinyint(4) NOT NULL,
  `no_of_repeat_usage` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `return_requests`
--

CREATE TABLE `return_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_variant_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `remarks` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `return_requests`
--

INSERT INTO `return_requests` (`id`, `user_id`, `product_id`, `product_variant_id`, `order_id`, `order_item_id`, `status`, `remarks`, `date_created`) VALUES
(11, 4, 90, 92, 148, 101, 1, '', '2020-10-15 13:27:26'),
(10, 4, 93, 95, 149, 102, 2, '', '2020-10-15 13:25:10'),
(9, 15, 84, 86, 137, 85, 1, '', '2020-10-15 12:19:51'),
(8, 15, 83, 85, 137, 84, 1, '', '2020-10-15 12:19:44'),
(7, 4, 70, 72, 120, 63, 0, NULL, '2020-09-28 18:09:26'),
(12, 32, 70, 72, 183, 159, 0, NULL, '2021-05-19 03:22:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `short_description` varchar(64) NOT NULL,
  `style` varchar(16) NOT NULL,
  `product_ids` varchar(1024) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seller`
--

CREATE TABLE `seller` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(32) NOT NULL,
  `company_name` varchar(64) NOT NULL,
  `personal_address` text NOT NULL,
  `company_address` text NOT NULL,
  `dob` date NOT NULL,
  `account_details` text NOT NULL,
  `password` varchar(32) NOT NULL,
  `gst_no` varchar(16) NOT NULL,
  `pan_no` varchar(16) NOT NULL,
  `status` varchar(8) NOT NULL,
  `commission` varchar(8) DEFAULT NULL,
  `balance` int(11) NOT NULL,
  `last_login_ip` varchar(32) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `variable` text NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `variable`, `value`) VALUES
(6, 'logo', 'logo.png'),
(9, 'privacy_policy', '<h2>AUTORIZACI&Oacute;N PARA EL TRATAMIENTO DE DATOS PERSONALES - SECCI&Oacute;N ESCRIBENOS</h2>\r\n\r\n<p>De conformidad con lo dispuesto en la Ley N&deg; 29733, Ley de Protecci&oacute;n de Datos Personales y su Reglamento, (&quot;Tiendas Tambo SAC&quot;) informa al cliente de que los datos de car&aacute;cter personal que ha facilitado a la Empresa y los que facilite en el futuro en el contexto de atender la solicitud ingresada mediante nuestra web (&quot;Datos Personales&quot;) ser&aacute;n incorporados de forma indeterminada en el banco de datos personales de Tiendas Tambo, ubicado en Avenida Javier Prado Este N&deg; 6210, Piso 12, La Molina, Lima para poder absolver su sugerencia, comentario o consulta.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>En tal sentido, usted queda informado del tratamiento automatizado de sus Datos Personales por Tiendas Tambo para las finalidades mencionadas y reconoce y acepta que dicho tratamiento es necesario para la gesti&oacute;n de la relaci&oacute;n comercial y las dem&aacute;s finalidades leg&Iacute;timas que se indican.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>A efectos de dar el tratamiento antes descrito y cumplir con las finalidades mencionadas, Tiendas Tambo contratar&aacute; los servicios de Orion Peru SAC, empresa encargada de brindar el servicio de procesamiento y almacenamiento ubicada en Estados Unidos. Como usted podr&aacute; advertir, el referido proveedor se encuentra ubicado fuera del pa&Iacute;s, siendo que dicho tratamiento de datos personales se efect&uacute;a conforme a la normativa sobre protecci&oacute;n de datos personales.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>Asimismo, usted autoriza a Tiendas Tambo a: (i) enviarle publicidad, ofertas masivas o personalizadas de productos y/o servicios de Tiendas Tambo de manera individual o conjunta con otros productos o servicios de la empresa o marcas vinculadas a la empresa; (ii) enviarle invitaciones a actividades convocadas por Tiendas Tambo; (iii) elaborar estad&Iacute;sticas y/o estudios de comportamiento, gustos y/o tendencias; y, (iv) realizar encuestas sobre los productos y/o servicios que ofrece.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>Los datos suministrados por usted son esenciales para las finalidades antes indicadas. Sin perjuicio de lo anterior, usted podr&aacute; revocar su consentimiento en cualquier momento. Para ejercer este derecho o cualquier otro que la ley establece con relaci&oacute;n a sus datos personales (acceso, rectificaci&oacute;n, cancelaci&oacute;n y oposici&oacute;n), deber&aacute; remitir una comunicaci&oacute;n a la siguiente direcci&oacute;n electr&oacute;nica:&nbsp;<a href=\"mailto:leyprotecciondatos@lindcorp.pe\">leyprotecciondatos@lindcorp.pe</a></p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>En caso no nos proporcione la autorizaci&oacute;n para el tratamiento de sus datos personales, Tiendas Tambo no podr&aacute; cumplir con las finalidades descritas.<br />\r\n&nbsp;</p>\r\n\r\n<h2>AUTORIZACI&Oacute;N PARA EL TRATAMIENTO DE DATOS PERSONALES - SECCI&Oacute;N TRABAJA CON NOSOTROS</h2>\r\n\r\n<p>De conformidad con lo dispuesto en la Ley N&deg; 29733, Ley de Protecci&oacute;n de Datos Personales y su Reglamento, (&quot;Tiendas Tambo SAC&quot;) informa al cliente de que los datos de car&aacute;cter personal que ha facilitado a la Empresa y los que facilite en el futuro en el contexto de atender la solicitud ingresada mediante nuestra web (&quot;Datos Personales&quot;) ser&aacute;n incorporados de forma indeterminada en el banco de datos personales de Tiendas Tambo, ubicado en Avenida Javier Prado Este N&deg; 6210, Piso 12, La Molina, Lima para poder absolver su sugerencia, comentario o consulta.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>En tal sentido, usted queda informado del tratamiento automatizado de sus Datos Personales por Tiendas Tambo para las finalidades mencionadas y reconoce y acepta que dicho tratamiento es necesario para la gesti&oacute;n de la relaci&oacute;n comercial y las dem&aacute;s finalidades leg&Iacute;timas que se indican.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>A efectos de dar el tratamiento antes descrito y cumplir con las finalidades mencionadas, Tiendas Tambo contratar&aacute; los servicios de Orion Peru SAC, empresa encargada de brindar el servicio de procesamiento y almacenamiento ubicada en Estados Unidos. Como usted podr&aacute; advertir, el referido proveedor se encuentra ubicado fuera del pa&Iacute;s, siendo que dicho tratamiento de datos personales se efect&uacute;a conforme a la normativa sobre protecci&oacute;n de datos personales.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>Asimismo, usted autoriza a Tiendas Tambo a: (i) enviarle publicidad, ofertas masivas o personalizadas de productos y/o servicios de Tiendas Tambo de manera individual o conjunta con otros productos o servicios de la empresa o marcas vinculadas a la empresa; (ii) enviarle invitaciones a actividades convocadas por Tiendas Tambo; (iii) elaborar estad&Iacute;sticas y/o estudios de comportamiento, gustos y/o tendencias; y, (iv) realizar encuestas sobre los productos y/o servicios que ofrece.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>Los datos suministrados por usted son esenciales para las finalidades antes indicadas. Sin perjuicio de lo anterior, usted podr&aacute; revocar su consentimiento en cualquier momento. Para ejercer este derecho o cualquier otro que la ley establece con relaci&oacute;n a sus datos personales (acceso, rectificaci&oacute;n, cancelaci&oacute;n y oposici&oacute;n), deber&aacute; remitir una comunicaci&oacute;n a la siguiente direcci&oacute;n electr&oacute;nica:&nbsp;<a href=\"mailto:leyprotecciondatos@lindcorp.pe\">leyprotecciondatos@lindcorp.pe</a></p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>En caso no nos proporcione la autorizaci&oacute;n para el tratamiento de sus datos personales, Tiendas Tambo no podr&aacute; cumplir con las finalidades descritas.<br />\r\n&nbsp;</p>\r\n\r\n<h2>AUTORIZACI&Oacute;N PARA EL TRATAMIENTO DE DATOS PERSONALES - SECCI&Oacute;N OFRECENOS TU LOCAL</h2>\r\n\r\n<p>De conformidad con lo dispuesto en la Ley N&deg; 29733, Ley de Protecci&oacute;n de Datos Personales y su Reglamento, (&quot;Tiendas Tambo SAC&quot;) informa al cliente de que los datos de car&aacute;cter personal que ha facilitado a la Empresa y los que facilite en el futuro en el contexto de atender la solicitud ingresada mediante nuestra web (&quot;Datos Personales&quot;) ser&aacute;n incorporados de forma indeterminada en el banco de datos personales de Tiendas Tambo, ubicado en Avenida Javier Prado Este N&deg; 6210, Piso 12, La Molina, Lima para poder absolver su sugerencia, comentario o consulta.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>En tal sentido, usted queda informado del tratamiento automatizado de sus Datos Personales por Tiendas Tambo para las finalidades mencionadas y reconoce y acepta que dicho tratamiento es necesario para la gesti&oacute;n de la relaci&oacute;n comercial y las dem&aacute;s finalidades leg&Iacute;timas que se indican.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>A efectos de dar el tratamiento antes descrito y cumplir con las finalidades mencionadas, Tiendas Tambo contratar&aacute; los servicios de Orion Peru SAC, empresa encargada de brindar el servicio de procesamiento y almacenamiento ubicada en Estados Unidos. Como usted podr&aacute; advertir, el referido proveedor se encuentra ubicado fuera del pa&Iacute;s, siendo que dicho tratamiento de datos personales se efect&uacute;a conforme a la normativa sobre protecci&oacute;n de datos personales.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>Asimismo, usted autoriza a Tiendas Tambo a: (i) enviarle publicidad, ofertas masivas o personalizadas de productos y/o servicios de Tiendas Tambo de manera individual o conjunta con otros productos o servicios de la empresa o marcas vinculadas a la empresa; (ii) enviarle invitaciones a actividades convocadas por Tiendas Tambo; (iii) elaborar estad&Iacute;sticas y/o estudios de comportamiento, gustos y/o tendencias; y, (iv) realizar encuestas sobre los productos y/o servicios que ofrece.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>Los datos suministrados por usted son esenciales para las finalidades antes indicadas. Sin perjuicio de lo anterior, usted podr&aacute; revocar su consentimiento en cualquier momento. Para ejercer este derecho o cualquier otro que la ley establece con relaci&oacute;n a sus datos personales (acceso, rectificaci&oacute;n, cancelaci&oacute;n y oposici&oacute;n), deber&aacute; remitir una comunicaci&oacute;n a la siguiente direcci&oacute;n electr&oacute;nica:&nbsp;<a href=\"mailto:leyprotecciondatos@lindcorp.pe\">leyprotecciondatos@lindcorp.pe</a></p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>En caso no nos proporcione la autorizaci&oacute;n para el tratamiento de sus datos personales, Tiendas Tambo no podr&aacute; cumplir con las finalidades descritas.<br />\r\n&nbsp;</p>\r\n\r\n<h2>AUTORIZACI&Oacute;N PARA EL TRATAMIENTO DE DATOS PERSONALES - SECCI&Oacute;N PORTAL DE PROVEEDORES</h2>\r\n\r\n<p>De conformidad con lo dispuesto en la Ley N&deg; 29733, Ley de Protecci&oacute;n de Datos Personales y su Reglamento, (&quot;Tiendas Tambo SAC&quot;) informa al cliente de que los datos de car&aacute;cter personal que ha facilitado a la Empresa y los que facilite en el futuro en el contexto de atender la solicitud ingresada mediante nuestra web (&quot;Datos Personales&quot;) ser&aacute;n incorporados de forma indeterminada en el banco de datos personales de Tiendas Tambo, ubicado en Avenida Javier Prado Este N&deg; 6210, Piso 12, La Molina, Lima para poder absolver su sugerencia, comentario o consulta.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>En tal sentido, usted queda informado del tratamiento automatizado de sus Datos Personales por Tiendas Tambo para las finalidades mencionadas y reconoce y acepta que dicho tratamiento es necesario para la gesti&oacute;n de la relaci&oacute;n comercial y las dem&aacute;s finalidades leg&Iacute;timas que se indican.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>A efectos de dar el tratamiento antes descrito y cumplir con las finalidades mencionadas, Tiendas Tambo contratar&aacute; los servicios de Orion Peru SAC, empresa encargada de brindar el servicio de procesamiento y almacenamiento ubicada en Estados Unidos. Como usted podr&aacute; advertir, el referido proveedor se encuentra ubicado fuera del pa&Iacute;s, siendo que dicho tratamiento de datos personales se efect&uacute;a conforme a la normativa sobre protecci&oacute;n de datos personales.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>Asimismo, usted autoriza a Tiendas Tambo a: (i) enviarle publicidad, ofertas masivas o personalizadas de productos y/o servicios de Tiendas Tambo de manera individual o conjunta con otros productos o servicios de la empresa o marcas vinculadas a la empresa; (ii) enviarle invitaciones a actividades convocadas por Tiendas Tambo; (iii) elaborar estad&Iacute;sticas y/o estudios de comportamiento, gustos y/o tendencias; y, (iv) realizar encuestas sobre los productos y/o servicios que ofrece.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>Los datos suministrados por usted son esenciales para las finalidades antes indicadas. Sin perjuicio de lo anterior, usted podr&aacute; revocar su consentimiento en cualquier momento. Para ejercer este derecho o cualquier otro que la ley establece con relaci&oacute;n a sus datos personales (acceso, rectificaci&oacute;n, cancelaci&oacute;n y oposici&oacute;n), deber&aacute; remitir una comunicaci&oacute;n a la siguiente direcci&oacute;n electr&oacute;nica:&nbsp;<a href=\"mailto:leyprotecciondatos@lindcorp.pe\">leyprotecciondatos@lindcorp.pe</a></p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<p>En caso no nos proporcione la autorizaci&oacute;n para el tratamiento de sus datos personales, Tiendas Tambo no podr&aacute; cumplir con las finalidades descritas.</p>\r\n'),
(10, 'terms_conditions', '<h2>T&Eacute;RMINOS Y CONDICIONES</h2>\r\n\r\n<p>Este es el sitio web de la empresa Great Retail S.A.C., con domicilio en Av. Javier Prado Este N&deg; 6210, piso 12, La Molina, Lima (en adelante, &ldquo;TAMBO+&rdquo;). Los siguientes T&eacute;rminos y Condiciones regulan el acceso, la navegaci&oacute;n y el uso de la p&aacute;gina web bajo el dominio &quot;www.tambomas.pe&quot; (en adelante, &quot;Sitio Web&quot;).</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<h3>1. USO</h3>\r\n\r\n<p>La finalidad del Sitio Web es brindar informaci&oacute;n, as&iacute; como promocionar y divulgar los servicios y/o productos que ofrece TAMBO+. Est&aacute; dirigido a clientes y/o p&uacute;blico en general.<br />\r\n<br />\r\nLa navegaci&oacute;n y uso del Sitio Web es de responsabilidad de los usuarios. Los usuarios se comprometen a utilizar los servicios y contenidos que le proporciona el Sitio Web conforme a los presentes T&eacute;rminos y Condiciones, a la legislaci&oacute;n vigente, a los principios de buena fe y buenas costumbres, as&iacute; como no contravenir los derechos de terceros. Los usuarios se comprometen a suministrar informaci&oacute;n verdadera y exacta acerca de s&iacute; mismos en los formularios de contacto del Sitio Web. TAMBO+ se reserva el derecho de tomar las acciones legales pertinentes en caso de verificarse la falsedad e inexactitud de la informaci&oacute;n proporcionada por los usuarios.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<h3>2. PROPIEDAD INTELECTUAL</h3>\r\n\r\n<p>TAMBO+, en su condici&oacute;n de propietaria, cesionaria o licenciataria es titular de todos los derechos de propiedad intelectual e industrial del Sitio Web, as&iacute; como de los elementos contenidos en este (a t&iacute;tulo enunciativo, contenidos, textos, videos, documentos, material publicitario, dibujos, material t&eacute;cnico de productos y servicios o de cualquier otro orden, bases de datos, sonidos, programas de software, distintivos corporativos, signos distintivos, marcas, dise&ntilde;os gr&aacute;ficos, combinaciones de elementos, logotipos e im&aacute;genes).<br />\r\n<br />\r\nEn este sentido, queda terminantemente prohibida la reproducci&oacute;n total o parcial, comunicaci&oacute;n p&uacute;blica, modificaci&oacute;n, transformaci&oacute;n, copia, distribuci&oacute;n, o cualquier otra forma de explotaci&oacute;n y manipulaci&oacute;n del Sitio Web, de sus dispositivos t&eacute;cnicos, contenidos, aplicaciones, c&oacute;digos fuente, dise&ntilde;o, selecci&oacute;n y forma de presentaci&oacute;n de los materiales y, en general, respecto de la informaci&oacute;n contenida en el Sitio Web.<br />\r\n<br />\r\nQueda, asimismo, prohibido descomponer, realizar ingenier&iacute;a inversiva o, en general, transmitir de cualquier modo o realizar obras derivadas de los programas de ordenador necesarios para el funcionamiento y acceso del Sitio Web y de los servicios aqu&iacute; contenidos, as&iacute; como realizar, respecto de los mismos cualquier acto de explotaci&oacute;n.<br />\r\n<br />\r\nEn caso, los usuarios consideran que alguna de sus obras u elemento de propiedad intelectual ha sido copiado y se encuentra disponible en el Sitio Web de forma que infrinja la normativa sobre protecci&oacute;n intelectual o descubre enlaces hacia sitio webs de terceros que infringen igualmente la normativa aplicable en esta materia, deber&aacute;n contactarse con TAMBO+ para notificar esta situaci&oacute;n a trav&eacute;s del siguiente correo electr&oacute;nico&nbsp;<a href=\"mailto:sugerencia@lindcorp.pe\">sugerencia@lindcorp.pe</a>.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<h3>3. USO DE COOKIES</h3>\r\n\r\n<p>TAMBO+ puede utilizar cookies cuando los usuarios navegan por el Sitio Web. Los usuarios pueden configurar su navegador para aceptar o rechazar la instalaci&oacute;n de cookies o suprimirlos una vez que haya finalizado su navegaci&oacute;n en el Sitio Web. TAMBO+ no se responsabiliza de que la desactivaci&oacute;n de las cookies pueda impedir el buen funcionamiento del Sitio Web.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<h3>4. MODIFICACIONES A LOS T&Eacute;RMINOS Y CONDICIONES</h3>\r\n\r\n<p>TAMBO+ se reserva el derecho de modificar los presentes T&eacute;rminos y Condiciones en cualquier momento y seg&uacute;n lo considere necesario. En tal sentido. TAMBO+ recomienda a los usuarios a leer atentamente los T&eacute;rminos y Condiciones cada vez que pretendan utilizar el Sitio Web.</p>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<h3>5. LEGISLACI&Oacute;N Y JURISDICCI&Oacute;N APLICABLES</h3>\r\n\r\n<p>Los presentes T&eacute;rminos y Condiciones Generales se rigen por la ley peruana. Estos T&eacute;rminos y Condiciones Generales ser&aacute;n aplicados e interpretados de acuerdo con la legislaci&oacute;n peruana, y cualquier disputa que surja en su aplicaci&oacute;n se ver&aacute; &uacute;nicamente ante los tribunales con jurisdicci&oacute;n en Lima, Per&uacute;. No obstante, esto no impedir&aacute; a TAMBO+ el derecho a resolver cualquier litigio en otra jurisdicci&oacute;n competente.</p>\r\n'),
(11, 'fcm_server_key', 'AAAAJertXjw:APA91bFK4unwigK9zaJZIgmIRWzmUhuqkVOeAAiZ1bYd1wAKPRu2ie98RgK9FjmuC-Cr8GDYtCjRQMRjPsl4D7ukQ8kyD_gTia4VZ8RzokRcPxYV7npjp9s1-KhwMiIyJn3fYo6r93RS	\r\n'),
(12, 'contact_us', '<p>Somos una cadena de practi-tiendas que ofrece los beneficios del canal moderno en un nuevo formato, buscando estar cada vez m&aacute;s cerca de nuestros clientes.</p>\r\n\r\n<p>Pertenecemos a Lindcorp Retail, grupo de empresas de la familia Lindley, quienes han estado comprometidos por d&eacute;cadas con el desarrollo del pa&iacute;s.</p>\r\n\r\n<p>Abrimos nuestra primera tienda, en Abril del 2015, en Comas y desde ese d&iacute;a no hemos parado. Contamos a la fecha con m&aacute;s de 400 tiendas, lo que nos convierte en la cadena de tiendas de conveniencia m&aacute;s grande del pa&iacute;s.</p>\r\n\r\n<p>Hoy d&iacute;a ofrecemos empleo digno a m&aacute;s de 3000 personas y somos una empresa 100% peruana.</p>\r\n'),
(13, 'system_timezone', '{\"system_configurations\":\"1\",\"system_timezone_gmt\":\"-05:00\",\"system_configurations_id\":\"13\",\"app_name\":\"M guerrero\",\"support_number\":\"+51 976893323\",\"support_email\":\"guerrerosystemsac@gmail.com\",\"current_version\":\"1.0.4\",\"minimum_version_required\":\"1.0.4\",\"is-version-system-on\":\"1\",\"currency\":\"S/.\",\"tax\":\"18\",\"delivery_charge\":\"0\",\"min_amount\":\"100\",\"system_timezone\":\"America/Bogota\",\"is-refer-earn-on\":\"1\",\"min-refer-earn-order-amount\":\"200\",\"refer-earn-bonus\":\"9\",\"refer-earn-method\":\"percentage\",\"max-refer-earn-amount\":\"29\",\"minimum-withdrawal-amount\":\"28\",\"max-product-return-days\":\"1\",\"delivery-boy-bonus-percentage\":\"10\",\"from_mail\":\"guerrerosystemsac@gmail.com\",\"reply_to\":\"guerrerosystemsac@gmail.com\"}'),
(14, 'payment_methods', '{\"paypal_payment_method\":\"1\",\"paypal_mode\":\"sandbox\",\"paypal_business_email\":\"edifanio.97@gmail.com\",\"payumoney_payment_method\":\"1\",\"payumoney_merchant_key\":\"FGCWtd8L\",\"payumoney_merchant_id\":\"6928786\",\"payumoney_salt\":\"40QIgAPmii\",\"razorpay_payment_method\":\"0\",\"razorpay_key\":\"rzp_test_PeH2Z44Chsje3E\",\"razorpay_secret_key\":\"JlFiUHYoRKZc5LwR6GGc3B3h\"}'),
(15, 'about_us', '<p>Somos una cadena de practi-tiendas que ofrece los beneficios del canal moderno en un nuevo formato, buscando estar cada vez m&aacute;s cerca de nuestros clientes.</p>\r\n\r\n<p>Pertenecemos a Lindcorp Retail, grupo de empresas de la familia Lindley, quienes han estado comprometidos por d&eacute;cadas con el desarrollo del pa&iacute;s.</p>\r\n\r\n<p>Abrimos nuestra primera tienda, en Abril del 2015, en Comas y desde ese d&iacute;a no hemos parado. Contamos a la fecha con m&aacute;s de 400 tiendas, lo que nos convierte en la cadena de tiendas de conveniencia m&aacute;s grande del pa&iacute;s.</p>\r\n\r\n<p>Hoy d&iacute;a ofrecemos empleo digno a m&aacute;s de 3000 personas y somos una empresa 100% peruana.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h3>Valores</h3>\r\n\r\n<p>Integridad:</p>\r\n\r\n<p>Actuar con respeto, honestidad y compromiso. Ser &iacute;ntegro es ser coherente entre lo que digo y lo que hago. Hacer lo correcto, decir la verdad, respetar las ideas de los dem&aacute;s y cumplir lo que prometo.</p>\r\n\r\n<p>Innovaci&oacute;n:</p>\r\n\r\n<p>Buscar nuevas formas de sorprender a nuestros clientes. Ser innovador es ir m&aacute;s all&aacute; de las expectativas de mi cliente. Ser creativo en la b&uacute;squeda de soluciones, preguntarme c&oacute;mo podr&iacute;a hacer las cosas diferentes, compartir mis ideas con mi equipo y tomar la iniciativa.</p>\r\n\r\n<p>Pasi&oacute;n:</p>\r\n\r\n<p>Creemos firmemente en que podemos hacer m&aacute;s sencillo el d&iacute;a a d&iacute;a de nuestros clientes, por ello no cesamos en desarrollar nuevas experiencias que marquen la diferencia y logren satisfacer sus necesidades.</p>\r\n\r\n<p>Trabajo en Equipo:</p>\r\n\r\n<p>Es unir esfuerzos y compartir un prop&oacute;sito com&uacute;n para entregar resultados de valor a nuestra organizaci&oacute;n, clientes y usuarios. Es trabajar con entusiasmo, manteniendo una comunicaci&oacute;n efectiva, aportando diferentes puntos de vista y de esta manera engrandecer las ideas y aportes de todos.</p>\r\n'),
(80, 'currency', 'S/.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `type` varchar(16) NOT NULL,
  `type_id` tinyint(4) NOT NULL,
  `image` varchar(256) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `slider`
--

INSERT INTO `slider` (`id`, `type`, `type_id`, `image`, `date_added`) VALUES
(70, 'defecto', 0, 'upload/slider/1624750539490.jpg', '2021-06-26 23:35:39'),
(71, 'defecto', 0, 'upload/slider/1624750551875.jpg', '2021-06-26 23:35:51'),
(72, 'defecto', 0, 'upload/slider/1624750691466.jpg', '2021-06-26 23:38:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `subtitle` text NOT NULL,
  `image` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `subcategory`
--

INSERT INTO `subcategory` (`id`, `category_id`, `name`, `slug`, `subtitle`, `image`) VALUES
(64, 33, 'demo1', 'demo1', 'demo1', 'upload/images/4882-2021-06-27.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  `last_order_time` time NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `time_slots`
--

INSERT INTO `time_slots` (`id`, `title`, `from_time`, `to_time`, `last_order_time`, `status`) VALUES
(15, 'mañana 9.00 am - 12.00 pm', '06:00:00', '09:00:00', '09:30:00', 1),
(19, 'hoy de 6:am a 9 pm', '09:00:00', '09:00:00', '21:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(128) NOT NULL,
  `type` varchar(12) NOT NULL,
  `txn_id` varchar(256) NOT NULL,
  `payu_txn_id` varchar(512) DEFAULT NULL,
  `amount` double NOT NULL,
  `status` varchar(8) NOT NULL,
  `message` varchar(128) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `order_id`, `type`, `txn_id`, `payu_txn_id`, `amount`, `status`, `message`, `transaction_date`, `date_created`) VALUES
(19, 33, '', 'payumoney', 'none', NULL, 1.18, 'canceled', 'Order Failed', '2021-06-26 18:02:11', '2021-06-26 23:02:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `short_code` varchar(8) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `conversion` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unit`
--

INSERT INTO `unit` (`id`, `name`, `short_code`, `parent_id`, `conversion`) VALUES
(1, 'Kilo Gram', 'kg', NULL, NULL),
(2, 'Gram', 'gm', 1, 1000),
(3, 'Liter', 'ltr', NULL, NULL),
(4, 'Milliliter', 'ml', 3, 1000),
(5, 'Pack', 'PKG ', NULL, NULL),
(9, 'Combo', 'combo', NULL, NULL),
(8, 'Unidad', 'unidad', NULL, NULL),
(10, 'Botella', 'BT', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `email` varchar(250) CHARACTER SET utf8 NOT NULL,
  `country_code` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '91',
  `mobile` varchar(14) CHARACTER SET utf8 NOT NULL,
  `dob` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `city` text CHARACTER SET utf8 NOT NULL,
  `area` text CHARACTER SET utf8 NOT NULL,
  `street` text CHARACTER SET utf8 NOT NULL,
  `pincode` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `apikey` varchar(32) CHARACTER SET utf8 NOT NULL,
  `balance` double NOT NULL DEFAULT '0',
  `referral_code` varchar(28) COLLATE utf8_unicode_ci NOT NULL,
  `friends_code` varchar(28) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fcm_id` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(256) CHARACTER SET utf8 NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `country_code`, `mobile`, `dob`, `city`, `area`, `street`, `pincode`, `apikey`, `balance`, `referral_code`, `friends_code`, `fcm_id`, `latitude`, `longitude`, `password`, `status`, `created_at`) VALUES
(30, 'gg', 'gg@gmail.com', '51', '976893323', '', '3', '9', 'millas', '002', '', 0, 'B073WKS6LD', '', 'f5OsdRf8QaerZBlQl2bfyz:APA91bGGPk5p5O1qWWFJVDRKpJIaLP-cnfYRc-SipZ3j1cuIx8IQP-beSIJB-y3HNDzo_ACEHxm6YUKLmOkxXbJyMd6Y1vPyq8Q09gm5K0qZCJ1ZWBlxUFcVDtdjmVWjG4-dC8TqprcC', '-12.077419', '-76.9751385', '827ccb0eea8a706c4c34a16891f84e7b', 1, '2021-03-30 05:22:12'),
(33, 'milla', 'edifanio.97@gmail.com', '51', '972805092', '', '1', '1', 'lima', '001', '', 0, 'PJNK84251O', '', 'fPu-HqgXS9mX_--FYDebDD:APA91bE8EI8fs7FnEfQsEWFahjKAzLJaFQS8z9859q74A_S2cahb-4uWtACVJWGOBxzAur8pLtFlISXUkbSVu0CSSVtVdpGoSKX6pdrOFGG3ovuciOYTkY-tq-z9sL4DnhGiISZiEydm', '-12.080082519093398', '-76.97351362556218', '202cb962ac59075b964b07152d234b70', 1, '2021-06-26 22:55:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(8) NOT NULL COMMENT 'credit | debit',
  `amount` double NOT NULL,
  `message` varchar(512) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `delivery_boy_notifications`
--
ALTER TABLE `delivery_boy_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fund_transfers`
--
ALTER TABLE `fund_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `payment_requests`
--
ALTER TABLE `payment_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product_variant`
--
ALTER TABLE `product_variant`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `return_requests`
--
ALTER TABLE `return_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `delivery_boy_notifications`
--
ALTER TABLE `delivery_boy_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;

--
-- AUTO_INCREMENT de la tabla `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `fund_transfers`
--
ALTER TABLE `fund_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT de la tabla `payment_requests`
--
ALTER TABLE `payment_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de la tabla `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT de la tabla `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
