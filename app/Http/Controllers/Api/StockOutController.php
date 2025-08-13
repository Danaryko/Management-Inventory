<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\StockOut;
use App\Models\StockOutItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Dompdf\Dompdf;
use Dompdf\Options;

class StockOutController extends Controller
{
    /**
     * GET /api/stock-outs
     * Query: search, date_from, date_to, per_page (default 10)
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);

        $q = StockOut::with(['user','items.product'])
            ->when($request->filled('search'), fn($qb) =>
                $qb->where('reference_number','like','%'.$request->search.'%')
            )
            ->when($request->filled('date_from'), fn($qb) =>
                $qb->whereDate('date','>=',$request->date_from)
            )
            ->when($request->filled('date_to'), fn($qb) =>
                $qb->whereDate('date','<=',$request->date_to)
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
     * (Opsional) GET /api/stock-outs/reference
     * Generate nomor referensi: SO-YYYYMMDD-xxx
     */
    public function reference()
    {
        $next = str_pad(
            StockOut::whereDate('created_at', today())->count() + 1,
            3, '0', STR_PAD_LEFT
        );
        $ref = 'SO-'.date('Ymd').'-'.$next;

        return ResponseFormatter::success(['reference_number' => $ref], 'OK');
    }

    /**
     * POST /api/stock-outs
     * Body: reference_number, date (Y-m-d), notes?, items: [{product_id, quantity}]
     * Akan menurunkan stock_quantity produk.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'reference_number'   => ['required','string','max:255','unique:stock_outs,reference_number'],
                'date'               => ['required','date'],
                'notes'              => ['nullable','string'],
                'items'              => ['required','array','min:1'],
                'items.*.product_id' => ['required','exists:products,id'],
                'items.*.quantity'   => ['required','integer','min:1'],
            ]);

            // Validasi ketersediaan stok (sebelum transaksi)
            foreach ($data['items'] as $it) {
                $p = Product::find($it['product_id']);
                if (!$p || $p->stock_quantity < $it['quantity']) {
                    return ResponseFormatter::error(
                        null,
                        "Insufficient stock for {$p->name}. Available: {$p->stock_quantity}, Requested: {$it['quantity']}",
                        422
                    );
                }
            }

            $stockOut = DB::transaction(function () use ($data) {
                $stockOut = StockOut::create([
                    'reference_number' => $data['reference_number'],
                    'user_id'          => auth()->id(),
                    'date'             => $data['date'],
                    'notes'            => $data['notes'] ?? null,
                ]);

                foreach ($data['items'] as $it) {
                    StockOutItem::create([
                        'stock_out_id' => $stockOut->id,
                        'product_id'   => $it['product_id'],
                        'quantity'     => $it['quantity'],
                    ]);

                    Product::whereKey($it['product_id'])
                        ->decrement('stock_quantity', $it['quantity']);
                }

                return $stockOut->load(['user','items.product']);
            });

            return ResponseFormatter::success($stockOut, 'Created');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Create failed', 500);
        }
    }

    /**
     * GET /api/stock-outs/{stockOut}
     */
    public function show(StockOut $stockOut)
    {
        return ResponseFormatter::success(
            $stockOut->load(['user','items.product']),
            'OK'
        );
    }

    /**
     * PUT /api/stock-outs/{stockOut}
     * Revert stok lama, validasi, lalu apply stok baru.
     */
    public function update(Request $request, StockOut $stockOut)
    {
        try {
            $data = $request->validate([
                'reference_number'   => ['required','string','max:255','unique:stock_outs,reference_number,'.$stockOut->id],
                'date'               => ['required','date'],
                'notes'              => ['nullable','string'],
                'items'              => ['required','array','min:1'],
                'items.*.product_id' => ['required','exists:products,id'],
                'items.*.quantity'   => ['required','integer','min:1'],
            ]);

            $stockOut = DB::transaction(function () use ($data, $stockOut) {
                // Kembalikan stok lama
                foreach ($stockOut->items as $old) {
                    Product::whereKey($old->product_id)
                        ->increment('stock_quantity', $old->quantity);
                }

                // Hapus detail lama
                $stockOut->items()->delete();

                // Validasi stok untuk item baru (setelah revert)
                foreach ($data['items'] as $it) {
                    $p = Product::find($it['product_id']);
                    if (!$p || $p->stock_quantity < $it['quantity']) {
                        throw new \RuntimeException("Insufficient stock for {$p->name}. Available: {$p->stock_quantity}, Requested: {$it['quantity']}");
                    }
                }

                // Update header
                $stockOut->update([
                    'reference_number' => $data['reference_number'],
                    'date'             => $data['date'],
                    'notes'            => $data['notes'] ?? null,
                ]);

                // Tambah detail baru & turunkan stok
                foreach ($data['items'] as $it) {
                    StockOutItem::create([
                        'stock_out_id' => $stockOut->id,
                        'product_id'   => $it['product_id'],
                        'quantity'     => $it['quantity'],
                    ]);

                    Product::whereKey($it['product_id'])
                        ->decrement('stock_quantity', $it['quantity']);
                }

                return $stockOut->load(['user','items.product']);
            });

            return ResponseFormatter::success($stockOut, 'Updated');
        } catch (ValidationException $e) {
            return ResponseFormatter::error($e->errors(), 'Validation error', 422);
        } catch (\RuntimeException $e) {
            return ResponseFormatter::error(null, $e->getMessage(), 422);
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Update failed', 500);
        }
    }

