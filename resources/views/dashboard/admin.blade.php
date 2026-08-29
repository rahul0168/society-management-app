@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Top Greeting Banner -->
    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div>
                <span class="px-2.5 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold uppercase tracking-wider">
                    Society Management Board
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-sm text-slate-400">Here is what's happening at Greenfield Heights housing complex today.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('members.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-bold text-slate-200 flex items-center space-x-1.5 transition">
                    <i data-feather="users" class="w-4 h-4 text-blue-400"></i>
                    <span>Manage Members</span>
                </a>
                <a href="{{ route('maintenance.index') }}" class="btn-gradient px-4 py-2 rounded-xl text-xs font-bold text-white flex items-center space-x-1.5">
                    <i data-feather="plus-circle" class="w-4 h-4"></i>
                    <span>Generate Bills</span>
                </a>
                <a href="{{ route('notices.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-bold text-slate-200 flex items-center space-x-1.5 transition">
                    <i data-feather="bell" class="w-4 h-4 text-amber-400"></i>
                    <span>Post Notice</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Overview Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Flats -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Units / Flats</span>
                <div class="p-2.5 rounded-xl bg-blue-500/10 text-blue-400">
                    <i data-feather="home" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white mt-2">{{ $totalFlats }}</div>
            <p class="text-xs text-slate-400 mt-1"><span class="text-emerald-400 font-bold">{{ $totalResidents }}</span> Registered Residents</p>
        </div>

        <!-- Total Revenue Collected -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Collected Revenue</span>
                <div class="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-400">
                    <i data-feather="dollar-sign" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-emerald-400 mt-2">₹{{ number_format($totalCollected, 2) }}</div>
            <p class="text-xs text-slate-400 mt-1"><span class="text-amber-400 font-bold">{{ $pendingBillsCount }}</span> Pending Bills</p>
        </div>

        <!-- Helpdesk Complaints -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Tickets</span>
                <div class="p-2.5 rounded-xl bg-rose-500/10 text-rose-400">
                    <i data-feather="life-buoy" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white mt-2">{{ $activeComplaintsCount }}</div>
            <p class="text-xs text-slate-400 mt-1">Plumbing, Electrical, Security</p>
        </div>

        <!-- Gate Visitors Today -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Gate Activity Today</span>
                <div class="p-2.5 rounded-xl bg-cyan-500/10 text-cyan-400">
                    <i data-feather="shield" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-cyan-400 mt-2">{{ $todayVisitorsCount }}</div>
            <p class="text-xs text-slate-400 mt-1">Visitors, Delivery, Cabs</p>
        </div>
    </div>

    <!-- 2 Column Layout: Recent Helpdesk Tickets & Recent Maintenance Bills -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Helpdesk Tickets -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <i data-feather="alert-circle" class="w-5 h-5 text-rose-400"></i>
                    <h3 class="font-bold text-lg text-white">Recent Complaints</h3>
                </div>
                <a href="{{ route('complaints.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-semibold">View All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentComplaints as $complaint)
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-white">{{ $complaint->title }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                                    {{ $complaint->priority === 'urgent' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">
                                    {{ $complaint->priority }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-400 mt-1">
                                By {{ $complaint->user->name }} (Flat {{ $complaint->flat->flat_number ?? 'N/A' }}) • {{ $complaint->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize 
                            {{ $complaint->status === 'resolved' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                            {{ str_replace('_', ' ', $complaint->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-4">No recent complaints.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Maintenance Bills -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <i data-feather="dollar-sign" class="w-5 h-5 text-emerald-400"></i>
                    <h3 class="font-bold text-lg text-white">Recent Maintenance Bills</h3>
                </div>
                <a href="{{ route('maintenance.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-semibold">View All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentBills as $bill)
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-white">Flat {{ $bill->flat->flat_number }} — {{ $bill->month_year }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">Invoice: <span class="font-mono text-slate-300">{{ $bill->bill_number }}</span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black text-white">₹{{ number_format($bill->total_amount, 2) }}</div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                                {{ $bill->status === 'paid' ? 'bg-emerald-500/20 text-emerald-400' : ($bill->status === 'overdue' ? 'bg-rose-500/20 text-rose-400' : 'bg-amber-500/20 text-amber-400') }}">
                                {{ $bill->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-4">No bills generated yet.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
