<?php

namespace App\Repositories\Admin;

use App\Models\TankerDeparture;

class ReceiptRepository implements ReceiptInterface
{
    public function findDeparture(int $id): TankerDeparture
    {
        return TankerDeparture::with(['fuels', 'recordedBy'])
            ->findOrFail($id);
    }
}