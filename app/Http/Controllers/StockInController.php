<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Product;
use App\Models\Supplier;
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
        $query = StockIn::with(['supplier', 'user', 'items.product']);

        if ($request->has('search') && $request->search) {
            $query->where('reference_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('supplier', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->has('supplier_id') && $request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $stockIns = $query->latest()->paginate(10);
        $suppliers = Supplier::all();

        return view('stock-ins.index', compact('stockIns', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $referenceNumber = 'SI-' . date('Ymd') . '-' . str_pad(StockIn::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
        
        return view('stock-ins.create', compact('suppliers', 'products', 'referenceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|max:255|unique:stock_ins',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;

            // Calculate total amount
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['purchase_price'];
            }

            // Create stock in record
            $stockIn = StockIn::create([
                'reference_number' => $request->reference_number,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'date' => $request->date,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Create stock in items and update product stock
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['purchase_price'];
                
                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $subtotal,
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
        $stockIn->load(['supplier', 'user', 'items.product']);
        return view('stock-ins.show', compact('stockIn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockIn $stockIn)
    {
        $stockIn->load('items.product');
        $suppliers = Supplier::all();
        $products = Product::all();
        
        return view('stock-ins.edit', compact('stockIn', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockIn $stockIn)
    {
        $request->validate([
            'reference_number' => 'required|string|max:255|unique:stock_ins,reference_number,' . $stockIn->id,
            'supplier_id' => 'nullable|exists:suppliers,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $stockIn) {
            // Revert old stock quantities
            foreach ($stockIn->items as $oldItem) {
                $product = Product::find($oldItem->product_id);
                $product->decrement('stock_quantity', $oldItem->quantity);
            }

            // Delete old items
            $stockIn->items()->delete();

            $totalAmount = 0;

            // Calculate total amount
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['purchase_price'];
            }

            // Update stock in record
            $stockIn->update([
                'reference_number' => $request->reference_number,
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Create new stock in items and update product stock
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['purchase_price'];
                
                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $subtotal,
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
        $query = StockIn::with(['supplier', 'user', 'items.product']);

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
        $totalAmount = $stockIns->sum('total_amount');
        $totalItems = $stockIns->sum(function($stockIn) {
            return $stockIn->items->sum('quantity');
        });

        return view('reports.stock-in', compact('stockIns', 'totalAmount', 'totalItems'));
    }

    /**
     * Export stock in report to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = StockIn::with(['supplier', 'user', 'items.product']);

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
        $totalAmount = $stockIns->sum('total_amount');
        $totalItems = $stockIns->sum(function($stockIn) {
            return $stockIn->items->sum('quantity');
        });

        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->endOfMonth()->format('Y-m-d');

        // Generate PDF using DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = new Dompdf($options);
        
        $html = view('reports.stock-in-pdf', compact('stockIns', 'totalAmount', 'totalItems', 'dateFrom', 'dateTo'))->render();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="stock-in-report-' . $dateFrom . '-to-' . $dateTo . '.pdf"'
        ]);
    }

    /**
     * Display stock in history for operator role
     */
    public function history(Request $request)
    {
        $query = StockIn::with(['supplier', 'user', 'items.product'])
                        ->where('user_id', auth()->id());

        // Apply date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $stockIns = $query->latest()->paginate(10);

        return view('operator.stock-in-history', compact('stockIns'));
    }
}
