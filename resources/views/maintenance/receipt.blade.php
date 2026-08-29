<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $bill->bill_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; color: black; }
            .receipt-card { background: white; color: black; border: 1px solid #ccc; shadow: none; }
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-slate-950">

    <div class="w-full max-w-xl receipt-card bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl space-y-6">

        <!-- Top Actions Bar -->
        <div class="no-print flex items-center justify-between border-b border-slate-800 pb-4">
            <a href="{{ route('maintenance.index') }}" class="text-xs text-blue-400 font-semibold hover:underline">&larr; Back to Maintenance</a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition">
                🖨️ Print Receipt
            </button>
        </div>

        <!-- Receipt Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-white">Greenfield Heights Housing Society</h1>
                <p class="text-xs text-slate-400">Official Payment Voucher Receipt</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full font-bold text-xs">
                    PAID RECEIPT
                </span>
            </div>
        </div>

        <!-- Bill & Resident Details Grid -->
        <div class="grid grid-cols-2 gap-4 p-4 rounded-xl bg-slate-950 border border-slate-800 text-xs">
            <div>
                <span class="text-slate-500 block">Receipt Number</span>
                <span class="font-mono font-bold text-white text-sm">{{ $bill->bill_number }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">Payment Date</span>
                <span class="font-bold text-white">{{ $bill->payment_date ? $bill->payment_date->format('M d, Y h:i A') : 'N/A' }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">Flat / Unit</span>
                <span class="font-bold text-white">Flat {{ $bill->flat->flat_number }} ({{ $bill->flat->wing->name ?? '' }})</span>
            </div>
            <div>
                <span class="text-slate-500 block">Payment Method</span>
                <span class="font-bold text-emerald-400">{{ $bill->payment_method ?? 'UPI Payment' }}</span>
            </div>
            <div class="col-span-2 border-t border-slate-800 pt-2">
                <span class="text-slate-500 block">Transaction Reference ID</span>
                <span class="font-mono text-slate-300">{{ $bill->transaction_id ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Itemized Table -->
        <div class="border border-slate-800 rounded-xl overflow-hidden text-xs">
            <table class="w-full text-left">
                <thead class="bg-slate-950 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Description</th>
                        <th class="p-3 text-right">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-200">
                    <tr>
                        <td class="p-3">Society Maintenance Charges ({{ $bill->month_year }})</td>
                        <td class="p-3 text-right font-mono">₹{{ number_format($bill->maintenance_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="p-3">Utility & Water Meter Charges</td>
                        <td class="p-3 text-right font-mono">₹{{ number_format($bill->utility_amount, 2) }}</td>
                    </tr>
                    @if($bill->penalty_amount > 0)
                        <tr>
                            <td class="p-3 text-rose-400">Late Fee Penalty</td>
                            <td class="p-3 text-right font-mono text-rose-400">₹{{ number_format($bill->penalty_amount, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot class="bg-slate-950 font-bold border-t border-slate-800 text-white text-sm">
                    <tr>
                        <td class="p-3">Total Amount Paid</td>
                        <td class="p-3 text-right font-mono text-emerald-400">₹{{ number_format($bill->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center text-[10px] text-slate-500 border-t border-slate-800 pt-4">
            Computer-generated official receipt. No signature required. Greenfield Heights Management Committee.
        </div>

    </div>

</body>
</html>
