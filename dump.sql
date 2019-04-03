/*Table structure for table `tbl_content` */

CREATE DATABASE `viloveul_contoh`;

DROP TABLE IF EXISTS `viloveul_contoh`.`tbl_content`;

CREATE TABLE `viloveul_contoh`.`tbl_content` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text,
  PRIMARY KEY (`id`),
  KEY `judul` (`judul`),
  FULLTEXT KEY `isi` (`isi`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_content` */

insert  into `viloveul_contoh`.`tbl_content`(`id`,`judul`,`isi`) values 
(1,'Halo','isi dari halo'),
(2,'Morning','Selamat pagi semuanya'),
(3,'Hai','Salam kenal'),
(4,'Aku','Aku adalah aku'),
(5,'Kamu','Kamuitu siapa sebenarnya ?'),
(6,'Dia','Dia lagi dia lagi');