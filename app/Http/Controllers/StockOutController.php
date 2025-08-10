<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\StockOutItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockOut::with(['user', 'items.product']);

        if ($request->has('search') && $request->search) {
            $query->where('reference_number', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $stockOuts = $query->latest()->paginate(10);

        return view('stock-outs.index', compact('stockOuts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('stock_quantity', '>', 0)->get();
        $referenceNumber = 'SO-' . date('Ymd') . '-' . str_pad(StockOut::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
        
        return view('stock-outs.create', compact('products', 'referenceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|max:255|unique:stock_outs',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.sale_price' => 'required|numeric|min:0',
        ]);

        // Validate stock availability
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return back()->withErrors([
                    'items' => "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}, Requested: {$item['quantity']}"
                ])->withInput();
            }
        }

        DB::transaction(function () use ($request) {
            $totalAmount = 0;

            // Calculate total amount
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['sale_price'];
            }

            // Create stock out record
            $stockOut = StockOut::create([
                'reference_number' => $request->reference_number,
                'user_id' => auth()->id(),
                'date' => $request->date,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Create stock out items and update product stock
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['sale_price'];
                
                StockOutItem::create([
                    'stock_out_id' => $stockOut->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'sale_price' => $item['sale_price'],
                    'subtotal' => $subtotal,
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock_quantity', $item['quantity']);
            }
        });

        return redirect()->route('stock-outs.index')
                        ->with('success', 'Stock Out transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockOut $stockOut)
    {
        $stockOut->load(['user', 'items.product']);
        return view('stock-outs.show', compact('stockOut'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockOut $stockOut)
    {
        $stockOut->load('items.product');
        $products = Product::all();
        
        return view('stock-outs.edit', compact('stockOut', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockOut $stockOut)
    {
        $request->validate([
            'reference_number' => 'required|string|max:255|unique:stock_outs,reference_number,' . $stockOut->id,
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.sale_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $stockOut) {
            // Revert old stock quantities
            foreach ($stockOut->items as $oldItem) {
                $product = Product::find($oldItem->product_id);
                $product->increment('stock_quantity', $oldItem->quantity);
            }

            // Validate stock availability with reverted quantities
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock_quantity}, Requested: {$item['quantity']}");
                }
            }

            // Delete old items
            $stockOut->items()->delete();

            $totalAmount = 0;

            // Calculate total amount
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['sale_price'];
            }

            // Update stock out record
            $stockOut->update([
                'reference_number' => $request->reference_number,
                'date' => $request->date,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Create new stock out items and update product stock
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['sale_price'];
                
                StockOutItem::create([
                    'stock_out_id' => $stockOut->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'sale_price' => $item['sale_price'],
                    'subtotal' => $subtotal,
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock_quantity', $item['quantity']);
            }
        });

        return redirect()->route('stock-outs.index')
                        ->with('success', 'Stock Out transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockOut $stockOut)
    {
        DB::transaction(function () use ($stockOut) {
            // Revert stock quantities
            foreach ($stockOut->items as $item) {
                $product = Product::find($item->product_id);
                $product->increment('stock_quantity', $item->quantity);
            }

            // Delete stock out items
            $stockOut->items()->delete();
            
            // Delete stock out record
            $stockOut->delete();
        });

        return redirect()->route('stock-outs.index')
                        ->with('success', 'Stock Out transaction deleted successfully.');
    }
}
