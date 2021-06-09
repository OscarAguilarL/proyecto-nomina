-- MySQL Workbench Forward Engineering
drop database if exists nomina;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema nomina
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `nomina` DEFAULT CHARACTER SET utf8 ;
USE `nomina` ;

-- -----------------------------------------------------
-- Table `nomina`.`puesto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `nomina`.`puesto` (
  `idPuesto` TINYINT(3) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `salario_hora_base` DECIMAL(20,2) UNSIGNED NOT NULL,
  `salario_hora_extra` DECIMAL(20,2) UNSIGNED NOT NULL,
  PRIMARY KEY (`idPuesto`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `nomina`.`trabajador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `nomina`.`trabajador` (
  `idTrabajador` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  `apellido_paterno` VARCHAR(80) NOT NULL,
  `apellido_materno` VARCHAR(80) NOT NULL,
  `RFC` VARCHAR(13) NOT NULL UNIQUE,
  `CURP` VARCHAR(18) NOT NULL UNIQUE,
  `domicilio` VARCHAR(255) NOT NULL,
  `celular` VARCHAR(10) NOT NULL,
  `Puesto_idPuesto` TINYINT(3) NOT NULL,
  PRIMARY KEY (`idTrabajador`),
  KEY `fk_Trabajador_Puesto_idx` (`Puesto_idPuesto`),
  CONSTRAINT `fk_Trabajador_Puesto`
    FOREIGN KEY (`Puesto_idPuesto`)
    REFERENCES `nomina`.`puesto` (`idPuesto`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `nomina`.`nomina`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `nomina`.`cheque` (
  `idCheque` INT NOT NULL AUTO_INCREMENT,
  `Trabajador_idTrabajador` INT NOT NULL,
  `hrs_normales_trabajadas` TINYINT(2) UNSIGNED NOT NULL,
  `hrs_extra_trabajadas` TINYINT(2) UNSIGNED NOT NULL,
  `sueldo_base` DECIMAL(20,2) NOT NULL, -- incluyendo el de horas normales y de horas extra
  `descuento_isr` DECIMAL(20,2) NOT NULL,
  `descuento_retiro` DECIMAL(20,2) NOT NULL,
  `descuento_vivienda` DECIMAL(20,2) NOT NULL,
  `descuento_seguro` DECIMAL(20,2) NOT NULL,
  `sueldo_neto` DECIMAL(20,2) NOT NULL,
  PRIMARY KEY (`idCheque`),
  KEY `fk_Nomina_Trabajador1_idx` (`Trabajador_idTrabajador`),
  CONSTRAINT `fk_Nomina_Trabajador1`
    FOREIGN KEY (`Trabajador_idTrabajador`)
    REFERENCES `nomina`.`trabajador` (`idTrabajador`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `nomina`.`configuracion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `nomina`.`configuracion` (
  `idConfiguracion` TINYINT(1) NOT NULL,
  `limite_hrs_normales` TINYINT(2) UNSIGNED NOT NULL,
  `limite_isr` FLOAT UNSIGNED NOT NULL,
  `isr_min` TINYINT(2) UNSIGNED NOT NULL,
  `isr_max` TINYINT(2) UNSIGNED NOT NULL,
  `ahorro_retiro` TINYINT(2) UNSIGNED NOT NULL,
  `vivienda` TINYINT(2) UNSIGNED NOT NULL,
  `seguro_social` TINYINT(2) UNSIGNED NOT NULL,
  PRIMARY KEY (`idConfiguracion`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

CREATE view vista_cheque AS
SELECT
	idTrabajador,
    idCheque,
    CONCAT(t.nombre, ' ', t.apellido_paterno, ' ', t.apellido_materno) AS Nombre,
    p.nombre AS Puesto,
    t.RFC,
    t.CURP,
    c.sueldo_base,
    c.descuento_isr,
    c.descuento_retiro,
    c.descuento_vivienda,
    c.descuento_seguro,
    c.sueldo_neto
FROM
	trabajador AS t
	INNER JOIN cheque AS c ON t.idTrabajador = c.Trabajador_idTrabajador
    INNER JOIN puesto AS p ON t.Puesto_idPuesto = p.idPuesto;

INSERT INTO `puesto` VALUES (1,'Frontend','React Frontend Developer',300,600),
							(2,'Backend','Django Backend Developer',250,500),
                            (3,'Lider de Desarrollo','Lider de desarrollo de ingenieria',400,800),
                            (9,'CEO','Chief Executive Officer',100,200),
                            (10,'Diseñador UI','User Interface Designer',80,160);
INSERT INTO `trabajador` VALUES (11,'Porfirio','Aguilar','Cuenca','AUCP710915CC7','AUCP710915HHGGNR09','Actopan, Hgo','7721239087',3),
								(15,'Oscar','Aguilar','López','AULO990824BC9','AULO990824HHGGPS03','Actopan, Hgo','1231321321',1);
INSERT INTO `cheque` VALUES (27,11,40,5,20000,3000,1000,1000,2000,13000),
                            (32,15,32,1,10200,1530,510,510,1020,6630);
INSERT INTO `configuracion` VALUE(1,40,20000,15,30,5,4,10);

DELIMITER $$
CREATE TRIGGER calcularDescuentos AFTER UPDATE ON configuracion
FOR EACH ROW
BEGIN
	DECLARE idCheck INT(11);
    DECLARE idTrab INT(10);
    DECLARE total_hrs TINYINT(3);
    DECLARE hrs_norm, hrs_ex TINYINT(2);
    DECLARE sueldo_b,desc_isr,desc_r,desc_v,desc_s,sueldo_n DECIMAL(20,2);
    DECLARE sal_hb,sal_he,total_desc DECIMAL (20,2);
    SET idCheck = (SELECT MIN(idCheque) FROM cheque);
    WHILE idCheck <= (SELECT MAX(idCheque) FROM cheque) DO
		IF (EXISTS(SELECT * FROM cheque WHERE idCheque = idCheck)) THEN
            SET total_hrs = (SELECT hrs_normales_trabajadas + hrs_extra_trabajadas FROM cheque WHERE idCheque = idCheck);
            IF total_hrs > NEW.limite_hrs_normales THEN
				SET hrs_norm = NEW.limite_hrs_normales;
                SET hrs_ex = total_hrs - NEW.limite_hrs_normales;
            ELSE
				SET hrs_norm = total_hrs;
                SET hrs_ex = 0;
            END IF;
            SET idTrab = (SELECT Trabajador_idTrabajador FROM cheque WHERE idCheque = idCheck);
			SET sal_hb = (SELECT salario_hora_base FROM puesto WHERE idPuesto = (SELECT Puesto_idPuesto FROM trabajador WHERE idTrabajador = idTrab));
            SET sal_he = (SELECT salario_hora_extra FROM puesto WHERE idPuesto = (SELECT Puesto_idPuesto FROM trabajador WHERE idTrabajador = idTrab));
            SET sueldo_b = sal_hb * hrs_norm + sal_he * hrs_ex;
            IF sueldo_b <= NEW.limite_isr THEN
				SET desc_isr = sueldo_b * (NEW.isr_min)/100;
            ELSE
				SET desc_isr = sueldo_b * (NEW.isr_max)/100;
            END IF;
            SET desc_r = sueldo_b * NEW.ahorro_retiro/100;
            SET desc_v = sueldo_b * NEW.vivienda/100;
            SET desc_s = sueldo_b * NEW.seguro_social/100;
            SET total_desc = desc_isr + desc_r + desc_v + desc_s;
            SET sueldo_n = sueldo_b - total_desc;
			UPDATE cheque
				SET hrs_normales_trabajadas = hrs_norm,
                hrs_extra_trabajadas = hrs_ex,
                sueldo_base = sueldo_b,
				descuento_isr = desc_isr,
				descuento_retiro = desc_r,
				descuento_vivienda = desc_v,
				descuento_seguro = desc_s,
				sueldo_neto = sueldo_n
				WHERE Trabajador_idTrabajador = idTrab;
        END IF;
		SET idCheck = idCheck+1;
    END WHILE;
END;
$$
DELIMITER ;

-- SELECT * FROM trabajador,cheque
   --         WHERE cheque.Trabajador_idTrabajador = trabajador.idTrabajador
     --       ORDER BY apellido_paterno ASC;