@extends('layouts.app')

@section('title', 'Society Members Directory')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="glass-panel rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center space-x-2">
                <i data-feather="users" class="w-6 h-6 text-blue-400"></i>
                <span>Society Members Directory</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Connect with Greenfield Heights residents, flat owners, tenants, and society committee members.</p>
        </div>

        <button onclick="document.getElementById('add-member-modal').classList.remove('hidden')" class="btn-gradient px-4 py-2.5 rounded-xl text-xs font-bold text-white flex items-center space-x-2 shadow-lg">
            <i data-feather="user-plus" class="w-4 h-4"></i>
            <span>Add Society Member</span>
        </button>
    </div>

    <!-- Search and Filter Bar -->
    <div class="glass-card rounded-2xl p-4 border border-slate-800">
        <form action="{{ route('members.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <i data-feather="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, flat number, phone, email..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <select name="wing_id" class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white outline-none">
                    <option value="">-- Filter by Wing --</option>
                    @foreach($wings as $w)
                        <option value="{{ $w->id }}" {{ $wingId == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1">
                    <i data-feather="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
                @if($search || $wingId || $role)
                    <a href="{{ route('members.index') }}" class="px-3 py-2.5 bg-slate-800 text-slate-400 hover:text-white rounded-xl text-xs font-semibold">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Members Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($members as $member)
            <div class="glass-card rounded-2xl p-5 border border-slate-800 hover:border-slate-700 transition flex flex-col justify-between space-y-4">
                <div>
                    <!-- Top Role & Wing Badge -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                            {{ $member->role === 'admin' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : ($member->role === 'guard' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30') }}">
                            {{ $member->role === 'admin' ? '👑 Secretary / Admin' : ($member->role === 'guard' ? '🛡️ Gate Security' : '🏠 Resident') }}
                        </span>

                        @if($member->flat)
                            <span class="px-2.5 py-0.5 rounded text-[11px] font-extrabold bg-blue-900/50 text-blue-300 border border-blue-700/50">
                                Flat {{ $member->flat->flat_number }}
                            </span>
                        @endif
                    </div>

                    <!-- Member Info -->
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-lg shrink-0">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white leading-tight">{{ $member->name }}</h3>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $member->flat->wing->name ?? 'Greenfield Heights' }}</p>
                        </div>
                    </div>

                    <!-- Contact Detail Lines -->
                    <div class="mt-4 pt-3 border-t border-slate-800 text-xs space-y-2 text-slate-300">
                        <div class="flex items-center space-x-2">
                            <i data-feather="phone" class="w-3.5 h-3.5 text-slate-500"></i>
                            <a href="tel:{{ $member->phone }}" class="hover:text-blue-400 transition">{{ $member->phone ?: 'Not provided' }}</a>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i data-feather="mail" class="w-3.5 h-3.5 text-slate-500"></i>
                            <a href="mailto:{{ $member->email }}" class="hover:text-blue-400 transition truncate">{{ $member->email }}</a>
                        </div>
                        @if($member->flat)
                            <div class="flex items-center space-x-2">
                                <i data-feather="home" class="w-3.5 h-3.5 text-slate-500"></i>
                                <span class="capitalize text-slate-400">
                                    {{ $member->flat->flat_type }} • {{ str_replace('_', ' ', $member->flat->occupancy_status) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="flex items-center justify-between pt-3 border-t border-slate-800/80 text-xs">
                    <div class="flex items-center space-x-2">
                        <a href="tel:{{ $member->phone }}" class="px-3 py-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-semibold transition flex items-center space-x-1">
                            <i data-feather="phone-call" class="w-3 h-3"></i>
                            <span>Call</span>
                        </a>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <div class="flex items-center space-x-2">
                            <button onclick="openEditMemberModal({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ $member->email }}', '{{ $member->phone }}', '{{ $member->role }}', '{{ $member->flat_id }}')" class="text-blue-400 hover:underline font-bold">
                                Edit
                            </button>

                            @if($member->id !== Auth::id())
                                <form action="{{ route('members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Remove {{ addslashes($member->name) }} from society directory?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:underline font-bold">Remove</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-3 glass-card rounded-2xl p-8 text-center text-slate-500">
                No society members found matching your search.
            </div>
        @endforelse
    </div>

    <div>
        {{ $members->links() }}
    </div>

</div>

<!-- Add New Society Member Modal -->
<div id="add-member-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-lg text-white">Add New Society Member</h3>
            <button onclick="document.getElementById('add-member-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('members.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-semibold mb-1">Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Ramesh Kumar" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Email Address</label>
                    <input type="email" name="email" required placeholder="member@society.com" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Phone Number</label>
                    <input type="text" name="phone" required placeholder="+91 98765 43210" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Society Role</label>
                    <select name="role" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        <option value="resident" selected>Resident / Flat Member</option>
                        <option value="admin">Admin / Management Board</option>
                        <option value="guard">Gate Security Guard</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Flat Assignment</label>
                    <select name="flat_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        <option value="">-- None / General Staff --</option>
                        @foreach($flats as $f)
                            <option value="{{ $f->id }}">Flat {{ $f->flat_number }} ({{ $f->wing->name ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Account Password</label>
                <input type="password" name="password" value="password" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                <span class="text-[10px] text-slate-500 mt-1 block">Default demo password: password</span>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('add-member-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 btn-gradient text-white rounded-xl font-bold">Save Member</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Member Modal -->
@if(Auth::user()->isAdmin())
<div id="edit-member-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-lg text-white">Edit Member Details</h3>
            <button onclick="document.getElementById('edit-member-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="edit-member-form" action="" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-semibold mb-1">Full Name</label>
                <input type="text" id="edit-name" name="name" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Email</label>
                    <input type="email" id="edit-email" name="email" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Phone</label>
                    <input type="text" id="edit-phone" name="phone" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Role</label>
                    <select id="edit-role" name="role" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        <option value="resident">Resident</option>
                        <option value="admin">Admin</option>
                        <option value="guard">Guard</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Flat Assignment</label>
                    <select id="edit-flat-id" name="flat_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        <option value="">-- None --</option>
                        @foreach($flats as $f)
                            <option value="{{ $f->id }}">Flat {{ $f->flat_number }} ({{ $f->wing->name ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('edit-member-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 btn-gradient text-white rounded-xl font-bold">Update Member</button>
            </div>
        </form>
    </div>
</div>
@endif

@section('scripts')
<script>
    function openEditMemberModal(id, name, email, phone, role, flatId) {
        const form = document.getElementById('edit-member-form');
        form.action = `/members/${id}/update`;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-phone').value = phone;
        document.getElementById('edit-role').value = role;
        document.getElementById('edit-flat-id').value = flatId;
        document.getElementById('edit-member-modal').classList.remove('hidden');
    }
</script>
@endsection
@endsection
