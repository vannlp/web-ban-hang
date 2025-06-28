<?php
namespace App\DataTables;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class ProductDataTable extends BaseDataTable {
    protected $columns = [
        'id',
        'name',
        'slug',
        'image_avatar',
        'status',
        'created_at',
        'updated_at',
    ];
    public function modelQuery(){
        return Product::query();
    }
    
    public function query($query) {
        
        $query->select([
            'products.*'
        ]);
        // Check for filter:search
        if ($search = request()->input('search')) {
            $field = 'name';
            $listField = [
                'name',
            ];

            $query->where(function ($q) use ($listField, $field, $search) {
                $field = trim($field);
                $operator = 'like';
                foreach($listField as $field) {
                    $q->orWhere($field, $operator, '%' . trim($search) . '%');
                }
            });
        }
    }
    
    public function editColumn($dataTable) {
        // $dataTable->addColumn("role", function($item) {
        //     return $item->role->name ?? null;
        // });
        
    }
}