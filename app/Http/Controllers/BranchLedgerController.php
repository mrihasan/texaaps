<?php

namespace App\Http\Controllers;

use App\Models\BranchLedger;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class BranchLedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
//        abort_if(Gate::denies('payment-access'), redirect('error'));
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(30)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        }
        $payments = BranchLedger::with('branch')->with('entryby')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->orderBy('transaction_date', 'desc')->get();
//        dd($payments);
        $header_title = 'Ledger From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return view('accounting.branch_ledger_index', compact('payments', 'header_title'));
    }

}
