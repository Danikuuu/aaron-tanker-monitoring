<?php

namespace App\Repositories\Admin;

use App\Models\TankerDeparture;

interface ReceiptInterface
{
    public function findDeparture(int $id): TankerDeparture;
}