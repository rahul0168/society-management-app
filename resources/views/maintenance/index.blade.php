@extends('layouts.app')

@section('title', 'Maintenance & Utility Bills')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="glass-panel rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center space-x-2">
                <i data-feather="credit-card" class="w-6 h-6 text-blue-400"></i>
                <span>Maintenance & Billing</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Track monthly maintenance fees, utility charges, and download official receipts.</p>
        </div>

        @if(Auth::user()->isAdmin())
            <button onclick="document.getElementById('generate-bill-modal').classList.remove('hidden')" class="btn-gradient px-4 py-2.5 rounded-xl text-xs font-bold text-white flex items-center space-x-2 shadow-lg">
                <i data-feather="plus" class="w-4 h-4"></i>
                <span>Generate Maintenance Bills</span>
            </button>
        @endif
    </div>

    <!-- Summary Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-card rounded-2xl p-4 border border-slate-800">
            <div class="text-xs font-semibold text-slate-400 uppercase">Total Bills</div>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['total_bills'] }}</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-slate-800">
            <div class="text-xs font-semibold text-slate-400 uppercase">Collected Total</div>
            <div class="text-2xl font-black text-emerald-400 mt-1">₹{{ number_format($stats['total_paid'], 2) }}</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-slate-800">
            <div class="text-xs font-semibold text-slate-400 uppercase">Outstanding Pending</div>
            <div class="text-2xl font-black text-amber-400 mt-1">₹{{ number_format($stats['total_pending'], 2) }}</div>
        </div>
    </div>

    <!-- Filter Pills -->
    <div class="flex items-center space-x-2 text-xs">
        <span class="text-slate-400 font-semibold mr-2">Filter:</span>
        <a href="{{ route('maintenance.index') }}" class="px-3 py-1.5 rounded-lg border font-medium {{ !$statusFilter ? 'bg-blue-600 text-white border-blue-500' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white' }}">All Bills</a>
        <a href="{{ route('maintenance.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white border-amber-500' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white' }}">Pending</a>
        <a href="{{ route('maintenance.index', ['status' => 'paid']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $statusFilter === 'paid' ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white' }}">Paid</a>
        <a href="{{ route('maintenance.index', ['status' => 'overdue']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $statusFilter === 'overdue' ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white' }}">Overdue</a>
    </div>

    <!-- Bills Table -->
    <div class="glass-card rounded-2xl overflow-hidden border border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/90 text-slate-400 uppercase font-bold text-[10px] tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Bill No / Month</th>
                        <th class="px-6 py-4">Flat Unit</th>
                        <th class="px-6 py-4">Breakdown</th>
                        <th class="px-6 py-4">Total Amount</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($bills as $bill)
                        <tr class="hover:bg-slate-900/50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-white">
                                <div>{{ $bill->bill_number }}</div>
                                <div class="text-[11px] font-sans text-slate-400">{{ $bill->month_year }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                Flat {{ $bill->flat->flat_number ?? 'N/A' }}
                                <div class="text-[10px] text-slate-500">{{ $bill->flat->wing->name ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-400">
                                Maint: ₹{{ number_format($bill->maintenance_amount, 2) }} <br>
                                Util: ₹{{ number_format($bill->utility_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 font-extrabold text-sm text-white">
                                ₹{{ number_format($bill->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-slate-400">
                                {{ $bill->due_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                    {{ $bill->status === 'paid' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($bill->status === 'overdue' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30') }}">
                                    {{ $bill->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($bill->status !== 'paid')
                                    <form action="{{ route('maintenance.pay', $bill->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="UPI">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] transition">
                                            Pay Online
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('maintenance.receipt', $bill->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-blue-400 font-bold text-[11px] transition inline-flex items-center space-x-1 border border-slate-700">
                                        <i data-feather="file-text" class="w-3 h-3"></i>
                                        <span>Receipt</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">No maintenance bills found matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-800">
            {{ $bills->links() }}
        </div>
    </div>

</div>

<!-- Admin Bill Generation Modal -->
@if(Auth::user()->isAdmin())
<div id="generate-bill-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-lg text-white">Generate Monthly Maintenance Bills</h3>
            <button onclick="document.getElementById('generate-bill-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('maintenance.generate') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-semibold mb-1">Billing Month & Year</label>
                <input type="text" name="month_year" value="September 2026" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Maintenance Amount (₹)</label>
                    <input type="number" step="0.01" name="maintenance_amount" value="2500" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Utility Amount (₹)</label>
                    <input type="number" step="0.01" name="utility_amount" value="400" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Due Date</label>
                <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+10 days')) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Target Flat (Optional - Leave blank for ALL flats)</label>
                <select name="flat_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                    <option value="">-- All Society Flats --</option>
                    @foreach($flats as $f)
                        <option value="{{ $f->id }}">Flat {{ $f->flat_number }} ({{ $f->wing->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('generate-bill-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 btn-gradient text-white rounded-xl font-bold">Generate Bills</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
