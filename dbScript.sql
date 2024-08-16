-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema olympus
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema olympus
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `olympus` DEFAULT CHARACTER SET utf8mb3 ;
USE `olympus` ;

-- -----------------------------------------------------
-- Table `olympus`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `olympus`.`user` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `correo` VARCHAR(45) NOT NULL,
  `contraseña` VARCHAR(255) NOT NULL,
  `tipo_de_usuario` ENUM('admin', 'client') NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `correo_UNIQUE` (`correo` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `olympus`.`pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `olympus`.`pedido` (
  `id_pedido` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL,
  `fecha_pendiente` DATE NOT NULL,
  `fecha_entregado` DATE NULL DEFAULT NULL,
  `estado` ENUM('pendiente', 'entregado') NOT NULL,
  PRIMARY KEY (`id_pedido`),
  INDEX `id_usuario_idx` (`id_usuario` ASC) VISIBLE,
  CONSTRAINT `id_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `olympus`.`user` (`id_usuario`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `olympus`.`producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `olympus`.`producto` (
  `id_producto` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `imagenes` VARCHAR(511) NOT NULL,
  `stock` INT NOT NULL,
  PRIMARY KEY (`id_producto`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `olympus`.`pedido_producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `olympus`.`pedido_producto` (
  `id_producto` INT NOT NULL,
  `id_pedido` INT NOT NULL,
  `cantidad` INT NOT NULL,
  PRIMARY KEY (`id_producto`, `id_pedido`),
  INDEX `id_pedido_idx` (`id_pedido` ASC) VISIBLE,
  CONSTRAINT `id_pedido`
    FOREIGN KEY (`id_pedido`)
    REFERENCES `olympus`.`pedido` (`id_pedido`),
  CONSTRAINT `id_plato`
    FOREIGN KEY (`id_producto`)
    REFERENCES `olympus`.`producto` (`id_producto`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
