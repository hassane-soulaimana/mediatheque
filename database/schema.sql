-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250718.d42db65a1e
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 19, 2025 at 09:57 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_mvc_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `media_id` int NOT NULL,
  `author` varchar(100) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `pages` int NOT NULL,
  `year` year NOT NULL,
  `summary` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`media_id`, `author`, `isbn`, `pages`, `year`, `summary`) VALUES
(13, 'Patricia Cornwell', '9782709674942', 400, '2025', 'Le Dr Kay Scarpetta est appelée sur une scène de crime étrange dans un parc d’attractions abandonné. La victime est un ancien amant, Sal Giordano, retrouvé au centre d’un agroglyphe. L’enquête mêle science, mystère et phénomènes inexpliqués, plongeant Kay dans une affaire aux implications terrifiantes.'),
(14, 'Michel Bussi', '9782258212299', 576, '2025', 'À travers trois générations, ce roman retrace les conséquences du génocide des Tutsis au Rwanda. Entre fiction et réalité, Michel Bussi mêle enquête, mémoire familiale et responsabilité politique dans une fresque bouleversante.'),
(15, 'ONE (scénario), Yusuke Murata (dessin)', '9782368522257', 200, '2016', 'Saitama est un héros si puissant qu’il bat tous ses ennemis d’un seul coup. Ennuyé par sa propre force, il cherche un adversaire à sa hauteur. Ce manga parodique mêle humour absurde et combats spectaculaires.'),
(16, 'Eiichiro Oda', '9782723435955', 192, '2000', 'Monkey D. Luffy rêve de devenir le roi des pirates. Après avoir mangé un fruit du démon, il acquiert un corps élastique. Il part à l’aventure pour trouver le légendaire trésor \"One Piece\", formant un équipage haut en couleur.'),
(17, 'Danielle Steel', '9782258203549', 336, '2025', 'Cosima Saverio gère l’entreprise familiale de maroquinerie à Venise tout en veillant sur sa sœur paralysée et son frère joueur compulsif. Une rencontre avec un magnat français pourrait changer son destin, entre sacrifice et renaissance.'),
(18, 'Danielle Steel', '9782258203556', 336, '2025', 'Theodora Morgan, icône de la mode, tente de se reconstruire après le meurtre de son mari et de son fils. Elle croise Mike Andrews, agent de la CIA infiltré, chargé de la protéger d’une menace persistante. Une romance naît dans un climat de danger et de secrets.'),
(36, 'J.R.R. Tolkien', '9782266282362', 527, '1954', 'Une quête épique pour détruire un anneau maléfique dans un monde fantasy.'),
(37, 'J.K. Rowling', '9782070518425', 320, '1997', 'Un jeune sorcier découvre ses pouvoirs et entre à l\'école de sorcellerie Poudlard.'),
(38, 'Isaac Asimov', '9782070360536', 255, '1951', 'L\'histoire de la chute d\'un empire galactique et des efforts pour préserver le savoir.'),
(39, 'Frank Herbert', '9782253074522', 512, '1965', 'Sur la planète désertique Arrakis, une lutte pour le contrôle de l\'épice.'),
(40, 'George Orwell', '9782070368228', 328, '1949', 'Une dystopie sur un futur totalitaire où la liberté individuelle n\'existe plus.');

-- --------------------------------------------------------

--
-- Table structure for table `borrows`
--

CREATE TABLE `borrows` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `media_id` int NOT NULL,
  `borrowed_at` date NOT NULL,
  `due_date` date NOT NULL,
  `returned_at` date DEFAULT NULL,
  `status` enum('En cours','Rendu') DEFAULT 'En cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `borrows`
--

INSERT INTO `borrows` (`id`, `user_id`, `media_id`, `borrowed_at`, `due_date`, `returned_at`, `status`) VALUES
(3, 11, 1, '2025-09-15', '2025-09-29', '2025-09-15', 'En cours'),
(4, 11, 6, '2025-09-15', '2025-09-29', '2025-09-16', 'En cours'),
(5, 11, 2, '2025-09-15', '2025-09-29', '2025-09-16', 'En cours'),
(6, 13, 8, '2025-09-15', '2025-09-29', '2025-09-15', 'En cours'),
(7, 11, 13, '2025-09-15', '2025-09-29', '2025-09-16', 'En cours'),
(8, 15, 1, '2025-09-17', '2025-10-01', '2025-09-17', 'En cours'),
(9, 15, 1, '2025-09-17', '2025-10-01', '2025-09-17', 'En cours'),
(10, 16, 2, '2025-09-17', '2025-10-01', NULL, 'En cours'),
(11, 16, 3, '2025-09-17', '2025-10-01', NULL, 'En cours');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `media_id` int NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `platform` enum('PC','PlayStation','Xbox','Nintendo','Mobile') NOT NULL,
  `min_age` enum('3','7','12','16','18') NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`media_id`, `publisher`, `platform`, `min_age`, `description`) VALUES
