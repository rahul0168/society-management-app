<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\User;
use App\Models\Wing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $wingId = $request->query('wing_id');
        $role = $request->query('role');

        $query = User::with(['flat.wing'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('flat', function ($fq) use ($search) {
                      $fq->where('flat_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($wingId) {
            $query->whereHas('flat', function ($q) use ($wingId) {
                $q->where('wing_id', $wingId);
            });
        }

        if ($role && in_array($role, ['admin', 'resident', 'guard'])) {
            $query->where('role', $role);
        }

        $members = $query->paginate(12);
        $wings = Wing::all();
        $flats = Flat::with('wing')->get();

        return view('members.index', compact('members', 'wings', 'flats', 'search', 'wingId', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,resident,guard',
            'flat_id' => 'nullable|exists:flats,id',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'flat_id' => $request->flat_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('members.index')->with('success', "Society member '{$request->name}' added successfully!");
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $member = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,resident,guard',
            'flat_id' => 'nullable|exists:flats,id',
        ]);

        $member->update($request->only(['name', 'email', 'phone', 'role', 'flat_id']));

        if ($request->filled('password')) {
            $member->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('members.index')->with('success', "Member '{$member->name}' updated successfully.");
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $member = User::findOrFail($id);
        if ($member->id === Auth::id()) {
            return redirect()->back()->withErrors(['member' => 'You cannot delete your own logged-in admin account.']);
        }

        $memberName = $member->name;
        $member->delete();

        return redirect()->route('members.index')->with('success', "Member '{$memberName}' removed from society directory.");
    }
}
