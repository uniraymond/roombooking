/*
SQLyog Professional v12.09 (64 bit)
MySQL - 5.6.17 : Database - booking
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`booking` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `booking`;

/*Table structure for table `book` */

DROP TABLE IF EXISTS `book`;

CREATE TABLE `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_start_date_time` datetime NOT NULL,
  `book_finish_date_time` datetime NOT NULL,
  `book_duration` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `book_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `book_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `book` */

insert  into `book`(`id`,`room_id`,`user_id`,`book_start_date_time`,`book_finish_date_time`,`book_duration`) values (3,3,4,'2016-03-13 14:45:00','0000-00-00 00:00:00',3),(4,3,4,'2016-03-13 17:45:00','2016-03-13 17:45:00',3),(5,3,4,'2016-03-13 17:45:00','2016-03-13 17:45:00',3),(6,2,2,'2016-03-13 14:15:00','2016-03-13 14:15:00',3),(7,2,2,'2016-03-13 11:15:00','2016-03-13 14:15:00',3),(8,2,2,'2016-03-13 11:15:00','2016-03-13 14:15:00',3);

/*Table structure for table `room` */

DROP TABLE IF EXISTS `room`;

CREATE TABLE `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` varchar(20) DEFAULT NULL,
  `room_name` varchar(200) DEFAULT NULL,
  `room_location` varchar(200) DEFAULT NULL,
  `room_type` enum('student','staff','professor','others') DEFAULT NULL,
  `room_open_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `room` */

insert  into `room`(`id`,`room_id`,`room_name`,`room_location`,`room_type`,`room_open_time`) values (1,NULL,'Meeting Room A','Main Building','student','9-22'),(2,NULL,'Meeting Room B','Main Building','student','9-23'),(3,NULL,'Staff Meeting Room','Library A','staff','9-18'),(4,NULL,'Professor Meeting Room','Main Building ','professor','9-18'),(5,NULL,'Hall Room','Hall','others','9-15');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `group` enum('student','staff','professor') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`id`,`fname`,`lname`,`student_id`,`group`) values (1,'Alex','Master','AM992932','student'),(2,'Grace','Lim','GL220832','student'),(3,'Steven','Jobs','SJ392839','student'),(4,'Downal','Trump','DT059922','staff');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
