-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2019 a las 20:24:04
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `instagram`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_recent_messages` ()  NO SQL
select * from v_recent_messages order by id asc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_recent_private_messages` (IN `i_user_fk` BIGINT(20) UNSIGNED, IN `i_user2_fk` BIGINT(20) UNSIGNED)  NO SQL
select t.*
from 

(select * 
from v_recent_private_messages
where (user_fk = i_user_fk and user2_fk = i_user2_fk)
or (user_fk = i_user2_fk and user2_fk = i_user_fk)
order by id desc
limit 10) t
order by t.id asc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_recent_unread_private_messages` (IN `i_user2_fk` INT(20) UNSIGNED)  NO SQL
select max(id) as id, user_fk, user2_fk, name
    from v_recent_private_messages
    where user2_fk = i_user2_fk and received=0
	group by user_fk
limit 5$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_username_for_id` (IN `i_user2_id` BIGINT(20) UNSIGNED)  NO SQL
select name from users where id = i_user2_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `mark_private_messages_as_read` (IN `i_user_fk` BIGINT(20) UNSIGNED, IN `i_user2_fk` BIGINT(20) UNSIGNED)  NO SQL
update messages
set received = 1
where user2_fk = i_user_fk and user_fk = i_user2_fk$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `send_message` (IN `i_user_fk` BIGINT(20) UNSIGNED, IN `s_message` VARCHAR(500) CHARSET utf8mb4)  NO SQL
insert into messages (user_fk,message)
values(i_user_fk,s_message)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `send_private_message` (IN `i_user_fk` BIGINT(20) UNSIGNED, IN `i_user2_fk` BIGINT(20) UNSIGNED, IN `s_message` VARCHAR(265) CHARSET utf8mb4)  NO SQL
insert into messages (user_fk,user2_fk, message)
values(i_user_fk, i_user2_fk, s_message)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comment` varchar(265) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_reply` tinyint(1) NOT NULL,
  `ref_comment` bigint(22) UNSIGNED NOT NULL,
  `user_fk` bigint(20) UNSIGNED NOT NULL,
  `image_fk` bigint(20) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `comments`
--

