-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema university
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `university` DEFAULT CHARACTER SET utf8mb4 ;
USE `university` ;

-- -----------------------------------------------------
-- Table `university`.`pre_def_info_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`pre_def_info_type` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `info_type` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `label_type_UNIQUE` (`info_type` ASC)  )
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`role` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `role_title` VARCHAR(45) NOT NULL,
    `is_active` VARCHAR(1) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `role_title_and_is_active_UNIQUE` (`role_title` ASC, `is_active` ASC)  )
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`additional_info`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`additional_info` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `role_id` INT(11) NOT NULL,
    `pre_def_info_type_id` INT(11) NOT NULL,
    `info_key` VARCHAR(45) NOT NULL,
    `required` VARCHAR(1) NOT NULL,
    `editable` VARCHAR(1) NOT NULL,
    `erasible` VARCHAR(1) NOT NULL DEFAULT 'Y',
    PRIMARY KEY (`id`),
    UNIQUE INDEX `label_and_role_id_UNIQUE` (`info_key` ASC, `role_id` ASC)  ,
    INDEX `fk_user_additional_info_role1_idx` (`role_id` ASC)  ,
    INDEX `fk_additional_info_pre_def_info_type1_idx` (`pre_def_info_type_id` ASC)  ,
    CONSTRAINT `fk_additional_info_pre_def_info_type1`
    FOREIGN KEY (`pre_def_info_type_id`)
    REFERENCES `university`.`pre_def_info_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_user_additional_info_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `university`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`year_season`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`year_season` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `date_bound` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`))
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`course`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`course` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `course_title` VARCHAR(45) NOT NULL,
    `course_full_mark` INT(11) NOT NULL,
    `num_tests` INT(11) NULL DEFAULT 0,
    `year_season_id` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `material_title_UNIQUE` (`course_title` ASC)  ,
    INDEX `fk_material_year_season1_idx` (`year_season_id` ASC)  ,
    CONSTRAINT `fk_material_year_season1`
    FOREIGN KEY (`year_season_id`)
    REFERENCES `university`.`year_season` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`topic`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`topic` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `course_id` INT(11) NULL,
    `topic_title` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_topic_material1_idx` (`course_id` ASC)  ,
    UNIQUE INDEX `topic_title_course_id_UNIQUE` (`topic_title`, `course_id` ASC)  ,
    CONSTRAINT `fk_topic_material1`
    FOREIGN KEY (`course_id`)
    REFERENCES `university`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`question` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `topic_id` INT(11) NOT NULL,
    `question_title` VARCHAR(45) NOT NULL,
    `question_text` VARCHAR(255) NULL DEFAULT NULL,
    `default_mark` INT(11) NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_question_topic1_idx` (`topic_id` ASC)  ,
    CONSTRAINT `fk_question_topic1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `university`.`topic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`option`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`option` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `question_id` INT(11) NOT NULL,
    `option_text` VARCHAR(255) NULL DEFAULT NULL,
    `is_correct` VARCHAR(1) NOT NULL,
    `option_order` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `option_order_UNIQUE` (`option_order` ASC)  ,
    INDEX `fk_option_question1_idx` (`question_id` ASC)  ,
    CONSTRAINT `fk_option_question1`
    FOREIGN KEY (`question_id`)
    REFERENCES `university`.`question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`pre_def_attachment_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`pre_def_attachment_type` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `attachment_type` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `attachment_type_label_UNIQUE` (`attachment_type` ASC)  )
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`attachment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`attachment` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `pre_def_attachment_type_id` INT(11) NOT NULL,
    `attachment_URL` VARCHAR(255) NULL DEFAULT NULL,
    `question_id` INT(11) NULL DEFAULT NULL,
    `option_id` INT(11) NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_attachment_question1_idx` (`question_id` ASC)  ,
    INDEX `fk_attachment_option1_idx` (`option_id` ASC)  ,
    INDEX `fk_attachment_pre_def_attachment_type1_idx` (`pre_def_attachment_type_id` ASC)  ,
    CONSTRAINT `fk_attachment_option1`
    FOREIGN KEY (`option_id`)
    REFERENCES `university`.`option` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_attachment_pre_def_attachment_type1`
    FOREIGN KEY (`pre_def_attachment_type_id`)
    REFERENCES `university`.`pre_def_attachment_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_attachment_question1`
    FOREIGN KEY (`question_id`)
    REFERENCES `university`.`question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`course_dependency`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`course_dependency` (
    `id` INT(11) NOT NULL,
    `course_id` INT(11) NOT NULL,
    `course_id1` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_material_dependency_material1_idx` (`course_id` ASC)  ,
    INDEX `fk_material_dependency_material2_idx` (`course_id1` ASC)  ,
    CONSTRAINT `fk_material_dependency_material1`
    FOREIGN KEY (`course_id`)
    REFERENCES `university`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_material_dependency_material2`
    FOREIGN KEY (`course_id1`)
    REFERENCES `university`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`pre_def_permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`pre_def_permission` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `permission_label` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `permission_label_UNIQUE` (`permission_label` ASC)  )
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`role_permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`role_permission` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `role_id` INT(11) NOT NULL,
    `pre_def_permission_id` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `pre_def_permission_id_and_role_id_UNIQUE` (`pre_def_permission_id` ASC, `role_id` ASC)  ,
    INDEX `fk_role_permission_role1_idx` (`role_id` ASC)  ,
    INDEX `fk_role_permission_pre_def_permission1_idx` (`pre_def_permission_id` ASC)  ,
    CONSTRAINT `fk_role_permission_pre_def_permission1`
    FOREIGN KEY (`pre_def_permission_id`)
    REFERENCES `university`.`pre_def_permission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_role_permission_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `university`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`test`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`test` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`test_center`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`test_center` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `test_center_location` VARCHAR(45) NOT NULL,
    `capacity` INT(11) NULL DEFAULT 0,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`test_description`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`test_description` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `test_id` INT(11) NOT NULL,
    `test_center_id` INT(11) NOT NULL,
    `start_time` DATETIME NOT NULL,
    `duration_min` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_test_description_test1_idx` (`test_id` ASC)  ,
    INDEX `fk_test_description_test_center1_idx` (`test_center_id` ASC)  ,
    CONSTRAINT `fk_test_description_test1`
    FOREIGN KEY (`test_id`)
    REFERENCES `university`.`test` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_test_description_test_center1`
    FOREIGN KEY (`test_center_id`)
    REFERENCES `university`.`test_center` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`user` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `role_id` INT(11) NOT NULL,
    `test_center_id` INT(11) NULL DEFAULT NULL,
    `email` VARCHAR(60) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(60) NOT NULL,
    `last_name` VARCHAR(60) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `email_UNIQUE` (`email` ASC)  ,
    INDEX `fk_user_role_idx` (`role_id` ASC)  ,
    INDEX `fk_user_test_center1_idx` (`test_center_id` ASC)  ,
    CONSTRAINT `fk_user_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `university`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_user_test_center1`
    FOREIGN KEY (`test_center_id`)
    REFERENCES `university`.`test_center` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`solved_test`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`solved_test` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `test_description_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `total_mark` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `fk_solved_test_test_description1_idx` (`test_description_id` ASC)  ,
    INDEX `fk_solved_test_user1_idx` (`user_id` ASC)  ,
    CONSTRAINT `fk_solved_test_test_description1`
    FOREIGN KEY (`test_description_id`)
    REFERENCES `university`.`test_description` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_solved_test_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `university`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`test_question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`test_question` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `test_id` INT(11) NOT NULL,
    `question_id` INT(11) NOT NULL,
    `full_mark` INT(11) NOT NULL,
    `question_order` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `question_order_UNIQUE` (`question_order` ASC)  ,
    INDEX `fk_test_question_test1_idx` (`test_id` ASC)  ,
    INDEX `fk_test_question_question1_idx` (`question_id` ASC)  ,
    CONSTRAINT `fk_test_question_question1`
    FOREIGN KEY (`question_id`)
    REFERENCES `university`.`question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_test_question_test1`
    FOREIGN KEY (`test_id`)
    REFERENCES `university`.`test` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`solved_question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`solved_question` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `test_question_id` INT(11) NOT NULL,
    `selected_options` VARCHAR(45) NULL DEFAULT '',
    `subtotal_mark` INT(11) NOT NULL DEFAULT 0,
    `solved_test_id` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_solved_question_test_question1_idx` (`test_question_id` ASC)  ,
    INDEX `fk_solved_question_solved_test1` (`solved_test_id` ASC)  ,
    CONSTRAINT `fk_solved_question_solved_test1`
    FOREIGN KEY (`solved_test_id`)
    REFERENCES `university`.`solved_test` (`id`),
    CONSTRAINT `fk_solved_question_test_question1`
    FOREIGN KEY (`test_question_id`)
    REFERENCES `university`.`test_question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`student_course`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`student_course` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `course_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `state` CHAR(1) NULL DEFAULT NULL,
    `mark` INT(11) NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `fk_student_material_material1_idx` (`course_id` ASC)  ,
    INDEX `fk_student_material_user1_idx` (`user_id` ASC)  ,
    CONSTRAINT `fk_student_material_material1`
    FOREIGN KEY (`course_id`)
    REFERENCES `university`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_student_material_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `university`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`student_test_schedule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`student_test_schedule` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `test_description_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_student_test_schedule_test_description1_idx` (`test_description_id` ASC)  ,
    INDEX `fk_student_test_schedule_user1_idx` (`user_id` ASC)  ,
    CONSTRAINT `fk_student_test_schedule_test_description1`
    FOREIGN KEY (`test_description_id`)
    REFERENCES `university`.`test_description` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_student_test_schedule_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `university`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`user_additional_info`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`user_additional_info` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `additional_info_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `value` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `user_id_and_additional_info_id_UNIQUE` (`user_id` ASC, `additional_info_id` ASC)  ,
    INDEX `fk_user_additional_info_additional_info1_idx` (`additional_info_id` ASC)  ,
    INDEX `fk_user_additional_info_user1_idx` (`user_id` ASC)  ,
    CONSTRAINT `fk_user_additional_info_additional_info1`
    FOREIGN KEY (`additional_info_id`)
    REFERENCES `university`.`additional_info` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_user_additional_info_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `university`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `university`.`view_design_info`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `university`.`view_design_info` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `additional_info_id` INT(11) NOT NULL,
    `column_order` INT(11) NOT NULL,
    `row_order` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `column_order_and_row_order_UNIQUE` (`column_order` ASC, `row_order` ASC)  ,
    INDEX `fk_view_category_content_info_additional_info1_idx` (`additional_info_id` ASC)  ,
    CONSTRAINT `fk_view_category_content_info_additional_info1`
    FOREIGN KEY (`additional_info_id`)
    REFERENCES `university`.`additional_info` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


ALTER TABLE `university`.`course_dependency`
    CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;
