@extends('layouts.app')

@section('title', 'Helpdesk Complaints & Tickets')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="glass-panel rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center space-x-2">
                <i data-feather="life-buoy" class="w-6 h-6 text-rose-400"></i>
                <span>Society Helpdesk & Complaints</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Submit maintenance tickets, track resolution progress, and contact support staff.</p>
        </div>

        <button onclick="document.getElementById('new-complaint-modal').classList.remove('hidden')" class="btn-gradient px-4 py-2.5 rounded-xl text-xs font-bold text-white flex items-center space-x-2 shadow-lg">
            <i data-feather="plus" class="w-4 h-4"></i>
            <span>Log New Complaint Ticket</span>
        </button>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center space-x-2 text-xs">
        <span class="text-slate-400 font-semibold mr-2">Status:</span>
        <a href="{{ route('complaints.index') }}" class="px-3 py-1.5 rounded-lg border font-medium {{ !$status ? 'bg-blue-600 text-white border-blue-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">All Tickets</a>
        <a href="{{ route('complaints.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $status === 'pending' ? 'bg-amber-600 text-white border-amber-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">Pending</a>
        <a href="{{ route('complaints.index', ['status' => 'in_progress']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $status === 'in_progress' ? 'bg-blue-600 text-white border-blue-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">In Progress</a>
        <a href="{{ route('complaints.index', ['status' => 'resolved']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $status === 'resolved' ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">Resolved</a>
    </div>

    <!-- Complaints Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($complaints as $c)
            <div class="glass-card rounded-2xl p-6 border border-slate-800 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-300">
                            {{ $c->category }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                            {{ $c->priority === 'urgent' || $c->priority === 'high' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-amber-500/20 text-amber-300' }}">
                            {{ $c->priority }}
                        </span>
                    </div>

                    <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize 
                        {{ $c->status === 'resolved' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : ($c->status === 'in_progress' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30') }}">
                        {{ str_replace('_', ' ', $c->status) }}
                    </span>
                </div>

                <div>
                    <h3 class="text-base font-extrabold text-white">{{ $c->title }}</h3>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">{{ $c->description }}</p>
                </div>

                @if($c->assigned_to || $c->resolution_notes)
                    <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800 text-xs space-y-1">
                        @if($c->assigned_to)
                            <div class="text-slate-400">Assigned Technician: <strong class="text-blue-400">{{ $c->assigned_to }}</strong></div>
                        @endif
                        @if($c->resolution_notes)
                            <div class="text-slate-400">Resolution Update: <span class="text-slate-200">{{ $c->resolution_notes }}</span></div>
                        @endif
                    </div>
                @endif

                <div class="flex items-center justify-between pt-3 border-t border-slate-800/80 text-[11px] text-slate-400">
                    <span>By {{ $c->user->name }} (Flat {{ $c->flat->flat_number ?? 'N/A' }})</span>

                    @if(Auth::user()->isAdmin())
                        <button onclick="openUpdateModal({{ $c->id }}, '{{ $c->status }}', '{{ addslashes($c->assigned_to) }}', '{{ addslashes($c->resolution_notes) }}')" class="text-blue-400 hover:underline font-bold">
                            Update Status &rarr;
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-2 glass-card rounded-2xl p-8 text-center text-slate-500">
                No complaint tickets submitted under this status.
            </div>
        @endforelse
    </div>

    <div>
        {{ $complaints->links() }}
    </div>

</div>

<!-- New Complaint Ticket Modal -->
<div id="new-complaint-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-lg text-white">Log New Complaint Ticket</h3>
            <button onclick="document.getElementById('new-complaint-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('complaints.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-semibold mb-1">Issue Title</label>
                <input type="text" name="title" required placeholder="e.g. Water leak in balcony pipe" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        <option value="plumbing">Plumbing</option>
                        <option value="electrical">Electrical</option>
                        <option value="security">Security</option>
                        <option value="cleanliness">Cleanliness</option>
                        <option value="noise">Noise / Disturbance</option>
                        <option value="parking">Parking</option>
                        <option value="general">General</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Priority</label>
                    <select name="priority" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Detailed Description</label>
                <textarea name="description" rows="4" required placeholder="Describe the issue in detail..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('new-complaint-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 btn-gradient text-white rounded-xl font-bold">Submit Ticket</button>
            </div>
        </form>
    </div>
</div>

<!-- Admin Update Complaint Modal -->
@if(Auth::user()->isAdmin())
<div id="update-complaint-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-lg text-white">Update Ticket Resolution</h3>
            <button onclick="document.getElementById('update-complaint-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="update-complaint-form" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-semibold mb-1">Status</label>
                <select id="modal-status" name="status" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Assign Technician / Staff</label>
                <input type="text" id="modal-assigned" name="assigned_to" placeholder="e.g. Ramesh Plumber" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Resolution Notes</label>
                <textarea id="modal-notes" name="resolution_notes" rows="3" placeholder="Notes for resident..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('update-complaint-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 btn-gradient text-white rounded-xl font-bold">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endif

@section('scripts')
<script>
    function openUpdateModal(id, status, assigned, notes) {
        const form = document.getElementById('update-complaint-form');
        form.action = `/complaints/${id}/update`;
        document.getElementById('modal-status').value = status;
        document.getElementById('modal-assigned').value = assigned;
        document.getElementById('modal-notes').value = notes;
        document.getElementById('update-complaint-modal').classList.remove('hidden');
    }
</script>
@endsection
@endsection
