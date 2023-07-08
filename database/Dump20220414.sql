-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: university
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.24-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `additional_info`
--

DROP TABLE IF EXISTS `additional_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `additional_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `pre_def_info_type_id` int(11) NOT NULL,
  `info_key` varchar(45) NOT NULL,
  `required` varchar(1) NOT NULL,
  `editable` varchar(1) NOT NULL,
  `erasable` varchar(1) NOT NULL DEFAULT 'Y',
  `fake` varchar(45) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE KEY `label_and_role_id_UNIQUE` (`info_key`,`role_id`),
  KEY `fk_user_additional_info_role1_idx` (`role_id`),
  KEY `fk_additional_info_pre_def_info_type1_idx` (`pre_def_info_type_id`),
  CONSTRAINT `fk_additional_info_pre_def_info_type1` FOREIGN KEY (`pre_def_info_type_id`) REFERENCES `pre_def_info_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_additional_info_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `additional_info`
--

LOCK TABLES `additional_info` WRITE;
/*!40000 ALTER TABLE `additional_info` DISABLE KEYS */;
INSERT INTO `additional_info` VALUES (7,2,7,'email','N','N','N','Y');
INSERT INTO `additional_info` VALUES (8,2,5,'first_name','N','N','N','Y');
INSERT INTO `additional_info` VALUES (9,2,5,'last_name','N','N','N','Y');
INSERT INTO `additional_info` VALUES (10,2,10,'role_title','N','N','N','Y');
INSERT INTO `additional_info` VALUES (11,2,1,'photo','N','N','N','Y');
INSERT INTO `additional_info` VALUES (12,2,9,'phone','N','N','N','Y');
/*!40000 ALTER TABLE `additional_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attachment`
--

DROP TABLE IF EXISTS `attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pre_def_attachment_type_id` int(11) NOT NULL,
  `attachment_URL` varchar(255) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_attachment_question1_idx` (`question_id`),
  KEY `fk_attachment_option1_idx` (`option_id`),
  KEY `fk_attachment_pre_def_attachment_type1_idx` (`pre_def_attachment_type_id`),
  CONSTRAINT `fk_attachment_option1` FOREIGN KEY (`option_id`) REFERENCES `option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_attachment_pre_def_attachment_type1` FOREIGN KEY (`pre_def_attachment_type_id`) REFERENCES `pre_def_attachment_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_attachment_question1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachment`
--

