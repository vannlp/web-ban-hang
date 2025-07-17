<?php

namespace App\Models\Traits;

use App\Models\Role;

trait AddressTrait
{
    public function getFullAddressAttribute(): string
    {
        return "{$this->street}, {$this->ward->name}, {$this->district->name}, {$this->city->name}";
    }
}
