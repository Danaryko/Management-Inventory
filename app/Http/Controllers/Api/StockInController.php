<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Validation\ValidationException;

class StockInController extends Controller
{
    /**
     * GET /api/stock-ins
     * Query: search, date_from, date_to, per_page (default 10)
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);

        $q = StockIn::with(['user', 'items.product'])
            ->when($request->filled('search'), fn($qb) =>
                $qb->where('reference_number', 'like', '%'.$request->search.'%')
            )
            ->when($request->filled('date_from'), fn($qb) =>
                $qb->whereDate('date', '>=', $request->date_from)
            )
            ->when($request->filled('date_to'), fn($qb) =>
                $qb->whereDate('date', '<=', $request->date_to)
            )
            ->latest();

        $p = $q->paginate($perPage);

        return ResponseFormatter::success([
            'items' => $p->items(),
            'pagination' => [
                'current_page' => $p->currentPage(),
                'per_page'     => $p->perPage(),
                'total'        => $p->total(),
                'last_page'    => $p->lastPage(),
            ],
        ], 'OK');
    }

    /**
     * (Opsional) GET /api/stock-ins/reference
     * Generate nomor referensi berikutnya (SI-YYYYMMDD-xxx)
     */
    public function reference()
    {
        $next = str_pad(
            StockIn::whereDate('created_at', today())->count() + 1,
            3, '0', STR_PAD_LEFT
        );
        $ref = 'SI-'.date('Ymd').'-'.$next;

        return ResponseFormatter::success(['reference_number' => $ref], 'OK');
    }

    /**
     * POST /api/stock-ins
     * Body: reference_number, date (Y-m-d), notes?, items: [{product_id, quantity}]
     * Akan menaikkan stock_quantity produk sesuai items.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'reference_number'   => ['required','string','max:255','unique:stock_ins,reference_number'],
                'date'               => ['required','date'],
                'notes'              => ['nullable','string'],
                'items'              => ['required','array','min:1'],
                'items.*.product_id' => ['required','exists:products,id'],
                'items.*.quantity'   => ['required','integer','min:1'],
            ]);

            $stockIn = DB::transaction(function () use ($data) {
                $stockIn = StockIn::create([
                    'reference_number' => $data['reference_number'],
                    'user_id'          => auth()->id(),
                    'date'             => $data['date'],
                    'notes'            => $data['notes'] ?? null,
                ]);

                foreach ($data['items'] as $item) {
                    StockInItem::create([
                        'stock_in_id' => $stockIn->id,
                        'product_id'  => $item['product_id'],
                        'quantity'    => $item['quantity'],
                    ]);

                    Product::whereKey($item['product_id'])
                        ->increment('stock_quantity', $item['quantity']);
                }

                return $stockIn->load(['user','items.product']);
            });

            return ResponseFormatter::success($stockIn, 'Created');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Create failed', 500);
        }
    }

    /**
     * GET /api/stock-ins/{stockIn}
     */
    public function show(StockIn $stockIn)
    {
        return ResponseFormatter::success(
            $stockIn->load(['user','items.product']),
            'OK'
        );
    }

    /**
     * PUT /api/stock-ins/{stockIn}
     * Revert stok lama, ganti items, update stok baru.
     */
    public function update(Request $request, StockIn $stockIn)
    {
        try {
            $data = $request->validate([
                'reference_number'   => ['required','string','max:255','unique:stock_ins,reference_number,'.$stockIn->id],
                'date'               => ['required','date'],
                'notes'              => ['nullable','string'],
                'items'              => ['required','array','min:1'],
                'items.*.product_id' => ['required','exists:products,id'],
                'items.*.quantity'   => ['required','integer','min:1'],
            ]);

            $stockIn = DB::transaction(function () use ($data, $stockIn) {
                // Revert stok lama
                foreach ($stockIn->items as $oldItem) {
                    Product::whereKey($oldItem->product_id)
                        ->decrement('stock_quantity', $oldItem->quantity);
                }

                // Hapus items lama
                $stockIn->items()->delete();

                // Update header
                $stockIn->update([
                    'reference_number' => $data['reference_number'],
                    'date'             => $data['date'],
                    'notes'            => $data['notes'] ?? null,
                ]);

                // Tambah items baru & update stok
                foreach ($data['items'] as $item) {
                    StockInItem::create([
                        'stock_in_id' => $stockIn->id,
                        'product_id'  => $item['product_id'],
                        'quantity'    => $item['quantity'],
                    ]);

                    Product::whereKey($item['product_id'])
                        ->increment('stock_quantity', $item['quantity']);
                }

                return $stockIn->load(['user','items.product']);
            });

            return ResponseFormatter::success($stockIn, 'Updated');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Update failed', 500);
        }
    }

