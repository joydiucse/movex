<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `settings` (`title`, `value`, `created_at`, `updated_at`) VALUES
('return_charge', '40', '2021-07-24 11:22:58', '2021-07-24 11:22:58'),
('fragile_charge', '20', '2021-07-24 11:22:58', '2021-07-24 11:22:58'),
('pickup_accept_start', '18', '2021-07-24 11:23:04', '2021-07-24 11:23:04'),
('pickup_accept_end', '24', '2021-07-24 11:23:04', '2021-07-24 11:23:04'),
('outside_dhaka_days', '10', '2021-07-24 11:23:04', '2021-07-24 11:23:04'),
('sms_provider', 'reve', '2021-07-25 00:15:32', '2021-07-25 00:48:33'),
('mask_name', 'GreenX', '2021-07-25 00:15:32', '2021-07-25 00:15:32'),
('sms_cli', '', '2021-07-25 00:15:32', '2021-07-25 00:15:32'),
('gp_sms_url', 'https://gpcmp.grameenphone.com/ecmapigw/webresources/ecmapigw.v2', '2021-07-25 00:15:32', '2021-07-25 00:15:32'),
('gp_username', 'GreenxADMIN', '2021-07-25 00:15:32', '2021-07-25 00:15:32'),
('gp_password', 'Laravel@2019@', '2021-07-25 00:15:32', '2021-07-25 00:15:32'),
('onnorokom_url', 'https://api2.onnorokomsms.com/sendSMS.asmx?wsdl', '2021-07-25 00:15:32', '2021-07-25 00:15:32'),
('onnorokom_username', '01725402187', '2021-07-25 00:15:32', '2021-07-25 00:15:32'),
('onnorokom_password', '95a3c57eb7', '2021-07-25 00:15:32', '2021-07-25 00:15:32'),
('reve_url', 'https://smpp.ajuratech.com:7790/sendtext', '2021-07-25 00:15:32', '2021-07-25 00:15:48'),
('reve_api_key', 'aea92f6e89a648e2', '2021-07-25 00:15:32', '2021-07-25 00:15:48'),
('reve_secret', '903ebeed', '2021-07-25 00:15:32', '2021-07-25 00:15:48'),
('return_charge_dhaka', '40', '2021-08-05 10:52:57', '2021-08-05 10:52:57'),
('return_charge_sub_city', '40', '2021-08-05 10:52:57', '2021-08-05 10:52:57'),
('return_charge_outside_dhaka', '40', '2021-08-05 10:52:57', '2021-08-05 10:52:57')");
    }
}
