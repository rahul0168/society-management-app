<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $codeSearch = $request->query('code');

        $query = Visitor::with(['flat.wing', 'creator'])->latest();

        if ($user->isResident() && $user->flat_id) {
            $query->where('flat_id', $user->flat_id);
        }

        if ($codeSearch) {
            $query->where('entry_code', trim($codeSearch));
        }

        $visitors = $query->paginate(15);
        $flats = Flat::with('wing')->get();

        return view('visitors.index', compact('visitors', 'flats', 'codeSearch'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:20',
            'purpose' => 'required|string|max:255',
            'vehicle_number' => 'nullable|string|max:50',
            'flat_id' => 'required_if:role,guard|nullable|exists:flats,id',
        ]);

        $flatId = $user->flat_id ?? $request->flat_id;

        if (!$flatId) {
            return redirect()->back()->withErrors(['flat_id' => 'Flat selection is required.']);
        }

        // Generate unique 6-digit passcode
        $entryCode = str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        Visitor::create([
            'flat_id' => $flatId,
            'created_by' => $user->id,
            'visitor_name' => $request->visitor_name,
            'visitor_phone' => $request->visitor_phone,
            'purpose' => $request->purpose,
            'vehicle_number' => $request->vehicle_number,
            'entry_code' => $entryCode,
            'expected_at' => now(),
            'status' => 'approved',
        ]);

        return redirect()->route('visitors.index')->with('success', "Visitor pass generated! Entry Passcode: {$entryCode}");
    }

    public function checkIn($id)
    {
        if (!Auth::user()->isGuard() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        return redirect()->back()->with('success', "Visitor {$visitor->visitor_name} checked in at gate.");
    }

    public function checkOut($id)
    {
        if (!Auth::user()->isGuard() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'status' => 'checked_out',
            'checked_out_at' => now(),
        ]);

        return redirect()->back()->with('success', "Visitor {$visitor->visitor_name} checked out.");
    }
}
