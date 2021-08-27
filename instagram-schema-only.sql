-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2019 a las 20:25:30
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `followed_users`
--

CREATE TABLE `followed_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fk_followed_user` bigint(20) UNSIGNED NOT NULL,
  `fk_user` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image_has_tag`
--

CREATE TABLE `image_has_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fk_image` bigint(20) UNSIGNED NOT NULL,
  `fk_tag` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `followed_users`
--
ALTER TABLE `followed_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `image_has_tag`
--
ALTER TABLE `image_has_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
