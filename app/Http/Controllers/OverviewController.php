<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\OverviewService;

class OverviewController extends Controller
{
    public function __construct(
        protected OverviewService $overviewService
    ) {}

    public function index()
    {
        $data = $this->overviewService->getDashboardData();

        return view('admin.overview', $data);
    }
}