    /**
     * DELETE /api/stock-ins/{stockIn}
     * Revert stok, hapus detail & header.
     */
    public function destroy(StockIn $stockIn)
    {
        try {
            DB::transaction(function () use ($stockIn) {
                foreach ($stockIn->items as $item) {
                    Product::whereKey($item->product_id)
                        ->decrement('stock_quantity', $item->quantity);
                }
                $stockIn->items()->delete();
                $stockIn->delete();
            });

            return ResponseFormatter::success(null, 'Deleted');
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Delete failed', 500);
        }
    }

    /**
     * GET /api/stock-ins/reports
     * Query: date_from?, date_to?
     * Default: bulan berjalan jika tidak ada filter.
     * Return: list + totalItems
     */
    public function reports(Request $request)
    {
        $q = StockIn::with(['user','items.product']);

        if ($request->filled('date_from')) {
            $q->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->whereDate('date', '<=', $request->date_to);
        }
        if (!$request->filled('date_from') && !$request->filled('date_to')) {
            $q->whereMonth('date', now()->month)->whereYear('date', now()->year);
        }

        $rows = $q->orderByDesc('date')->get();
        $totalItems = $rows->sum(fn($si) => $si->items->sum('quantity'));

        return ResponseFormatter::success([
            'rows' => $rows,
            'summary' => [
                'total_transactions' => $rows->count(),
                'total_items'        => $totalItems,
                'period' => [
                    'from' => $request->date_from ?? now()->startOfMonth()->toDateString(),
                    'to'   => $request->date_to   ?? now()->endOfMonth()->toDateString(),
                ],
            ],
        ], 'OK');
    }

    /**
     * GET /api/stock-ins/reports/pdf
     * Mengembalikan file PDF (response biner).
     * Query: date_from?, date_to?
     */
    public function exportPdf(Request $request)
    {
        // ambil data sama seperti reports()
        $q = StockIn::with(['user','items.product']);
        if ($request->filled('date_from')) $q->whereDate('date','>=',$request->date_from);
        if ($request->filled('date_to'))   $q->whereDate('date','<=',$request->date_to);
        if (!$request->filled('date_from') && !$request->filled('date_to')) {
            $q->whereMonth('date', now()->month)->whereYear('date', now()->year);
        }

        $stockIns = $q->orderByDesc('date')->get();
        $totalItems = $stockIns->sum(fn($si) => $si->items->sum('quantity'));

        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->endOfMonth()->format('Y-m-d');

        // render HTML sederhana untuk PDF (tanpa view)
        $html = view('reports.stock-in-pdf', compact('stockIns','totalItems','dateFrom','dateTo'))->render();

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = new Dompdf($options);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="stock-in-report-'.$dateFrom.'-to-'.$dateTo.'.pdf"'
        ]);
    }

    /**
     * GET /api/stock-ins/history (staff)
     * Hanya menampilkan milik user login.
     * Query: date_from?, date_to?, per_page (default 10)
     */
    public function history(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);

        $q = StockIn::with(['user','items.product'])
            ->where('user_id', auth()->id())
            ->when($request->filled('date_from'), fn($qb) =>
                $qb->whereDate('date', '>=', $request->date_from)
            )
            ->when($request->filled('date_to'), fn($qb) =>
                $qb->whereDate('date', '<=', $request->date_to)
            )
            ->latest();

        $p = $q->paginate($perPage);

        return ResponseFormatter::success([
            'items' => $p->items(),
            'pagination' => [
                'current_page' => $p->currentPage(),
                'per_page'     => $p->perPage(),
                'total'        => $p->total(),
                'last_page'    => $p->lastPage(),
            ]
        ], 'OK');
    }
}