    /**
     * DELETE /api/stock-outs/{stockOut}
     * Revert stok, hapus detail & header.
     */
    public function destroy(StockOut $stockOut)
    {
        try {
            DB::transaction(function () use ($stockOut) {
                foreach ($stockOut->items as $it) {
                    Product::whereKey($it->product_id)
                        ->increment('stock_quantity', $it->quantity);
                }
                $stockOut->items()->delete();
                $stockOut->delete();
            });

            return ResponseFormatter::success(null, 'Deleted');
        } catch (\Throwable $e) {
            return ResponseFormatter::error(null, 'Delete failed', 500);
        }
    }

    /**
     * GET /api/stock-outs/reports
     * Query: date_from?, date_to?
     * Default: bulan berjalan (jika kosong)
     * Return: list + summary (total items & periode)
     */
    public function reports(Request $request)
    {
        $q = StockOut::with(['user','items.product']);

        if ($request->filled('date_from')) {
            $q->whereDate('date','>=',$request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->whereDate('date','<=',$request->date_to);
        }
        if (!$request->filled('date_from') && !$request->filled('date_to')) {
            $q->whereMonth('date', now()->month)->whereYear('date', now()->year);
        }

        $rows = $q->orderByDesc('date')->get();
        $totalItems = $rows->sum(fn($so) => $so->items->sum('quantity'));

        return ResponseFormatter::success([
            'rows' => $rows,
            'summary' => [
                'total_transactions' => $rows->count(),
                'total_items'        => $totalItems,
                'period'             => [
                    'from' => $request->date_from ?? now()->startOfMonth()->toDateString(),
                    'to'   => $request->date_to   ?? now()->endOfMonth()->toDateString(),
                ],
            ],
        ], 'OK');
    }

    /**
     * GET /api/stock-outs/reports/pdf
     * Mengembalikan file PDF (response biner)
     */
    public function exportPdf(Request $request)
    {
        $q = StockOut::with(['user','items.product']);
        if ($request->filled('date_from')) $q->whereDate('date','>=',$request->date_from);
        if ($request->filled('date_to'))   $q->whereDate('date','<=',$request->date_to);
        if (!$request->filled('date_from') && !$request->filled('date_to')) {
            $q->whereMonth('date', now()->month)->whereYear('date', now()->year);
        }

        $stockOuts = $q->orderByDesc('date')->get();
        $totalItems = $stockOuts->sum(fn($so) => $so->items->sum('quantity'));

        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->endOfMonth()->format('Y-m-d');

        // Render HTML via view lama agar layout rapi
        $html = view('reports.stock-out-pdf', compact('stockOuts','totalItems','dateFrom','dateTo'))->render();

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = new Dompdf($options);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="stock-out-report-'.$dateFrom.'-to-'.$dateTo.'.pdf"'
        ]);
    }

    /**
     * GET /api/stock-outs/history
     * Hanya milik user login (staff), dengan paginate.
     * Query: date_from?, date_to?, per_page (default 10)
     */
    public function history(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);

        $q = StockOut::with(['user','items.product'])
            ->where('user_id', auth()->id())
            ->when($request->filled('date_from'), fn($qb) =>
                $qb->whereDate('date','>=',$request->date_from)
            )
            ->when($request->filled('date_to'), fn($qb) =>
                $qb->whereDate('date','<=',$request->date_to)
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
