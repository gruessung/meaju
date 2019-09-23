SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `stat` (
  `id` int(11) NOT NULL,
  `url` int(20) NOT NULL,
  `time` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `urls` (
  `id` int(11) NOT NULL,
  `short` varchar(255) NOT NULL,
  `long` varchar(999) NOT NULL,
  `pw` varchar(255) DEFAULT NULL,
  `userid` int(5) NOT NULL,
  `abuse` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `stat`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `urls`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `stat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `urls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
