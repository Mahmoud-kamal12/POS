<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            'ar' =>  ['name' => 'منتج-' , 'desc' => 'وصف-'] ,
            'en' =>  ['name' => 'product-' , 'desc' => 'desc-']
        ];

        for ($i = 1 ; $i < 100 ; $i++) {
            Product::create([
                'category_id' => Category::all()->random()->id,
                'ar' => ['name' => $products['ar']['name'] . $i , 'description' => $products['ar']['desc'] . $i],
                'en' => ['name' => $products['en']['name'] . $i , 'description' => $products['en']['desc'] . $i],
                'purchase_price' => 100,
                'sale_price' => 150,
                'stock' => 10

            ]);
        }
    }
}
