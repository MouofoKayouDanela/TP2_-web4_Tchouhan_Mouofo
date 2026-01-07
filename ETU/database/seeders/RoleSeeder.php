<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
       public function run()
    {
        $sql = file_get_contents(database_path() . '/seeders/roles.sql');
        DB::statement($sql);
    }
}
