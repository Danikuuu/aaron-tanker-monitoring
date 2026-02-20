<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\FuelSummaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuelSummaryController extends Controller
{
    public function __construct(
        protected FuelSummaryService $fuelSummaryService
    ) {}

    /**
     * Display the fuel summary dashboard with aggregated data on fuel usage, arrivals, and departures.
     */
    public function index(Request $request)
    {
        $data = $this->fuelSummaryService->getSummaryData($request->query());

        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('admin.fuel-summary', $data);
        }

        return view('super_admin.fuel-summary', $data);
    }

    /**
     * Export fuel summary data for arrivals to CSV.
     */
    public function exportArrivals()
    {
        return $this->fuelSummaryService->exportArrivals(request()->query());
    }

    /**
     * Export fuel summary data for departures to CSV.
     */
    public function exportDepartures()
    {
        return $this->fuelSummaryService->exportDepartures(request()->query());
    }

    public function exportArrivalsPdf(Request $request)
    {
        return $this->fuelSummaryService->exportArrivalsPdf($request->query());
    }

    public function exportDeparturesPdf(Request $request)
    {
        return $this->fuelSummaryService->exportDeparturesPdf($request->query());
    }
}