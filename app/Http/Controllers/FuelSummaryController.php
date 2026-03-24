<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Admin\FuelArrivalRequest;
use App\Requests\Admin\FuelDepartureRequest;
use App\Services\Admin\FuelSummaryService;
use Illuminate\Http\RedirectResponse;
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

    /**
     * Update a fuel arrival record.
     */
    public function updateArrival(FuelArrivalRequest $request, int $id): RedirectResponse
    {
        $this->fuelSummaryService->updateArrival($id, $request->validated());

        $route = Auth::user()->role === 'super_admin' ? 'super_admin.fuel-summary' : 'admin.fuel-summary';

        return redirect()
            ->route($route)
            ->with('success', 'Fuel arrival updated successfully.');
    }

    /**
     * Update a fuel departure record.
     */
    public function updateDeparture(FuelDepartureRequest $request, int $id): RedirectResponse
    {
        $this->fuelSummaryService->updateDeparture($id, $request->validated());

        $route = Auth::user()->role === 'super_admin' ? 'super_admin.fuel-summary' : 'admin.fuel-summary';

        return redirect()
            ->route($route)
            ->with('success', 'Fuel departure updated successfully.');
    }
}