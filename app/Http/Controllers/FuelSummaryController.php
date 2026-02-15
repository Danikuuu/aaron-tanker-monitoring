<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\FuelSummaryService;

class FuelSummaryController extends Controller
{
    public function __construct(
        protected FuelSummaryService $fuelSummaryService
    ) {}

    public function index()
    {
        $data = $this->fuelSummaryService->getSummaryData();

        return view('admin.fuel-summary', $data);
    }

    public function exportArrivals()
    {
        return $this->fuelSummaryService->exportArrivals();
    }

    public function exportDepartures()
    {
        return $this->fuelSummaryService->exportDepartures();
    }
}