<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $fillable = [
        'reference_number',
        'supplier_id',
        'user_id',
        'date',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(StockInItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, StockInItem::class);
    }
}
