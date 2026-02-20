<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Admin\AnalyticsFilterRequest;
use App\Services\Admin\AnalyticsService;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    /**
     * Return analytics data for the dashboard based on the selected period.
     */
    public function index(AnalyticsFilterRequest $request)
    {
        $data = $this->analyticsService->getAnalyticsData(
            $request->period()
        );
        
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('admin.analytics', $data);
        }

        return view('super_admin.analytics', $data);
    }

    /**
     * Export analytics data to CSV.
     */
    public function export(AnalyticsFilterRequest $request)
    {
        return $this->analyticsService->exportCsv(
            $request->get('type', 'arrival'),
            $request->months()
        );
    }
}