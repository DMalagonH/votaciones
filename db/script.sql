SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


USE `votaciones` ;

-- -----------------------------------------------------
-- Table `tbl_candidatos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_candidatos` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `tbl_candidatos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `candidato_nombre` VARCHAR(60) NOT NULL ,
  `candidato_no_tarjeton` INT NOT NULL ,
  `candidato_imagen` VARCHAR(60) NULL ,
  `candidato_partido` VARCHAR(60) NULL ,
  `candidato_descripcion_corta` TEXT NULL ,
  `candidato_descripcion` TEXT NULL ,
  `candidato_link_facebook` VARCHAR(100) NULL ,
  `candidato_link_twiter` VARCHAR(100) NULL ,
  `candidato_link_linkedin` VARCHAR(100) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tabla para canditatos de las votaciones';

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tbl_usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_usuarios` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `tbl_usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `usuario_email` VARCHAR(60) NOT NULL ,
  `usuario_nombre` VARCHAR(60) NOT NULL ,
  `usuario_apellido` VARCHAR(60) NOT NULL ,
  `usuario_tipo_documento` VARCHAR(60) NULL ,
  `usuario_documento` VARCHAR(45) NOT NULL ,
  `usuario_rol` VARCHAR(45) NOT NULL ,
  `usuario_profesion` VARCHAR(60) NULL ,
  `usuario_telefono` VARCHAR(45) NULL ,
  `usuario_celular` VARCHAR(45) NULL ,
  `usuario_activado` TINYINT(1) NULL ,
  `permiso_nuevos_usuarios` TINYINT(1) NULL ,
  `usuario_fecha_nacimiento` DATE NULL ,
  `usuario_password` VARCHAR(45) NULL ,
  `usuario_hash` VARCHAR(100) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `usuario_email_UNIQUE` (`usuario_email` ASC) )
ENGINE = InnoDB
COMMENT = 'Tabla de usuarios de la aplicacion';

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tbl_votaciones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_votaciones` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `tbl_votaciones` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `usuario_id` INT NOT NULL ,
  `candidato_id` INT NOT NULL ,
  `voto_validado` TINYINT(1) NOT NULL ,
  `voto_fecha` DATETIME NOT NULL ,
  `usuario_id_validador` TINYINT(1) NULL ,
  INDEX `fk_tbl_usuarios_has_tbl_candidatos_tbl_candidatos1_idx` (`candidato_id` ASC) ,
  INDEX `fk_tbl_usuarios_has_tbl_candidatos_tbl_usuarios_idx` (`usuario_id` ASC) ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_tbl_usuarios_has_tbl_candidatos_tbl_usuarios`
    FOREIGN KEY (`usuario_id` )
    REFERENCES `tbl_usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_usuarios_has_tbl_candidatos_tbl_candidatos1`
    FOREIGN KEY (`candidato_id` )
    REFERENCES `tbl_candidatos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `token` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `token` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(60) NOT NULL ,
  `hash` VARCHAR(80) NULL ,
  `token` VARCHAR(80) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

SHOW WARNINGS;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;