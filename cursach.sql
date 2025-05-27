-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 27, 2025 at 12:44 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cursach`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Боевики', '2025-05-17 12:17:17'),
(2, 'Комедии', '2025-05-17 12:17:17'),
(3, 'Драмы', '2025-05-17 12:17:17'),
(4, 'Ужасы', '2025-05-17 12:17:17'),
(5, 'Фантастика', '2025-05-17 12:17:17'),
(6, 'Триллеры', '2025-05-17 12:17:17'),
(7, 'Мелодрамы', '2025-05-17 12:17:17'),
(8, 'Детективы', '2025-05-17 12:17:17'),
(9, 'Приключения', '2025-05-17 12:17:17'),
(10, 'Фэнтези', '2025-05-17 12:17:17'),
(11, 'Семейные', '2025-05-17 12:17:17'),
(12, 'Анимация', '2025-05-17 12:17:17'),
(13, 'Документальные', '2025-05-17 12:17:17'),
(14, 'Исторические', '2025-05-17 12:17:17'),
(15, 'Биографические', '2025-05-17 12:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `films`
--

CREATE TABLE `films` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slogan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `director` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `poster` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_premium` tinyint(1) DEFAULT '0',
  `category_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `films`
--

INSERT INTO `films` (`id`, `title`, `original_title`, `description`, `year`, `country`, `slogan`, `director`, `poster`, `video_file`, `is_premium`, `category_id`, `created_at`) VALUES
(1, 'Чёрное море', 'Black Sea', 'Бывший капитан подводной лодки получает шанс на спасение, когда ему предлагают возглавить опасную миссию по поиску затонувшего нацистского золота в глубинах Чёрного моря. Собрав команду из бывших военных и гражданских специалистов, он отправляется в рискованное путешествие.', 2014, 'Великобритания, США', 'Глубоко под водой. Далеко от закона.', 'Кевин Макдональд', 'catalog1.png', 'default.mp4', 0, 1, '2025-05-17 12:17:17'),
(2, 'Отель у озера', 'The Lake House', 'Два человека, живущие в разное время, начинают общаться через таинственный почтовый ящик у озера. Постепенно они влюбляются друг в друга, но их разделяют годы. Они должны найти способ встретиться, не нарушая ход времени.', 2006, 'США', 'Что, если бы вы могли написать письмо в прошлое?', 'Алехандро Агрести', 'catalog2.png', 'default.mp4', 1, 7, '2025-05-17 12:17:17'),
(3, 'Тень звезды', 'Shadow of the Star', 'Молодой астроном обнаруживает странные сигналы из космоса, которые могут изменить представление человечества о Вселенной. Вместе с командой ученых он пытается разгадать тайну этих сигналов, сталкиваясь с неожиданными препятствиями.', 2023, 'Россия', 'Иногда тень может рассказать больше, чем сам свет', 'Алексей Учитель', 'catalog3.png', 'default.mp4', 0, 5, '2025-05-17 12:17:17'),
(4, 'Оторви и выбрось', 'Tear It Off and Throw It Away', 'История о молодом человеке, который пытается изменить свою жизнь, избавившись от всего, что его сдерживает. В процессе он понимает, что некоторые вещи нельзя просто выбросить.', 2022, 'Россия', 'Иногда нужно потерять всё, чтобы найти себя', 'Иван Твердовский', 'catalog4.png', 'default.mp4', 0, 3, '2025-05-17 12:17:17'),
(5, 'Космические войска', 'Space Force', 'Комедийный сериал о создании нового рода войск США - Космических сил. Главный герой, генерал Марк Нэрд, должен справиться с нелепыми ситуациями и бюрократией, пытаясь сделать космос безопасным для Америки.', 2020, 'США', 'Один маленький шаг для человека, один гигантский шаг для комедии', 'Грег Дэниелс', 'catalog5.png', 'default.mp4', 0, 2, '2025-05-17 12:17:17'),
(6, 'Алеф', 'Aleph', 'Мистический триллер о молодом писателе, который обнаруживает, что может путешествовать между параллельными реальностями. Каждое его решение создает новую ветку реальности, и он должен найти способ вернуться в свой мир.', 2021, 'Россия', 'Каждый выбор создает новую реальность', 'Игорь Копылов', 'catalog6.png', 'default.mp4', 1, 5, '2025-05-17 12:17:17'),
(7, 'Фобии', 'Phobias', 'Антология ужасов, состоящая из пяти историй, каждая из которых исследует разные человеческие страхи. От клаустрофобии до страха темноты, фильм показывает, как фобии могут разрушить человеческую психику.', 2021, 'США', 'Ваш самый большой страх - это только начало', 'Джо Карнахан', 'catalog7.png', 'default.mp4', 0, 4, '2025-05-17 12:17:17'),
(8, 'Случайная хрень', 'Random Shit', 'Черная комедия о группе друзей, которые случайно оказываются втянутыми в серию нелепых и опасных ситуаций. Их попытки выпутаться из проблем только усугубляют положение.', 2022, 'США', 'Иногда жизнь - это просто случайная хрень', 'Джеймс Франко', 'catalog8.png', 'default.mp4', 0, 2, '2025-05-17 12:17:17'),
(9, 'Верь в', 'Believe in', 'Драматическая история о молодой женщине, которая после трагической потери близкого человека находит утешение в помощи другим. Её путь к исцелению приводит к неожиданным открытиям о себе и мире.', 2023, 'США', 'Иногда нужно потерять веру, чтобы найти её снова', 'Дэвид О. Расселл', 'catalog9.png', 'default.mp4', 1, 3, '2025-05-17 12:17:17'),
(10, 'Под морем: История потомков', 'Under the Sea: A Descendants Story', 'Мюзикл о приключениях дочери Урсулы, которая пытается найти своё место в подводном королевстве. Ей предстоит столкнуться с предрассудками и доказать, что она достойна быть частью королевской семьи.', 2021, 'США', 'Где-то под волнами начинается новая история', 'Кенни Ортега', 'catalog10.png', 'default.mp4', 0, 12, '2025-05-17 12:17:17'),
(11, 'Туфельки', 'The Shoes', 'Сказка о волшебных туфельках, которые меняют жизнь каждой девушки, которая их надевает. История о том, как маленькая вещь может изменить судьбу и привести к настоящей любви.', 2022, 'Россия', 'Иногда счастье начинается с пары туфель', 'Анна Меликян', 'catalog11.png', 'default.mp4', 0, 7, '2025-05-17 12:17:17'),
(12, 'Честные воры', 'Honest Thieves', 'Бывший грабитель банков пытается начать новую жизнь, но его прошлое настигает его. Когда его обвиняют в преступлении, которое он не совершал, он должен доказать свою невиновность.', 2020, 'США', 'Иногда честность - лучшая политика', 'Марк Уильямс', 'catalog12.png', 'default.mp4', 1, 1, '2025-05-17 12:17:17'),
(13, 'Цвет из иных миров', 'Color Out of Space', 'Научно-фантастический хоррор о семье, чья жизнь меняется после падения метеорита на их ферму. Странный цвет, исходящий от метеорита, начинает влиять на окружающую среду и самих людей.', 2019, 'США', 'Некоторые цвета не должны существовать', 'Ричард Стэнли', 'catalog13.png', 'default.mp4', 0, 4, '2025-05-17 12:17:17'),
(14, 'Человек состоит в основном из воды', 'Man Is Mostly Made of Water', 'Экспериментальный фильм о жизни современного человека, исследующий тему одиночества и поиска себя в большом городе. История told через призму воды как метафоры человеческой природы.', 2021, 'Россия', 'Мы все - капли в океане жизни', 'Андрей Звягинцев', 'catalog14.png', 'default.mp4', 0, 3, '2025-05-17 12:17:17'),
(15, 'Ученик', 'The Apprentice', 'Драма о молодом человеке, который становится учеником известного мастера в необычной профессии. В процессе обучения он сталкивается с моральными дилеммами и должен сделать сложный выбор.', 2022, 'США', 'Каждый мастер когда-то был учеником', 'Даррен Аронофски', 'catalog15.png', 'default.mp4', 1, 3, '2025-05-17 12:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `start_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `payment_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('active','cancelled','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_requests`
--

