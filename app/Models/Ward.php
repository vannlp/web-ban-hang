<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    
    protected $table = "wards";
    
    protected $fillable = [
        'id',
        'code',
        'type',
        'name',
        'full_name',
        'id_ward_vnp',
        'name_ward_vnp',
        'ma_buu_chinh',
        'description',
        'district_id',
        'district_code',
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
