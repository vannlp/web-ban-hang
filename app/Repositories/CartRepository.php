<?php
namespace App\Repositories;

use App\Models\Cart;
use App\Models\User;

class CartRepository extends Repository {
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Cart::class;
    }

}