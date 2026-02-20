<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Staff\TankerHistoryService;

class TankerHistoryController extends Controller
{
    public function __construct(protected TankerHistoryService $service) {}

    /**
     * Display the tanker history page with a list of past tanker arrivals and departures for the logged-in staff member.
     */
    public function index()
    {
        $data = $this->service->getUserHistory();

        return view('staff.fuel-supply', $data);
    }
}
