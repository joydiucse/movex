<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use DB;

class PackagingAndChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `package_and_charges` (`id`, `package_type`, `charge`, `created_at`, `updated_at`) VALUES
(1, 'Poly', '5.00', '2021-07-29 05:29:16', '2021-07-29 05:29:16'),
(2, 'Bubble Poly', '10.00', '2021-07-29 05:29:16', '2021-07-29 05:29:16'),
(3, 'Box', '15.00', '2021-07-29 05:29:16', '2021-07-29 05:29:16'),
(4, 'Box Poly', '20.00', '2021-07-29 05:29:16', '2021-07-29 05:29:16')");
    }
}
