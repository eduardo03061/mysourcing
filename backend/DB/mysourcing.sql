-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-04-2024 a las 18:41:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mysourcing`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `names` varchar(20) NOT NULL,
  `firstSurname` varchar(20) NOT NULL,
  `secondSurname` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `zipCode` int(11) NOT NULL,
  `state` varchar(35) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `names`, `firstSurname`, `secondSurname`, `email`, `phone`, `zipCode`, `state`, `isActive`) VALUES
(1, 'Eduardo Al', 'Hernandez', 'Garcia', 'eduardoalejandrohg@gmail.com', '3122714600', 28984, 'Colima', 1),
(2, 'Test', 'firstSurname', 'secondSurname', 'email@gmail.com', '3122714600', 48741, 'Jalisco', 1),
(3, 'Eduardo', 'hernandez', 'garcia', 'eduardoalejandrohg2@gmail.com', '32423', 28984, 'x59g8Rxn2PUhbWx', 1),
(4, 'Eduardo', 'hernandez', 'garcia', 'eduardoalejandrohg3@gmail.com', '32432423', 28983, 'hw2Y4HebbB', 1),
(5, 'Eduardo', 'hernandez', 'garcia', 'eduardoalejandrohg4@gmail.com', '2343232', 28984, 'eEmyZdwJ0eAR', 1),
(6, 'Eduardo', 'hernandez', 'garcia', 'eduardoalejandrohg6@gmail.com', '6456', 28984, 'c4Te0W7N', 1),
(7, 'Carlos', 'Perez', 'Lopez', 'carlos@gmail.com', '55457877', 28983, 'Jspz5', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
