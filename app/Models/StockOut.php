<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $fillable = [
        'reference_number',
        'user_id',
        'date',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(StockOutItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, StockOutItem::class);
    }
}
