@extends('layouts.app')

@section('title', 'Resident Portal')

@section('content')
<div class="space-y-6">

    <!-- Top Resident Welcome Header with Flat Badge -->
    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-bold">
                        Flat {{ $flat->flat_number ?? 'N/A' }} • {{ $flat->wing->name ?? 'Main Wing' }}
                    </span>
                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold capitalize">
                        {{ str_replace('_', ' ', $flat->occupancy_status ?? 'Resident') }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-3">Welcome Home, {{ Auth::user()->name }}! 🏠</h1>
                <p class="text-sm text-slate-400 mt-1">Manage your maintenance bills, gate visitor approvals, and society helpdesk.</p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('members.index') }}" class="px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-bold text-slate-200 flex items-center space-x-1.5 transition">
                    <i data-feather="users" class="w-4 h-4 text-blue-400"></i>
                    <span>Society Directory</span>
                </a>
                <a href="{{ route('visitors.index') }}" class="btn-gradient px-4 py-2.5 rounded-xl text-xs font-bold text-white flex items-center space-x-1.5 shadow-lg">
                    <i data-feather="plus-circle" class="w-4 h-4"></i>
                    <span>Pre-Approve Visitor</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Pending Maintenance Bill Highlight Banner (If any) -->
    @if($pendingBill)
        <div class="p-5 rounded-2xl bg-gradient-to-r from-amber-900/40 via-amber-900/20 to-slate-900 border border-amber-500/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start space-x-3">
                <div class="p-3 rounded-xl bg-amber-500/20 text-amber-400 shrink-0">
                    <i data-feather="alert-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Pending Maintenance Bill: {{ $pendingBill->month_year }}</h3>
                    <p class="text-xs text-slate-300 mt-0.5">
                        Amount Due: <span class="font-bold text-amber-400 text-sm">₹{{ number_format($pendingBill->total_amount, 2) }}</span> • Due Date: {{ $pendingBill->due_date->format('M d, Y') }}
                    </p>
                </div>
            </div>
            
            <form action="{{ route('maintenance.pay', $pendingBill->id) }}" method="POST" class="flex items-center space-x-2">
                @csrf
                <input type="hidden" name="payment_method" value="UPI">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-lg shadow-amber-500/20 transition flex items-center space-x-1.5 whitespace-nowrap">
                    <i data-feather="credit-card" class="w-4 h-4"></i>
                    <span>Pay via UPI / Card</span>
                </button>
            </form>
        </div>
    @endif

    <!-- 2 Column Section: Pinned Notices & Visitor Quick Pass -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Digital Notice Board Preview -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-feather="bell" class="w-5 h-5 text-blue-400"></i>
                    <h3 class="font-bold text-lg text-white">Digital Notice Board</h3>
                </div>
                <a href="{{ route('notices.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-semibold">View All Notices &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($notices as $notice)
                    <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 hover:border-slate-700 transition">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center space-x-2">
                                @if($notice->is_pinned)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">PINNED</span>
                                @endif
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                                    {{ $notice->category === 'urgent' ? 'bg-rose-500/20 text-rose-300' : 'bg-blue-500/20 text-blue-300' }}">
                                    {{ $notice->category }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-500">{{ $notice->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="font-bold text-white text-sm mt-1">{{ $notice->title }}</h4>
                        <p class="text-xs text-slate-300 mt-1 line-clamp-2 leading-relaxed">{{ $notice->content }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-4 text-center">No notices posted yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Right 1 Col: Quick Visitor Pass & Helpdesk Status -->
        <div class="space-y-6">

            <!-- Gate Passes Card -->
            <div class="glass-card rounded-2xl p-5 border border-slate-800">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-base text-white flex items-center space-x-2">
                        <i data-feather="shield" class="w-4 h-4 text-emerald-400"></i>
                        <span>Recent Visitors</span>
                    </h3>
                    <a href="{{ route('visitors.index') }}" class="text-xs text-blue-400 font-semibold">+ New Pass</a>
                </div>

                <div class="space-y-2.5">
                    @forelse($myVisitors as $visitor)
                        <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between text-xs">
                            <div>
                                <div class="font-bold text-white">{{ $visitor->visitor_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $visitor->purpose }}</div>
                            </div>
                            <div class="text-right">
                                <span class="font-mono px-2 py-0.5 rounded bg-blue-900/50 text-blue-300 border border-blue-700/50 text-[11px] font-bold block">
                                    {{ $visitor->entry_code }}
                                </span>
                                <span class="text-[10px] text-slate-400 capitalize mt-0.5 block">{{ $visitor->status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2 text-center">No recent visitors registered.</p>
                    @endforelse
                </div>
            </div>

            <!-- Amenity Booking Status Card -->
            <div class="glass-card rounded-2xl p-5 border border-slate-800">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-base text-white flex items-center space-x-2">
                        <i data-feather="calendar" class="w-4 h-4 text-cyan-400"></i>
                        <span>My Amenity Bookings</span>
                    </h3>
                    <a href="{{ route('amenities.index') }}" class="text-xs text-blue-400 font-semibold">Book &rarr;</a>
                </div>

                <div class="space-y-2.5">
                    @forelse($myBookings as $booking)
                        <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 text-xs">
                            <div class="font-bold text-white">{{ $booking->amenity->name }}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                {{ $booking->booking_date->format('M d, Y') }} • {{ $booking->start_time }}
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2 text-center">No active facility bookings.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
