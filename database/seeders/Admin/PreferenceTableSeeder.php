<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use DB;

class PreferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `preferences` (`id`, `title`, `staff`, `merchant`, `created_at`, `updated_at`) VALUES
(1, 'read_merchant_api', 1, 1, NULL, NULL),
(2, 'merchant_api_update', 1, 1, NULL, NULL),
(3, 'create_parcel', 1, 1, NULL, '2021-07-31 17:10:09'),
(4, 'create_payment_request', 1, 1, NULL, '2021-07-31 17:10:10'),
(5, 'same_day', 1, 1, NULL, '2021-08-13 16:15:13'),
(6, 'next_day', 1, 1, NULL, '2021-07-31 17:10:10'),
(7, 'sub_city', 1, 1, NULL, '2021-07-31 17:10:10'),
(8, 'outside_dhaka', 1, 1, NULL, '2021-07-31 17:10:10')");
    }
}
