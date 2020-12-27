<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin Bumi Flora',
            'email' => 'admin@bumiflora.com',
            'phone' => '-',
            'role' => 'admin',
            'password' => bcrypt('adminbumiflora'),
        ]);

        $user->syncRoles('root');
    }
}
