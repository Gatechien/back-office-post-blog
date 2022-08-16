-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

INSERT INTO `author` (`id`, `lastname`, `firstname`, `created_at`, `updated_at`) VALUES
(1,	'Blonblon',	'Jean',	'2022-06-14 14:05:29',	NULL),
(2,	'Klein',	'Etienne',	'2022-06-14 14:05:48',	NULL),
(3,	'Oda',	'Eiichirō ',	'2022-06-14 14:06:39',	NULL),
(4,	'de Saint-Exupéry',	'Antoine',	'2022-06-14 14:07:57',	NULL),
(5,	'Einstein',	'Albert',	'2022-06-14 14:08:16',	NULL);

INSERT INTO `post` (`id`, `author_id`, `title`, `body`, `nb_likes`, `published_at`, `created_at`, `updated_at`, `image`) VALUES
(1,	1,	'Le petit asticot',	'Bla',	1,	'2022-06-14 14:09:01',	'2022-06-14 14:09:01',	NULL,	NULL),
(2,	1,	'le moyen asticot',	'bla',	1,	'2022-06-14 14:09:19',	'2022-06-14 14:09:19',	NULL,	NULL),
(3,	3,	'One Piece',	'One Piece est une série de mangas shōnen créée par Eiichirō Oda. Elle est prépubliée depuis le 22 juillet 1997 dans le magazine hebdomadaire Weekly Shōnen Jump, puis regroupée en volumes reliés aux éditions Shūeisha depuis le 24 décembre 1997. 102 tomes sont commercialisés au Japon en avril 2022.',	1,	'2022-06-14 14:10:03',	'2022-06-14 14:10:03',	NULL,	NULL),
(4,	2,	'petit voyage dans le monde du quanta',	'bla',	1,	NULL,	'2022-06-14 14:10:27',	NULL,	NULL);

INSERT INTO `post_tag` (`post_id`, `tag_id`) VALUES
(1,	1),
(1,	2),
(3,	3),
(3,	5);

INSERT INTO `tag` (`id`, `name`) VALUES
(1,	'Serge'),
(2,	'Bichon'),
(3,	'Symfony'),
(4,	'Insectes'),
(5,	'Maillot une pièce');

-- 2022-06-14 12:19:14