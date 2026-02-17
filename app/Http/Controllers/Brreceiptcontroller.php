<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\BrReceiptService;

class BrReceiptController extends Controller
{
    public function __construct(
        protected BrReceiptService $service
    ) {}

    public function index()
    {
        $departures = $this->service->getDepartures();

        return view('admin.receipt.br-receipt', compact('departures'));
    }
}