<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use Translatable;
    public $translatedAttributes = ['name' , 'description'];
    protected  $fillable = ['name' , 'description' , 'category_id' , 'purchase_price' , 'sale_price' , 'stock', 'image'];
    protected $appends = ['image_path' , 'profit_percent'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getImagePathAttribute(){
        return asset('uploads/products_images/' . $this->image);
    }

    public function getProfitPercentAttribute(){
        $profit = $this->purchase_price - $this->sale_price ;
        $profit_percent = $profit * 100 / $this->purchase_price;
        return $profit_percent;
    }

    public function orders(){
        return $this->belongsToMany(Order::class , 'product_order');
    }
}
