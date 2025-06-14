<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'name',
        'description',
        'image_avatar',
        'created_at',
        'updated_at',
        'status',
        'slug',
        'parent_id'
    ];
    
    public function products() {
        return $this->belongsToMany(Product::class);
    }
}
