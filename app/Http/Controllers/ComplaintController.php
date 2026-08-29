<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');

        $query = Complaint::with(['user', 'flat.wing'])->latest();

        if ($user->isResident()) {
            $query->where('user_id', $user->id);
        }

        if ($status && in_array($status, ['pending', 'in_progress', 'resolved', 'closed'])) {
            $query->where('status', $status);
        }

        $complaints = $query->paginate(10);
        return view('complaints.index', compact('complaints', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:plumbing,electrical,security,cleanliness,noise,parking,general',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $user = Auth::user();

        Complaint::create([
            'user_id' => $user->id,
            'flat_id' => $user->flat_id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        return redirect()->route('complaints.index')->with('success', 'Helpdesk ticket submitted successfully. Support team will be notified.');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $complaint = Complaint::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'assigned_to' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
        ]);

        $complaint->update($request->only(['status', 'assigned_to', 'resolution_notes']));

        return redirect()->route('complaints.index')->with('success', 'Ticket updated successfully.');
    }
}
