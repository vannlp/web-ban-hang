<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    
    protected $table = "cities";
    
    protected $fillable = [
        'id',
        'code',
        'type',
        'name',
        'grab_code',
        'id_city_vnp',
        'name_city_vnp',
        'full_name',
        'description',
        'region_code',
        'region_name',
        'is_active',
        'deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'code_ghn',
    ];
}