(1, 'Rockstar Games', 'PlayStation', '18', 'Se déroulant dans une version moderne de Vice City (inspirée de Miami), GTA 6 introduit pour la première fois une protagoniste féminine nommée Lucia, accompagnée d’un second personnage masculin. Le jeu propose un monde vivant avec des saisons dynamiques.'),
(2, 'Rockstar Games', 'PlayStation', '18', 'Se déroulant en 1899, à la fin de l’ère du Far West, le jeu suit Arthur Morgan, membre de la bande de Dutch van der Linde, dans sa lutte pour survivre face à l’effondrement du monde des hors-la-loi.'),
(3, 'Ubisoft', 'PC', '18', 'Se déroule dans le Japon féodal du XVIe siècle. Deux protagonistes jouables : Naoe (shinobi) et Yasuke (samouraï africain). Monde ouvert évolutif selon les saisons, gameplay varié entre furtivité et combat.'),
(4, 'Epic Games', 'PC', '12', 'Fortnite est un jeu multijoueur en ligne où 100 joueurs s’affrontent dans un mode Battle Royale jusqu’à ce qu’il ne reste qu’un survivant. Le jeu se distingue par son système de construction.'),
(5, 'Activision', 'PC', '18', 'Ce reboot de la série Modern Warfare plonge le joueur dans un conflit contemporain inspiré de guerres réelles. La campagne suit des agents de la CIA, des forces spéciales britanniques (SAS) et des rebelles d’Urzikstan.'),
(6, 'Sony Interactive Entertainment', 'PlayStation', '18', 'Suite directe de God of War (2018), ce nouvel opus plonge Kratos et son fils Atreus dans le chaos du Fimbulvetr, l’hiver précédant le Ragnarök, selon la mythologie nordique.'),
(26, 'CD Projekt Red', 'PC', '18', 'Jeu de rôle en monde ouvert se déroulant dans un univers fantasy. Vous incarnez Geralt de Riv, un chasseur de monstres.'),
(27, 'Naughty Dog', 'PlayStation', '18', 'Suite du jeu acclamé, suivant les aventures d\'Ellie et Joel dans un monde post-apocalyptique.'),
(28, 'CD Projekt Red', 'PC', '18', 'RPG en monde ouvert se déroulant dans la mégalopole de Night City, un monde obsédé par le pouvoir, la gloire et les modifications corporelles.'),
(29, 'Mojang', 'PC', '7', 'Jeu de construction et d\'aventure dans un monde composé de blocs.'),
(30, 'FromSoftware', 'PC', '16', 'Jeu d\'action-RPG en monde ouvert se déroulant dans l\'Interstice.'),
(41, 'Nintendo', 'Nintendo', '12', 'Jeu d\'action-aventure en monde ouvert dans l\'univers de Zelda.'),
(42, 'Rockstar Games', 'PlayStation', '18', 'Jeu d\'action-aventure en monde ouvert se déroulant dans l\'Ouest américain.');

-- --------------------------------------------------------

--
-- Table structure for table `medias`
--

CREATE TABLE `medias` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `type` enum('book','movie','game') NOT NULL,
  `genre` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '1',
  `cover` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `medias`
--

