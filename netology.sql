-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 17 2018 г., 17:30
-- Версия сервера: 5.6.34-log
-- Версия PHP: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `netology`
--

-- --------------------------------------------------------

--
-- Структура таблицы `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `is_done` tinyint(4) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `task`
--

INSERT INTO `task` (`id`, `user_id`, `assigned_user_id`, `description`, `is_done`, `date_added`) VALUES
(3, 3, 1, 'Большая задача от Ани', 1, '2018-03-17 19:01:09'),
(4, 3, 4, 'Еще одна большая задача', 0, '2018-03-17 19:04:43'),
(5, 1, 1, 'И еще одна от Макса', 0, '2018-03-17 19:04:47'),
(6, 3, 4, 'Тестовая задача', 0, '2018-03-17 19:36:13'),
(7, 1, 4, 'Задача от Анны для Kate', 0, '2018-03-17 20:22:40'),
(8, 1, 5, 'Задача от Анны для еще Ивана', 0, '2018-03-17 20:22:52'),
(9, 1, 5, 'Задача для Ивана', 0, '2018-03-17 20:23:02');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hash_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `hash_password`) VALUES
(1, 'Anna', '123', '$2y$10$Qhf98FrtCfhc5jq59uT2Bu69NaE3gCcNxvZNUbY4dmM18hzcHedf2'),
(3, 'Max', '123456', '$2y$10$KmHTrlvwgKAh..CYQVubQe3qKHOeSe7shQnrz6Ys28nQlLoMEOGtG'),
(4, 'Kate', '123456', '$2y$10$fihTbR8gIduZ4X8EoA86/e86GFX0YsbPXFP6eZ./fh/C.umLmsOD.'),
(5, 'Ivan', '123456', '$2y$10$bDsniSp2diAfs1AGPkbP.uIcQJSS0JNCjAr1oRcvFgGbxhuIk3/RG');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
