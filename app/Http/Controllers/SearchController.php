<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TankerArrival;
use App\Models\TankerDeparture;
use App\Models\BrReceipt;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // ── Tanker Arrivals ──────────────────────────────────────────────
        TankerArrival::with('recordedBy')
            ->where('tanker_number', 'like', "%{$query}%")
            ->orWhereHas('recordedBy', fn($q) =>
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name',  'like', "%{$query}%")
            )
            ->latest()->limit(5)->get()
            ->each(function ($a) use (&$results) {
                $results[] = [
                    'type'     => 'arrival',
                    'label'    => 'Tanker Arrival',
                    'title'    => $a->tanker_number,
                    'subtitle' => 'Arrived ' . $a->arrival_date?->format('M d, Y'),
                    'url'      => $this->roleRoute('fuel-summary'),
                    'icon'     => 'arrival',
                ];
            });

        // ── Tanker Departures ────────────────────────────────────────────
        TankerDeparture::with('recordedBy')
            ->where(function ($q) use ($query) {
                $q->where('tanker_number', 'like', "%{$query}%")
                  ->orWhere('driver',        'like', "%{$query}%");
            })
            ->latest()->limit(5)->get()
            ->each(function ($d) use (&$results) {
                $results[] = [
                    'type'     => 'departure',
                    'label'    => 'Tanker Departure',
                    'title'    => $d->tanker_number,
                    'subtitle' => 'Driver: ' . ($d->driver ?? '—') . ' · ' . $d->departure_date?->format('M d, Y'),
                    'url'      => $this->roleRoute('transaction-history'),
                    'icon'     => 'departure',
                ];
            });

        // ── BR Receipts ──────────────────────────────────────────────────
        BrReceipt::where(function ($q) use ($query) {
                $q->where('receipt_no',   'like', "%{$query}%")
                  ->orWhere('delivered_to', 'like', "%{$query}%")
                  ->orWhere('address',       'like', "%{$query}%");
            })
            ->latest()->limit(5)->get()
            ->each(function ($r) use (&$results) {
                $results[] = [
                    'type'     => 'receipt',
                    'label'    => 'BR Receipt',
                    'title'    => $r->receipt_no,
                    'subtitle' => 'Delivered to: ' . ($r->delivered_to ?? '—'),
                    'url'      => $this->roleRoute('br-receipt', 'transaction-history'),
                    'icon'     => 'receipt',
                ];
            });

        // ── Staff / Users ────────────────────────────────────────────────
        User::where('role', '!=', 'super_admin')
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name',  'like', "%{$query}%")
                  ->orWhere('email',       'like', "%{$query}%");
            })
            ->latest()->limit(5)->get()
            ->each(function ($u) use (&$results) {
                $results[] = [
                    'type'     => 'user',
                    'label'    => ucfirst($u->role),
                    'title'    => $u->first_name . ' ' . $u->last_name,
                    'subtitle' => $u->email . ' · ' . ucfirst($u->status),
                    'url'      => $this->roleRoute('staff-management', 'overview'),
                    'icon'     => 'user',
                ];
            });

        // ── Audit Logs ───────────────────────────────────────────────────
        AuditLog::with('user')
            ->where(function ($q) use ($query) {
                $q->where('action',      'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()->limit(5)->get()
            ->each(function ($log) use (&$results) {
                $results[] = [
                    'type'     => 'audit',
                    'label'    => 'Audit Log',
                    'title'    => str_replace('_', ' ', ucfirst($log->action)),
                    'subtitle' => $log->description,
                    'url'      => $this->roleRoute('overview'),
                    'icon'     => 'audit',
                ];
            });

        return response()->json(['results' => $results]);
    }

    // Build the correct route prefix based on the logged-in user's role
    private function roleRoute(string $page, string $fallback = 'overview'): string
    {
        $role = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';
        $targetRoute = "{$role}.{$page}";
        if (Route::has($targetRoute)) {
            return route($targetRoute);
        }

        return route("{$role}.{$fallback}");
    }
}