<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'name',
        'description',
        'slug',
        'price',
        'original_price',
        'image_avatar',
        'short_description',
        'list_image',
        'bard_id',
        'status',
        'created_at',
        'updated_at',
    ];
    
    public function bard() {
        return $this->belongsTo(Bard::class, 'bard_id');
    }
    
    public function categories() {
        return $this->belongsToMany(Category::class);
    }
}
