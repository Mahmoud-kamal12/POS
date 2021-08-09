<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class Categoryseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['ar'=> 'فئه-' , 'en' => 'cat-'];

        for ($i = 1 ; $i < 10 ; $i++) {
            Category::create([
                'ar' => ['name' => $categories['ar'] . $i],
                'en' => ['name' => $categories['en'] . $i]
            ]);
        }
    }
}
