<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class StockInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockIn::with(['user', 'items.product']);

        if ($request->has('search') && $request->search) {
            $query->where('reference_number', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $stockIns = $query->latest()->paginate(10);

        return view('stock-ins.index', compact('stockIns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $referenceNumber = 'SI-' . date('Ymd') . '-' . str_pad(StockIn::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
        
        return view('stock-ins.create', compact('products', 'referenceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|max:255|unique:stock_ins',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {

            // Create stock in record
            $stockIn = StockIn::create([
                'reference_number' => $request->reference_number,
                'user_id' => auth()->id(),
                'date' => $request->date,
                'notes' => $request->notes,
            ]);

            // Create stock in items and update product stock
            foreach ($request->items as $item) {
                
                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->increment('stock_quantity', $item['quantity']);
            }
        });

        return redirect()->route('stock-ins.index')
                        ->with('success', 'Stock In transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockIn $stockIn)
    {
        $stockIn->load(['user', 'items.product']);
        return view('stock-ins.show', compact('stockIn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockIn $stockIn)
    {
        $stockIn->load('items.product');
        $products = Product::all();
        
        return view('stock-ins.edit', compact('stockIn', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockIn $stockIn)
    {
        $request->validate([
            'reference_number' => 'required|string|max:255|unique:stock_ins,reference_number,' . $stockIn->id,
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $stockIn) {
            // Revert old stock quantities
            foreach ($stockIn->items as $oldItem) {
                $product = Product::find($oldItem->product_id);
                $product->decrement('stock_quantity', $oldItem->quantity);
            }

            // Delete old items
            $stockIn->items()->delete();

            // Update stock in record
            $stockIn->update([
                'reference_number' => $request->reference_number,
                'date' => $request->date,
                'notes' => $request->notes,
            ]);

            // Create new stock in items and update product stock
            foreach ($request->items as $item) {
                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->increment('stock_quantity', $item['quantity']);
            }
        });

        return redirect()->route('stock-ins.index')
                        ->with('success', 'Stock In transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockIn $stockIn)
    {
        DB::transaction(function () use ($stockIn) {
            // Revert stock quantities
            foreach ($stockIn->items as $item) {
                $product = Product::find($item->product_id);
                $product->decrement('stock_quantity', $item->quantity);
            }

            // Delete stock in items
            $stockIn->items()->delete();
            
            // Delete stock in record
            $stockIn->delete();
        });

        return redirect()->route('stock-ins.index')
                        ->with('success', 'Stock In transaction deleted successfully.');
    }

    /**
     * Display stock in reports for owner role
     */
    public function reports(Request $request)
    {
        $query = StockIn::with(['user', 'items.product']);

        // Apply date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Default to current month if no dates provided
        if (!$request->has('date_from') && !$request->has('date_to')) {
            $query->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year);
        }

        $stockIns = $query->orderBy('date', 'desc')->get();
        $totalItems = $stockIns->sum(function($stockIn) {
            return $stockIn->items->sum('quantity');
        });

        return view('reports.stock-in', compact('stockIns', 'totalItems'));
    }

    /**
     * Export stock in report to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = StockIn::with(['user', 'items.product']);

        // Apply date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Default to current month if no dates provided
        if (!$request->has('date_from') && !$request->has('date_to')) {
            $query->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year);
        }

        $stockIns = $query->orderBy('date', 'desc')->get();
        $totalItems = $stockIns->sum(function($stockIn) {
            return $stockIn->items->sum('quantity');
        });

        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->endOfMonth()->format('Y-m-d');

        // Generate PDF using DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = new Dompdf($options);
        
        $html = view('reports.stock-in-pdf', compact('stockIns', 'totalItems', 'dateFrom', 'dateTo'))->render();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="stock-in-report-' . $dateFrom . '-to-' . $dateTo . '.pdf"'
        ]);
    }

    /**
     * Display stock in history for staff role
     */
    public function history(Request $request)
    {
        $query = StockIn::with(['user', 'items.product'])
                        ->where('user_id', auth()->id());

        // Apply date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $stockIns = $query->latest()->paginate(10);

        return view('staff.stock-in-history', compact('stockIns'));
    }
}