CREATE TABLE `subscription_requests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `card_number` varchar(19) COLLATE utf8mb4_general_ci NOT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription_requests`
--

INSERT INTO `subscription_requests` (`id`, `user_id`, `name`, `email`, `phone`, `card_number`, `card_holder`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Yura', 'babylya03@yandex.ru', '11111111111', '1111111111111111', '213', 'approved', '2025-05-26 14:56:43', '2025-05-27 09:40:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'default.jpg',
  `is_admin` tinyint(1) DEFAULT '0',
  `is_subscribed` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`, `avatar`, `is_admin`, `is_subscribed`, `created_at`) VALUES
(1, 'user@mail.ru', 'Пользователь', '$2y$10$q7ikq4zQB5R7jWh14oIPzemDBth.0tpkuVr2HYJjrCbXzKVLMgYIG', 'default.jpg', 0, 1, '2025-05-25 20:29:01'),
(2, 'admin@admin.com', 'Администратор', '$2y$10$mup3gdmrpbxI.BVqiGJ0ZeQ2uAYoO2g0uQwsFEAf8Rqdk1yKasg.K', 'default.jpg', 1, 0, '2025-05-27 09:34:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `films`
--
ALTER TABLE `films`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `films_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  ADD CONSTRAINT `subscription_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
