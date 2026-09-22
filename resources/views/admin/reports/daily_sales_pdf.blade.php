<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daily Sales Report - {{ $date }}</title>
    <style>
        /* dompdf only supports a limited CSS subset: no flexbox, no grid,
           no CSS variables. Keep this file plain table/box layout. */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 18px;
            margin: 0 0 4px;
        }
        .subtitle {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 16px;
        }
        table.summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.summary td {
            width: 25%;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            vertical-align: top;
        }
        table.summary .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            display: block;
            margin-bottom: 3px;
        }
        table.summary .value {
            font-size: 15px;
            font-weight: bold;
        }
        .text-danger { color: #dc2626; }
        .text-success { color: #16a34a; }

        table.transactions {
            width: 100%;
            border-collapse: collapse;
        }
        table.transactions th,
        table.transactions td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 10px;
        }
        table.transactions th {
            background: #f1f5f9;
            text-align: left;
        }
        table.transactions td.text-end,
        table.transactions th.text-end {
            text-align: right;
        }
        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

<h1>Daily Sales Report</h1>
<p class="subtitle">Nita Mart &middot; {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>

<table class="summary">
    <tr>
        <td>
            <span class="label">Total Sales</span>
            <span class="value">${{ number_format($summary['total_sales'], 2) }}</span>
        </td>
        <td>
            <span class="label">Total Paid</span>
            <span class="value text-success">${{ number_format($summary['total_paid'], 2) }}</span>
        </td>
        <td>
            <span class="label">Total Balance</span>
            @php $bal = $summary['total_balance']; @endphp
            <span class="value {{ $bal < 0 ? 'text-danger' : '' }}">
                    {{ $bal < 0 ? '-' : '' }}${{ number_format(abs($bal), 2) }}
                </span>
        </td>
        <td>
            <span class="label">Total Orders</span>
            <span class="value">{{ $summary['total_orders'] }}</span>
        </td>
    </tr>
</table>

<table class="transactions">
    <thead>
    <tr>
        <th>Time</th>
        <th>Reference</th>
        <th>Biller</th>
        <th class="text-end">Grand Total</th>
        <th class="text-end">Paid</th>
        <th class="text-end">Balance</th>
        <th>Payment</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $sale)
        @php
            $paid = $sale->payments->sum('amount');
            $rowBalance = $sale->total_amount - $paid;
        @endphp
        <tr>
            <td>{{ $sale->created_at->format('H:i:s') }}</td>
            <td>{{ $sale->reference }}</td>
            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
            <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
            <td class="text-end">${{ number_format($paid, 2) }}</td>
            <td class="text-end {{ $rowBalance < 0 ? 'text-danger' : '' }}">
                {{ $rowBalance < 0 ? '-' : '' }}${{ number_format(abs($rowBalance), 2) }}
            </td>
            <td>{{ $sale->payment_status === 'paid' ? 'Completed' : ucfirst($sale->payment_status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p class="footer">Generated {{ now()->format('d M Y, H:i') }}</p>

</body>
</html>
