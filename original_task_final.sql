-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Янв 11 2022 г., 02:00
-- Версия сервера: 10.4.13-MariaDB
-- Версия PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `original_task`
--

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE `author` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `author`
--

INSERT INTO `author` (`id`, `name`) VALUES
(9, 'Булгаков М. А.'),
(2, 'Бунин И. А.'),
(10, 'Громов А. Н.'),
(8, 'Лем С. Г.'),
(7, 'Лукьяненко С. В.'),
(3, 'Паустовский К. Г.'),
(6, 'Пелевин В. О.'),
(5, 'Перумов Н. Д.'),
(1, 'Пушкин А. С.'),
(4, 'Толстой Л. Н.');

-- --------------------------------------------------------

--
-- Структура таблицы `book`
--

CREATE TABLE `book` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `reader_count` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `book`
--

INSERT INTO `book` (`id`, `name`, `author_id`, `reader_count`) VALUES
(1, 'Вольность', 1, 2),
(2, 'Погасло дневное светило...', 1, 0),
(3, 'К Чаадаеву', 1, 0),
(4, 'На Воронцова', 1, 2),
(5, 'Песнь о Вещем Олеге', 1, 1),
(6, 'Я здесь, Инезилья...', 1, 0),
(7, 'Суровый Дант не презирал сонета...', 1, 2),
(8, 'Румяный критик мой, насмешник толстопузый...', 1, 3),
(9, 'Чистый понедельник', 2, 4),
(10, 'Господин из Сан-Франциско', 2, 2),
(11, 'Кот ворюга', 3, 1),
(12, 'Заячьи лапы', 3, 0),
(13, 'После бала', 4, 0),
(14, 'Первый перелёт', 4, 0),
(15, 'Черное Копье', 5, 0),
(16, 'Воин Великой Тьмы', 5, 0),
(17, 'Чапаев и Пустота', 6, 1),
(18, 'Омон Ра', 6, 1),
(19, 'Рыцари сорока островов', 7, 4),
(20, 'Атомный сон', 7, 1),
(21, 'Эдем', 8, 0),
(22, 'Непобедимый', 8, 0),
(23, 'Мастер и Маргарита', 9, 4),
(24, 'Собачье сердце', 9, 3),
(25, 'Рубеж', 10, 0),
(26, 'Волна', 10, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `author_unique` (`name`);

--
-- Индексы таблицы `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_author_id_foreign` (`author_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `author`
--
ALTER TABLE `author`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `book`
--
ALTER TABLE `book`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
