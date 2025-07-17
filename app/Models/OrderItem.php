<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'price',
        'final_price',
        'created_at',
        'updated_at',
    ];
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
