<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;
    
    protected $table = "cart_detail";
    
    protected $fillable = [
        'id',
        'cart_id',
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
