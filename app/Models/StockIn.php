<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $fillable = [
        'reference_number',
        'user_id',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

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
