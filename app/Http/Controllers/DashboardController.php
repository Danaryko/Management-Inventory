<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display role-based dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $role = $user->roles;

        // Get common data
        $data = [
            'user' => $user,
            'role' => $role,
        ];

        // Role-specific data
        switch ($role) {
            case 'admin':
                $data = array_merge($data, $this->getAdminDashboardData());
                break;
            case 'owner':
                $data = array_merge($data, $this->getOwnerDashboardData());
                break;
            case 'manager':
                $data = array_merge($data, $this->getManagerDashboardData());
                break;
            case 'operator':
                $data = array_merge($data, $this->getOperatorDashboardData());
                break;
            default:
                $data = array_merge($data, $this->getBasicDashboardData());
        }

        return view('dashboard', $data);
    }

    /**
     * Admin dashboard data - Full system access
     */
    private function getAdminDashboardData()
    {
        return [
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers' => Supplier::count(),
            'recentActivities' => Activity::with('user')->latest()->limit(10)->get(),
            'usersByRole' => User::selectRaw('roles, count(*) as count')->groupBy('roles')->pluck('count', 'roles'),
            'todayActivities' => Activity::whereDate('created_at', today())->count(),
            'systemStats' => [
                'total_stock_ins' => StockIn::count(),
                'total_stock_outs' => StockOut::count(),
                'recent_users' => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
            ],
            'quickActions' => [
                ['title' => 'Manage Users', 'url' => route('users.index'), 'icon' => 'users', 'color' => 'blue'],
                ['title' => 'System Settings', 'url' => '#', 'icon' => 'cog', 'color' => 'gray'],
                ['title' => 'Activity Logs', 'url' => route('activities.index'), 'icon' => 'document-text', 'color' => 'green'],
                ['title' => 'Reports', 'url' => '#', 'icon' => 'chart-bar', 'color' => 'purple'],
            ]
        ];
    }

    /**
     * Owner dashboard data - Business overview
     */
    private function getOwnerDashboardData()
    {
        return [
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'totalSuppliers' => Supplier::count(),
            'recentActivities' => Activity::with('user')->latest()->limit(5)->get(),
            'businessStats' => [
                'total_stock_value' => $this->calculateTotalStockValue(),
                'monthly_stock_ins' => StockIn::whereMonth('created_at', now()->month)->count(),
                'monthly_stock_outs' => StockOut::whereMonth('created_at', now()->month)->count(),
                'low_stock_items' => $this->getLowStockCount(),
            ],
            'quickActions' => [
                ['title' => 'Stock Reports', 'url' => route('reports.stock-in'), 'icon' => 'chart-bar', 'color' => 'blue'],
                ['title' => 'Export Data', 'url' => '#', 'icon' => 'download', 'color' => 'green'],
                ['title' => 'Business Analytics', 'url' => '#', 'icon' => 'trending-up', 'color' => 'purple'],
                ['title' => 'Inventory Overview', 'url' => route('products.index'), 'icon' => 'cube', 'color' => 'orange'],
            ]
        ];
    }

    /**
     * Manager dashboard data - Department management
     */
    private function getManagerDashboardData()
    {
        return [
            'totalUsers' => User::where('roles', '!=', 'admin')->count(),
            'totalProducts' => Product::count(),
            'recentActivities' => Activity::with('user')->latest()->limit(8)->get(),
            'teamStats' => [
                'team_members' => User::whereIn('roles', ['operator'])->count(),
                'today_activities' => Activity::whereDate('created_at', today())->count(),
                'pending_tasks' => 0, // Placeholder for future task management
                'completed_today' => Activity::whereDate('created_at', today())->where('action', 'completed')->count(),
            ],
            'quickActions' => [
                ['title' => 'Team Overview', 'url' => route('users.index'), 'icon' => 'users', 'color' => 'blue'],
                ['title' => 'Task Management', 'url' => '#', 'icon' => 'clipboard-list', 'color' => 'green'],
                ['title' => 'Performance Reports', 'url' => '#', 'icon' => 'chart-bar', 'color' => 'purple'],
                ['title' => 'Inventory Management', 'url' => route('products.index'), 'icon' => 'cube', 'color' => 'orange'],
            ]
        ];
    }

    /**
     * Operator dashboard data - Personal workspace
     */
    private function getOperatorDashboardData()
    {
        $userId = auth()->id();
        return [
            'myActivities' => Activity::where('user_id', $userId)->count(),
            'todayActivities' => Activity::where('user_id', $userId)->whereDate('created_at', today())->count(),
            'recentActivities' => Activity::where('user_id', $userId)->with('user')->latest()->limit(5)->get(),
            'personalStats' => [
                'weekly_tasks' => Activity::where('user_id', $userId)->whereDate('created_at', '>=', now()->subDays(7))->count(),
                'completed_tasks' => Activity::where('user_id', $userId)->where('action', 'completed')->count(),
                'pending_tasks' => 0, // Placeholder
                'efficiency_score' => '95%', // Placeholder calculation
            ],
            'quickActions' => [
                ['title' => 'My Profile', 'url' => route('profile'), 'icon' => 'user', 'color' => 'blue'],
                ['title' => 'My Activities', 'url' => route('activities.index'), 'icon' => 'document-text', 'color' => 'green'],
                ['title' => 'Stock Management', 'url' => route('products.index'), 'icon' => 'cube', 'color' => 'orange'],
                ['title' => 'Quick Entry', 'url' => route('stock-ins.create'), 'icon' => 'plus', 'color' => 'purple'],
            ]
        ];
    }

    /**
     * Basic dashboard data for any other roles
     */
    private function getBasicDashboardData()
    {
        return [
            'totalUsers' => User::count(),
            'recentActivities' => Activity::with('user')->latest()->limit(3)->get(),
            'quickActions' => [
                ['title' => 'View Profile', 'url' => route('profile'), 'icon' => 'user', 'color' => 'blue'],
            ]
        ];
    }

    /**
     * Calculate total stock value (placeholder implementation)
     */
    private function calculateTotalStockValue()
    {
        // This would need actual stock quantity and price calculation
        // For now, return a placeholder
        return 0;
    }

    /**
     * Get count of low stock items (placeholder implementation)
     */
    private function getLowStockCount()
    {
        // This would need actual stock level checking
        // For now, return a placeholder
        return 0;
    }

    /**
     * Get dashboard widgets based on role
     */
    public function getWidgets()
    {
        $user = auth()->user();
        $role = $user->roles;

        $widgets = [];

        // Common widgets
        $widgets[] = [
            'title' => 'Welcome',
            'type' => 'welcome',
            'data' => ['user' => $user],
            'size' => 'full'
        ];

        // Role-specific widgets
        switch ($role) {
            case 'admin':
                $widgets = array_merge($widgets, [
                    ['title' => 'System Overview', 'type' => 'system_stats', 'size' => 'half'],
                    ['title' => 'User Management', 'type' => 'user_stats', 'size' => 'half'],
                    ['title' => 'Recent Activities', 'type' => 'activities', 'size' => 'full'],
                ]);
                break;
            case 'owner':
                $widgets = array_merge($widgets, [
                    ['title' => 'Business Overview', 'type' => 'business_stats', 'size' => 'half'],
                    ['title' => 'Revenue Trends', 'type' => 'revenue_chart', 'size' => 'half'],
                    ['title' => 'Inventory Status', 'type' => 'inventory_status', 'size' => 'full'],
                ]);
                break;
            case 'manager':
                $widgets = array_merge($widgets, [
                    ['title' => 'Team Performance', 'type' => 'team_stats', 'size' => 'half'],
                    ['title' => 'Department Activities', 'type' => 'department_activities', 'size' => 'half'],
                    ['title' => 'Task Overview', 'type' => 'task_overview', 'size' => 'full'],
                ]);
                break;
            case 'operator':
                $widgets = array_merge($widgets, [
                    ['title' => 'My Performance', 'type' => 'personal_stats', 'size' => 'half'],
                    ['title' => 'Today\'s Tasks', 'type' => 'daily_tasks', 'size' => 'half'],
                    ['title' => 'My Recent Activities', 'type' => 'personal_activities', 'size' => 'full'],
                ]);
                break;
        }

        return response()->json($widgets);
    }
}