<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\MaintenanceBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->query('status');

        $query = MaintenanceBill::with(['flat.wing', 'flat.residents']);

        if ($user->isResident() && $user->flat_id) {
            $query->where('flat_id', $user->flat_id);
        }

        if ($statusFilter && in_array($statusFilter, ['pending', 'paid', 'overdue'])) {
            $query->where('status', $statusFilter);
        }

        $bills = $query->latest()->paginate(15);
        $flats = Flat::with('wing')->get();

        $stats = [
            'total_bills' => MaintenanceBill::count(),
            'total_paid' => MaintenanceBill::where('status', 'paid')->sum('total_amount'),
            'total_pending' => MaintenanceBill::whereIn('status', ['pending', 'overdue'])->sum('total_amount'),
        ];

        return view('maintenance.index', compact('bills', 'flats', 'stats', 'statusFilter'));
    }

    public function generate(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'month_year' => 'required|string',
            'due_date' => 'required|date',
            'maintenance_amount' => 'required|numeric|min:0',
            'utility_amount' => 'nullable|numeric|min:0',
            'flat_id' => 'nullable|exists:flats,id',
        ]);

        $flats = $request->flat_id ? Flat::where('id', $request->flat_id)->get() : Flat::all();
        $count = 0;

        foreach ($flats as $flat) {
            $billNumber = 'INV-' . strtoupper(date('M', strtotime($request->month_year))) . '-' . date('Y') . '-' . str_replace('-', '', $flat->flat_number);
            
            // Avoid duplicate bill number
            if (MaintenanceBill::where('bill_number', $billNumber)->exists()) {
                $billNumber .= '-' . rand(10, 99);
            }

            $mAmount = (float)$request->maintenance_amount;
            $uAmount = (float)($request->utility_amount ?? 0);

            MaintenanceBill::create([
                'flat_id' => $flat->id,
                'bill_number' => $billNumber,
                'month_year' => $request->month_year,
                'maintenance_amount' => $mAmount,
                'utility_amount' => $uAmount,
                'penalty_amount' => 0,
                'total_amount' => $mAmount + $uAmount,
                'due_date' => $request->due_date,
                'status' => 'pending',
            ]);
            $count++;
        }

        return redirect()->route('maintenance.index')->with('success', "Generated maintenance bills for {$count} flat(s).");
    }

    public function pay(Request $request, $id)
    {
        $bill = MaintenanceBill::findOrFail($id);

        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $bill->update([
            'status' => 'paid',
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'transaction_id' => strtoupper($request->payment_method) . '-' . rand(10000000, 99999999),
            'notes' => 'Paid online via society app',
        ]);

        return redirect()->back()->with('success', 'Maintenance bill paid successfully! Payment receipt generated.');
    }

    public function receipt($id)
    {
        $bill = MaintenanceBill::with(['flat.wing', 'flat.residents'])->findOrFail($id);
        return view('maintenance.receipt', compact('bill'));
    }
}
