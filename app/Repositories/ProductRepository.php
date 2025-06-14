<?php
namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends Repository {
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Product::class;
    }

}