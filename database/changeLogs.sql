





##########################
### Marchant Changes:
Removed This Item -> 2024-04-25 (10:45 pm)
INSERT INTO `merchant_withdraws` (`id`, `withdraw_id`, `merchant_id`, `created_by`, `withdraw_batch_id`, `note`, `amount`, `status`, `withdraw_to`, `account_details`, `date`, `created_at`, `updated_at`) VALUES
(258, 'MVX73716598', 37, 1, NULL, '', 60435.00, 'pending', 'bank', '[\"Islami Bank Bangladesh Ltd.\",\"Barura Branch\",\"Mohammad  Younus\",\"20504210200939401\",\"125190495\"]', '2024-04-20', '2024-04-20 11:08:41', '2024-04-20 11:08:41');

##########################



--DB CHANGE::::
----------------------------------------------
-- 14-04-2024
----------------------------------------------
ALTER TABLE `third_parties` ADD `api_details` TEXT NULL DEFAULT NULL AFTER `phone_number`;

ALTER TABLE `parcels`
    CHANGE `selling_price` `selling_price` DECIMAL(8,2) DEFAULT 0.00 NULL COMMENT 'parcel actual price for damage of parcel money return purpose',
    ADD COLUMN `product_details` TEXT NULL AFTER `selling_price`;

ALTER TABLE `parcels`
    ADD COLUMN `pathao_city` INT NULL AFTER `product_details`,
  ADD COLUMN `pathao_zone` INT NULL AFTER `pathao_city`,
  ADD COLUMN `pathao_area` INT NULL AFTER `pathao_zone`;


CREATE TABLE `pathao_zones` (
                                `id` int NOT NULL AUTO_INCREMENT,
                                `city_id` int DEFAULT NULL,
                                `zone_id` int DEFAULT NULL,
                                `zone_name` varchar(100) CHARACTER SET utf8mb4  DEFAULT NULL,
                                `created_at` timestamp NOT NULL,
                                `updated_at` timestamp NOT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=391 DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE `pathao_cities` (
                                 `id` int NOT NULL AUTO_INCREMENT,
                                 `city_id` int NOT NULL,
                                 `city_name` varchar(100) NOT NULL,
                                 `created_at` timestamp NOT NULL,
                                 `updated_at` timestamp NOT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE `pathao_areas` (
                                `id` int NOT NULL AUTO_INCREMENT,
                                `zone_id` int DEFAULT NULL,
                                `area_id` int DEFAULT NULL,
                                `area_name` varchar(100) CHARACTER SET utf8mb4   DEFAULT NULL,
                                `home_delivery_available` varchar(20) DEFAULT NULL,
                                `pickup_available` varchar(20) DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=896 DEFAULT CHARSET=utf8mb4;


insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (1,32,'B. Baria','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (2,52,'Bagerhat','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (3,62,'Bandarban ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (4,34,'Barguna ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (5,17,'Barisal','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (6,53,'Bhola','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (7,9,'Bogra','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (8,8,'Chandpur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (9,15,'Chapainawabganj','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (10,2,'Chittagong','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (11,61,'Chuadanga','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (12,11,'Cox\'s Bazar','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (13,5,'Cumilla','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (14,1,'Dhaka','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (15,35,'Dinajpur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (16,18,'Faridpur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (17,6,'Feni','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (18,38,'Gaibandha','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (19,22,'Gazipur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (20,56,'Gopalgonj ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (21,30,'Habiganj','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (22,41,'Jamalpur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (23,19,'Jashore','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (24,27,'Jhalokathi','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (25,49,'Jhenidah','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (26,48,'Joypurhat','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (27,63,'Khagrachari','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (28,20,'Khulna','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (29,42,'Kishoreganj','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (30,55,'Kurigram ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (31,28,'Kushtia','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (32,40,'Lakshmipur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (33,57,'Lalmonirhat ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (34,43,'Madaripur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (35,60,'Magura ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (36,16,'Manikganj','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (37,50,'Meherpur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (38,12,'Moulvibazar','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (39,23,'Munsiganj','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (40,26,'Mymensingh','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (41,46,'Naogaon','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (42,54,'Narail ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (43,21,'Narayanganj','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (44,47,'Narshingdi','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (45,14,'Natore','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (46,44,'Netrakona','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (47,39,'Nilphamari','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (48,7,'Noakhali','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (49,24,'Pabna','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (50,37,'Panchagarh','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (51,29,'Patuakhali','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (52,31,'Pirojpur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (53,58,'Rajbari ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (54,4,'Rajshahi','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (55,59,'Rangamati ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (56,25,'Rangpur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (57,51,'Satkhira','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (58,64,'Shariatpur ','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (59,33,'Sherpur','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (60,10,'Sirajganj','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (61,45,'Sunamganj','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (62,3,'Sylhet','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (63,13,'Tangail','0000-00-00 00:00:00','0000-00-00 00:00:00');
insert  into `pathao_cities`(`id`,`city_id`,`city_name`,`created_at`,`updated_at`) values (64,36,'Thakurgaon ','0000-00-00 00:00:00','0000-00-00 00:00:00');
