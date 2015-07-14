-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema cms
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema cms
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `cms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `cms` ;

-- -----------------------------------------------------
-- Table `cms`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`category` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id Primary key',
  `parent` INT(11) NOT NULL COMMENT 'Date update',
  `deleted_flag` INT(1) NOT NULL DEFAULT '0' COMMENT '1: deleted',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`language`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`language` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `code` VARCHAR(2) NOT NULL COMMENT '',
  `name` VARCHAR(50) NOT NULL COMMENT '',
  `status` INT(1) NOT NULL DEFAULT '1' COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`category_language`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`category_language` (
  `id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `alias` VARCHAR(255) NOT NULL COMMENT '',
  `description` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `created` INT(10) NOT NULL COMMENT '',
  `changed` INT(10) NOT NULL COMMENT '',
  `seotitle` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `seokeyword` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `seodescription` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `status` INT(1) NOT NULL DEFAULT '1' COMMENT '',
  `category_id` INT(11) UNSIGNED NOT NULL COMMENT '',
  `language_id` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`id`, `category_id`, `language_id`)  COMMENT '',
  UNIQUE INDEX `alias` (`alias` ASC)  COMMENT '',
  INDEX `fk_category_language_category1_idx` (`category_id` ASC)  COMMENT '',
  INDEX `fk_category_language_language1_idx` (`language_id` ASC)  COMMENT '',
  CONSTRAINT `fk_category_language_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `cms`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_category_language_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `cms`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`shop`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`shop` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `address` VARCHAR(255) NOT NULL COMMENT '',
  `phone` VARCHAR(12) NULL DEFAULT NULL COMMENT '',
  `fax` VARCHAR(12) NULL DEFAULT NULL COMMENT '',
  `status` INT(1) NOT NULL DEFAULT '1' COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`supplier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`supplier` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `phone` VARCHAR(12) NOT NULL COMMENT '',
  `address` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `email` VARCHAR(50) NULL DEFAULT NULL COMMENT '',
  `companyname` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `tax` VARCHAR(20) NULL DEFAULT NULL COMMENT '',
  `note` TEXT NULL DEFAULT NULL COMMENT '',
  `account` VARCHAR(20) NULL DEFAULT NULL COMMENT '',
  `status` INT(11) NOT NULL DEFAULT '1' COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `name` (`name` ASC)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`trademark`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`trademark` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `slug` VARCHAR(255) NOT NULL COMMENT '',
  `seotitle` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `seokeyword` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `seodescription` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `status` INT(1) NOT NULL DEFAULT '1' COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `slug` (`slug` ASC)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`product` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `code` VARCHAR(20) NOT NULL COMMENT '',
  `delete_flag` INT(1) NOT NULL DEFAULT '0' COMMENT '1: is deleted',
  `category_id` INT(11) UNSIGNED NOT NULL COMMENT '',
  `shop_id` INT(10) UNSIGNED NOT NULL COMMENT '',
  `supplier_id` INT(10) UNSIGNED NOT NULL COMMENT '',
  `trademark_id` INT(10) UNSIGNED NOT NULL COMMENT '',
  PRIMARY KEY (`id`, `category_id`, `shop_id`, `supplier_id`, `trademark_id`)  COMMENT '',
  INDEX `fk_product_category1_idx` (`category_id` ASC)  COMMENT '',
  INDEX `fk_product_shop1_idx` (`shop_id` ASC)  COMMENT '',
  INDEX `fk_product_supplier1_idx` (`supplier_id` ASC)  COMMENT '',
  INDEX `fk_product_trademark1_idx` (`trademark_id` ASC)  COMMENT '',
  CONSTRAINT `fk_product_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `cms`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_shop1`
    FOREIGN KEY (`shop_id`)
    REFERENCES `cms`.`shop` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_supplier1`
    FOREIGN KEY (`supplier_id`)
    REFERENCES `cms`.`supplier` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_trademark1`
    FOREIGN KEY (`trademark_id`)
    REFERENCES `cms`.`trademark` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`product_language`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`product_language` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `alias` VARCHAR(255) NOT NULL COMMENT '',
  `excerpt` VARCHAR(255) NOT NULL COMMENT '',
  `content` LONGTEXT NOT NULL COMMENT '',
  `status` INT(11) NOT NULL DEFAULT '1' COMMENT '1: Published, 0: Unpublished',
  `created` INT(10) NOT NULL COMMENT '',
  `modified` INT(10) NOT NULL COMMENT '',
  `weight` INT(10) NOT NULL COMMENT '',
  `comment` BIGINT(20) NOT NULL COMMENT '',
  `author` INT(11) NOT NULL COMMENT '',
  `sale` INT(2) NOT NULL DEFAULT '0' COMMENT 'Min:0 - Max: 99',
  `hot` INT(1) NOT NULL DEFAULT '0' COMMENT '1: hot',
  `sticky` INT(1) NOT NULL DEFAULT '0' COMMENT '1: Sticky show home page',
  `promote` INT(1) NOT NULL DEFAULT '0' COMMENT '',
  `color` VARCHAR(255) NOT NULL COMMENT 'Red, Blue, Green, ...',
  `size` VARCHAR(255) NOT NULL COMMENT 'M, L, 35, 37, ...',
  `language_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`id`, `language_id`, `product_id`)  COMMENT '',
  INDEX `fk_product_language_language1_idx` (`language_id` ASC)  COMMENT '',
  INDEX `fk_product_language_product1_idx` (`product_id` ASC)  COMMENT '',
  CONSTRAINT `fk_product_language_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `cms`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_language_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `cms`.`product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `username` VARCHAR(45) NOT NULL COMMENT '',
  `password` VARCHAR(45) NOT NULL COMMENT '',
  `password_salt` VARCHAR(45) NULL COMMENT '',
  `fullname` VARCHAR(45) NULL COMMENT '',
  `birthday` INT(10) NULL COMMENT '',
  `sex` INT(1) NULL COMMENT '',
  `address` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`comment` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `type` VARCHAR(10) NOT NULL COMMENT '',
  `author` VARCHAR(255) NOT NULL COMMENT 'Author name',
  `created` INT(10) NOT NULL COMMENT '',
  `parent` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `email` VARCHAR(255) NOT NULL COMMENT '',
  `content` TEXT NOT NULL COMMENT '',
  `language_code` VARCHAR(2) NOT NULL COMMENT '',
  `product_language_id` INT(11) NOT NULL COMMENT '',
  `product_language_language_id` INT(11) NOT NULL COMMENT '',
  `product_language_product_id` INT(11) NOT NULL COMMENT '',
  `user_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`, `product_language_id`, `product_language_language_id`, `product_language_product_id`, `user_id`)  COMMENT '',
  INDEX `fk_comment_product_language1_idx` (`product_language_id` ASC, `product_language_language_id` ASC, `product_language_product_id` ASC)  COMMENT '',
  INDEX `fk_comment_user1_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_comment_product_language1`
    FOREIGN KEY (`product_language_id` , `product_language_language_id` , `product_language_product_id`)
    REFERENCES `cms`.`product_language` (`id` , `language_id` , `product_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `cms`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`customer` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `address` VARCHAR(255) NOT NULL COMMENT '',
  `phone` VARCHAR(12) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`payment_method`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`payment_method` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(45) NOT NULL COMMENT '',
  `status` INT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cms`.`order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`order` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `payment` INT(11) NOT NULL COMMENT '',
  `total` BIGINT(20) NOT NULL COMMENT '',
  `status` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `customer_id` INT(10) UNSIGNED NOT NULL COMMENT '',
  `payment_method_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`, `customer_id`, `payment_method_id`)  COMMENT '',
  INDEX `fk_order_customer1_idx` (`customer_id` ASC)  COMMENT '',
  INDEX `fk_order_payment_method1_idx` (`payment_method_id` ASC)  COMMENT '',
  CONSTRAINT `fk_order_customer1`
    FOREIGN KEY (`customer_id`)
    REFERENCES `cms`.`customer` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_payment_method1`
    FOREIGN KEY (`payment_method_id`)
    REFERENCES `cms`.`payment_method` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cms`.`order_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cms`.`order_detail` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '',
  `quantity` INT(11) NOT NULL COMMENT '',
  `color` VARCHAR(50) NULL DEFAULT NULL COMMENT '',
  `size` VARCHAR(50) NULL DEFAULT NULL COMMENT '',
  `price` BIGINT(20) NOT NULL COMMENT '',
  `order_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NULL COMMENT '',
  PRIMARY KEY (`id`, `order_id`)  COMMENT '',
  INDEX `fk_order_detail_order1_idx` (`order_id` ASC)  COMMENT '',
  INDEX `fk_order_detail_product1_idx` (`product_id` ASC)  COMMENT '',
  CONSTRAINT `fk_order_detail_order1`
    FOREIGN KEY (`order_id`)
    REFERENCES `cms`.`order` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_detail_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `cms`.`product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
