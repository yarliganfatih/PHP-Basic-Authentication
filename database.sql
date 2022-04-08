-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- Anamakine: db5007193537.hosting-data.io
-- Üretim Zamanı: 08 Nis 2022, 14:11:14
-- Sunucu sürümü: 5.7.33-log
-- PHP Sürümü: 7.0.33-0+deb9u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `php_basic_authentication`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tbl_token_auth`
--

CREATE TABLE `tbl_token_auth` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `username` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `member_email` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `password_hash` varchar(512) COLLATE utf8_turkish_ci NOT NULL,
  `selector_hash` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` text COLLATE utf8_turkish_ci NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `admin_confirm` tinyint(1) NOT NULL DEFAULT '1',
  `username` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `email` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `email_confirm` tinyint(1) NOT NULL DEFAULT '1',
  `email_spam` tinyint(1) NOT NULL DEFAULT '1',
  `password` varchar(512) COLLATE utf8_turkish_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `surname` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `theme` varchar(80) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'light',
  `rank` varchar(80) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=DYNAMIC;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `tbl_token_auth`
--
ALTER TABLE `tbl_token_auth`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `tbl_token_auth`
--
ALTER TABLE `tbl_token_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
