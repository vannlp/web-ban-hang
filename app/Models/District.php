<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    
    protected $table = "districts";
    
    protected $fillable = [
        'id',
        'code',
        'type',
        'name',
        'id_district_vnp',
        'name_district_vnp',
        'full_name',
        'description',
        'city_id',
        'city_code',
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
