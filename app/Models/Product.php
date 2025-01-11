<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'product_name',
        'cost_price',
        'sale_price',
        'wholesale_price',
        'supplier_id',
        'category_id',
        'coop_id',
        'brand_id',
        'stock',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes');
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    // Relasi dengan Brand, Category, Coop
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function coop()
    {
        return $this->belongsTo(Coop::class);
    }
}
