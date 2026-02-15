<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Staff\FuelSupplyService;

class FuelSupplyController extends Controller
{
    public function __construct(protected FuelSupplyService $service) {}

    /**
     * Display the logged-in user's fuel supply transactions.
     */
    public function index()
    {
        $transactions = $this->service->getUserTransactions();

        return view('staff.fuel-supply', [
            'transactions' => $transactions,
        ]);
    }
}
