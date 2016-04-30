-- MySQL dump 10.15  Distrib 10.0.23-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: tolkien
-- ------------------------------------------------------
-- Server version	10.0.23-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appears`
--

DROP TABLE IF EXISTS `appears`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appears` (
  `appearsid` int(11) NOT NULL AUTO_INCREMENT,
  `bookid` int(11) NOT NULL,
  `characterid` int(11) NOT NULL,
  PRIMARY KEY (`appearsid`),
  KEY `bookid_idx` (`bookid`),
  KEY `characterid_idx` (`characterid`),
  CONSTRAINT `bookid` FOREIGN KEY (`bookid`) REFERENCES `books` (`bookid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `characterid` FOREIGN KEY (`characterid`) REFERENCES `characters` (`characterid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appears`
--

LOCK TABLES `appears` WRITE;
/*!40000 ALTER TABLE `appears` DISABLE KEYS */;
INSERT INTO `appears` VALUES (1,1,1),(2,1,2),(3,3,1),(4,3,2),(5,3,3),(6,3,4),(7,3,5),(8,3,6),(9,4,10),(10,4,1),(11,4,2),(12,4,3),(13,4,4),(14,4,5),(15,4,6),(16,5,1),(17,5,2),(18,5,3),(19,5,4),(20,5,5),(38,1,52),(39,3,52),(40,5,52),(41,4,52),(42,3,53),(43,3,54),(44,5,54),(45,3,55),(46,3,56);
/*!40000 ALTER TABLE `appears` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `bookid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) DEFAULT NULL,
  `storyid` int(11) NOT NULL,
  PRIMARY KEY (`bookid`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  KEY `storyid_idx` (`storyid`),
  CONSTRAINT `storyid` FOREIGN KEY (`storyid`) REFERENCES `stories` (`storyid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (1,'The Hobbit',1),(3,'The Fellowship of the Ring',2),(4,'The Two Towers',2),(5,'The Return of the King',2);
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `characters`
--

DROP TABLE IF EXISTS `characters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `characters` (
  `characterid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `race` varchar(45) NOT NULL,
  `side` varchar(45) NOT NULL,
  PRIMARY KEY (`characterid`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `characters`
--

LOCK TABLES `characters` WRITE;
/*!40000 ALTER TABLE `characters` DISABLE KEYS */;
INSERT INTO `characters` VALUES (1,'Gandolf','Man','good'),(2,'Bilbo','Hobbit','good'),(3,'Samwise','Hobbit','good'),(4,'Pippin','Hobbit','good'),(5,'Merry','Hobbit','good'),(6,'Saruman','Man','evil'),(10,'Treebeard','Ent','good'),(52,'Aragon','Hobbit','good'),(53,'Boromir','Human','evil'),(54,'Arwin','Hobbit','good'),(55,'newchar','ok','good'),(56,'char2','lol','good');
/*!40000 ALTER TABLE `characters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login` (
  `loginid` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `user` varchar(45) DEFAULT NULL,
  `date` datetime NOT NULL,
  `action` varchar(10) NOT NULL,
  PRIMARY KEY (`loginid`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login`
--

LOCK TABLES `login` WRITE;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` VALUES (1,'198.18.0.154','aa','2016-03-10 16:01:17','Fail'),(3,'198.18.0.154','admin','2016-03-10 16:01:29','Success'),(5,'198.18.0.154','aaaaa','2016-03-10 16:10:08','Fail'),(6,'198.18.0.154','pari','2016-03-10 16:27:35','Success'),(7,'198.18.0.154','user','2016-03-10 16:27:50','Fail'),(8,'198.18.1.106','ssdsaada','2016-03-10 16:35:15','Fail'),(9,'198.18.0.154','what','2016-03-10 20:45:46','Fail'),(10,'198.18.0.154','admin','2016-03-10 20:46:00','Success'),(11,'198.18.0.154','admin','2016-03-10 20:48:13','Success'),(12,'198.18.0.154','admin','2016-03-10 21:44:08','Success'),(13,'198.18.0.154','admin','2016-03-10 21:44:50','Success'),(14,'198.18.0.154','admin','2016-03-10 21:45:40','Success'),(15,'198.18.0.154','admin','2016-03-10 21:48:00','Success'),(16,'198.18.0.154','pari','2016-03-10 21:49:58','Success'),(17,'198.18.0.154','admin','2016-03-10 22:55:19','Success'),(18,'198.18.0.154','admin','2016-03-10 22:56:09','Success'),(19,'198.18.0.154','hacker','2016-03-10 22:56:16','Fail'),(20,'198.18.0.154','abhimanyu','2016-03-10 22:56:35','Fail'),(21,'198.18.0.154','admin','2016-03-10 22:56:41','Success'),(22,'198.18.0.154','abhimanyu','2016-03-10 22:57:05','Fail'),(23,'198.18.0.154','abhimanyu','2016-03-10 22:57:16','Fail'),(24,'198.18.0.154','abhimanyu','2016-03-10 22:57:20','Success'),(25,'198.18.0.154','admin','2016-03-10 22:58:58','Success'),(26,'198.18.0.154','abhimanyu','2016-03-10 22:59:38','Fail'),(27,'198.18.0.154','admin','2016-03-10 22:59:46','Success'),(28,'198.18.0.154','admin','2016-03-10 23:04:55','Success'),(29,'198.18.0.154','haha','2016-03-10 23:05:18','Fail'),(30,'198.18.0.154','admin','2016-03-10 23:06:18','Success'),(31,'198.18.0.154','admin','2016-03-10 23:16:53','Success'),(32,'198.18.0.154','admin','2016-03-11 01:35:57','Success'),(33,'198.18.0.154','pari','2016-03-11 01:48:51','Success'),(34,'198.18.0.154','admin','2016-03-14 20:09:16','Success'),(35,'198.18.0.154','abhi','2016-03-14 20:10:23','Fail'),(36,'198.18.0.154','s','2016-03-14 20:10:38','Fail'),(37,'198.18.0.154','HACKER','2016-03-14 20:10:47','Fail'),(38,'198.18.0.154','ssss','2016-03-14 20:11:02','Fail'),(39,'198.18.0.154','sssssss','2016-03-14 20:11:03','Fail'),(40,'198.18.0.154','pari','2016-03-14 20:11:11','Success'),(41,'198.18.0.154','admin','2016-03-14 20:11:41','Success'),(42,'198.18.0.154','HACKER','2016-03-14 20:11:59','Fail'),(43,'198.18.0.154','admin','2016-03-14 20:12:43','Fail'),(44,'198.18.0.154','admin','2016-03-14 20:12:51','Success'),(45,'198.18.0.154','pari','2016-03-14 20:14:52','Success'),(46,'198.18.0.154','admin','2016-03-14 20:15:57','Success'),(47,'198.18.0.154','admin','2016-03-14 20:17:19','Success'),(48,'198.18.0.154','pari','2016-03-14 20:18:22','Success'),(49,'198.18.0.154','admin','2016-03-14 20:18:27','Success'),(50,'198.18.0.154','admin','2016-03-14 20:19:40','Success'),(51,'198.18.0.154','pari','2016-03-14 20:19:56','Success'),(52,'198.18.0.154','admin','2016-03-14 20:23:31','Success'),(53,'198.18.0.154','pari','2016-03-14 20:23:38','Success'),(54,'198.18.0.154','admin','2016-03-14 20:27:25','Success'),(55,'198.18.0.154','pari','2016-03-14 20:27:33','Success'),(56,'198.18.0.154','admin','2016-03-14 20:30:24','Success'),(57,'198.18.0.154','pari','2016-03-14 20:31:02','Success'),(58,'198.18.0.154','admin','2016-03-14 20:31:21','Success'),(59,'198.18.0.154','pari','2016-03-14 20:31:43','Success'),(60,'198.18.0.154','admin','2016-03-14 20:36:59','Success'),(61,'198.18.0.154','pari','2016-03-14 20:37:11','Success'),(62,'198.18.0.154','admin','2016-03-14 20:43:59','Success'),(63,'198.18.1.190','admin','2016-03-27 14:42:11','Success'),(64,'198.18.1.190','admin','2016-03-27 14:47:46','Fail'),(65,'198.18.1.190','admin','2016-03-27 14:47:49','Fail'),(66,'198.18.1.190','admin','2016-03-27 14:47:53','Fail'),(67,'198.18.1.190','admin','2016-03-27 14:47:56','Fail'),(68,'198.18.1.190','admin','2016-03-27 14:47:59','Fail'),(69,'198.18.1.190','admin','2016-03-27 14:48:04','Success'),(70,'198.18.1.190','admin','2016-03-27 14:48:15','Fail'),(71,'198.18.0.154','admin','2016-03-29 15:51:32','Success'),(72,'198.18.0.154','admin','2016-03-30 18:42:51','Success'),(73,'198.18.0.154','admin','2016-03-31 15:55:49','Success'),(74,'198.18.0.154','hacks','2016-03-31 15:56:03','Fail'),(75,'198.18.0.154','admin','2016-03-31 15:56:07','Success'),(76,'198.18.0.154','hacker','2016-03-31 15:58:56','Fail'),(77,'198.18.0.154','admin','2016-03-31 15:59:41','Success'),(78,'198.18.0.154','admin','2016-03-31 16:11:47','Success'),(79,'198.18.0.154','hacker','2016-03-31 16:41:36','Fail'),(80,'198.18.0.154','hacker','2016-03-31 21:20:03','Fail'),(81,'198.18.0.154','fake','2016-03-31 21:22:30','Fail'),(82,'198.18.0.154','admin','2016-03-31 21:22:41','Success'),(83,'198.18.0.154','admin','2016-03-31 23:11:17','Success'),(84,'198.18.0.154','admin','2016-03-31 23:23:38','Success'),(85,'198.18.0.154','pari','2016-03-31 23:25:22','Success'),(86,'198.18.0.154','admin','2016-03-31 23:26:48','Success'),(87,'198.18.0.154','admin','2016-03-31 23:27:02','Success'),(88,'198.18.0.154','pari','2016-04-01 00:03:44','Success'),(89,'198.18.0.154','admin','2016-04-01 00:04:18','Fail'),(90,'198.18.0.154','admin','2016-04-01 00:04:22','Success'),(91,'198.18.0.154','admin','2016-04-01 00:04:51','Success'),(92,'198.18.0.154','admin','2016-04-01 00:07:56','Success'),(93,'198.18.0.154','admin','2016-04-01 00:09:10','Success'),(94,'198.18.0.154','admin','2016-04-01 00:29:01','Success'),(95,'198.18.0.154','admin','2016-04-01 00:29:40','Success'),(96,'198.18.0.154','admin','2016-04-01 00:30:48','Success'),(97,'198.18.0.154','admin','2016-04-01 00:31:19','Success'),(98,'198.18.0.154','admin','2016-04-01 00:40:30','Success'),(99,'198.18.0.154','admin','2016-04-01 00:42:30','Success'),(100,'198.18.0.154','admin','2016-04-01 00:44:07','Success'),(101,'198.18.0.154','admin','2016-04-01 01:10:23','Success'),(102,'198.18.0.154','admin','2016-04-01 01:12:14','Success'),(103,'198.18.0.154','hacker','2016-04-16 10:31:32','Fail'),(104,'198.18.0.154','admin','2016-04-17 00:34:33','Fail'),(105,'198.18.0.154','admin','2016-04-17 00:34:40','Fail'),(106,'198.18.0.154','admin','2016-04-17 00:35:00','Fail'),(107,'198.18.0.154','admin','2016-04-17 00:35:10','Fail'),(108,'198.18.0.154','pari','2016-04-17 00:35:19','Fail'),(109,'198.18.0.154','pari','2016-04-17 00:38:25','Fail'),(110,'198.18.0.154','pari','2016-04-17 00:42:25','Fail'),(111,'198.18.0.154','pari','2016-04-17 00:43:49','Fail'),(112,'198.18.0.154','pari','2016-04-17 00:45:45','Fail'),(113,'198.18.0.154','pari','2016-04-17 00:46:59','Success'),(114,'198.18.0.154','admin','2016-04-17 00:48:04','Success'),(115,'198.18.0.154','hax','2016-04-17 00:48:19','Fail'),(116,'198.18.0.154','ffake','2016-04-17 00:48:28','Fail'),(117,'198.18.0.154','lola','2016-04-17 00:48:32','Fail'),(118,'198.18.0.154','aaaa','2016-04-17 00:48:35','Fail'),(119,'198.18.0.154','ad','2016-04-17 00:48:38','Fail'),(120,'198.18.0.154','admin','2016-04-17 00:48:42','Success'),(121,'198.18.0.154','admin','2016-04-17 00:49:04','Success'),(122,'198.18.0.154','pari','2016-04-17 00:49:54','Success'),(123,'198.18.0.154','pari','2016-04-17 01:04:51','Success'),(124,'198.18.0.154','admin','2016-04-17 01:21:32','Success'),(125,'198.18.0.154','pari','2016-04-20 21:26:21','Success'),(126,'198.18.0.154','pari','2016-04-20 21:27:25','Success');
/*!40000 ALTER TABLE `login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pictures`
--

DROP TABLE IF EXISTS `pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pictures` (
  `pictureid` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(512) NOT NULL,
  `characterid` int(11) NOT NULL,
  PRIMARY KEY (`pictureid`),
  KEY `characterid_idx` (`characterid`),
  CONSTRAINT `piccharacterid` FOREIGN KEY (`characterid`) REFERENCES `characters` (`characterid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pictures`
--

LOCK TABLES `pictures` WRITE;
/*!40000 ALTER TABLE `pictures` DISABLE KEYS */;
INSERT INTO `pictures` VALUES (1,'http://img4.wikia.nocookie.net/__cb20140731215229/lotr/images/thumb/a/a6/GandalfTDOMTextlessPoster.jpg/220px-GandalfTDOMTextlessPoster.jpg',1),(2,'http://img1.wikia.nocookie.net/__cb20131211160320/lotr/images/thumb/c/ca/148531_433225943406882_1431199174_n.jpg/220px-148531_433225943406882_1431199174_n.jpg',2),(3,'http://img4.wikia.nocookie.net/__cb20070623123241/lotr/images/thumb/2/20/Sam.jpg/220px-Sam.jpg',3),(4,'http://img2.wikia.nocookie.net/__cb20060310083048/lotr/images/0/0a/Pippinprintscreen.jpg',4),(5,'http://img4.wikia.nocookie.net/__cb20080318214905/lotr/images/thumb/d/d8/Merry1.jpg/220px-Merry1.jpg',5),(6,'http://img3.wikia.nocookie.net/__cb20140426125614/lotr/images/thumb/a/a0/Saruman_%21.jpeg/220px-Saruman_%21.jpeg',6),(7,'http://img4.wikia.nocookie.net/__cb20120312183330/lotr/images/thumb/2/23/TreebeardatIsengard.png/220px-TreebeardatIsengard.png',10),(32,'http://img1.wikia.nocookie.net/__cb20080318215744/lotr/images/thumb/4/43/Aragorn3.jpg/220px-Aragorn3.jpg',52),(33,'http://img3.wikia.nocookie.net/__cb20121023114949/lotr/images/thumb/d/de/Boromir_-_FOTR.png/220px-Boromir_-_FOTR.png',53),(34,'http://vignette4.wikia.nocookie.net/lotr/images/d/dd/100_beautiful_arwen.jpg/revision/latest?cb=20110313201140',54),(35,'s',55),(36,'a',56);
/*!40000 ALTER TABLE `pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stories`
--

DROP TABLE IF EXISTS `stories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stories` (
  `storyid` int(11) NOT NULL AUTO_INCREMENT,
  `story` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`storyid`),
  UNIQUE KEY `title_UNIQUE` (`story`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stories`
--

LOCK TABLES `stories` WRITE;
/*!40000 ALTER TABLE `stories` DISABLE KEYS */;
INSERT INTO `stories` VALUES (1,'The Hobbit'),(2,'The Lord of the Rings');
/*!40000 ALTER TABLE `stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `salt` varchar(64) NOT NULL,
  `email` varchar(256) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','0465df62bf2f12467bb724c77a3ef22eb59dab8082e50567d3bbe3b5f7178ae4','52d98a612368acc0308b62d3fbd817802dff0d262f09f406d4044c0832795f0b','ralphie@colorado.edu'),(2,'abhimanyu','681343a72790bda0862badd35d4f0a48d8d4d33540753526841373d74276bfaf','6c7f0da65e2671814dabaa114cff3223a55d22456d82ab01f37e2fbb98ffee5f','abhi@colorado.edu'),(3,'lol','a5813aad8306e65cb7ad3714bc899b4481590b50c529d571481f8669fe984ad6','e99583ab9e779a03eda203bb3309f9bf7f4295fed7a8e31b4cef9eef724d61fc','test@colorado.edu'),(6,'newuser','703d730754f937c59c635e9cf7e45c062ac7ef4990cafe62ef173f6d5fb26aba','df53150e88dae48bf543e42a0beb8c2d7e3cb7de9f61f1c5ef83dbda7787b9da','newuser@colorado.edu'),(7,'pari','ba2e945b7d1fc1eaf8a0d77167eca259257478059589cbf8835608caf26b509d','97116f56a85dcf66655ad3a7f072ed455ca431d2427b0ba2254fab07249f89a4','pari@colorado.edu'),(8,'zoro','4d2e7fc3cd0e75684febb79adb7f7fc2801b06d119bd7bb90fbc96420d974c4f','b138abc671af4e3f2524fdad7b1049a139a026386f82ade240c4057117725aa4','zoro@colorado.edu'),(9,'hacker','81ee45d2f813027d7e38ff3cb614db8020db95d4e2d281c765e12b29c6e54022','c6878d5adb19c0272bffc901c61d9dc15cf30518a2104da56189cd21cd67e571','hacker@colorado.edu'),(10,'a','7677d03a8b8748db2890e25627aafcb218f981dec612f397dd02b99460a37cdc','c1fb010feb0c6bfe243af80f58d2b06af2599c413d48d8d7ce89801aac860737','a');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-29 19:08:05
