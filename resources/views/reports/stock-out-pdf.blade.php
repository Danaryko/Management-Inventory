<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stock Out</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            color: #666;
        }
        
        .report-period {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .summary {
            margin: 20px 0;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .summary-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-weight: bold;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
        }
        
        .table td {
            font-size: 10px;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Management Inventory</div>
        <div class="report-title">LAPORAN STOCK OUT</div>
        <div class="report-period">
            Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
        </div>
        <div style="font-size: 10px; color: #666;">
            Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="summary">
        <div class="summary-title">Ringkasan</div>
        <div class="summary-row">
            <span class="summary-label">Total Transaksi:</span>
            <span>{{ $stockOuts->count() }} transaksi</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Items:</span>
            <span>{{ number_format($totalItems) }} items</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Nilai:</span>
            <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($stockOuts->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 20%;">Referensi</th>
                    <th style="width: 30%;">Items</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 18%;">Total Nilai</th>
                    <th style="width: 15%;">User</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockOuts as $index => $stockOut)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $stockOut->date->format('d/m/Y') }}</td>
                        <td>{{ $stockOut->reference_number }}</td>
                        <td>
                            @foreach($stockOut->items as $item)
                                {{ $item->product->name }}
                                @if(!$loop->last)<br>@endif
                            @endforeach
                        </td>
                        <td class="text-right">{{ number_format($stockOut->items->sum('quantity')) }}</td>
                        <td class="text-right">Rp {{ number_format($stockOut->total_amount, 0, ',', '.') }}</td>
                        <td>{{ $stockOut->user->name }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" class="text-center">TOTAL</td>
                    <td class="text-right">{{ number_format($totalItems) }}</td>
                    <td class="text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="no-data">
            Tidak ada data transaksi stock out pada periode yang dipilih.
        </div>
    @endif

    <div class="footer">
        <div>Laporan ini dibuat secara otomatis oleh sistem Management Inventory</div>
        <div>Halaman 1 dari 1</div>
    </div>
</body>
</html>