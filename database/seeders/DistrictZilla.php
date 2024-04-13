<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DistrictZilla extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path   = base_path('public/admin/sql/district_zilla.sql');
        $sql    = file_get_contents($path);
        DB::unprepared($sql);
    }
}
