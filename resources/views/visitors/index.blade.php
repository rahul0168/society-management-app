@extends('layouts.app')

@section('title', 'Visitor Management & Gate Passes')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="glass-panel rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center space-x-2">
                <i data-feather="shield" class="w-6 h-6 text-emerald-400"></i>
                <span>Visitor Management & Gate Passes</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Pre-approve guests, cabs, delivery drivers, and track gate check-ins with 6-digit codes.</p>
        </div>

        <button onclick="document.getElementById('new-visitor-modal').classList.remove('hidden')" class="btn-gradient px-4 py-2.5 rounded-xl text-xs font-bold text-white flex items-center space-x-2 shadow-lg">
            <i data-feather="plus" class="w-4 h-4"></i>
            <span>Pre-Approve Visitor / Passcode</span>
        </button>
    </div>

    <!-- Quick Passcode Search Bar -->
    <div class="glass-card rounded-2xl p-4 border border-slate-800">
        <form action="{{ route('visitors.index') }}" method="GET" class="flex items-center space-x-3">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <i data-feather="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="code" value="{{ $codeSearch }}" placeholder="Search by 6-digit entry code or visitor name..." 
                    class="w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs transition">Search</button>
            @if($codeSearch)
                <a href="{{ route('visitors.index') }}" class="px-3 py-2 bg-slate-800 text-slate-400 hover:text-white rounded-xl text-xs">Clear</a>
            @endif
        </form>
    </div>

    <!-- Visitor Pass List -->
    <div class="glass-card rounded-2xl overflow-hidden border border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/90 text-slate-400 uppercase font-bold text-[10px] tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Visitor & Purpose</th>
                        <th class="px-6 py-4">Flat Unit</th>
                        <th class="px-6 py-4">Vehicle No</th>
                        <th class="px-6 py-4">Entry Passcode</th>
                        <th class="px-6 py-4">Status & Time</th>
                        <th class="px-6 py-4 text-right">Gate Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($visitors as $v)
                        <tr class="hover:bg-slate-900/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-sm">{{ $v->visitor_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $v->purpose }} • {{ $v->visitor_phone }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                Flat {{ $v->flat->flat_number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-slate-300 font-mono">
                                {{ $v->vehicle_number ?: 'Walk-in' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-black px-2.5 py-1 rounded bg-blue-900/50 text-blue-300 border border-blue-600/50">
                                    {{ $v->entry_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                    {{ $v->status === 'checked_in' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($v->status === 'checked_out' ? 'bg-slate-800 text-slate-400' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30') }}">
                                    {{ str_replace('_', ' ', $v->status) }}
                                </span>
                                @if($v->checked_in_at)
                                    <div class="text-[10px] text-slate-500 mt-1">In: {{ $v->checked_in_at->format('h:i A') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if(Auth::user()->isGuard() || Auth::user()->isAdmin())
                                    @if($v->status === 'approved')
                                        <form action="{{ route('visitors.check-in', $v->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg text-[11px] transition">
                                                Check In
                                            </button>
                                        </form>
                                    @elseif($v->status === 'checked_in')
                                        <form action="{{ route('visitors.check-out', $v->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-lg text-[11px] transition">
                                                Check Out
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">No visitors logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-800">
            {{ $visitors->links() }}
        </div>
    </div>

</div>

<!-- Pre-Approve Visitor Modal -->
<div id="new-visitor-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-lg text-white">Generate Gate Passcode</h3>
            <button onclick="document.getElementById('new-visitor-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('visitors.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-semibold mb-1">Visitor Full Name</label>
                <input type="text" name="visitor_name" required placeholder="e.g. Ramesh Kumar" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Phone Number</label>
                    <input type="text" name="visitor_phone" required placeholder="+91 9876543210" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Purpose of Visit</label>
                    <input type="text" name="purpose" required placeholder="Guest / Delivery / Cab" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Vehicle Number (Optional)</label>
                <input type="text" name="vehicle_number" placeholder="e.g. MH 02 AB 1234" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            @if(Auth::user()->isGuard() || Auth::user()->isAdmin())
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Destination Flat Unit</label>
                    <select name="flat_id" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        @foreach($flats as $f)
                            <option value="{{ $f->id }}">Flat {{ $f->flat_number }} ({{ $f->wing->name ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('new-visitor-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 btn-gradient text-white rounded-xl font-bold">Generate Passcode</button>
            </div>
        </form>
    </div>
</div>
@endsection
