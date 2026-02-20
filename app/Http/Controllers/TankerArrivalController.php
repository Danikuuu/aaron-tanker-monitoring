<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Staff\StoreTankerArrivalRequest;
use App\Services\Staff\TankerArrivalService;

class TankerArrivalController extends Controller
{
    public function __construct(
        protected TankerArrivalService $service
    ) {}

    /**
     * Handle the recording of a tanker arrival, including validation and business logic, and provide feedback to the user.
     */
    public function store(StoreTankerArrivalRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()
            ->back()
            ->with('success', 'Tanker arrival recorded successfully.');
    }
}