LOCK TABLES `attachment` WRITE;
/*!40000 ALTER TABLE `attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_title` varchar(45) NOT NULL,
  `course_full_mark` int(11) NOT NULL,
  `num_tests` int(11) DEFAULT 0,
  `year_season_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `material_title_UNIQUE` (`course_title`),
  KEY `fk_material_year_season1_idx` (`year_season_id`),
  CONSTRAINT `fk_material_year_season1` FOREIGN KEY (`year_season_id`) REFERENCES `year_season` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (2,'math1',100,1,2);
INSERT INTO `course` VALUES (3,'math2',75,1,2);
INSERT INTO `course` VALUES (4,'math3',75,1,2);
INSERT INTO `course` VALUES (5,'math4',100,1,2);
INSERT INTO `course` VALUES (13,'math2copy',75,1,2);
INSERT INTO `course` VALUES (14,'math3copy',75,1,2);
INSERT INTO `course` VALUES (15,'math4copy',100,1,2);
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_dependency`
--

DROP TABLE IF EXISTS `course_dependency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_dependency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `course_id1` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_material_dependency_material1_idx` (`course_id`),
  KEY `fk_material_dependency_material2_idx` (`course_id1`),
  CONSTRAINT `fk_material_dependency_material1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_material_dependency_material2` FOREIGN KEY (`course_id1`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_dependency`
--

LOCK TABLES `course_dependency` WRITE;
/*!40000 ALTER TABLE `course_dependency` DISABLE KEYS */;
INSERT INTO `course_dependency` VALUES (2,15,14);
INSERT INTO `course_dependency` VALUES (3,15,13);
/*!40000 ALTER TABLE `course_dependency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `option`
--

DROP TABLE IF EXISTS `option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `option_text` varchar(255) DEFAULT NULL,
  `is_correct` varchar(1) NOT NULL,
  `option_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_order_question_id_UNIQUE` (`option_order`,`question_id`),
  UNIQUE KEY `option_text_question_id_UNIQUE` (`option_text`,`question_id`),
  KEY `fk_option_question1_idx` (`question_id`),
  CONSTRAINT `fk_option_question1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `option`
--

LOCK TABLES `option` WRITE;
/*!40000 ALTER TABLE `option` DISABLE KEYS */;
INSERT INTO `option` VALUES (20,8,'opt text first question1','N',1);
INSERT INTO `option` VALUES (21,8,'opt text first question2','Y',2);
INSERT INTO `option` VALUES (22,8,'opt text first question3','N',3);
INSERT INTO `option` VALUES (23,8,'opt text first question4','N',4);
INSERT INTO `option` VALUES (24,8,'opt text first question5','N',5);
INSERT INTO `option` VALUES (25,9,'opt text second question1','N',1);
INSERT INTO `option` VALUES (26,9,'opt text second question2','Y',2);
INSERT INTO `option` VALUES (27,9,'opt text second question3','N',3);
INSERT INTO `option` VALUES (28,9,'opt text second question4','N',4);
INSERT INTO `option` VALUES (29,9,'opt text second question5','N',5);
INSERT INTO `option` VALUES (30,10,'opt text third question1','N',1);
INSERT INTO `option` VALUES (31,10,'opt text third question2','Y',2);
INSERT INTO `option` VALUES (32,10,'opt text third question3','N',3);
INSERT INTO `option` VALUES (33,10,'opt text third question4','N',4);
INSERT INTO `option` VALUES (34,10,'opt text third question5','N',5);
INSERT INTO `option` VALUES (35,11,'opt text 2 question1','N',1);
INSERT INTO `option` VALUES (36,11,'opt text 2 question2','Y',2);
INSERT INTO `option` VALUES (37,11,'opt text 2 question3','N',3);
INSERT INTO `option` VALUES (38,11,'opt text 2 question4','N',4);
INSERT INTO `option` VALUES (39,11,'opt text 2 question5','N',5);
/*!40000 ALTER TABLE `option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_def_attachment_type`
--

DROP TABLE IF EXISTS `pre_def_attachment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pre_def_attachment_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attachment_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attachment_type_label_UNIQUE` (`attachment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_def_attachment_type`
--

LOCK TABLES `pre_def_attachment_type` WRITE;
/*!40000 ALTER TABLE `pre_def_attachment_type` DISABLE KEYS */;
INSERT INTO `pre_def_attachment_type` VALUES (3,'AUDIO');
INSERT INTO `pre_def_attachment_type` VALUES (4,'FILE');
INSERT INTO `pre_def_attachment_type` VALUES (1,'IMAGE');
INSERT INTO `pre_def_attachment_type` VALUES (2,'VIDEO');
/*!40000 ALTER TABLE `pre_def_attachment_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_def_info_type`
--

DROP TABLE IF EXISTS `pre_def_info_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pre_def_info_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `label_type_UNIQUE` (`info_type`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_def_info_type`
--

LOCK TABLES `pre_def_info_type` WRITE;
/*!40000 ALTER TABLE `pre_def_info_type` DISABLE KEYS */;
INSERT INTO `pre_def_info_type` VALUES (3,'AUDIO');
INSERT INTO `pre_def_info_type` VALUES (8,'DATE');
INSERT INTO `pre_def_info_type` VALUES (7,'EMAIL');
INSERT INTO `pre_def_info_type` VALUES (4,'FILE');
INSERT INTO `pre_def_info_type` VALUES (1,'IMAGE');
INSERT INTO `pre_def_info_type` VALUES (5,'NAME');
INSERT INTO `pre_def_info_type` VALUES (10,'NORMAL_TEXT');
INSERT INTO `pre_def_info_type` VALUES (6,'PASSWORD');
INSERT INTO `pre_def_info_type` VALUES (9,'PHONE');
INSERT INTO `pre_def_info_type` VALUES (2,'VIDEO');
/*!40000 ALTER TABLE `pre_def_info_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pre_def_permission`
--

DROP TABLE IF EXISTS `pre_def_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pre_def_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_label` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_label_UNIQUE` (`permission_label`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_def_permission`
--

LOCK TABLES `pre_def_permission` WRITE;
/*!40000 ALTER TABLE `pre_def_permission` DISABLE KEYS */;
INSERT INTO `pre_def_permission` VALUES (1,'permission1');
INSERT INTO `pre_def_permission` VALUES (2,'permission2');
INSERT INTO `pre_def_permission` VALUES (3,'permission3');
INSERT INTO `pre_def_permission` VALUES (4,'permission4');
INSERT INTO `pre_def_permission` VALUES (5,'permission5');
INSERT INTO `pre_def_permission` VALUES (6,'permission6');
INSERT INTO `pre_def_permission` VALUES (7,'permission7');
/*!40000 ALTER TABLE `pre_def_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `question_title` varchar(45) NOT NULL,
  `question_text` varchar(255) DEFAULT NULL,
  `default_mark` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_topic1_idx` (`topic_id`),
  CONSTRAINT `fk_question_topic1` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (8,1,'title of first question','text  of first question',10);
INSERT INTO `question` VALUES (9,1,'title of second question','text  of second question',10);
INSERT INTO `question` VALUES (10,1,'title of third question','text  of third question',10);
INSERT INTO `question` VALUES (11,2,'title of 2 question','text  of 2 question',10);
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_title` varchar(45) NOT NULL,
  `is_active` varchar(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_title_and_is_active_UNIQUE` (`role_title`,`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (3,'ADMIN','N');
INSERT INTO `role` VALUES (2,'ADMIN','Y');
INSERT INTO `role` VALUES (1,'HEADQUARTERS','Y');
INSERT INTO `role` VALUES (5,'STUDENT','N');
INSERT INTO `role` VALUES (4,'STUDENT','Y');
INSERT INTO `role` VALUES (7,'TC_ADMIN','N');
INSERT INTO `role` VALUES (6,'TC_ADMIN','Y');
INSERT INTO `role` VALUES (10,'TEST','N');
INSERT INTO `role` VALUES (9,'TEST','Y');
INSERT INTO `role` VALUES (8,'VISITOR','Y');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `pre_def_permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pre_def_permission_id_and_role_id_UNIQUE` (`pre_def_permission_id`,`role_id`),
  KEY `fk_role_permission_role1_idx` (`role_id`),
  KEY `fk_role_permission_pre_def_permission1_idx` (`pre_def_permission_id`),
  CONSTRAINT `fk_role_permission_pre_def_permission1` FOREIGN KEY (`pre_def_permission_id`) REFERENCES `pre_def_permission` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_permission_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (1,1,1);
INSERT INTO `role_permission` VALUES (12,2,1);
INSERT INTO `role_permission` VALUES (8,8,1);
INSERT INTO `role_permission` VALUES (2,1,2);
INSERT INTO `role_permission` VALUES (13,2,2);
INSERT INTO `role_permission` VALUES (9,8,2);
INSERT INTO `role_permission` VALUES (3,1,3);
INSERT INTO `role_permission` VALUES (10,8,3);
INSERT INTO `role_permission` VALUES (4,1,4);
INSERT INTO `role_permission` VALUES (11,8,4);
INSERT INTO `role_permission` VALUES (5,1,5);
INSERT INTO `role_permission` VALUES (6,1,6);
INSERT INTO `role_permission` VALUES (7,1,7);
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solved_question`
--

DROP TABLE IF EXISTS `solved_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solved_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_question_id` int(11) NOT NULL,
  `selected_options` varchar(45) DEFAULT '',
  `subtotal_mark` int(11) NOT NULL DEFAULT 0,
  `solved_test_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_solved_question_test_question1_idx` (`test_question_id`),
  KEY `fk_solved_question_solved_test1` (`solved_test_id`),
  CONSTRAINT `fk_solved_question_solved_test1` FOREIGN KEY (`solved_test_id`) REFERENCES `solved_test` (`id`),
  CONSTRAINT `fk_solved_question_test_question1` FOREIGN KEY (`test_question_id`) REFERENCES `test_question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solved_question`
--

LOCK TABLES `solved_question` WRITE;
/*!40000 ALTER TABLE `solved_question` DISABLE KEYS */;
INSERT INTO `solved_question` VALUES (7,4,'',0,4);
INSERT INTO `solved_question` VALUES (8,5,'',0,4);
INSERT INTO `solved_question` VALUES (9,6,'',0,4);
/*!40000 ALTER TABLE `solved_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solved_test`
--

DROP TABLE IF EXISTS `solved_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solved_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_description_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_mark` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_solved_test_test_description1_idx` (`test_description_id`),
  KEY `fk_solved_test_user1_idx` (`user_id`),
  CONSTRAINT `fk_solved_test_test_description1` FOREIGN KEY (`test_description_id`) REFERENCES `test_description` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_solved_test_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solved_test`
--

LOCK TABLES `solved_test` WRITE;
/*!40000 ALTER TABLE `solved_test` DISABLE KEYS */;
INSERT INTO `solved_test` VALUES (4,5,2,0);
/*!40000 ALTER TABLE `solved_test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_course`
--

DROP TABLE IF EXISTS `student_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `state` char(1) DEFAULT NULL,
  `mark` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_student_material_material1_idx` (`course_id`),
  KEY `fk_student_material_user1_idx` (`user_id`),
  CONSTRAINT `fk_student_material_material1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_material_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_course`
--

LOCK TABLES `student_course` WRITE;
/*!40000 ALTER TABLE `student_course` DISABLE KEYS */;
INSERT INTO `student_course` VALUES (1,4,2,'R',0);
INSERT INTO `student_course` VALUES (9,14,2,'R',0);
INSERT INTO `student_course` VALUES (10,13,2,'R',0);
INSERT INTO `student_course` VALUES (11,5,2,'R',0);
INSERT INTO `student_course` VALUES (12,2,2,'R',0);
/*!40000 ALTER TABLE `student_course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_test_schedule`
--

DROP TABLE IF EXISTS `student_test_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_test_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_description_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_test_schedule_test_description1_idx` (`test_description_id`),
  KEY `fk_student_test_schedule_user1_idx` (`user_id`),
  CONSTRAINT `fk_student_test_schedule_test_description1` FOREIGN KEY (`test_description_id`) REFERENCES `test_description` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_test_schedule_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_test_schedule`
--

LOCK TABLES `student_test_schedule` WRITE;
/*!40000 ALTER TABLE `student_test_schedule` DISABLE KEYS */;
INSERT INTO `student_test_schedule` VALUES (3,5,2);
/*!40000 ALTER TABLE `student_test_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` VALUES (2);
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_center`
--

DROP TABLE IF EXISTS `test_center`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_center` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_center_location` varchar(45) NOT NULL,
  `capacity` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_center`
--

LOCK TABLES `test_center` WRITE;
/*!40000 ALTER TABLE `test_center` DISABLE KEYS */;
INSERT INTO `test_center` VALUES (1,'damas',10);
INSERT INTO `test_center` VALUES (2,'homs',2);
/*!40000 ALTER TABLE `test_center` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_description`
--

DROP TABLE IF EXISTS `test_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `test_center_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `duration_min` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_test_description_test1_idx` (`test_id`),
  KEY `fk_test_description_test_center1_idx` (`test_center_id`),
  CONSTRAINT `fk_test_description_test1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_test_description_test_center1` FOREIGN KEY (`test_center_id`) REFERENCES `test_center` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_description`
--

LOCK TABLES `test_description` WRITE;
/*!40000 ALTER TABLE `test_description` DISABLE KEYS */;
INSERT INTO `test_description` VALUES (5,2,1,'2022-04-14 08:49:31',120);
/*!40000 ALTER TABLE `test_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_question`
--

DROP TABLE IF EXISTS `test_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `full_mark` int(11) NOT NULL,
  `question_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_order_UNIQUE` (`question_order`),
  KEY `fk_test_question_test1_idx` (`test_id`),
  KEY `fk_test_question_question1_idx` (`question_id`),
  CONSTRAINT `fk_test_question_question1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_test_question_test1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_question`
--

LOCK TABLES `test_question` WRITE;
/*!40000 ALTER TABLE `test_question` DISABLE KEYS */;
INSERT INTO `test_question` VALUES (4,2,10,10,1);
INSERT INTO `test_question` VALUES (5,2,11,10,2);
INSERT INTO `test_question` VALUES (6,2,9,10,3);
/*!40000 ALTER TABLE `test_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `topic_title` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `topic_title_course_id_UNIQUE` (`topic_title`,`course_id`),
  KEY `fk_topic_material1_idx` (`course_id`),
  CONSTRAINT `fk_topic_material1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topic`
--

LOCK TABLES `topic` WRITE;
/*!40000 ALTER TABLE `topic` DISABLE KEYS */;
INSERT INTO `topic` VALUES (1,2,'topic1 math1');
INSERT INTO `topic` VALUES (10,3,'topic1 math2');
INSERT INTO `topic` VALUES (12,4,'topic1 math3');
INSERT INTO `topic` VALUES (2,2,'topic2 math1');
INSERT INTO `topic` VALUES (11,3,'topic2 math2');
INSERT INTO `topic` VALUES (13,4,'topic2 math3');
INSERT INTO `topic` VALUES (5,2,'topic3 math1');
INSERT INTO `topic` VALUES (14,4,'topic3 math3');
INSERT INTO `topic` VALUES (6,2,'topic4 math1');
/*!40000 ALTER TABLE `topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `test_center_id` int(11) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_user_role_idx` (`role_id`),
  KEY `fk_user_test_center1_idx` (`test_center_id`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_test_center1` FOREIGN KEY (`test_center_id`) REFERENCES `test_center` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,4,NULL,'studen@hiast.edu.sy','$2y$10$teOCUkJ0oHD519/tlGnM/.gKE7IAqlfgelucT14EtWliw84q.QkJW','std','std');
INSERT INTO `user` VALUES (3,3,NULL,'dsadsa@hiast.edu.sy','$2y$10$fdjpualUrAssZFsCTe0THeRq3iaCXwG4y1d1a2x6LThKBrZXZOBOu','dsadsa','dsadsa');
INSERT INTO `user` VALUES (4,3,NULL,'admin@hiast.edu.sy','$2y$10$lwCKTgXDPrvxXDmVXlRmnOsBt9isF7q1TVEp9k0jdkl.p8hgjA2Me','first name','last name');
INSERT INTO `user` VALUES (6,10,NULL,'test.ahmad1@hiast.edu.sy','$2y$10$tK3sAARZ6DwYneGSghJ0Uur6bauXj17gcQUvSOPwjAlXM97pARdA.','ahmad','temp');
INSERT INTO `user` VALUES (7,10,NULL,'test.ahmad2@hiast.edu.sy','$2y$10$RtWd6xmqtp6wANtd6owHx.HLPcsZYMRg4IkCF4ZuOpGULHY9sXhWC','ahmad','temp');
INSERT INTO `user` VALUES (8,10,NULL,'test.ahmad3@hiast.edu.sy','$2y$10$WnCRItfZAYYYO7jZsctZ6.y9/5LRqfpZKM0rfavW72LqARtxG/XKy','ahmad','temp');
INSERT INTO `user` VALUES (9,10,NULL,'test.ahmad4@hiast.edu.sy','$2y$10$OU62OY.UKC3SvMeWOpcaQ.zBx6V/MWo2NB4FImPXScrq58GLche8e','ahmad','temp');
INSERT INTO `user` VALUES (10,10,NULL,'test.ahmad5@hiast.edu.sy','$2y$10$7F2YxsaTkSEhPVkRvalSUOVT21VY./GifZ9svzkYCWFc5KUcomVBa','ahmad','temp');
INSERT INTO `user` VALUES (11,10,NULL,'test.ahmad6@hiast.edu.sy','$2y$10$Ac7yDNRVFhrBqJQ5FE9u9OIuuRhAUKfpiyAbM51n.YpCzCWyTUsPO','ahmad','temp');
INSERT INTO `user` VALUES (12,10,NULL,'test.ahmad7@hiast.edu.sy','$2y$10$rYFvpNipH4KVGloq53QLoO7xRGbEhUm6Q.LlGQNj1Pn8ZewtHMUhy','ahmad','temp');
INSERT INTO `user` VALUES (13,10,NULL,'test.ahmad8@hiast.edu.sy','$2y$10$VVzSo7GfCguxNyv/RvP2OeRTSeWl89izG/dTx/dublavI5arj5LFe','ahmad','temp');
INSERT INTO `user` VALUES (14,10,NULL,'test.ahmad9@hiast.edu.sy','$2y$10$5Bwmd1wK.f/Q/Eii6buzrONtGct99mo8H6xm8mdT6UpND8EsAoajy','ahmad','temp');
INSERT INTO `user` VALUES (15,10,NULL,'test.ahmad10@hiast.edu.sy','$2y$10$/ZUx9CKV9waHG21sQQzL4.07nlWCEObizB2XHcGZeRsjsoHD6ZbF6','ahmad','temp');
INSERT INTO `user` VALUES (16,10,NULL,'test.ahmad11@hiast.edu.sy','$2y$10$RKjZ1lK8DrBU4Xec47JfV.t//kLVC2R3u6CejRRGKftaVRQfHliBG','ahmad','temp');
INSERT INTO `user` VALUES (17,10,NULL,'test.ahmad12@hiast.edu.sy','$2y$10$Q46MC3rtpQRCaZXYyhysa.1qvLqoxG9/rzK.PG7a4WFQaXOFV4NMi','ahmad','temp');
INSERT INTO `user` VALUES (18,10,NULL,'test.ahmad13@hiast.edu.sy','$2y$10$AoriYTamo27a9j9Di3m0Z.LAqX.LsfRi3clskOZSZg9OxgAju.6zS','ahmad','temp');
INSERT INTO `user` VALUES (19,10,NULL,'test.ahmad14@hiast.edu.sy','$2y$10$t4clXd2mM2kANXw1l4hkEOYbkaUVCNkia31.rXkvI7emiXFtf35IS','ahmad','temp');
INSERT INTO `user` VALUES (20,10,NULL,'test.ahmad15@hiast.edu.sy','$2y$10$y9EINOyO6RJXrhXKTOwmLeiTX4Jl6hMnvob4w6BH11ZHp/LhyEAwe','ahmad','temp');
INSERT INTO `user` VALUES (21,10,NULL,'test.ahmad16@hiast.edu.sy','$2y$10$dOS0YwKGnKY1hsrNrqhGue0HbHb2FOh2bC96tgF6BdLIfVAp62wBK','ahmad','temp');
INSERT INTO `user` VALUES (22,10,NULL,'test.ahmad17@hiast.edu.sy','$2y$10$vq/.323oWxrrUb2iMAb/IeUD7ZSFrosaK7gwnkY5QMLMsI6nnI5I2','ahmad','temp');
INSERT INTO `user` VALUES (23,10,NULL,'test.ahmad18@hiast.edu.sy','$2y$10$9ddHxwLl7zRAnPTBLgMPfe5GOSEDSoFtgFsbdLVYwj51zhAUROTte','ahmad','temp');
INSERT INTO `user` VALUES (24,10,NULL,'test.ahmad19@hiast.edu.sy','$2y$10$WCrpQi/L6ExsQrBKmRI88Ov73MJuzA7jwbNYqFfHkE6o74qstIccO','ahmad','temp');
INSERT INTO `user` VALUES (25,10,NULL,'test.ahmad20@hiast.edu.sy','$2y$10$IZEJGWDLp1cMZHc1TMy6y.He./tWpiZmBeRNGUAp9/KmphToc7bOG','ahmad','temp');
INSERT INTO `user` VALUES (26,10,NULL,'test.ahmad21@hiast.edu.sy','$2y$10$p8WzI5yR16xwRfNVuk1EMOQlPf42/IovVgR7NK.ndU7OiJxfha8u2','ahmad','temp');
INSERT INTO `user` VALUES (27,10,NULL,'test.ahmad22@hiast.edu.sy','$2y$10$D/L4sT.LKQfJqpkZJSZIce86odit0Eg3FlLnJV1y3Dx4snX/4L8.C','ahmad','temp');
INSERT INTO `user` VALUES (28,10,NULL,'test.ahmad23@hiast.edu.sy','$2y$10$ogWgKoqgJ.xpHLHX/rxJnu80ACcFABFFVpYWyQau7PBGHcIo6B9QC','ahmad','temp');
INSERT INTO `user` VALUES (29,10,NULL,'test.ahmad24@hiast.edu.sy','$2y$10$9d9UBdai6yfHAkduJVobouF1/1Xusp59p84h1X07FuNG8oWgTmyke','ahmad','temp');
INSERT INTO `user` VALUES (30,10,NULL,'test.ahmad25@hiast.edu.sy','$2y$10$gKApZOnAMOR3IcMRGtFTiONVCxYTXS97rSRYuoU6nqxOappLGjH.S','ahmad','temp');
INSERT INTO `user` VALUES (31,10,NULL,'test.ahmad26@hiast.edu.sy','$2y$10$jVYzb1f6J9DyhcZZ6HlT7.IV.8YNyhoN5gah7np57dRBNkXEUMi0C','ahmad','temp');
INSERT INTO `user` VALUES (32,10,NULL,'test.ahmad27@hiast.edu.sy','$2y$10$inpXoYekvA1PLdJMvckYJe0o/KB/vu/E7VLGiVZjVoT4aJz65miVO','ahmad','temp');
INSERT INTO `user` VALUES (33,10,NULL,'test.ahmad28@hiast.edu.sy','$2y$10$8eG3V/cmp6bbyiyREEWbBu9wgsBCNPIUiHsWP4xf8tOkdVfEmZCu.','ahmad','temp');
INSERT INTO `user` VALUES (34,10,NULL,'test.ahmad29@hiast.edu.sy','$2y$10$qwsA0WjtzaJyOSuhjp7Q3uEEJ5LNEYpu2mYrgQnI/gmV1oIFBn68e','ahmad','temp');
INSERT INTO `user` VALUES (35,10,NULL,'test.ahmad30@hiast.edu.sy','$2y$10$HjbvQKY8Ihp4Zqa/jlgdh.mIDgVNxU/v5KLBADe3m8P0mWeDUDxJe','ahmad','temp');
INSERT INTO `user` VALUES (36,10,NULL,'test.ahmad31@hiast.edu.sy','$2y$10$q6rJoxBzIyJvqyDzD9I1wOQEQueiNfKJ0a9GZWwarfSkv7Ec2sVha','ahmad','temp');
INSERT INTO `user` VALUES (37,10,NULL,'test.ahmad32@hiast.edu.sy','$2y$10$f2oHYSX8k285sZiOABwUDOvu7YgZ6opf3t9QjJVP/pmAuPTGNm7Y2','ahmad','temp');
INSERT INTO `user` VALUES (38,10,NULL,'test.ahmad33@hiast.edu.sy','$2y$10$FxBs/0/F1ctDUPf9o1KZwezy6VlNJp80JP9WckygmgZicTPHXGQdm','ahmad','temp');
INSERT INTO `user` VALUES (39,10,NULL,'test.ahmad34@hiast.edu.sy','$2y$10$UEYE9OCv3oKRpsuUajLK6.OaPOkTTBHTuE30ArQJFzXWP7NPxzy5m','ahmad','temp');
INSERT INTO `user` VALUES (40,10,NULL,'test.ahmad35@hiast.edu.sy','$2y$10$nmZB3FVmJhwQUB2NgNfNEOIFa4MsVGNNvRNl1p//aaEXAGQdOyFU2','ahmad','temp');
INSERT INTO `user` VALUES (41,10,NULL,'test.ahmad36@hiast.edu.sy','$2y$10$W5hZuFqbeLGdB9BbjQPx8u94oag8dWTDKP0jEmwaFUESqIQYFZT/2','ahmad','temp');
INSERT INTO `user` VALUES (42,10,NULL,'test.ahmad37@hiast.edu.sy','$2y$10$bITmGLXNVESWxNkxCMRE4.Rxe/4XUJ7yCLdVc71CqMJVGZJHjUag.','ahmad','temp');
INSERT INTO `user` VALUES (43,10,NULL,'test.ahmad38@hiast.edu.sy','$2y$10$93mOXBjNJ324rGR5tANcxucBb1dKRi7ndB5uKA7G9syJNhUr9p9Za','ahmad','temp');
INSERT INTO `user` VALUES (44,10,NULL,'test.ahmad39@hiast.edu.sy','$2y$10$wy5qhupIezP0y6GPNnCTKeYOHEFoz.4M0QKhcbosIba16OgYDr7aG','ahmad','temp');
INSERT INTO `user` VALUES (45,10,NULL,'test.ahmad40@hiast.edu.sy','$2y$10$jWolWx6uZqR53yogsOJkoufhIYHdGW3TD8CS7mSegJUn04.n12GyW','ahmad','temp');
INSERT INTO `user` VALUES (46,10,NULL,'test.ahmad41@hiast.edu.sy','$2y$10$KovxC9rVrA2ThSIXYul9Q.wsAlR0y8212R3ybVwZFVeuFD0m2icbq','ahmad','temp');
INSERT INTO `user` VALUES (47,10,NULL,'test.ahmad42@hiast.edu.sy','$2y$10$LqBGHDotmBKWUVp0BSdRkOzgquVe7HeUZATyt9i.WV1ni9NV/nssK','ahmad','temp');
INSERT INTO `user` VALUES (48,10,NULL,'test.ahmad43@hiast.edu.sy','$2y$10$tgp4gI8.kgSBIXMKZXOWWu7RhHcXGHM2x.EOI2OpXJsucjVKQnfxe','ahmad','temp');
INSERT INTO `user` VALUES (49,10,NULL,'test.ahmad44@hiast.edu.sy','$2y$10$TOsWyhpCefxmx/0oTzNQqeguhOIbkOzcK3b59n8zXDJCHP3Qp39X.','ahmad','temp');
INSERT INTO `user` VALUES (50,10,NULL,'test.ahmad45@hiast.edu.sy','$2y$10$AABwmje2QzYK5Byhbfcu6.u7EG1Jf0BaUfnNeJoLubz3ePnCa9UYa','ahmad','temp');
INSERT INTO `user` VALUES (51,10,NULL,'test.ahmad46@hiast.edu.sy','$2y$10$WzuBuxFWq99n7vgFTpnm/ebhcK9WWS/W3CPS1jOO73i04/ysW30bO','ahmad','temp');
INSERT INTO `user` VALUES (52,10,NULL,'test.ahmad47@hiast.edu.sy','$2y$10$AiOYaxFLRkc0e8sPqQSyte1cuYvqFSQCExhu0svKCCQfD4F91JJXS','ahmad','temp');
INSERT INTO `user` VALUES (53,10,NULL,'test.ahmad48@hiast.edu.sy','$2y$10$p.h32Cc7cuYs8RYrOgK0DOsDaDZQYN4Q.iWRKjeSsBjsKbJ4FIM7.','ahmad','temp');
INSERT INTO `user` VALUES (54,10,NULL,'test.ahmad49@hiast.edu.sy','$2y$10$7laW9iYSugjq813iheS9PeDzKFcEpY6xnrGiuBiiBnQy1HqTweI.S','ahmad','temp');
INSERT INTO `user` VALUES (55,10,NULL,'test.ahmad50@hiast.edu.sy','$2y$10$dSEFRJmHJxLkmVL0966K0u2ix.HfzazlvW31uZpYyKGjjMwZiiNe.','ahmad','temp');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_additional_info`
--

DROP TABLE IF EXISTS `user_additional_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_additional_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `additional_info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_and_additional_info_id_UNIQUE` (`user_id`,`additional_info_id`),
  KEY `fk_user_additional_info_additional_info1_idx` (`additional_info_id`),
  KEY `fk_user_additional_info_user1_idx` (`user_id`),
  CONSTRAINT `fk_user_additional_info_additional_info1` FOREIGN KEY (`additional_info_id`) REFERENCES `additional_info` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_additional_info_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_additional_info`
--

LOCK TABLES `user_additional_info` WRITE;
/*!40000 ALTER TABLE `user_additional_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_additional_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `view_design_info`
--

DROP TABLE IF EXISTS `view_design_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `view_design_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `additional_info_id` int(11) NOT NULL,
  `column_order` int(11) DEFAULT NULL,
  `row_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `column_order_and_row_order_UNIQUE` (`column_order`,`row_order`),
  KEY `fk_view_category_content_info_additional_info1_idx` (`additional_info_id`),
  CONSTRAINT `fk_view_category_content_info_additional_info1` FOREIGN KEY (`additional_info_id`) REFERENCES `additional_info` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_design_info`
--

LOCK TABLES `view_design_info` WRITE;
/*!40000 ALTER TABLE `view_design_info` DISABLE KEYS */;
INSERT INTO `view_design_info` VALUES (25,8,0,0);
INSERT INTO `view_design_info` VALUES (26,9,1,0);
INSERT INTO `view_design_info` VALUES (27,12,1,1);
INSERT INTO `view_design_info` VALUES (28,7,2,0);
/*!40000 ALTER TABLE `view_design_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `year_season`
--

DROP TABLE IF EXISTS `year_season`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `year_season` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_bound` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `year_season`
--

LOCK TABLES `year_season` WRITE;
/*!40000 ALTER TABLE `year_season` DISABLE KEYS */;
INSERT INTO `year_season` VALUES (1,'2022-04-12 05:41:42');
INSERT INTO `year_season` VALUES (2,'2022-04-12 17:02:42');
/*!40000 ALTER TABLE `year_season` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-14 21:27:14
