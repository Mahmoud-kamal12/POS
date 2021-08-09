<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'super',
            'last_name' => 'admin',
            'email' => 'admin@g.com',
            'password' => bcrypt('123456789')
        ]);
        $user->attachRole('super_admin');
    }
}
