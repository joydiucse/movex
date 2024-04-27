





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

ALTER TABLE `movex`.`parcels`
    CHANGE `selling_price` `selling_price` DECIMAL(8,2) DEFAULT 0.00 NULL COMMENT 'parcel actual price for damage of parcel money return purpose',
    ADD COLUMN `product_details` TEXT NULL AFTER `selling_price`;

ALTER TABLE `movex`.`parcels`
    ADD COLUMN `pathao_city` INT NULL AFTER `product_details`,
  ADD COLUMN `pathao_zone` INT NULL AFTER `pathao_city`,
  ADD COLUMN `pathao_area` INT NULL AFTER `pathao_zone`;


CREATE TABLE `pathao_zones` (
                                `id` int NOT NULL AUTO_INCREMENT,
                                `city_id` int DEFAULT NULL,
                                `zone_id` int DEFAULT NULL,
                                `zone_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
                                `created_at` timestamp NOT NULL,
                                `updated_at` timestamp NOT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=391 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `pathao_cities` (
                                 `id` int NOT NULL AUTO_INCREMENT,
                                 `city_id` int NOT NULL,
                                 `city_name` varchar(100) NOT NULL,
                                 `created_at` timestamp NOT NULL,
                                 `updated_at` timestamp NOT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `pathao_areas` (
                                `id` int NOT NULL AUTO_INCREMENT,
                                `zone_id` int DEFAULT NULL,
                                `area_id` int DEFAULT NULL,
                                `area_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
                                `home_delivery_available` varchar(20) DEFAULT NULL,
                                `pickup_available` varchar(20) DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=896 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
