@extends('layouts.app')

@section('title', 'Gate Security Desk')

@section('content')
<div class="space-y-6">

    <!-- Guard Security Console Banner -->
    <div class="glass-panel rounded-2xl p-6 border border-amber-500/30">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-bold uppercase tracking-wider">
                    🛡️ Main Gate Security Portal
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-2">Gate Console — {{ Auth::user()->name }}</h1>
                <p class="text-sm text-slate-400">Verify 6-digit resident entry passcodes and record guest check-ins.</p>
            </div>
            
            <a href="{{ route('visitors.index') }}" class="btn-gradient px-4 py-2.5 rounded-xl text-xs font-bold text-white flex items-center space-x-1.5 shadow-lg">
                <i data-feather="user-plus" class="w-4 h-4"></i>
                <span>Log Walk-in Guest</span>
            </a>
        </div>
    </div>

    <!-- Quick Passcode Lookup Search Box -->
    <div class="glass-card rounded-2xl p-6 border border-blue-500/30">
        <h3 class="font-bold text-lg text-white mb-2 flex items-center space-x-2">
            <i data-feather="key" class="w-5 h-5 text-blue-400"></i>
            <span>Verify Entry Passcode</span>
        </h3>
        <p class="text-xs text-slate-400 mb-4">Ask visitor for their 6-digit entry code provided by the flat resident.</p>

        <form action="{{ route('visitors.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="code" placeholder="Enter 6-digit passcode (e.g. 849201)" maxlength="6" 
                class="flex-grow px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-lg font-mono text-center sm:text-left font-bold text-emerald-400 tracking-widest focus:ring-2 focus:ring-blue-500 outline-none">
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-sm transition flex items-center justify-center space-x-2">
                <i data-feather="search" class="w-4 h-4"></i>
                <span>Verify Code</span>
            </button>
        </form>
    </div>

    <!-- 2 Column Layout: Currently Checked-in & Today's Visitor Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Currently Inside Society -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-base text-white flex items-center space-x-2">
                    <i data-feather="user-check" class="w-5 h-5 text-emerald-400"></i>
                    <span>Currently Checked-In ({{ $checkedInVisitors->count() }})</span>
                </h3>
            </div>

            <div class="space-y-3">
                @forelse($checkedInVisitors as $v)
                    <div class="p-4 rounded-xl bg-slate-900 border border-emerald-500/30 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-white text-sm">{{ $v->visitor_name }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                Flat: <span class="text-blue-300 font-semibold">{{ $v->flat->flat_number ?? 'N/A' }}</span> • {{ $v->purpose }}
                            </div>
                            <div class="text-[11px] text-emerald-400 mt-1">Checked In: {{ $v->checked_in_at ? $v->checked_in_at->format('h:i A') : 'Recently' }}</div>
                        </div>

                        <form action="{{ route('visitors.check-out', $v->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/30 text-rose-300 text-xs font-bold transition flex items-center space-x-1">
                                <i data-feather="log-out" class="w-3.5 h-3.5"></i>
                                <span>Check Out</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-6">No visitors currently inside the premises.</p>
                @endforelse
            </div>
        </div>

        <!-- Today's Visitor Log -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-base text-white flex items-center space-x-2">
                    <i data-feather="list" class="w-5 h-5 text-cyan-400"></i>
                    <span>Today's Visitors Log</span>
                </h3>
            </div>

            <div class="space-y-3">
                @forelse($todayVisitors as $v)
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-white">{{ $v->visitor_name }} ({{ $v->purpose }})</div>
                            <div class="text-slate-400 mt-0.5">Flat {{ $v->flat->flat_number ?? 'N/A' }} • Phone: {{ $v->visitor_phone }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-mono text-xs font-bold text-blue-400 block">{{ $v->entry_code }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider capitalize mt-1 inline-block
                                {{ $v->status === 'checked_in' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-800 text-slate-400' }}">
                                {{ str_replace('_', ' ', $v->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-6">No visitors recorded today.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
