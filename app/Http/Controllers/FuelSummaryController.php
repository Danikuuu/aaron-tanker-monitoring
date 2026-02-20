<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\FuelSummaryService;

class FuelSummaryController extends Controller
{
    public function __construct(
        protected FuelSummaryService $fuelSummaryService
    ) {}

    /**
     * Display the fuel summary dashboard with aggregated data on fuel usage, arrivals, and departures.
     */
    public function index()
    {
        $data = $this->fuelSummaryService->getSummaryData();

        return view('admin.fuel-summary', $data);
    }

    /**
     * Export fuel summary data for arrivals to CSV.
     */
    public function exportArrivals()
    {
        return $this->fuelSummaryService->exportArrivals();
    }

    /**
     * Export fuel summary data for departures to CSV.
     */
    public function exportDepartures()
    {
        return $this->fuelSummaryService->exportDepartures();
    }
}