<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    
    protected $table = "address";
    
    protected $fillable = [
        'id',
        'street',
        'city_id',
        'district_id',
        'ward_id',
        'user_id',
        'phone',
        'is_defaut',
        'created_at',
        'updated_at',
    ];
    
    public function city() {
        return $this->belongsTo(City::class);
    }
    
    public function district() {
        return $this->belongsTo(District::class);
    }
    
    public function ward() {
        return $this->belongsTo(Ward::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
