<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Notice::with('author')->orderBy('is_pinned', 'desc')->latest();

        if ($category && in_array($category, ['urgent', 'general', 'event', 'maintenance'])) {
            $query->where('category', $category);
        }

        $notices = $query->paginate(10);
        return view('notices.index', compact('notices', 'category'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:general,event,urgent,maintenance',
            'target_audience' => 'required|in:all,owners,tenants',
        ]);

        Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'target_audience' => $request->target_audience,
            'is_pinned' => $request->boolean('is_pinned'),
            'posted_by' => Auth::id(),
        ]);

        return redirect()->route('notices.index')->with('success', 'Notice published successfully on digital board.');
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $notice = Notice::findOrFail($id);
        $notice->delete();

        return redirect()->route('notices.index')->with('success', 'Notice deleted.');
    }
}
