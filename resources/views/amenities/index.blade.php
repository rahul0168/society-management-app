@extends('layouts.app')

@section('title', 'Amenities & Facility Booking')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="glass-panel rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center space-x-2">
                <i data-feather="calendar" class="w-6 h-6 text-cyan-400"></i>
                <span>Society Facilities & Amenities</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Reserve clubhouse hall, tennis courts, swimming pool slots, and gym sessions.</p>
        </div>
    </div>

    <!-- Amenities Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($amenities as $amenity)
            <div class="glass-card rounded-2xl p-5 border border-slate-800 flex flex-col justify-between space-y-4 hover:border-slate-700 transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 flex items-center justify-center">
                            <i data-feather="{{ $amenity->icon ?: 'star' }}" class="w-5 h-5"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            {{ $amenity->status }}
                        </span>
                    </div>

                    <h3 class="text-base font-extrabold text-white">{{ $amenity->name }}</h3>
                    <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">{{ $amenity->description }}</p>

                    <div class="mt-4 pt-3 border-t border-slate-800 text-xs space-y-1 text-slate-400">
                        <div>Location: <span class="text-slate-200 font-semibold">{{ $amenity->location }}</span></div>
                        <div>Capacity: <span class="text-slate-200 font-semibold">{{ $amenity->max_capacity }} persons</span></div>
                        <div>Fee: <span class="text-emerald-400 font-extrabold">{{ $amenity->fee_per_slot > 0 ? '₹' . number_format($amenity->fee_per_slot, 2) : 'Free' }}</span></div>
                    </div>
                </div>

                <button onclick="openBookingModal({{ $amenity->id }}, '{{ addslashes($amenity->name) }}', '{{ $amenity->fee_per_slot }}')" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs transition">
                    Reserve Slot
                </button>
            </div>
        @endforeach
    </div>

    <!-- Active Reservations Table -->
    <div class="glass-card rounded-2xl p-6 border border-slate-800">
        <h3 class="font-bold text-lg text-white mb-4 flex items-center space-x-2">
            <i data-feather="check-square" class="w-5 h-5 text-emerald-400"></i>
            <span>Active Reservations</span>
        </h3>

        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left text-slate-300">
                <thead class="bg-slate-900 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Facility</th>
                        <th class="p-3">Resident / Flat</th>
                        <th class="p-3">Booking Date & Time</th>
                        <th class="p-3">Fee</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @php $list = Auth::user()->isAdmin() ? $allBookings : $myBookings; @endphp
                    @forelse($list as $b)
                        <tr>
                            <td class="p-3 font-bold text-white">{{ $b->amenity->name }}</td>
                            <td class="p-3 text-slate-300">{{ $b->user->name ?? 'Resident' }} (Flat {{ $b->flat->flat_number ?? 'N/A' }})</td>
                            <td class="p-3 text-slate-400">{{ $b->booking_date->format('M d, Y') }} ({{ $b->start_time }} - {{ $b->end_time }})</td>
                            <td class="p-3 font-bold text-emerald-400">₹{{ number_format($b->total_fee, 2) }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $b->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                @if($b->status === 'confirmed')
                                    <form action="{{ route('amenities.cancel', $b->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-rose-400 hover:underline font-bold text-xs">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-500">No facility bookings placed yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Amenity Slot Reservation Modal -->
<div id="amenity-booking-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-lg text-white" id="modal-amenity-name">Reserve Facility</h3>
            <button onclick="document.getElementById('amenity-booking-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('amenities.book') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <input type="hidden" id="modal-amenity-id" name="amenity_id">

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Booking Date</label>
                <input type="date" name="booking_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Start Time</label>
                    <input type="text" name="start_time" value="10:00 AM" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">End Time</label>
                    <input type="text" name="end_time" value="12:00 PM" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Purpose / Notes</label>
                <input type="text" name="purpose" placeholder="e.g. Family gathering / Practice" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div class="p-3 rounded-xl bg-slate-950 border border-slate-800 flex justify-between items-center text-xs">
                <span class="text-slate-400 font-semibold">Total Fee:</span>
                <span class="font-extrabold text-emerald-400 text-sm" id="modal-fee-display">₹0.00</span>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('amenity-booking-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 btn-gradient text-white rounded-xl font-bold">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function openBookingModal(id, name, fee) {
        document.getElementById('modal-amenity-id').value = id;
        document.getElementById('modal-amenity-name').innerText = 'Reserve ' + name;
        document.getElementById('modal-fee-display').innerText = fee > 0 ? '₹' + parseFloat(fee).toFixed(2) : 'Free';
        document.getElementById('amenity-booking-modal').classList.remove('hidden');
    }
</script>
@endsection
@endsection
