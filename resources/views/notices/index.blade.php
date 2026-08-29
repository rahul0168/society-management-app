@extends('layouts.app')

@section('title', 'Digital Notice Board')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="glass-panel rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center space-x-2">
                <i data-feather="bell" class="w-6 h-6 text-amber-400"></i>
                <span>Digital Notice Board</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Official announcements, event circulars, and emergency maintenance notices.</p>
        </div>

        @if(Auth::user()->isAdmin())
            <button onclick="document.getElementById('post-notice-modal').classList.remove('hidden')" class="btn-gradient px-4 py-2.5 rounded-xl text-xs font-bold text-white flex items-center space-x-2 shadow-lg">
                <i data-feather="plus" class="w-4 h-4"></i>
                <span>Publish New Notice</span>
            </button>
        @endif
    </div>

    <!-- Category Filters -->
    <div class="flex items-center space-x-2 text-xs">
        <span class="text-slate-400 font-semibold mr-2">Category:</span>
        <a href="{{ route('notices.index') }}" class="px-3 py-1.5 rounded-lg border font-medium {{ !$category ? 'bg-blue-600 text-white border-blue-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">All</a>
        <a href="{{ route('notices.index', ['category' => 'urgent']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $category === 'urgent' ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">Urgent</a>
        <a href="{{ route('notices.index', ['category' => 'general']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $category === 'general' ? 'bg-blue-600 text-white border-blue-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">General</a>
        <a href="{{ route('notices.index', ['category' => 'event']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $category === 'event' ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">Events</a>
        <a href="{{ route('notices.index', ['category' => 'maintenance']) }}" class="px-3 py-1.5 rounded-lg border font-medium {{ $category === 'maintenance' ? 'bg-amber-600 text-white border-amber-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">Maintenance</a>
    </div>

    <!-- Notice Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($notices as $notice)
            <div class="glass-card rounded-2xl p-6 border border-slate-800 flex flex-col justify-between space-y-4 hover:border-slate-700 transition relative">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            @if($notice->is_pinned)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                    📌 PINNED
                                </span>
                            @endif
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                                {{ $notice->category === 'urgent' ? 'bg-rose-500/20 text-rose-300' : ($notice->category === 'event' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-blue-500/20 text-blue-300') }}">
                                {{ $notice->category }}
                            </span>
                        </div>
                        <span class="text-xs text-slate-500">{{ $notice->created_at->format('M d, Y') }}</span>
                    </div>

                    <h3 class="text-base font-extrabold text-white leading-snug">{{ $notice->title }}</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed whitespace-pre-line">{{ $notice->content }}</p>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-800/80 text-[11px] text-slate-400">
                    <span>Posted by <strong class="text-slate-200">{{ $notice->author->name ?? 'Admin' }}</strong></span>

                    @if(Auth::user()->isAdmin())
                        <form action="{{ route('notices.destroy', $notice->id) }}" method="POST" onsubmit="return confirm('Delete this notice?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-300 font-bold">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-2 glass-card rounded-2xl p-8 text-center text-slate-500">
                No notices available in this category.
            </div>
        @endforelse
    </div>

    <div>
        {{ $notices->links() }}
    </div>

</div>

<!-- Admin Publish Notice Modal -->
@if(Auth::user()->isAdmin())
<div id="post-notice-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-lg text-white">Publish New Society Notice</h3>
            <button onclick="document.getElementById('post-notice-modal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('notices.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-semibold mb-1">Notice Title</label>
                <input type="text" name="title" required placeholder="e.g. Lift Maintenance Schedule" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        <option value="general">General</option>
                        <option value="urgent">Urgent</option>
                        <option value="event">Event</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Audience</label>
                    <select name="target_audience" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        <option value="all">All Residents</option>
                        <option value="owners">Owners Only</option>
                        <option value="tenants">Tenants Only</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-semibold mb-1">Notice Content</label>
                <textarea name="content" rows="4" required placeholder="Write details here..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none"></textarea>
            </div>

            <label class="flex items-center space-x-2 text-slate-300 cursor-pointer">
                <input type="checkbox" name="is_pinned" value="1" class="rounded bg-slate-900 border-slate-700 text-blue-600">
                <span>Pin this notice to top of board</span>
            </label>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('post-notice-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 btn-gradient text-white rounded-xl font-bold">Publish Notice</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
