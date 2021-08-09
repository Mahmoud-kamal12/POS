<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1 ; $i < 10 ; $i++) {
            Client::create([
                'name' => 'Client-'.$i,
                'phone' => ['01254780'.$i , '01154781'.$i],
                'address' => 'cairo'
            ]);
        }
    }
}
