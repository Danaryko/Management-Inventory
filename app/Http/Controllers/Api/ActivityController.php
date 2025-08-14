<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel; // opsional jika paket terpasang
use App\Exports\ActivitiesExport;    // opsional, jika kamu punya export class
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
{
    /**
     * GET /api/activities
     * Query:
     *  - action: string
     *  - date_from: Y-m-d
     *  - date_to:   Y-m-d
     *  - user_id:   int (hanya admin/owner/manager)
     *  - per_page:  int (default 15)
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);
        $user    = $request->user();

        $q = Activity::with('user');

        // Role-based scoping
        if ($user->roles === 'staff') {
            $q->where('user_id', $user->id);
        } elseif ($user->roles === 'manager') {
            // tempatkan filter khusus manager jika ada (department, dsb.)
        }
        // owner/admin: lihat semua

        // Filters
        if ($request->filled('action')) {
            $q->where('action', $request->action);
        }
        if ($request->filled('date_from')) {
            $q->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('user_id') && in_array($user->roles, ['admin','owner','manager'], true)) {
            $q->where('user_id', $request->user_id);
        }

        $p = $q->latest()->paginate($perPage);

        // optional: daftar action unik untuk filter dropdown di FE
        $actions = Activity::distinct()->pluck('action');

        return ResponseFormatter::success([
            'items' => $p->items(),
            'pagination' => [
                'current_page' => $p->currentPage(),
                'per_page'     => $p->perPage(),
                'total'        => $p->total(),
                'last_page'    => $p->lastPage(),
            ],
            'filters' => [
                'actions' => $actions,
            ],
        ], 'OK');
    }

    /**
     * GET /api/activities/export
     * Query sama seperti index + export=csv|xlsx (default xlsx, fallback ke csv jika paket Excel tidak ada)
     * Mengembalikan file biner (download).
     */
    public function export(Request $request)
    {
        $user = $request->user();

        $q = Activity::with('user');

        if ($user->roles === 'staff') {
            $q->where('user_id', $user->id);
        } elseif ($user->roles === 'manager') {
            // filter khusus manager jika ada
        }

        if ($request->filled('action')) {
            $q->where('action', $request->action);
        }
        if ($request->filled('date_from')) {
            $q->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('user_id') && in_array($user->roles, ['admin','owner','manager'], true)) {
            $q->where('user_id', $request->user_id);
        }

        $rows = $q->latest()->get();
        $filename = 'activities_' . now()->format('Y-m-d_H-i-s');

        $format = $request->string('export')->lower()->value() ?? 'xlsx';
        if ($format === 'csv') {
            return $this->exportCsv($rows, $filename);
        }

        // coba Excel dulu; kalau tidak ada paketnya, fallback ke CSV
        if (class_exists(Excel::class) && class_exists(ActivitiesExport::class)) {
            try {
                return Excel::download(new ActivitiesExport($rows), "{$filename}.xlsx");
            } catch (\Throwable $e) {
                // fallback
            }
        }

        return $this->exportCsv($rows, $filename);
    }

    /**
     * GET /api/activities/recent
     * Query: limit (default 5)
     * Untuk widget dashboard.
     */
    public function recent(Request $request)
    {
        $limit = (int) $request->integer('limit', 5);
        $user  = $request->user();

        $q = Activity::with('user');
        if ($user->roles === 'staff') {
            $q->where('user_id', $user->id);
        }

        $items = $q->latest()->limit($limit)->get();

        return ResponseFormatter::success($items, 'OK');
    }

    /** -------------------- Helpers -------------------- */

    private function exportCsv($activities, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function () use ($activities) {
            $fh = fopen('php://output', 'w');
            // header kolom
            fputcsv($fh, ['ID','Action','Description','User','User Role','Model Type','Model ID','IP Address','Created At']);

            foreach ($activities as $a) {
                fputcsv($fh, [
                    $a->id,
                    $a->action,
                    $a->description,
                    optional($a->user)->name ?? 'System',
                    optional($a->user)->roles ?? 'system',
                    $a->model_type ? class_basename($a->model_type) : '',
                    $a->model_id ?? '',
                    $a->ip_address ?? '',
                    $a->created_at?->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($fh);
        };

        return Response::stream($callback, 200, $headers);
    }
}
