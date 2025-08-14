<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\User;
use App\Models\Activity;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * GET /api/dashboard
     * Ringkasan dashboard berbasis role (JSON)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->roles;

        $data = [
            'user' => $user,
            'role' => $role,
        ];

        switch ($role) {
            case 'admin':
                $data += $this->getAdminDashboardData();
                break;
            case 'owner':
                $data += $this->getOwnerDashboardData();
                break;
            case 'manager':
                $data += $this->getManagerDashboardData();
                break;
            case 'staff':
                $data += $this->getStaffDashboardData();
                break;
            default:
                $data += $this->getBasicDashboardData();
        }

        return ResponseFormatter::success($data, 'OK');
    }

    /**
     * GET /api/dashboard/widgets
     * Daftar widget (layout) sesuai role
     */
    public function widgets(Request $request)
    {
        $user = $request->user();
        $role = $user->roles;

        $widgets = [[
            'title' => 'Welcome',
            'type'  => 'welcome',
            'data'  => ['user' => $user],
            'size'  => 'full',
        ]];

        switch ($role) {
            case 'admin':
                $widgets = array_merge($widgets, [
                    ['title' => 'System Overview',   'type' => 'system_stats',     'size' => 'half'],
                    ['title' => 'User Management',   'type' => 'user_stats',       'size' => 'half'],
                    ['title' => 'Recent Activities', 'type' => 'activities',       'size' => 'full'],
                ]);
                break;
            case 'owner':
                $widgets = array_merge($widgets, [
                    ['title' => 'Business Overview', 'type' => 'business_stats',   'size' => 'half'],
                    ['title' => 'Revenue Trends',    'type' => 'revenue_chart',    'size' => 'half'],
                    ['title' => 'Inventory Status',  'type' => 'inventory_status', 'size' => 'full'],
                ]);
                break;
            case 'manager':
                $widgets = array_merge($widgets, [
                    ['title' => 'Team Performance',      'type' => 'team_stats',           'size' => 'half'],
                    ['title' => 'Department Activities', 'type' => 'department_activities','size' => 'half'],
                    ['title' => 'Task Overview',         'type' => 'task_overview',        'size' => 'full'],
                ]);
                break;
            case 'staff':
                $widgets = array_merge($widgets, [
                    ['title' => 'My Performance',        'type' => 'personal_stats',   'size' => 'half'],
                    ['title' => "Today's Tasks",         'type' => 'daily_tasks',      'size' => 'half'],
                    ['title' => 'My Recent Activities',  'type' => 'personal_activities','size' => 'full'],
                ]);
                break;
        }

        return ResponseFormatter::success($widgets, 'OK');
    }

    /* ==================== PRIVATE DATA BUILDERS ==================== */

    private function getAdminDashboardData(): array
    {
        return [
            'totalUsers'       => User::count(),
            'totalProducts'    => Product::count(),
            'totalCategories'  => Category::count(),
            'recentActivities' => Activity::with('user')->latest()->limit(10)->get(),
            'usersByRole'      => User::selectRaw('roles, COUNT(*) AS count')->groupBy('roles')->pluck('count', 'roles'),
            'todayActivities'  => Activity::whereDate('created_at', today())->count(),
            'systemStats'      => [
                'total_stock_ins'  => StockIn::count(),
                'total_stock_outs' => StockOut::count(),
                'recent_users'     => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
            ],
            'quickActions'     => [
                // karena ini API, berikan path API/FE yang relevan (opsional)
                ['title' => 'Manage Users',   'url' => '/users',           'icon' => 'users',          'color' => 'blue'],
                ['title' => 'Activity Logs',  'url' => '/activities',      'icon' => 'document-text', 'color' => 'green'],
            ],
        ];
    }

    private function getOwnerDashboardData(): array
    {
        return [
            'totalUsers'       => User::count(),
            'totalProducts'    => Product::count(),
            'recentActivities' => Activity::with('user')->latest()->limit(5)->get(),
            'businessStats'    => [
                'total_stock_value' => $this->calculateTotalStockValue(),
                'monthly_stock_ins' => StockIn::whereMonth('created_at', now()->month)->count(),
                'monthly_stock_outs'=> StockOut::whereMonth('created_at', now()->month)->count(),
                'low_stock_items'   => $this->getLowStockCount(),
            ],
            'stockChart'       => $this->getStockChartData(),
            'topStockOutChart' => $this->getTopStockOutProductsChartData(),
            'quickActions'     => [
                ['title' => 'Stock Reports',      'url' => '/reports/stock-ins', 'icon' => 'chart-bar', 'color' => 'blue'],
                ['title' => 'Inventory Overview', 'url' => '/products',          'icon' => 'cube',      'color' => 'orange'],
            ],
        ];
    }

    private function getManagerDashboardData(): array
    {
        return [
            'totalUsers'       => User::where('roles', '!=', 'admin')->count(),
            'totalProducts'    => Product::count(),
            'recentActivities' => Activity::with('user')->latest()->limit(8)->get(),
            'teamStats'        => [
                'team_members'     => User::whereIn('roles', ['staff'])->count(),
                'today_activities' => Activity::whereDate('created_at', today())->count(),
                'pending_tasks'    => 0,
                'completed_today'  => Activity::whereDate('created_at', today())->where('action', 'completed')->count(),
            ],
            'quickActions'     => [
                ['title' => 'Team Overview',        'url' => '/users',    'icon' => 'users',        'color' => 'blue'],
                ['title' => 'Task Management',      'url' => '#',         'icon' => 'clipboard-list','color' => 'green'],
                ['title' => 'Performance Reports',  'url' => '#',         'icon' => 'chart-bar',    'color' => 'purple'],
                ['title' => 'Inventory Management', 'url' => '/products', 'icon' => 'cube',         'color' => 'orange'],
            ],
        ];
    }

    private function getStaffDashboardData(): array
    {
        $userId = auth()->id();

        return [
            'todayActivities'  => Activity::where('user_id', $userId)->whereDate('created_at', today())->count(),
            'recentActivities' => Activity::where('user_id', $userId)->with('user')->latest()->limit(5)->get(),
            'personalStats'    => [
                'weekly_tasks'     => Activity::where('user_id', $userId)->whereDate('created_at', '>=', now()->subDays(7))->count(),
                'completed_tasks'  => Activity::where('user_id', $userId)->where('action', 'completed')->count(),
                'pending_tasks'    => 0,
                'efficiency_score' => '95%',
            ],
            'quickActions'     => [
                ['title' => 'My Profile',       'url' => '/auth/profile',   'icon' => 'user', 'color' => 'blue'],
                ['title' => 'Stock Management', 'url' => '/products',       'icon' => 'cube', 'color' => 'orange'],
                ['title' => 'Quick Entry',      'url' => '/stock-ins/new',  'icon' => 'plus', 'color' => 'purple'],
            ],
        ];
    }

    private function getBasicDashboardData(): array
    {
        return [
            'totalUsers'       => User::count(),
            'recentActivities' => Activity::with('user')->latest()->limit(3)->get(),
            'quickActions'     => [
                ['title' => 'View Profile', 'url' => '/auth/profile', 'icon' => 'user', 'color' => 'blue'],
            ],
        ];
    }

    private function calculateTotalStockValue(): int|float
    {
        // TODO: ganti sesuai struktur field (misal qty * price).
        return 0;
    }

    private function getLowStockCount(): int
    {
        // TODO: ganti sesuai threshold stok.
        return 0;
    }

    /**
     * Data chart Stock In/Out 6 bulan terakhir
     */
    private function getStockChartData(): array
    {
        $labels = [];
        $stockInData = [];
        $stockOutData = [];

        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $labels[] = $d->format('M Y');

            $in  = StockIn::whereYear('date', $d->year)->whereMonth('date', $d->month)->count();
            $out = StockOut::whereYear('date', $d->year)->whereMonth('date', $d->month)->count();

            $stockInData[]  = $in;
            $stockOutData[] = $out;
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                ['label' => 'Stock In',  'data' => $stockInData],
                ['label' => 'Stock Out', 'data' => $stockOutData],
            ],
        ];
    }

    /**
     * Top produk berdasarkan Stock-Out (default 30 hari)
     */
    private function getTopStockOutProductsChartData(int $limit = 10, ?int $days = 30): array
    {
        $dateCol = Schema::hasColumn('stock_outs', 'date') ? 'stock_outs.date' : 'stock_outs.created_at';

        $rows = DB::table('stock_out_items')
            ->join('stock_outs', 'stock_out_items.stock_out_id', '=', 'stock_outs.id')
            ->join('products',   'products.id',                  '=', 'stock_out_items.product_id')
            ->when($days, fn($q) => $q->where($dateCol, '>=', now()->subDays($days)->startOfDay()))
            ->groupBy('products.id', 'products.name')
            ->selectRaw('products.id, products.name AS product_name')
            ->selectRaw('SUM(stock_out_items.quantity) AS total_qty')
            ->selectRaw('COUNT(DISTINCT stock_outs.id) AS freq')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();

        return [
            'labels' => $rows->pluck('product_name')->values(),
            'qty'    => $rows->pluck('total_qty')->map(fn($v)=>(int)$v)->values(),
            'freq'   => $rows->pluck('freq')->map(fn($v)=>(int)$v)->values(),
            'window' => $days,
        ];
    }
}
