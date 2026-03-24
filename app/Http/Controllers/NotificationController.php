<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TankerArrival;
use App\Models\TankerDeparture;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Return recent arrivals + departures + new user registrations
     * for the admin notification bell.
     * Only records from the last 24 hours are shown as "new".
     */
    public function index()
    {
        $arrivals = TankerArrival::with(['fuels', 'recordedBy'])
            ->where('created_at', '>=', now()->subHours(24))
            ->latest()
            ->get()
            ->map(fn($a) => [
                'id'             => $a->id,
                'type'           => 'arrival',
                'tanker_number'  => $a->tanker_number,
                'driver'         => null,
                'date'           => $a->arrival_date?->format('M d, Y') ?? '—',
                'recorded_by'    => $a->recordedBy
                                    ? $a->recordedBy->first_name . ' ' . $a->recordedBy->last_name
                                    : 'Staff',
                'created_at'     => $a->created_at->diffForHumans(),
                'fuels'          => $a->fuels->map(fn($f) => [
                    'fuel_type'        => $f->fuel_type,
                    'liters'           => $f->liters,
                    'methanol_liters'  => $f->methanol_liters ?? 0,
                    'methanol_percent' => $f->methanol_percent ?? 0,
                ]),
            ]);

        $departures = TankerDeparture::with(['fuels', 'recordedBy'])
            ->where('created_at', '>=', now()->subHours(24))
            ->latest()
            ->get()
            ->map(fn($d) => [
                'id'             => $d->id,
                'type'           => 'departure',
                'tanker_number'  => $d->tanker_number,
                'driver'         => $d->driver,
                'date'           => $d->departure_date?->format('M d, Y') ?? '—',
                'recorded_by'    => $d->recordedBy
                                    ? $d->recordedBy->first_name . ' ' . $d->recordedBy->last_name
                                    : 'Staff',
                'created_at'     => $d->created_at->diffForHumans(),
                'fuels'          => $d->fuels->map(fn($f) => [
                    'fuel_type'        => $f->fuel_type,
                    'liters'           => $f->liters,
                    'methanol_liters'  => $f->methanol_liters ?? 0,
                    'methanol_percent' => $f->methanol_percent ?? 0,
                ]),
            ]);

        // New user registrations in the last 24 hours
        $newUsers = User::where('created_at', '>=', now()->subHours(24))
            ->where('role', '!=', 'super_admin')
            ->latest()
            ->get()
            ->map(fn($u) => [
                'id'          => $u->id,
                'type'        => 'new_user',
                'name'        => $u->first_name . ' ' . $u->last_name,
                'email'       => $u->email,
                'role'        => $u->role,
                'status'      => $u->status,
                'created_at'  => $u->created_at->diffForHumans(),
                'fuels'       => [], // keep consistent shape
            ]);

        $all = $arrivals->concat($departures)->concat($newUsers)
            ->sortByDesc('created_at')
            ->values();

        return response()->json([
            'count'         => $all->count(),
            'notifications' => $all,
        ]);
    }
}