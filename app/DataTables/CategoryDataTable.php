<?php
namespace App\DataTables;

use App\Models\Category;
use App\Models\User;

class CategoryDataTable extends BaseDataTable {
    protected $columns = [
        'id',
        'name',
        'description',
        'image_avatar',
        'slug',
        'status',
        'parent_id',
        'created_at',
        'updated_at',
    ];
    public function modelQuery(){
        return Category::query();
    }
    
    public function query($query) {
        
        $query->select([
            'categories.*'
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