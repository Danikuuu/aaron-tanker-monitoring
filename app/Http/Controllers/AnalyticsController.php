<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Admin\AnalyticsFilterRequest;
use App\Services\Admin\AnalyticsService;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class AnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    /**
     * Display analytics dashboard.
     */
    public function index(AnalyticsFilterRequest $request)
    {
        $data = $this->analyticsService->getAnalyticsData(
            $request->period()
        );

        $user = Auth::user();

        return $user->role === 'admin'
            ? view('admin.analytics', $data)
            : view('super_admin.analytics', $data);
    }

    /**
     * Export analytics data to CSV.
     */
    public function exportCsv(AnalyticsFilterRequest $request)
    {
        $type   = $request->get('type', 'arrival');
        $period = $request->period(); // ✅ fixed

        return $this->analyticsService->exportCsv($type, $period);
    }

    /**
     * Export analytics data to PDF.
     */
    public function exportPdf(AnalyticsFilterRequest $request)
    {
        $type   = $request->get('type', 'arrival');
        $period = $request->period();

        // Get rows
        $rows = $type === 'arrival'
            ? $this->analyticsService->getAnalyticsData($period)['arrivalData']
            : $this->analyticsService->getAnalyticsData($period)['departureData'];

        $filename = ($type === 'arrival' ? 'fuel_arrivals_' : 'fuel_departures_') 
                    . now()->format('Ymd') . '.pdf';

        $pdf = Pdf::loadView('super_admin.analytics_pdf', [
            'rows'  => $rows,
            'type'  => $type,
            'period'=> $period,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
