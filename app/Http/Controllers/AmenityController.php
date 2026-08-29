<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\AmenityBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::all();
        $user = Auth::user();
        
        $myBookings = AmenityBooking::with('amenity')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $allBookings = Auth::user()->isAdmin() 
            ? AmenityBooking::with(['amenity', 'user', 'flat'])->latest()->get()
            : collect();

        return view('amenities.index', compact('amenities', 'myBookings', 'allBookings'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'amenity_id' => 'required|exists:amenities,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'purpose' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        if (!$user->flat_id && !$user->isAdmin()) {
            return redirect()->back()->withErrors(['booking' => 'You must be assigned to a flat to book amenities.']);
        }

        $amenity = Amenity::findOrFail($request->amenity_id);

        AmenityBooking::create([
            'amenity_id' => $amenity->id,
            'user_id' => $user->id,
            'flat_id' => $user->flat_id ?? 1,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_fee' => $amenity->fee_per_slot,
            'status' => 'confirmed',
            'purpose' => $request->purpose,
        ]);

        return redirect()->route('amenities.index')->with('success', "Amenity '{$amenity->name}' booked successfully!");
    }

    public function cancel($id)
    {
        $booking = AmenityBooking::findOrFail($id);

        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('amenities.index')->with('success', 'Amenity booking cancelled.');
    }
}
