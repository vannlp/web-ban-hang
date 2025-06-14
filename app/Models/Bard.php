<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bard extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'name',
        'description',
        'image_avatar',
        'created_at',
        'updated_at',
    ];
    
    public function products() {
        return $this->hasMany(Product::class, 'bard_id', 'id');
    }
}
