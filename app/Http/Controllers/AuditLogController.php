<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search', '');
        $action    = $request->input('action', 'all');
        $dateFrom  = $request->input('date_from', '');
        $dateTo    = $request->input('date_to', '');
        $perPage   = 15;

        $query = AuditLog::with('user')->latest();

        // Search across action, description, ip_address, user name/email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by action type
        if ($action && $action !== 'all') {
            $query->where('action', $action);
        }

        // Date range
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs = $query->paginate($perPage)->withQueryString();

        // Distinct action types for the filter dropdown
        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');

        return view('super_admin.auditlogs', compact('logs', 'actions', 'search', 'action', 'dateFrom', 'dateTo'));
    }
}
