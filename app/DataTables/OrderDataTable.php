<?php
namespace App\DataTables;

use App\Models\Order;

class OrderDataTable extends BaseDataTable {
    protected $columns = [
        'id',
        'user_id',
        'code',
        'total_price',
        'final_price',
        'final_price_formatted',
        'status',
        'cart_id',
        'address_id',
        'created_at',
        'updated_at',
        'phone',
        'final_price',
        'status_label',
        'full_address',
    ];
    public function modelQuery(){
        return Order::query();
    }
    
    public function query($query) {
        
        $query->select([
            'orders.*'
        ])->with([
            'address',
            'address.city',
            'address.district',
            'address.ward'
        ]);
        // Check for filter:search
        if ($search = request()->input('search')) {
            $field = 'code';
            $listField = [
                'code',
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
        $dataTable->addColumn("phone", function($item) {
            return $item->address->phone ?? null;
        });
        
        $dataTable->addColumn("status_label", function($item) {
            return $item->status_label ?? null;
        });
        
        $dataTable->addColumn("final_price_formatted", function($item) {
            return $item->final_price_formatted ?? null;
        });
        
        $dataTable->addColumn("full_address", function($item) {
            return $item->address->full_address ?? null;
        });
        
    }
}