INSERT INTO `comments` (`id`, `comment`, `is_reply`, `ref_comment`, `user_fk`, `image_fk`, `timestamp`) VALUES
(51, 'pretty', 0, 0, 27, 40, '2019-06-03 10:48:34'),
(52, 'yeah', 0, 0, 34, 40, '2019-06-03 10:51:36'),
(54, 'my private zoo', 0, 0, 48, 54, '2019-06-17 23:42:12'),
(55, ' cute', 0, 0, 27, 54, '2019-06-17 23:42:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emotions`
--

CREATE TABLE `emotions` (
  `image_fk` bigint(20) UNSIGNED NOT NULL,
  `user_fk` bigint(20) UNSIGNED NOT NULL,
  `emotion` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `emotions`
--

INSERT INTO `emotions` (`image_fk`, `user_fk`, `emotion`, `timestamp`) VALUES
(40, 34, '3', '2019-06-02 22:56:03'),
(43, 34, '0', '2019-06-02 22:51:08'),
(45, 27, '3', '2019-06-02 23:17:44'),
(46, 27, '2', '2019-06-02 23:17:42'),
(48, 27, '2', '2019-06-02 23:17:29'),
(50, 27, '3', '2019-06-17 17:42:59'),
(51, 27, '3', '2019-06-17 17:43:12'),
(53, 27, '3', '2019-06-18 10:36:47'),
(54, 27, '2', '2019-06-17 23:42:29'),
(54, 48, '2', '2019-06-17 23:41:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `followed_users`
--

CREATE TABLE `followed_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fk_followed_user` bigint(20) UNSIGNED NOT NULL,
  `fk_user` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `followed_users`
--

INSERT INTO `followed_users` (`id`, `fk_followed_user`, `fk_user`) VALUES
(10, 27, 34),
(12, 34, 27);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
--

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Untitled',
  `url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_fk` bigint(20) UNSIGNED NOT NULL,
  `uploaded_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `images`
--

INSERT INTO `images` (`id`, `title`, `url`, `user_fk`, `uploaded_date`) VALUES
(40, 'Butterfly and flower', 'uploaded-5cf45130e8eba7.47655481.jpg', 27, '2019-06-03 10:44:00'),
(43, 'My girlfriend', 'uploaded-5cf452d236bf07.98658172.jpg', 34, '2019-06-03 10:50:58'),
(44, 'My new pet', 'uploaded-5cf454d0723f41.88652506.jpg', 34, '2019-06-03 10:59:28'),
(45, 'Oscar is hungry', 'uploaded-5cf454fc4fd215.59493953.png', 34, '2019-06-02 23:00:12'),
(46, 'Orange and green', 'uploaded-5cf4553e94e929.90365616.jpg', 34, '2019-06-02 23:01:18'),
(47, 'On the beach', 'uploaded-5cf4556dc42aa3.59161759.jpg', 34, '2019-06-02 23:02:05'),
(48, 'Untitled', 'uploaded-5cf455900dfaa2.74027647.jpg', 34, '2019-06-02 23:13:35'),
(50, 'Untitled', 'uploaded-5cf457e567bfc3.75980297.png', 27, '2019-06-02 23:12:37'),
(51, 'Mr Midnight', 'uploaded-5cf458525df308.54580923.jpg', 27, '2019-06-02 23:14:26'),
(52, 'Sunflowers', 'uploaded-5cf45b8e3fa1b0.22986303.jpg', 27, '2019-06-02 23:28:14'),
(53, 'Green thing', 'uploaded-5cf49491693172.71706832.jpg', 34, '2019-06-03 03:31:29'),
(54, 'My furries', 'uploaded-5d0825133e23b9.52385643.jpg', 48, '2019-06-17 23:41:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image_has_tag`
--

CREATE TABLE `image_has_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fk_image` bigint(20) UNSIGNED NOT NULL,
  `fk_tag` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `image_has_tag`
--

INSERT INTO `image_has_tag` (`id`, `fk_image`, `fk_tag`) VALUES
(37, 40, 1),
(38, 40, 19),
(39, 40, 20),
(36, 40, 24),
(46, 43, 2),
(48, 43, 11),
(47, 43, 22),
(49, 44, 1),
(50, 44, 19),
(51, 45, 1),
(52, 45, 25),
(53, 46, 19),
(54, 46, 30),
(55, 46, 31),
(57, 47, 3),
(56, 47, 26),
(58, 47, 29),
(59, 48, 19),
(63, 50, 1),
(62, 50, 32),
(65, 51, 1),
(66, 51, 18),
(64, 51, 32),
(67, 52, 19),
(68, 52, 20),
(69, 52, 31),
(70, 53, 19),
(71, 53, 31),
(72, 54, 1),
(73, 54, 25),
(74, 54, 32);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_fk` bigint(20) UNSIGNED NOT NULL,
  `user2_fk` bigint(20) UNSIGNED NOT NULL,
  `received` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `messages`
--

INSERT INTO `messages` (`id`, `message`, `timestamp`, `user_fk`, `user2_fk`, `received`) VALUES
(66, 'hi\n', '2019-06-02 22:05:03', 34, 0, 0),
(67, 'anyone there?\n', '2019-06-02 22:53:21', 34, 0, 0),
(68, 'hello\n', '2019-06-02 22:53:41', 27, 0, 0),
(80, 'hey Anna\n', '2019-06-14 00:43:25', 48, 27, 1),
(81, 'hi\n', '2019-06-14 13:30:04', 27, 48, 1),
(117, 'hello\n', '2019-06-17 16:39:39', 27, 0, 0),
(118, 'hi\n', '2019-06-17 16:41:40', 27, 0, 0),
(131, 'hi\n', '2019-06-17 18:43:27', 48, 27, 1),
(132, 'hello\n', '2019-06-17 18:43:37', 27, 48, 1),
(133, 'hi Klara\n', '2019-06-17 18:51:51', 27, 48, 1),
(134, 'hello\n', '2019-06-17 18:52:12', 48, 27, 1),
(141, 'hi anna\n', '2019-06-17 23:31:24', 34, 27, 1),
(142, 'how are you\n', '2019-06-17 23:31:27', 34, 27, 1),
(143, 'hi klara\n', '2019-06-17 23:33:49', 27, 48, 1),
(144, 'hello\n', '2019-06-17 23:35:31', 34, 48, 1),
(145, 'Jimmy hey\n', '2019-06-17 23:50:30', 27, 34, 1),
(146, 'hey Anna\n', '2019-06-17 23:50:51', 34, 27, 1),
(147, 'hey hey\n', '2019-06-17 23:57:26', 34, 0, 0),
(148, 'hi there\n', '2019-06-17 23:58:01', 48, 0, 0),
(149, 'hello\n', '2019-06-18 10:24:01', 27, 48, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`id`, `tag`) VALUES
(27, ''),
(1, 'animals'),
(14, 'antique'),
(13, 'aquatic'),
(11, 'art'),
(12, 'aviation'),
(3, 'beach'),
(23, 'birds'),
(10, 'bubbles'),
(5, 'cat'),
(32, 'cats'),
(9, 'clouds'),
(18, 'dark'),
(25, 'dogs'),
(20, 'flowers'),
(30, 'food'),
(24, 'insects'),
(15, 'lights'),
(31, 'nature'),
(16, 'night'),
(28, 'party'),
(2, 'people'),
(19, 'plants'),
(22, 'rain'),
(6, 'red'),
(29, 'sea'),
(8, 'sky'),
(26, 'summer'),
(4, 'sun'),
(17, 'tree'),
(21, 'trees'),
(7, 'woman');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `picture`, `password`) VALUES
(27, 'Anna', 'aaa@aaa.com', 'uploaded-5cf4587e030ef3.96654288.jpg', '$2y$10$Ylziyn0ARqwWAbSOBHvp/e78nU7F0db6sCheFPc2YL6Pub.OTf/Ry'),
(34, 'Jimmy', 'jjj@jjj.com', 'uploaded-5cf4483e9acb46.25161317.jpg', '$2y$10$00.dttZCTg.0W6LoR7SlROYy79ATm/Jt0g6lJtT0yPI/f7LuVuXxa'),
(48, 'Klara', 'kkk@kkk.com', 'uploaded-5d0191032c80c6.92607624.jpg', '$2y$10$4Uz6c8AkRUGii4WcCuQj6Odq6jWPGxusIaK3YM6AmjryQXxCYkLTe'),
(50, 'Nick', 'nnn@nnn.com', 'default.jpg', '$2y$10$6UH2He0vuj6PHNBKDERTdeoq5rtLGXt0ZUx5KNosXBrBnjiScuMw2');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_color_of_emotions`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_color_of_emotions` (
`user_id` bigint(20) unsigned
,`image_id` bigint(20) unsigned
,`emotion` char(1)
,`color_of_loves` varchar(5)
,`color_of_likes` varchar(5)
,`color_of_dislikes` varchar(5)
,`color_of_poops` varchar(5)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_images_with_emotions`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_images_with_emotions` (
`id` bigint(20) unsigned
,`user_fk` bigint(20) unsigned
,`url` varchar(100)
,`number_of_loves` decimal(23,0)
,`number_of_likes` decimal(23,0)
,`number_of_dislikes` decimal(23,0)
,`number_of_poops` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_recent_messages`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_recent_messages` (
`message` varchar(500)
,`timestamp` timestamp
,`name` varchar(20)
,`id` bigint(20) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_recent_private_messages`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_recent_private_messages` (
`message` varchar(500)
,`timestamp` timestamp
,`name` varchar(20)
,`user_fk` bigint(20) unsigned
,`user2_fk` bigint(20) unsigned
,`id` bigint(20) unsigned
,`received` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_users_images`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_users_images` (
`id` bigint(20) unsigned
,`name` varchar(20)
,`title` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_color_of_emotions`
--
DROP TABLE IF EXISTS `v_color_of_emotions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_color_of_emotions`  AS  select `users`.`id` AS `user_id`,`emotions`.`image_fk` AS `image_id`,`emotions`.`emotion` AS `emotion`,(case `emotions`.`emotion` when 3 then 'green' else 'black' end) AS `color_of_loves`,(case `emotions`.`emotion` when 2 then 'green' else 'black' end) AS `color_of_likes`,(case `emotions`.`emotion` when 1 then 'green' else 'black' end) AS `color_of_dislikes`,(case `emotions`.`emotion` when 0 then 'green' else 'black' end) AS `color_of_poops` from (`users` left join `emotions` on((`users`.`id` = `emotions`.`user_fk`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_images_with_emotions`
--
DROP TABLE IF EXISTS `v_images_with_emotions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_images_with_emotions`  AS  select `images`.`id` AS `id`,`images`.`user_fk` AS `user_fk`,`images`.`url` AS `url`,sum((case `emotions`.`emotion` when 3 then 1 else 0 end)) AS `number_of_loves`,sum((case `emotions`.`emotion` when 2 then 1 else 0 end)) AS `number_of_likes`,sum((case `emotions`.`emotion` when 1 then 1 else 0 end)) AS `number_of_dislikes`,sum((case `emotions`.`emotion` when 0 then 1 else 0 end)) AS `number_of_poops` from (`images` left join `emotions` on((`images`.`id` = `emotions`.`image_fk`))) group by `images`.`id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_recent_messages`
--
DROP TABLE IF EXISTS `v_recent_messages`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recent_messages`  AS  select `m`.`message` AS `message`,`m`.`timestamp` AS `timestamp`,`u`.`name` AS `name`,`m`.`id` AS `id` from (`messages` `m` left join `users` `u` on((`m`.`user_fk` = `u`.`id`))) where (`m`.`user2_fk` = 0) order by `m`.`id` desc limit 0,10 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_recent_private_messages`
--
DROP TABLE IF EXISTS `v_recent_private_messages`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recent_private_messages`  AS  select `m`.`message` AS `message`,`m`.`timestamp` AS `timestamp`,`u`.`name` AS `name`,`m`.`user_fk` AS `user_fk`,`m`.`user2_fk` AS `user2_fk`,`m`.`id` AS `id`,`m`.`received` AS `received` from (`messages` `m` left join `users` `u` on((`m`.`user_fk` = `u`.`id`))) where (`m`.`user2_fk` <> 0) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_users_images`
--
DROP TABLE IF EXISTS `v_users_images`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_users_images`  AS  select `users`.`id` AS `id`,`users`.`name` AS `name`,`images`.`title` AS `title` from (`users` join `images` on((`users`.`id` = `images`.`user_fk`))) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_fk` (`user_fk`),
  ADD KEY `image_fk` (`image_fk`);

--
-- Indices de la tabla `emotions`
--
ALTER TABLE `emotions`
  ADD UNIQUE KEY `image_fk` (`image_fk`,`user_fk`),
  ADD KEY `delete emotions after user deletion` (`user_fk`);

--
-- Indices de la tabla `followed_users`
--
ALTER TABLE `followed_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `fk_followed_user` (`fk_followed_user`,`fk_user`),
  ADD KEY `delete after user deletion` (`fk_user`);

--
-- Indices de la tabla `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `delete img after user deletion` (`user_fk`);

--
-- Indices de la tabla `image_has_tag`
--
ALTER TABLE `image_has_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `fk_image` (`fk_image`,`fk_tag`),
  ADD KEY `fk_tag` (`fk_tag`);

--
-- Indices de la tabla `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_fk` (`user_fk`);

--
-- Indices de la tabla `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `tag_2` (`tag`),
  ADD KEY `tag` (`tag`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `followed_users`
--
ALTER TABLE `followed_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `image_has_tag`
--
ALTER TABLE `image_has_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT de la tabla `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`image_fk`) REFERENCES `images` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `emotions`
--
ALTER TABLE `emotions`
  ADD CONSTRAINT `delete emotions after pic deletion` FOREIGN KEY (`image_fk`) REFERENCES `images` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delete emotions after user deletion` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `followed_users`
--
ALTER TABLE `followed_users`
  ADD CONSTRAINT `delete after user deletion` FOREIGN KEY (`fk_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `followed_users_ibfk_1` FOREIGN KEY (`fk_followed_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `delete img after user deletion` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `image_has_tag`
--
ALTER TABLE `image_has_tag`
  ADD CONSTRAINT `delete after img deletion` FOREIGN KEY (`fk_image`) REFERENCES `images` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `image_has_tag_ibfk_1` FOREIGN KEY (`fk_tag`) REFERENCES `tags` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
