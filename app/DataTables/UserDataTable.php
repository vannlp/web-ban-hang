<?php
namespace App\DataTables;

use App\Models\User;

class UserDataTable extends BaseDataTable {
    protected $columns = [
        "id",
        "name",
        "email",
        // "role",
        // 'status',
        'username'
    ];
    public function modelQuery(){
        return User::query();
    }
    
    public function query($query) {
        $query->with([
            'roles'
        ]);
        
        $query->select([
            'users.*'
        ]);
        // Check for filter:search
        if ($search = request()->input('search')) {
            $field = 'name';
            $listField = [
                'name',
                'username',
                'email',
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
        $dataTable->addColumn("role", function($item) {
            return $item->role->name ?? null;
        });
        
    }
}