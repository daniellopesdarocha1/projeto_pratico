-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 29-Jun-2015 às 21:44
-- Versão do servidor: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `angularnoticiasv1`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagem`
--

CREATE TABLE IF NOT EXISTS `imagem` (
`idimagem` int(11) NOT NULL,
  `imagemtitulo` varchar(160) NOT NULL,
  `imagemarquivo` varchar(100) NOT NULL,
  `noticia_idnoticia` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `imagem`
--

INSERT INTO `imagem` (`idimagem`, `imagemtitulo`, `imagemarquivo`, `noticia_idnoticia`) VALUES
(18, 'teste', '9_5581daea87207_foto.jpg', 9),
(19, '', '10_558d9b5a2b7cc_foto.jpg', 10),
(20, '', '10_558d9b5b4c088_foto.jpg', 10),
(21, '', '10_558d9b5c22e20_foto.jpg', 10),
(22, '', '10_558d9b5cc5041_foto.jpg', 10),
(23, '', '10_558d9b63770f8_foto.jpg', 10),
(24, '', '10_558d9b6425371_foto.jpg', 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `noticia`
--

CREATE TABLE IF NOT EXISTS `noticia` (
`idnoticia` int(11) NOT NULL,
  `noticiatitulo` varchar(200) NOT NULL,
  `noticiadescricao` varchar(250) DEFAULT NULL,
  `noticiatexto` text,
  `noticiadata` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noticiastatus` int(11) NOT NULL DEFAULT '1' COMMENT '1 = bloqueado\n2 = desbloqueado'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `noticia`
--

INSERT INTO `noticia` (`idnoticia`, `noticiatitulo`, `noticiadescricao`, `noticiatexto`, `noticiadata`, `noticiastatus`) VALUES
(9, 'teste', 'teste', 'teste', '2015-06-17 03:00:00', 2),
(10, 'teste 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus laoreet scelerisque felis id bibendum. Nullam auctor interdum malesuada. Donec gravida fermentum tellus eget dictum. Nunc non ipsum nunc. Sed tellus lectus, dignissim at enim eget, ma', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus laoreet scelerisque felis id bibendum. Nullam auctor interdum malesuada. Donec gravida fermentum tellus eget dictum. Nunc non ipsum nunc. Sed tellus lectus, dignissim at enim eget, mattis porttitor orci. Phasellus luctus dignissim feugiat. Nam finibus, neque a consectetur rutrum, dolor tellus auctor lorem, in varius est tortor quis massa. Nullam gravida, ante cursus sodales porttitor, ligula risus molestie risus, at gravida est lacus ac tortor. \n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus laoreet scelerisque felis id bibendum. Nullam auctor interdum malesuada. Donec gravida fermentum tellus eget dictum. Nunc non ipsum nunc. Sed tellus lectus, dignissim at enim eget, mattis porttitor orci. Phasellus luctus dignissim feugiat. Nam finibus, neque a consectetur rutrum, dolor tellus auctor lorem, in varius est tortor quis massa. Nullam gravida, ante cursus sodales porttitor, ligula risus molestie risus, at gravida est lacus ac tortor. \n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus laoreet scelerisque felis id bibendum. Nullam auctor interdum malesuada. Donec gravida fermentum tellus eget dictum. Nunc non ipsum nunc. Sed tellus lectus, dignissim at enim eget, mattis porttitor orci. Phasellus luctus dignissim feugiat. Nam finibus, neque a consectetur rutrum, dolor tellus auctor lorem, in varius est tortor quis massa. Nullam gravida, ante cursus sodales porttitor, ligula risus molestie risus, at gravida est lacus ac tortor.', '2015-06-24 03:00:00', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `imagem`
--
ALTER TABLE `imagem`
 ADD PRIMARY KEY (`idimagem`), ADD KEY `fk_imagem_noticia_idx` (`noticia_idnoticia`);

--
-- Indexes for table `noticia`
--
ALTER TABLE `noticia`
 ADD PRIMARY KEY (`idnoticia`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `imagem`
--
ALTER TABLE `imagem`
MODIFY `idimagem` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `noticia`
--
ALTER TABLE `noticia`
MODIFY `idnoticia` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `imagem`
--
ALTER TABLE `imagem`
ADD CONSTRAINT `fk_imagem_noticia` FOREIGN KEY (`noticia_idnoticia`) REFERENCES `noticia` (`idnoticia`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
