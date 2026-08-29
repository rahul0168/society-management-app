<?php

namespace App\Http\Controllers;

use App\Models\AmenityBooking;
use App\Models\Complaint;
use App\Models\Flat;
use App\Models\MaintenanceBill;
use App\Models\Notice;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $totalFlats = Flat::count();
            $totalResidents = User::where('role', 'resident')->count();
            $totalCollected = MaintenanceBill::where('status', 'paid')->sum('total_amount');
            $pendingBillsCount = MaintenanceBill::whereIn('status', ['pending', 'overdue'])->count();
            $activeComplaintsCount = Complaint::whereIn('status', ['pending', 'in_progress'])->count();
            $todayVisitorsCount = Visitor::whereDate('created_at', now())->count();

            $recentComplaints = Complaint::with(['user', 'flat'])->latest()->take(5)->get();
            $recentBills = MaintenanceBill::with('flat')->latest()->take(5)->get();
            $recentNotices = Notice::with('author')->latest()->take(3)->get();
            $recentVisitors = Visitor::with('flat')->latest()->take(5)->get();

            return view('dashboard.admin', compact(
                'totalFlats',
                'totalResidents',
                'totalCollected',
                'pendingBillsCount',
                'activeComplaintsCount',
                'todayVisitorsCount',
                'recentComplaints',
                'recentBills',
                'recentNotices',
                'recentVisitors'
            ));
        }

        if ($user->isGuard()) {
            $todayVisitors = Visitor::with(['flat', 'creator'])->whereDate('created_at', now())->latest()->get();
            $checkedInVisitors = Visitor::with('flat')->where('status', 'checked_in')->get();
            return view('dashboard.guard', compact('todayVisitors', 'checkedInVisitors'));
        }

        // Resident Dashboard
        $flat = $user->flat;
        $pendingBill = $flat ? MaintenanceBill::where('flat_id', $flat->id)->whereIn('status', ['pending', 'overdue'])->first() : null;
        $myBills = $flat ? MaintenanceBill::where('flat_id', $flat->id)->latest()->take(5)->get() : collect();
        $notices = Notice::with('author')->orderBy('is_pinned', 'desc')->latest()->take(4)->get();
        $myComplaints = Complaint::where('user_id', $user->id)->latest()->take(5)->get();
        $myVisitors = $flat ? Visitor::where('flat_id', $flat->id)->latest()->take(5)->get() : collect();
        $myBookings = AmenityBooking::with('amenity')->where('user_id', $user->id)->latest()->take(3)->get();

        return view('dashboard.resident', compact(
            'flat',
            'pendingBill',
            'myBills',
            'notices',
            'myComplaints',
            'myVisitors',
            'myBookings'
        ));
    }
}
