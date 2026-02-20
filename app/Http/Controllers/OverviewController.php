<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\OverviewService;

class OverviewController extends Controller
{
    public function __construct(
        protected OverviewService $overviewService
    ) {}

    /**
     * Display the overview dashboard with key metrics and summaries for arrivals, departures, fuel usage, and pending approvals.
     */
    public function index()
    {
        $data = $this->overviewService->getDashboardData();

        return view('admin.overview', $data);
    }
}