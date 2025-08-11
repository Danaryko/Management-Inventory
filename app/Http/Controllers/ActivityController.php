<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivitiesExport;

class ActivityController extends Controller
{
    /**
     * Display activity history with role-based access
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Activity::with('user');

        // Role-based filtering
        if ($user->roles === 'operator') {
            // Operators see only their own activities
            $query->where('user_id', $user->id);
        } elseif ($user->roles === 'manager') {
            // Managers see activities from their department/team (for now, same as all)
            // In future, could filter by department
        }
        // Admin and owner see all activities

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('user_id') && in_array($user->roles, ['admin', 'owner', 'manager'])) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->latest()->paginate(15);
        $actions = Activity::distinct()->pluck('action');

        // For AJAX requests (auto-refresh), return partial view
        if ($request->ajax()) {
            return response()->json([
                'html' => view('activities.partials.table-body', compact('activities'))->render(),
                'pagination' => $activities->appends(request()->query())->links()->render()
            ]);
        }

        return view('activities.index', compact('activities', 'actions'));
    }

    /**
     * Export activities to CSV or Excel
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Activity::with('user');

        // Apply same role-based filtering as index
        if ($user->roles === 'operator') {
            $query->where('user_id', $user->id);
        } elseif ($user->roles === 'manager') {
            // Manager filtering logic
        }

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('user_id') && in_array($user->roles, ['admin', 'owner', 'manager'])) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->latest()->get();
        $filename = 'activities_' . now()->format('Y-m-d_H-i-s');

        if ($request->export === 'csv') {
            return $this->exportCsv($activities, $filename);
        } else {
            return $this->exportExcel($activities, $filename);
        }
    }

    /**
     * Export to CSV format
     */
    private function exportCsv($activities, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'Action', 'Description', 'User', 'User Role', 'Model Type', 'Model ID', 'IP Address', 'Created At']);
            
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->action,
                    $activity->description,
                    $activity->user->name ?? 'System',
                    $activity->user->roles ?? 'system',
                    $activity->model_type ? class_basename($activity->model_type) : '',
                    $activity->model_id ?? '',
                    $activity->ip_address ?? '',
                    $activity->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel format (if Excel package is available)
     */
    private function exportExcel($activities, $filename)
    {
        // Fallback to CSV if Excel package not available
        if (!class_exists('Maatwebsite\Excel\Facades\Excel')) {
            return $this->exportCsv($activities, $filename);
        }

        try {
            return Excel::download(new ActivitiesExport($activities), "{$filename}.xlsx");
        } catch (\Exception $e) {
            return $this->exportCsv($activities, $filename);
        }
    }

    /**
     * Get recent activities for dashboard widget
     */
    public function getRecentActivities($limit = 5)
    {
        $user = auth()->user();
        $query = Activity::with('user');

        // Role-based filtering for dashboard
        if ($user->roles === 'operator') {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->limit($limit)->get();
    }
}
