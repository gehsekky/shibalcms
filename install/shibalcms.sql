SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `marduk_shibalcms` ;
CREATE SCHEMA IF NOT EXISTS `marduk_shibalcms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `marduk_shibalcms` ;

-- -----------------------------------------------------
-- Table `marduk_shibalcms`.`sitesetting`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `marduk_shibalcms`.`sitesetting` (
  `name` VARCHAR(128) NOT NULL ,
  `value` VARCHAR(1024) NULL DEFAULT NULL ,
  PRIMARY KEY (`name`) ,
  UNIQUE INDEX `sitesettingsname_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `marduk_shibalcms`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `marduk_shibalcms`.`user` (
  `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(256) NOT NULL ,
  `is_admin` TINYINT NOT NULL DEFAULT 0 ,
  `last_seen_ip` VARCHAR(45) NOT NULL DEFAULT '0.0.0.0' ,
  `last_login` DATETIME NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `dynamic_salt` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`user_id`) ,
  UNIQUE INDEX `id_UNIQUE` (`user_id` ASC) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `marduk_shibalcms`.`link_category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `marduk_shibalcms`.`link_category` (
  `link_category_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(256) NULL DEFAULT NULL ,
  `parent_id` INT NULL DEFAULT NULL ,
  `display_order` INT UNSIGNED NOT NULL DEFAULT 999 ,
  PRIMARY KEY (`link_category_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `marduk_shibalcms`.`link`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `marduk_shibalcms`.`link` (
  `link_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `href` VARCHAR(1024) NOT NULL ,
  `text` VARCHAR(512) NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `display_order` INT UNSIGNED NOT NULL DEFAULT 999 ,
  `link_category_id` INT UNSIGNED NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`link_id`) ,
  INDEX `fk_link_user1` (`user_id` ASC) ,
  INDEX `fk_link_link_category1` (`link_category_id` ASC) ,
  CONSTRAINT `fk_link_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `marduk_shibalcms`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_links_link_category1`
    FOREIGN KEY (`link_category_id` )
    REFERENCES `marduk_shibalcms`.`link_category` (`link_category_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `marduk_shibalcms`.`module`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `marduk_shibalcms`.`module` (
  `module_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(128) NOT NULL ,
  `header_menu_display_order` INT NOT NULL DEFAULT -1 ,
  `header_menu_display_text` VARCHAR(64) NULL ,
  `header_menu_href` VARCHAR(1024) NULL ,
  `widget_display_order` INT NOT NULL DEFAULT -1 ,
  `is_enabled` TINYINT NOT NULL DEFAULT false ,
  PRIMARY KEY (`module_id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
