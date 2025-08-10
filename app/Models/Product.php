<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'sku',
        'brand',
        'size',
        'color',
        'price',
        'stock_quantity',
        'min_stock_level',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockInItems()
    {
        return $this->hasMany(StockInItem::class);
    }

    public function stockOutItems()
    {
        return $this->hasMany(StockOutItem::class);
    }

    public function stockIns()
    {
        return $this->hasManyThrough(StockIn::class, StockInItem::class);
    }

    public function stockOuts()
    {
        return $this->hasManyThrough(StockOut::class, StockOutItem::class);
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }
}
