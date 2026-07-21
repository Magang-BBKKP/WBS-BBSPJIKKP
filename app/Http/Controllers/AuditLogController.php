<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-audit-log');

        $search    = $request->input('search');
        $filterUser = $request->input('user_id');
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');

        $query = AuditLog::with('user')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($filterUser) {
            $query->where('user_id', $filterUser);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs  = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('audit-log.index', compact('logs', 'users', 'search', 'filterUser', 'dateFrom', 'dateTo'));
    }
}
