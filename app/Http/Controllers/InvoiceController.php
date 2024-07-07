<?php

namespace App\Http\Controllers;

use App\DataTables\PurchaseDataTable;
use App\DataTables\SalesDataTable;
use App\Models\BranchLedger;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Ledger;
use App\Models\User;
use App\Models\WalkingCustomer;
use Illuminate\Http\Request;
use carbon\carbon;
use Auth;
use DB;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\Gate;
use DataTables;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDataTable(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Invoice::query()
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('transaction_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->where('transaction_date', '<=', $endDate);
            });

        return DataTables::of($query)->make(true);
    }

    public function showDataTable()
    {
        return view('supply.datatable');
    }

    public function salesTransaction(Request $request)
    {
        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        }
//        dd($start_date.'---'.$end_date);
        if (session()->get('branch') != 'all') {
            $transactionSales = Invoice::
            with('user')
                ->with('branch')
                ->with('entryBy')
                ->with('updatedBy')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Sales')
                ->whereBetween('transaction_date', [$start_date, $end_date])
                ->orderBy('transaction_date', 'desc')
                ->orderBy('transaction_code', 'desc')
                ->get();
        } else {
            $transactionSales = Invoice::
            with('user')
                ->with('branch')
                ->with('entryBy')
                ->with('updatedBy')
                ->where('transaction_type', 'Sales')
                ->whereBetween('transaction_date', [$start_date, $end_date])
                ->orderBy('transaction_date', 'desc')
                ->orderBy('transaction_code', 'desc')
                ->get();
        }
        $title_date_range = 'From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return view('supply.sales_transactions', compact('transactionSales', 'title_date_range'));
    }

    public function salesTransaction_dt(SalesDataTable $dataTable, Request $request)
    {
//        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
//        if ($request->start_date == null) {
//            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
//            $end_date = date('Y-m-d') . ' 23:59:59';
//        } else {
//            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
//            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
//        }

//        return $dataTable->with(['start_date' => $request->start_date, 'end_date' => $request->end_date])
//            ->render('supply.sales_transactions');
//        $title_date_range = 'From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return $dataTable->render('supply.salesTransactions');
//        return $dataTable->with(['start_date' => $start_date, 'end_date' => $end_date])
//            ->render('supply.salesTransactions');
    }


    public function purchaseTransaction(Request $request)
    {
        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        }
        if (session()->get('branch') != 'all') {
            $transactionPurchase = Invoice::
            with('user')
                ->with('branch')
                ->with('entryBy')
                ->with('updatedBy')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Purchase')
                ->whereBetween('transaction_date', [$start_date, $end_date])
                ->orderBy('transaction_date', 'desc')
                ->orderBy('transaction_code', 'desc')
                ->get();
        } else {
            $transactionPurchase = Invoice::
            with('user')
                ->with('branch')
                ->with('entryBy')
                ->with('updatedBy')
                ->where('transaction_type', 'Purchase')
                ->whereBetween('transaction_date', [$start_date, $end_date])
                ->orderBy('transaction_date', 'desc')
                ->orderBy('transaction_code', 'desc')
                ->get();
        }
        $title_date_range = 'From ' . Carbon::parse($start_date)->format('d-M-Y') . ' To ' . Carbon::parse($end_date)->format('d-M-Y');
        return view('supply.purchase_transactions', compact('transactionPurchase', 'title_date_range'));
    }

    public function purchaseTransaction_dt(PurchaseDataTable $dataTable, Request $request)
    {
        if ($request->start_date == null) {
            $start_date = Carbon::now()->subDays(90)->format('Y-m-d') . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        }
//        dd($dataTable);
        $title = '';
        return $dataTable->with(['start_date' => $start_date, 'end_date' => $end_date])
            ->render('supply.purchaseTransaction', compact('title'));
    }

    public function returnTransaction()
    {
        $transactionReturn = Invoice::
        with('user.profile.company_name')
            ->with('entryBy')
            ->with('updatedBy')
            ->where('transaction_type', 'Return')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('transaction_code', 'desc')
            ->get();
        return view('inventory_transaction_account.return_transaction', compact('transactionReturn'));
    }

    public function putbackTransaction()
    {
        $transactionPutback = Invoice::
        with('user.profile.company_name')
            ->with('entryBy')
            ->with('updatedBy')
            ->where('transaction_type', 'Put Back')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('transaction_code', 'desc')
            ->get();

        return view('inventory_transaction_account.putback_transaction', compact('transactionPutback'));
    }

    public function show($id)
    {
//        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        $invoice = Invoice::where('id', $id)->first();
        if ((Auth::user()->user_type_id == 3 || Auth::user()->user_type_id == 4) && ($invoice->user_id != Auth::user()->id)) {
            \Session::flash('flash_error', 'You can not view this');
            return redirect('error');
        }
//        dd($invoice);
        if ($invoice->user_id == 6) {
            $related_customer = WalkingCustomer::where('type', 'Invoice')->where('invoice_id', $invoice->id)->first();
        } else
            $related_customer = null;
        $transactionDetails = DB::table('invoice_details')
            ->select('invoice_details.id', 'invoice_details.product_id','invoice_details.qty', 'invoice_details.unit_name', 'invoice_details.usell_price',
                'invoice_details.ubuy_price', 'invoice_details.status', 'invoice_details.line_total', 'brands.title as brand_title',
                'products.title as product_title', 'invoice_details.product_id', 'invoice_details.model', 'invoice_details.product_details'
//            ,'product_types.title as product_type_title'
                // 'pq_details.product_details'
            )
            ->join('products', 'products.id', '=', 'invoice_details.product_id')
            ->join('brands', 'brands.id', '=', 'invoice_details.brand_id')
            // ->join('pq_details', 'product.id', '=', 'invoice_details.product_id')
//            ->join('product_types', 'product_types.id', '=', 'products.product_type_id')
            ->where('invoice_details.invoice_id', $invoice->id)
            // ->groupBy(DB::raw('product_id'))
            ->get();
    //    dd($transactionDetails);
        $mindate_ledger1 = DB::table('ledgers')->where('user_id', $invoice->user_id)->MIN('transaction_date');
        $mindate_ledger = date('Y-m-d', strtotime($mindate_ledger1));
        $mindate_ledger_datetime = $mindate_ledger . ' 00:00:00';
        $settings = DB::table('settings')->first();
        $related_payment = Ledger::with('user')->with('entryby')
            ->where('invoice_id', $invoice->id)
            ->orderBy('transaction_date', 'desc')->get();

        if (date('Y-m-d', strtotime($invoice->transaction_date)) == $mindate_ledger)
            $before1day_invoice = date('Y-m-d', strtotime($invoice->transaction_date));
        else {
            $before1day_invoice1 = new DateTime($invoice->transaction_date);
            $before1day_invoice1->sub(new DateInterval('P1D'));
            $before1day_invoice = $before1day_invoice1->format('Y-m-d');
        }
        $before1day_invoice_datetime = $before1day_invoice . ' 23:59:59';
        if ($invoice->transaction_type == 'Purchase') {
            $ledger = $this->ledger($invoice->user_id, $mindate_ledger_datetime, $before1day_invoice_datetime, $invoice->transaction_date, 'Purchase', 4);
            return view('supply.show_purchase', compact('invoice', 'transactionDetails', 'mindate_ledger', 'before1day_invoice', 'ledger', 'related_payment'));
        } else if ($invoice->transaction_type == 'Sales') {
            if ($invoice->user_id == 6)
                return view('supply.show_sales_walking', compact('invoice', 'settings', 'transactionDetails', 'related_customer'));
            else {
                $ledger = $this->ledger($invoice->user_id, $mindate_ledger_datetime, $before1day_invoice_datetime, $invoice->transaction_date, 'Sales', 3);
                return view('supply.show_sales', compact('invoice', 'transactionDetails', 'settings',
                    'mindate_ledger', 'before1day_invoice', 'ledger', 'related_customer', 'related_payment'));
            }
        } else if ($invoice->transaction_type == 'Order') {
            $ledger = $this->ledger($invoice->user_id, $mindate_ledger_datetime, $before1day_invoice_datetime, $invoice->transaction_date, 'Sales', 3);
//dd($ledger);
            return view('inventory_transaction_account.show_order', compact('inventory_transaction_account', 'transaction_history', 'results', 'pad', 'transactionDetails',
                'mindate_ledger', 'before1day_invoice', 'ledger'));
        } else if ($invoice->transaction_type == 'Return') {
            return view('inventory_transaction_account.show_return', compact('inventory_transaction_account',
                'transaction_history', 'transactionDetails'));
        } else if ($invoice->transaction_type == 'Put Back') {
            return view('inventory_transaction_account.show_putback', compact('inventory_transaction_account',
                'transaction_history', 'transactionDetails'));
        }
    }

    private function ledger($user_id, $mindate_ledger, $before1day_invoice, $inventory_transaction_account_transaction_date, $inventory_transaction_account_transaction_type, $ledger_transaction_type)
    {
//        dd($inventory_transaction_account_transaction_type);
        $ledger['consumption_before1day'] = 0;
        $ledger['return_before1day'] = 0;
        $ledger['putback_before1day'] = 0;
        $ledger['deposite_before1day'] = 0;
        $ledger['balance_before1day'] = 0;
        $ledger['consumption_today'] = 0;
        $ledger['putback_today'] = 0;
        $ledger['return_today'] = 0;
        $ledger['deposite_today'] = 0;
        $ledger['balance_today'] = 0;
        $ledger['lastPayment'] = 0;
        if (date('Y-m-d', strtotime($inventory_transaction_account_transaction_date)) == date('Y-m-d', strtotime($mindate_ledger))) {
            $ledger['consumption_before1day'] = 0;
            $ledger['return_before1day'] = 0;
            $ledger['putback_before1day'] = 0;
            $ledger['deposite_before1day'] = 0;
            $ledger['balance_before1day'] = 0;
        } else {
//            dd('not same');
            $ledger['consumption_before1day'] = DB::table('invoices')
                ->where('transaction_type', $inventory_transaction_account_transaction_type)
                ->where('user_id', $user_id)
                ->whereBetween('transaction_date', [$mindate_ledger, $before1day_invoice])
                ->sum('invoice_total');
            $ledger['return_before1day'] = DB::table('invoices')
                ->where('transaction_type', 'Return')
                ->where('user_id', $user_id)
                ->whereBetween('transaction_date', [$mindate_ledger, $before1day_invoice])
                ->sum('invoice_total');
            $ledger['putback_before1day'] = DB::table('invoices')
                ->where('transaction_type', 'Put Back')
                ->where('user_id', $user_id)
                ->whereBetween('transaction_date', [$mindate_ledger, $before1day_invoice])
                ->sum('invoice_total');
            $ledger['deposite_before1day'] = DB::table('ledgers')
                ->where('transaction_type_id', $ledger_transaction_type)
                ->where('user_id', $user_id)
                ->whereBetween('transaction_date', [$mindate_ledger, $before1day_invoice])
                ->sum('amount');
            $ledger['balance_before1day'] = $ledger['deposite_before1day'] + $ledger['return_before1day'] + $ledger['putback_before1day'] - $ledger['consumption_before1day'];
//            dd($ledger['deposite_before1day']);
        }
        $ledger['consumption_today'] = DB::table('invoices')
            ->where('transaction_type', $inventory_transaction_account_transaction_type)
            ->where('user_id', $user_id)
            ->whereDate('transaction_date', date('Y-m-d', strtotime($inventory_transaction_account_transaction_date)))
            ->sum('invoice_total');
        $ledger['return_today'] = DB::table('invoices')
            ->where('transaction_type', 'Return')
            ->where('user_id', $user_id)
            ->whereDate('transaction_date', date('Y-m-d', strtotime($inventory_transaction_account_transaction_date)))
            ->sum('invoice_total');
        $ledger['putback_today'] = DB::table('invoices')
            ->where('transaction_type', 'Put Back')
            ->where('user_id', $user_id)
            ->whereDate('transaction_date', date('Y-m-d', strtotime($inventory_transaction_account_transaction_date)))
            ->sum('invoice_total');
        $ledger['deposite_today'] = DB::table('ledgers')
            ->where('transaction_type_id', $ledger_transaction_type)
            ->where('user_id', $user_id)
            ->whereDate('transaction_date', date('Y-m-d', strtotime($inventory_transaction_account_transaction_date)))
            ->sum('amount');
        $ledger['balance_today'] = $ledger['deposite_today'] + $ledger['return_today'] + $ledger['putback_today'] - $ledger['consumption_today'];
        $ledger['lastPayment'] = Ledger::with('transaction_type')
            ->where('transaction_type_id', $ledger_transaction_type)
            ->where('user_id', $user_id)
            ->latest('transaction_date')->first();
//        dd($ledger);

        return $ledger;

    }

    public function edit(Invoice $invoice)
    {
        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        $user = User::where('id', Auth::user()->id)->first();
        if (
        ($user->user_type_id == 1 || $user->user_type_id == 2)
        ) {
            $inventory = InvoiceDetail::where('invoice_id', $invoice->id)->get();
            $supplier = supplier_list();
            $customers = customer_list();
            $branch = branch_list();
            $brands = brand_list();
            if ($invoice->transaction_type == 'Purchase')
                return view('supply.purchaseEdit', compact('invoice', 'inventory', 'supplier', 'branch', 'brands'));
            else if ($invoice->transaction_type == 'Sales') {
                if ($invoice->user_id == 6) {
                    $related_customer = WalkingCustomer::where('type', 'Invoice')->where('invoice_id', $invoice->id)->first();
                    return view('supply.salesEditWalking', compact('invoice', 'inventory', 'branch', 'customers', 'supplier', 'related_customer', 'brands'));
                } else
                    return view('supply.salesEdit', compact('invoice', 'inventory', 'customers', 'supplier', 'branch', 'brands'));
            } elseif ($invoice->transaction_type == 'Return')
                return view('supply.edit_return', compact('inventory_transaction_account', 'invoice', 'inventory', 'customer', 'supplier'));
            elseif ($invoice->transaction_type == 'Put Back')
                return view('supply.edit_putback', compact('inventory_transaction_account', 'invoice', 'inventory', 'customer', 'supplier'));
            else return view('errors.503');
        } else
            return view('errors.403');
    }

    public function update(Request $request, Invoice $invoice)
    {
        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        if ($invoice->transaction_type == 'Purchase') { //Purchase
            $this->validate($request, [
                'supplier_id' => 'required',
                'total_amount' => 'required',
                'less_amount' => 'required',
                'invoice_total' => 'required',
            ]);

            $ProductID_a = [];
            $BrandID_a = [];
            $Model_a = [];
            $unitBuyPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
            $mrpTotal_a = [];

            foreach ($request['productId'] as $ProductID_) {
                $ProductID_a[] = $ProductID_;
            }
            $ProductID_e = $ProductID_a;
            foreach ($request['brandId'] as $BrandID_) {
                $BrandID_a[] = $BrandID_;
            }
            $BrandID_e = $BrandID_a;
            foreach ($request['model'] as $Model_) {
                $Model_a[] = $Model_;
            }
            $Model_e = $Model_a;
            foreach ($request['unitBuyPrice'] as $unitBuyPrice_) {
                $unitBuyPrice_a[] = $unitBuyPrice_;
            }
            $unitBuyPrice_e = $unitBuyPrice_a;

            foreach ($request['unit_name'] as $unit_name_) {
                $unit_name_a[] = $unit_name_;
            }
            $unit_name_e = $unit_name_a;

            foreach ($request['quantity'] as $Qty_) {
                $Qty_a[] = $Qty_;
            }
            $Qty_e = $Qty_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;

            $del_inventory_transaction_account = DB::table('invoice_details')
                ->where('invoice_id', $invoice->id)->delete();

            $invoice->transaction_date = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $invoice->reference = $request->reference;
            $invoice->user_id = $request->supplier_id;
            $invoice->branch_id = $request->branch;
            $invoice->vat = $request->tax_amount;
            $invoice->vat_per = $request->vat_per;
            $invoice->discount = $request->discount_amount;
            $invoice->disc_per = $request->disc_per;
            $invoice->less_amount = $request->less_amount;
            $invoice->product_total = $request->product_total;
            $invoice->total_amount = $request->total_amount;
            $invoice->invoice_total = $request->invoice_total;
            $invoice->notes = $request->notes;
//            $invoice->entry_by = Auth::id();
            $invoice->updated_by = Auth::id();
            $invoice->save();

            $count_ids = count($ProductID_e);
            if (count($unitBuyPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {
                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new InvoiceDetail();
                $inventory_transaction->invoice_id = $invoice->id;
                $inventory_transaction->branch_id = $request->branch;
                $inventory_transaction->product_id = $ProductID_e[$i];
                $inventory_transaction->brand_id = $BrandID_e[$i];
                $inventory_transaction->model = $Model_e[$i];
                $inventory_transaction->ubuy_price = $unitBuyPrice_e[$i];
                $inventory_transaction->qty = $Qty_e[$i];
                $inventory_transaction->unit_name = $unit_name_e[$i];
                $inventory_transaction->line_total = $mrpTotal_e[$i];
                $inventory_transaction->transaction_type = $invoice->transaction_type;
                $inventory_transaction->save();
            }
            $last_insert_id = $invoice->id;

            \Session::flash('flash_message', 'Successfully Updated');
            return redirect('invoice/' . $last_insert_id);
        } elseif ($request->transaction_type == 'Sales' && $request->customer_id == 6) { //Sales Walking customer
            $this->validate($request, [
                'customer_id' => 'required',
                'total_amount' => 'required',
                'less_amount' => 'required',
                'invoice_total' => 'required',
            ]);
            $ProductID_a = [];
            $BrandID_a = [];
            $Model_a = [];
            $unitSellPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
            $mrpTotal_a = [];

            foreach ($request['productId'] as $ProductID_) {
                $ProductID_a[] = $ProductID_;
            }
            $ProductID_e = $ProductID_a;
            foreach ($request['unitSellPrice'] as $unitSellPrice_) {
                $unitSellPrice_a[] = $unitSellPrice_;
            }
            foreach ($request['brandId'] as $BrandID_) {
                $BrandID_a[] = $BrandID_;
            }
            $BrandID_e = $BrandID_a;
            foreach ($request['model'] as $Model_) {
                $Model_a[] = $Model_;
            }
            $Model_e = $Model_a;
            $unitSellPrice_e = $unitSellPrice_a;
            foreach ($request['quantity'] as $Qty_) {
                $Qty_a[] = $Qty_;
            }
            $Qty_e = $Qty_a;
            foreach ($request['unit_name'] as $unit_name_) {
                $unit_name_a[] = $unit_name_;
            }
            $unit_name_e = $unit_name_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;

            $related_customer = WalkingCustomer::where('type', 'Invoice')->where('invoice_id', $invoice->id)->first(); //invoice_id, ledger_id
            $related_ledger = Ledger::where('id', $related_customer->ledger_id)->first();

            $del_walking_customer = DB::table('walking_customers')
                ->where('invoice_id', $invoice->id)->delete();
            $del_invoice_details = DB::table('invoice_details')
                ->where('invoice_id', $invoice->id)->delete();

            $invoice->reference = $request->reference;
            $invoice->user_id = $request->customer_id;
            $invoice->branch_id = $request->branch;
            $invoice->vat = $request->tax_amount;
            $invoice->discount = $request->discount_amount;
            $invoice->vat_per = $request->vat_per;
            $invoice->disc_per = $request->disc_per;
            $invoice->less_amount = $request->less_amount;
            $invoice->product_total = $request->product_total;
            $invoice->total_amount = $request->total_amount;
            $invoice->invoice_total = $request->invoice_total;
            $invoice->notes = $request->notes;
            $invoice->updated_by = Auth::id();
            $invoice->save();

            $count_ids = count($ProductID_e);
            if (count($unitSellPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {
                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new InvoiceDetail();
                $inventory_transaction->invoice_id = $invoice->id;
                $inventory_transaction->branch_id = $request->branch;
                $inventory_transaction->product_id = $ProductID_e[$i];
                $inventory_transaction->brand_id = $BrandID_e[$i];
                $inventory_transaction->model = $Model_e[$i];
                $inventory_transaction->usell_price = $unitSellPrice_e[$i];
                $inventory_transaction->qty = $Qty_e[$i];
                $inventory_transaction->unit_name = $unit_name_e[$i];
                $inventory_transaction->line_total = $mrpTotal_e[$i];
                $inventory_transaction->transaction_type = $request->transaction_type;
                $inventory_transaction->save();
            }

            $customer = new WalkingCustomer();
            $customer->type = 'Invoice';
            $customer->invoice_id = $invoice->id;
            $customer->ledger_id = null;
            $customer->name = $request->name;
            $customer->mobile = $request->mobile;
            $customer->address = $request->address;
            $customer->save();

            $last_insert_id = $invoice->id;

            \Session::flash('flash_message', 'Successfully Updated');
            return redirect('invoice/' . $last_insert_id);
        } elseif ($request->transaction_type == 'Sales') { //Sales Walking customer
//            dd($request);
            $this->validate($request, [
                'customer_id' => 'required',
                'total_amount' => 'required',
                'less_amount' => 'required',
                'invoice_total' => 'required',
            ]);
            $ProductID_a = [];
            $BrandID_a = [];
            $Model_a = [];
            $Details_a = [];
            $unitSellPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
            $mrpTotal_a = [];

            foreach ($request['productId'] as $ProductID_) {
                $ProductID_a[] = $ProductID_;
            }
            $ProductID_e = $ProductID_a;
            foreach ($request['unitSellPrice'] as $unitSellPrice_) {
                $unitSellPrice_a[] = $unitSellPrice_;
            }
            foreach ($request['brandId'] as $BrandID_) {
                $BrandID_a[] = $BrandID_;
            }
            $BrandID_e = $BrandID_a;
            foreach ($request['model'] as $Model_) {
                $Model_a[] = $Model_;
            }
            $Model_e = $Model_a;
            foreach ($request['product_details'] as $Details_) {
                $Details_a[] = $Details_;
            }
            $Details_e = $Details_a;

            $unitSellPrice_e = $unitSellPrice_a;
            foreach ($request['quantity'] as $Qty_) {
                $Qty_a[] = $Qty_;
            }
            $Qty_e = $Qty_a;
            foreach ($request['unit_name'] as $unit_name_) {
                $unit_name_a[] = $unit_name_;
            }
            $unit_name_e = $unit_name_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;

            $del_inventory_transaction_account = DB::table('invoice_details')
                ->where('invoice_id', $invoice->id)->delete();

            $invoice->reference = $request->reference;
            $invoice->user_id = $request->customer_id;
            $invoice->branch_id = $request->branch;
            $invoice->vat = $request->tax_amount;
            $invoice->discount = $request->discount_amount;
            $invoice->vat_per = $request->vat_per;
            $invoice->disc_per = $request->disc_per;
            $invoice->less_amount = $request->less_amount;
            $invoice->product_total = $request->product_total;
            $invoice->total_amount = $request->total_amount;
            $invoice->invoice_total = $request->invoice_total;
            $invoice->notes = $request->notes;
            $invoice->updated_by = Auth::id();
            $invoice->save();

            $count_ids = count($ProductID_e);
            if (count($unitSellPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {
                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new InvoiceDetail();
                $inventory_transaction->invoice_id = $invoice->id;
                $inventory_transaction->branch_id = $request->branch;
                $inventory_transaction->product_id = $ProductID_e[$i];
                $inventory_transaction->brand_id = $BrandID_e[$i];
                $inventory_transaction->model = $Model_e[$i];
                $inventory_transaction->product_details = $Details_e[$i];
                $inventory_transaction->usell_price = $unitSellPrice_e[$i];
                $inventory_transaction->qty = $Qty_e[$i];
                $inventory_transaction->unit_name = $unit_name_e[$i];
                $inventory_transaction->line_total = $mrpTotal_e[$i];
                $inventory_transaction->transaction_type = $request->transaction_type;
                $inventory_transaction->save();
            }

            $last_insert_id = $invoice->id;

            \Session::flash('flash_message', 'Successfully Updated');
            return redirect('invoice/' . $last_insert_id);
        } elseif ($request->transaction_type == 'Return') { //Return
            $this->validate($request, [
                'customer_id' => 'required',
            ]);
            $ProductID_a = [];
            $mrpUnitPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
            $mrpTotal_a = [];

            foreach ($request['productId'] as $ProductID_) {
                $ProductID_a[] = $ProductID_;
            }
            $ProductID_e = $ProductID_a;
            foreach ($request['mrpUnitPrice'] as $mrpUnitPrice_) {
                $mrpUnitPrice_a[] = $mrpUnitPrice_;
            }
            $mrpUnitPrice_e = $mrpUnitPrice_a;
            foreach ($request['quantity'] as $Qty_) {
                $Qty_a[] = $Qty_;
            }
            $Qty_e = $Qty_a;
            foreach ($request['unit_name'] as $unit_name_) {
                $unit_name_a[] = $unit_name_;
            }
            $unit_name_e = $unit_name_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;

            $del_inventory_transaction = DB::table('inventory_transactions')
                ->where('transaction_code', $request->transaction_code)->delete();
            $del_inventory_transaction_account = DB::table('inventory_transaction_accounts')
                ->where('transaction_code', $request->transaction_code)->delete();

            $count_ids = count($ProductID_e);
            if (count($mrpUnitPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {

                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new InvoiceDetail();
                $inventory_transaction->product_id = $ProductID_e[$i];
                $inventory_transaction->mrpUnitPrice = $mrpUnitPrice_e[$i];
                $inventory_transaction->qty = $Qty_e[$i];
                $inventory_transaction->unit_name = $unit_name_e[$i];
                $inventory_transaction->mrpTotal = $mrpTotal_e[$i];
                $inventory_transaction->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
                $inventory_transaction->user_id = $request->customer_id;
                $inventory_transaction->transaction_type = $request->transaction_type;
                $inventory_transaction->transaction_code = $request->transaction_code;
                $inventory_transaction->save();

            }
            $inventory_transaction_account = new Invoice();
            $inventory_transaction_account->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
            $inventory_transaction_account->transaction_code = $request->transaction_code;
            $inventory_transaction_account->reference = $request->reference;
            $inventory_transaction_account->user_id = $request->customer_id;
            $inventory_transaction_account->transaction_type = $request->transaction_type;
            $inventory_transaction_account->vat = $request->tax_amount;
            $inventory_transaction_account->discount = $request->discount_amount;
            $inventory_transaction_account->less_amount = $request->less_amount;
            $inventory_transaction_account->product_total = $request->product_total;
            $inventory_transaction_account->total_amount = $request->total_amount;
            $inventory_transaction_account->invoice_total = $request->invoice_total;
            $inventory_transaction_account->notes = $request->notes;
            $inventory_transaction_account->entry_by = $request->entry_by;
            $inventory_transaction_account->created_at = date('Y-m-d H:i:s', strtotime($request->created_at));
            $inventory_transaction_account->updated_by = Auth::user()->id;
            $inventory_transaction_account->vat_per = $request->vat_per;
            $inventory_transaction_account->disc_per = $request->disc_per;
            $inventory_transaction_account->save();

            $last_insert_id = $inventory_transaction_account->id;

            \Session::flash('flash_message', 'Successfully Updated');
            return redirect('inventory_transaction_account/' . $last_insert_id);
        } elseif ($request->transaction_type == 'Put Back') { //Putback
            $this->validate($request, [
                'supplier_id' => 'required',
            ]);

            $ProductID_a = [];
            $mrpUnitPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
            $mrpTotal_a = [];
            foreach ($request['productId'] as $ProductID_) {
                $ProductID_a[] = $ProductID_;
            }
            $ProductID_e = $ProductID_a;
            foreach ($request['mrpUnitPrice'] as $mrpUnitPrice_) {
                $mrpUnitPrice_a[] = $mrpUnitPrice_;
            }
            $mrpUnitPrice_e = $mrpUnitPrice_a;
            foreach ($request['quantity'] as $Qty_) {
                $Qty_a[] = $Qty_;
            }
            $Qty_e = $Qty_a;
            foreach ($request['unit_name'] as $unit_name_) {
                $unit_name_a[] = $unit_name_;
            }
            $unit_name_e = $unit_name_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;

            $del_inventory_transaction = DB::table('inventory_transactions')
                ->where('transaction_code', $request->transaction_code)->delete();
            $del_inventory_transaction_account = DB::table('inventory_transaction_accounts')
                ->where('transaction_code', $request->transaction_code)->delete();

            $count_ids = count($ProductID_e);
            if (count($mrpUnitPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {

                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new InvoiceDetail();
                $inventory_transaction->product_id = $ProductID_e[$i];
                $inventory_transaction->mrpUnitPrice = $mrpUnitPrice_e[$i];
                $inventory_transaction->qty = $Qty_e[$i];
                $inventory_transaction->unit_name = $unit_name_e[$i];
//                $inventory_transaction->discountPercentage = $discountPercentage_e[$i];
//                $inventory_transaction->discountUnit = $discountUnit_e[$i];
//                $inventory_transaction->discountedMrp = $discountedMrp_e[$i];
                $inventory_transaction->mrpTotal = $mrpTotal_e[$i];
//                $inventory_transaction->discountTotal = $discountTotal_e[$i];
//                $inventory_transaction->discountedTotalMrp = $discountedTotalMrp_e[$i];
                $inventory_transaction->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
                $inventory_transaction->user_id = $request->supplier_id;
                $inventory_transaction->transaction_type = $request->transaction_type;
                $inventory_transaction->transaction_code = $request->transaction_code;
//                $inventory_transaction->supplied_by = $request->supplier_id;
                $inventory_transaction->save();

            }
            $inventory_transaction_account = new Invoice();
            $inventory_transaction_account->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
            $inventory_transaction_account->transaction_code = $request->transaction_code;
            $inventory_transaction_account->reference = $request->reference;
            $inventory_transaction_account->user_id = $request->supplier_id;
            $inventory_transaction_account->transaction_type = $request->transaction_type;
            $inventory_transaction_account->vat = $request->tax_amount;
            $inventory_transaction_account->discount = $request->discount_amount;
            $inventory_transaction_account->less_amount = $request->less_amount;
            $inventory_transaction_account->product_total = $request->product_total;
            $inventory_transaction_account->total_amount = $request->total_amount;
            $inventory_transaction_account->invoice_total = $request->invoice_total;
            $inventory_transaction_account->notes = $request->notes;
            $inventory_transaction_account->entry_by = $request->entry_by;
            $inventory_transaction_account->created_at = date('Y-m-d H:i:s', strtotime($request->created_at));
            $inventory_transaction_account->updated_by = Auth::user()->id;
//            $inventory_transaction_account->supplied_by = $request->supplier_id;
            $inventory_transaction_account->vat_per = $request->vat_per;
            $inventory_transaction_account->disc_per = $request->disc_per;
            $inventory_transaction_account->save();

            $last_insert_id = $inventory_transaction_account->id;

            \Session::flash('flash_message', 'Successfully Updated');
            return redirect('inventory_transaction_account/' . $last_insert_id);
        }

    }

    public function my_sales()
    {
        $mySales = DB::table('inventory_transaction_accounts')
            ->select('inventory_transaction_accounts.transaction_code', 'inventory_transaction_accounts.transaction_date', 'inventory_transaction_accounts.total_amount', 'inventory_transaction_accounts.less_amount',
                'inventory_transaction_accounts.id', 'profiles.mobile', 'profiles.address', 'users.name as user_name', 'company_names.title as org_name', 'inventory_transaction_accounts.entry_by')
            ->join('users', 'users.id', '=', 'inventory_transaction_accounts.user_id')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('company_names', 'company_names.id', '=', 'profiles.company_name_id')
//            ->groupBy(DB::raw('transaction_code') )
            ->where('transaction_type', 'Sales')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('transaction_code', 'desc')
            ->get();

        return view('inventory_transaction_account.mySales', compact('mySales'));

    }

    public function destroy(Invoice $invoice)
    {
        abort_if(Gate::denies('SupplyDelete'), redirect('error'));
//check the sales is made aganest by order
        if (strpos($invoice->reference, 'OSE-') !== false)
            return redirect()->back()->with('flash_message', 'You can not Delete a process order');

        $del_invoice_details = DB::table('invoice_details')
            ->where('invoice_id', $invoice->id)->delete();
        $invoice->delete();
        \Session::flash('flash_message', 'Successfully Deleted');
        return redirect()->back();
    }

    public function invoice_due_report()
    {
        // Get all invoices
        $invoices = Invoice::all();

// Initialize an array to store invoices with due amounts and payment history
        $invoicesWithDueAndPaymentHistory = [];

// Loop through each invoice
        foreach ($invoices as $invoice) {
            // Calculate the total amount of the invoice
            $totalInvoiceAmount = $invoice->invoice_total;

            // Calculate the total amount paid for this invoice
            $totalAmountPaid = Ledger::where('invoice_id', $invoice->id)->sum('amount');

            // Calculate the due amount
            $dueAmount = $totalInvoiceAmount - $totalAmountPaid;

            // Fetch payment history for this invoice
            $paymentHistory = Ledger::where('invoice_id', $invoice->id)
                ->select('transaction_date', 'amount')
                ->orderBy('transaction_date')
                ->get();

            // If the due amount is greater than 0, add the invoice to the list of invoices with due amounts
            if ($dueAmount > 0) {
                $invoicesWithDueAndPaymentHistory[] = [
                    'invoice' => $invoice,
                    'due_amount' => $dueAmount,
                    'payment_history' => $paymentHistory
                ];
            }

        }
//        dd($invoicesWithDueAndPaymentHistory);
        $title_date_range='Invoice wise due report';
        return view('report.invoice_due_report', compact('invoicesWithDueAndPaymentHistory','title_date_range'));

    }
}
