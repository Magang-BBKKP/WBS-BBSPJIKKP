<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Tampilkan halaman utama dashboard
     */
    public function index(): View
    {
        Gate::authorize('view-dashboard');

        $data = $this->dashboardService->getDashboardData();

        return view('dashboard.index', $data);
    }
}
