<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Staff\StoreTankerDepartureRequest;
use App\Services\Staff\TankerDepartureService;
use Exception;

class TankerDepartureController extends Controller
{
    public function __construct(
        protected TankerDepartureService $service
    ) {}

    /**
     * Handle the recording of a tanker departure, including validation and business logic, and provide feedback to the user.
     */
    public function store(StoreTankerDepartureRequest $request)
    {
        try {
            $this->service->store($request->validated());

            return back()->with('success', 'Fuel supply out recorded successfully.');
        } catch (Exception $e) {
            return back()
                ->withErrors(['stock' => $e->getMessage()])
                ->withInput();
        }
    }
}
