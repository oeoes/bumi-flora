<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin Bumi Flora',
            'email' => 'admin@bumiflora.com',
            'phone' => '-',
            'role' => 'admin',
            'password' => bcrypt('adminbumiflora'),
        ]);
    }
}
