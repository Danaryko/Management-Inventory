<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockInItem extends Model
{
    protected $fillable = [
        'stock_in_id',
        'product_id',
        'quantity',
        'purchase_price',
        'subtotal',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function stockIn()
    {
        return $this->belongsTo(StockIn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
