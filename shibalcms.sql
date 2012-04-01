SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `shibalcms` ;

CREATE SCHEMA IF NOT EXISTS `shibalcms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

USE `shibalcms`;

CREATE  TABLE IF NOT EXISTS `shibalcms`.`sitesetting` (
  `name` VARCHAR(128) NOT NULL ,
  `value` VARCHAR(1024) NULL DEFAULT NULL ,
  PRIMARY KEY (`name`) ,
  UNIQUE INDEX `sitesettingsname_UNIQUE` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `shibalcms`.`user` (
  `user_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(256) NOT NULL ,
  `is_admin` TINYINT(4) NOT NULL DEFAULT 0 ,
  `last_seen_ip` VARCHAR(45) NOT NULL DEFAULT '0.0.0.0' ,
  `last_login` DATETIME NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `dynamic_salt` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`user_id`) ,
  UNIQUE INDEX `id_UNIQUE` (`user_id` ASC) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `shibalcms`.`link_category` (
  `link_category_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(256) NULL DEFAULT NULL ,
  `parent_id` INT(11) NULL DEFAULT NULL ,
  `display_order` INT(10) UNSIGNED NOT NULL DEFAULT 999 ,
  PRIMARY KEY (`link_category_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `shibalcms`.`link` (
  `link_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `href` VARCHAR(1024) NOT NULL ,
  `text` VARCHAR(512) NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `display_order` INT(10) UNSIGNED NOT NULL DEFAULT 999 ,
  `link_category_id` INT(10) UNSIGNED NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`link_id`) ,
  INDEX `fk_link_user1` (`user_id` ASC) ,
  INDEX `fk_link_link_category1` (`link_category_id` ASC) ,
  CONSTRAINT `fk_link_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `shibalcms`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_links_link_category1`
    FOREIGN KEY (`link_category_id` )
    REFERENCES `shibalcms`.`link_category` (`link_category_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
