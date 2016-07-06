-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema angularnoticiasv1
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema angularnoticiasv1
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `angularnoticiasv1` DEFAULT CHARACTER SET utf8 ;
USE `angularnoticiasv1` ;

-- -----------------------------------------------------
-- Table `angularnoticiasv1`.`noticia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `angularnoticiasv1`.`noticia` (
  `idnoticia` INT NOT NULL AUTO_INCREMENT,
  `noticiatitulo` VARCHAR(200) NOT NULL,
  `noticiadescricao` VARCHAR(250) NULL,
  `noticiatexto` TEXT NULL,
  `noticiadata` TIMESTAMP NOT NULL DEFAULT now(),
  `noticiastauts` INT NOT NULL DEFAULT 1 COMMENT '1 = bloqueado\n2 = desbloqueado',
  PRIMARY KEY (`idnoticia`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `angularnoticiasv1`.`imagem`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `angularnoticiasv1`.`imagem` (
  `idimagem` INT NOT NULL AUTO_INCREMENT,
  `imagemtitulo` VARCHAR(160) NOT NULL,
  `imagemarquivo` VARCHAR(100) NOT NULL,
  `noticia_idnoticia` INT NOT NULL,
  PRIMARY KEY (`idimagem`),
  INDEX `fk_imagem_noticia_idx` (`noticia_idnoticia` ASC),
  CONSTRAINT `fk_imagem_noticia`
    FOREIGN KEY (`noticia_idnoticia`)
    REFERENCES `angularnoticiasv1`.`noticia` (`idnoticia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