INSERT INTO `medias` (`id`, `title`, `type`, `genre`, `stock`, `cover`, `created_at`, `updated_at`) VALUES
(1, 'Grand Theft Auto VI', 'game', 'action', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\GTA.jpg', '2025-09-01 08:04:41', '2025-09-17 09:34:18'),
(2, 'Red Dead Redemption 2', 'game', 'action', 0, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\readdeadredemption.jpg', '2025-09-01 08:04:41', '2025-09-17 13:54:35'),
(3, 'Assassin’s Creed Shadows', 'game', 'action', 0, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\assassinscreed.jpg', '2025-09-01 08:04:41', '2025-09-17 13:54:41'),
(4, 'Fortnite', 'game', 'action', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\fortnite.jpg', '2025-09-01 08:04:41', '2025-09-12 07:50:42'),
(5, 'Call of Duty: Modern Warfare', 'game', 'adventure', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\cod.jpg', '2025-09-01 08:04:41', '2025-09-12 07:51:12'),
(6, 'God of War Ragnarök', 'game', 'action', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\gow.jpg', '2025-09-01 08:04:41', '2025-09-12 07:51:53'),
(7, 'Demon Slayer: Kimetsu no Yaiba – La Forteresse Infinie', 'movie', 'action', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\demonslayer.jpg', '2025-09-04 10:05:47', '2025-09-12 07:52:18'),
(8, 'F1 Le Film', 'movie', 'action', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\f1.webp', '2025-09-04 10:05:47', '2025-09-12 07:53:06'),
(9, 'Dragons', 'movie', 'action', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\dragons.jpg', '2025-09-04 10:05:47', '2025-09-12 07:53:35'),
(10, 'Sinners', 'movie', 'drame', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\sinners.png', '2025-09-04 10:05:47', '2025-09-12 07:53:56'),
(11, 'Karate Kid: Legends', 'movie', 'drame', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\karatekid.jpg', '2025-09-04 10:05:47', '2025-09-12 07:54:22'),
(12, 'Superman', 'movie', 'action', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\superman.jpg', '2025-09-04 10:05:47', '2025-09-12 07:55:10'),
(13, 'Identité inconnue', 'book', 'thriller', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\identiteinconnue.jpg', '2025-09-04 10:06:44', '2025-09-16 12:21:24'),
(14, 'Les Ombres du monde', 'book', 'thriller', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\ombresdumonde.jpg', '2025-09-04 10:06:44', '2025-09-12 07:56:12'),
(15, 'One-Punch Man', 'book', 'action', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\one-punch-man.jpg', '2025-09-04 10:06:44', '2025-09-12 07:56:55'),
(16, 'One Piece', 'book', 'adventure', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\onepiece.jpg', '2025-09-04 10:06:44', '2025-09-12 07:57:17'),
(17, 'Palazzo', 'book', 'drame', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\palazzo.jpg', '2025-09-04 10:06:44', '2025-09-12 07:57:45'),
(18, 'Suspects', 'book', 'thriller', 1, '\\mediatheque_paris_grp2\\public\\assets\\uploads\\covers\\suspect.jpg', '2025-09-04 10:06:44', '2025-09-12 07:58:07'),
(25, 'Naruto', 'movie', 'Action', 1, '/mediatheque_paris_grp2/public/assets/uploads/covers/naruto.jpg', '2025-09-18 08:25:06', '2025-09-18 08:26:53'),
(26, 'The Witcher 3: Wild Hunt', 'game', 'RPG', 3, NULL, '2025-09-18 11:26:58', '2025-09-19 09:56:23'),
(27, 'The Last of Us Part II', 'game', 'action', 2, '/mediatheque_paris_grp2/public/assets/uploads/covers/tlou2.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(28, 'Cyberpunk 2077', 'game', 'RPG', 1, '/mediatheque_paris_grp2/public/assets/uploads/covers/cyberpunk.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(29, 'Minecraft', 'game', 'aventure', 5, '/mediatheque_paris_grp2/public/assets/uploads/covers/minecraft.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(30, 'Elden Ring', 'game', 'RPG', 2, '/mediatheque_paris_grp2/public/assets/uploads/covers/eldenring.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(31, 'The Shawshank Redemption', 'movie', 'drame', 3, '/mediatheque_paris_grp2/public/assets/uploads/covers/default_cover.jpg\r\n', '2025-09-18 11:26:58', '2025-09-19 09:51:03'),
(32, 'The Dark Knight', 'movie', 'action', 2, '/mediatheque_paris_grp2/public/assets/uploads/covers/darkknight.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(33, 'Pulp Fiction', 'movie', 'crime', 2, '/mediatheque_paris_grp2/public/assets/uploads/covers/pulpfiction.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(34, 'Inception', 'movie', 'science-fiction', 3, '/mediatheque_paris_grp2/public/assets/uploads/covers/inception.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(35, 'Interstellar', 'movie', 'science-fiction', 2, '/mediatheque_paris_grp2/public/assets/uploads/covers/interstellar.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(36, 'Le Seigneur des Anneaux', 'book', 'fantasy', 4, '/mediatheque_paris_grp2/public/assets/uploads/covers/lotr.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(37, 'Harry Potter à l\'école des sorciers', 'book', 'fantasy', 5, '/mediatheque_paris_grp2/public/assets/uploads/covers/harrypotter.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(38, 'Fondation', 'book', 'science-fiction', 2, '/mediatheque_paris_grp2/public/assets/uploads/covers/fondation.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(39, 'Dune', 'book', 'science-fiction', 3, '/mediatheque_paris_grp2/public/assets/uploads/covers/dune.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(40, '1984', 'book', 'dystopie', 4, '/mediatheque_paris_grp2/public/assets/uploads/covers/1984.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(41, 'The Legend of Zelda: Breath of the Wild', 'game', 'aventure', 2, '/mediatheque_paris_grp2/public/assets/uploads/covers/zelda.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(42, 'Red Dead Redemption', 'game', 'action', 1, '/mediatheque_paris_grp2/public/assets/uploads/covers/rdr1.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(43, 'Forrest Gump', 'movie', 'drame', 3, '/mediatheque_paris_grp2/public/assets/uploads/covers/forrestgump.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(44, 'La Liste de Schindler', 'movie', 'drame', 2, '/mediatheque_paris_grp2/public/assets/uploads/covers/schindler.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58'),
(45, 'Le Parrain', 'movie', 'crime', 3, '/mediatheque_paris_grp2/public/assets/uploads/covers/godfather.jpg', '2025-09-18 11:26:58', '2025-09-18 11:26:58');

-- --------------------------------------------------------

--
-- Stand-in structure for view `media_stats`
-- (See below for the actual view)
--
CREATE TABLE `media_stats` (
`type` enum('book','movie','game')
,`total_medias` bigint
,`total_stock` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `media_id` int NOT NULL,
  `director` varchar(100) NOT NULL,
  `year` year NOT NULL,
  `duration` int NOT NULL,
  `classification` enum('Tous publics','-12','-16','-18') NOT NULL,
  `synopsis` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`media_id`, `director`, `year`, `duration`, `classification`, `synopsis`) VALUES
(7, 'Haruo Sotozaki', '2025', 155, '-12', 'Tanjiro, Nezuko et les Piliers sont piégés dans la Forteresse Infinie, un domaine démoniaque contrôlé par Nakime. Alors que Muzan Kibutsuji lance l’assaut final, les Pourfendeurs de Démons doivent affronter les Douze Lunes Démoniaques dans une bataille décisive pour l’avenir de l’humanité.'),
(8, 'Joseph Kosinski', '2025', 155, 'Tous publics', 'En 1993, Sonny Hayes est un jeune pilote de Formule 1 dont la carrière prometteuse est brisée par un grave accident au Grand Prix d’Espagne. Trente ans plus tard, il revient en F1 comme second pilote aux côtés du jeune prodige Joshua Pearce.'),
(9, 'Dean DeBlois', '2025', 125, 'Tous publics', 'Sur l’île escarpée de Beurk, les Vikings combattent les dragons depuis des générations. Harold, jeune rêveur et fils du chef Stoïk, défie les traditions en se liant d’amitié avec Krokmou, une Furie Nocturne.'),
(10, 'Ryan Coogler', '2025', 137, '-12', 'Dans les années 1930, deux frères jumeaux, Elijah « Smoke » et Elias « Stack », reviennent dans leur ville natale du Mississippi après avoir travaillé avec la pègre de Chicago. Ils tentent de reconstruire leur vie en ouvrant un bar clandestin, mais une force surnaturelle les attend.'),
(11, 'Jonathan Entwistle', '2025', 94, '-12', 'Après une tragédie personnelle, le jeune prodige du kung-fu Li Fong quitte Pékin pour s’installer à New York avec sa mère. Son mentor M. Han (Jackie Chan) fait appel à Daniel LaRusso (Ralph Macchio), le Karaté Kid original, pour l’aider.'),
(12, 'James Gunn', '2025', 129, 'Tous publics', 'Dans un monde divisé par les conflits, Superman intervient pour protéger l’humanité. Mais ses actes suscitent des doutes et des tensions politiques. Lex Luthor, milliardaire manipulateur, profite de cette vulnérabilité pour tenter de l’éliminer.'),
(25, 'Masashi Kishimoto', '2000', 80, '-12', 'HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH'),
(31, 'Frank Darabont', '1994', 142, '-16', 'Un banquier est condamné à la prison à vie et se lie d\'amitié avec un autre détenu.'),
(32, 'Christopher Nolan', '2008', 152, '-12', 'Batman doit affronter le Joker, un criminel psychotique qui sème le chaos à Gotham City.'),
(33, 'Quentin Tarantino', '1994', 154, '-16', 'Histoires entrelacées de gangsters, de boxeurs et de petits criminels à Los Angeles.'),
(34, 'Christopher Nolan', '2010', 148, '-12', 'Un voleur qui s\'introduit dans les rêves est chargé de planter une idée dans l\'esprit d\'un PDG.'),
(35, 'Christopher Nolan', '2014', 169, '-12', 'Une équipe d\'explorateurs voyage à travers un trou de ver dans l\'espace.'),
(43, 'Robert Zemeckis', '1994', 142, 'Tous publics', 'L\'histoire extraordinaire d\'un homme simple qui vit des événements historiques importants.'),
(44, 'Steven Spielberg', '1993', 195, '-16', 'Un industriel allemand sauve des centaines de Juifs pendant l\'Holocauste.'),
(45, 'Francis Ford Coppola', '1972', 175, '-16', 'La saga de la famille Corleone, une dynastie de la mafia italo-américaine.');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Médiathèque', 'Nom du site web', '2025-08-28 12:54:32', '2025-08-28 13:00:44'),
(2, 'maintenance_mode', '0', 'Mode maintenance (0 = désactivé, 1 = activé)', '2025-08-28 12:54:32', '2025-08-28 12:54:32'),
(3, 'max_login_attempts', '5', 'Nombre maximum de tentatives de connexion', '2025-08-28 12:54:32', '2025-08-28 12:54:32'),
(4, 'session_timeout', '7200', 'Timeout de session en secondes', '2025-08-28 12:54:32', '2025-08-28 12:54:32'),
(5, 'max_borrow_limit', '3', 'Nombre maximum d\'emprunts par utilisateur', '2025-08-28 12:54:32', '2025-08-28 12:54:32'),
(6, 'duration_borrow', '14', 'Durée d\'emprunt en jours', '2025-08-28 12:54:32', '2025-08-28 13:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `lastname` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `lastname`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(11, 'Hassane', 'Houssein', 'Hassane@yopmail.com', '$2y$10$mvzDwxt2OD209YeDZ3hrruSI10ls9meINXi2etEUrZj04xf6p1/6e', 'admin', '2025-09-15 07:53:08', '2025-09-17 08:24:15'),
(13, 'Doe', 'Joe', 'Joe@gmail.com', '$2y$10$LMAXkuLnpmfrOfdn68GCZ.AuhzZqysfPwUEoz1w19SDmaQnH992Fm', 'user', '2025-09-15 19:05:51', '2025-09-15 19:05:51'),
(15, 'Test', 'admin', 'test@yopmail.com', '$2y$10$E8wEjhi.KYYfdXpRCcRnwOIdJGhMBxtYlLFUzvCIhCuMiLSeLlKUK', 'user', '2025-09-17 09:32:02', '2025-09-17 09:32:02'),
(16, 'Azerty', 'Azerty', 'Azerty@gmail.com', '$2y$10$pCcvX8xH0zMn5E8f2dT4KuKFdOtpapkh2FT2hs5rNIr8soltWgs.C', 'user', '2025-09-17 13:54:16', '2025-09-17 13:54:16');

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_stats`
-- (See below for the actual view)
--
CREATE TABLE `user_stats` (
`total_users` bigint
,`new_users_30d` bigint
,`new_users_7d` bigint
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`media_id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_borrows_user` (`user_id`),
  ADD KEY `fk_borrows_media` (`media_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `medias`
--
ALTER TABLE `medias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medias`
--
ALTER TABLE `medias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

-- --------------------------------------------------------

--
-- Structure for view `media_stats`
--
DROP TABLE IF EXISTS `media_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `media_stats`  AS SELECT `medias`.`type` AS `type`, count(0) AS `total_medias`, sum(`medias`.`stock`) AS `total_stock` FROM `medias` GROUP BY `medias`.`type` ;

-- --------------------------------------------------------

--
-- Structure for view `user_stats`
--
DROP TABLE IF EXISTS `user_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_stats`  AS SELECT count(0) AS `total_users`, count((case when (`users`.`created_at` >= (now() - interval 30 day)) then 1 end)) AS `new_users_30d`, count((case when (`users`.`created_at` >= (now() - interval 7 day)) then 1 end)) AS `new_users_7d` FROM `users` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_media` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `fk_borrows_media` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`),
  ADD CONSTRAINT `fk_borrows_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `fk_games_media` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_movies_media` FOREIGN KEY (`media_id`) REFERENCES `medias` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
