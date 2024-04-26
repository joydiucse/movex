





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
