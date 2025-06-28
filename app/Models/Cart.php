<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;  
    
    protected $fillable = [
        'id',
        'user_id',
        'session_id',
        'total_price',
        'final_price',
        'address_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    public function cartDetail() {
        return $this->hasMany(CartDetail::class, 'cart_id', 'id');
    }
}
