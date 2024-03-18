<?php

namespace App\Http\Controllers;

use App\Models\BranchLedger;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Ledger;
use App\Models\TransactionMethod;
use App\Models\User;
use App\Models\WalkingCustomer;
use Illuminate\Http\Request;
use DB;
use carbon\carbon;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class InvoiceDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function purchaseCreate()
    {
        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        $supplier = supplier_list();
        $transaction_methods = transaction_method();
        $branch = branch_list();
        return view('supply.purchaseCreate', compact('supplier', 'transaction_methods', 'branch'));
    }

    public function salesCreate()
    {
        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        $customers = customer_list();
        $transaction_methods = transaction_method();
        $branch = branch_list();
        return view('supply.salesCreate', compact('customers', 'transaction_methods', 'branch'));

    }

    public function putbackCreate()
    {
        $supplier = supplier_list();
        $transaction_methods = transaction_method();
        return view('inventory_transaction.putbackCreate', compact('supplier', 'transaction_methods'));
    }

    public function returnCreate()
    {
        $customers = customer_list();
        return view('inventory_transaction.returnCreate', compact('customers'));
    }


    public function in_stock_qty(Request $request)
    {
        if ($request->ajax()) {
            $in_stock1 = '';
            $in_stock1 = (DB::table('invoice_details')->where('product_id', $request->product_id)
                    ->where('transaction_type', 'Purchase')->sum('qty'))
                + (DB::table('invoice_details')->where('product_id', $request->product_id)
                    ->where('transaction_type', 'Return')->sum('qty'))
                - (DB::table('invoice_details')->where('product_id', $request->product_id)
                    ->where('transaction_type', 'Put back')->sum('qty'))
                - (DB::table('invoice_details')->where('product_id', $request->product_id)
                    ->where('transaction_type', 'Sales')->sum('qty'))
                - (DB::table('invoice_details')->where('product_id', $request->product_id)
                    ->where('transaction_type', 'Order')->where('status', 2)->sum('qty'));

            if ($request->transaction_type == 'Purchase')
                $in_stock = $in_stock1 + $request->qty;
            elseif($request->transaction_type == 'Sales')
                $in_stock = $in_stock1 - $request->qty;
            else
                $in_stock = $in_stock1;
            return response()->json(['in_stock' => $in_stock]);
        }
    }

    public
    function auto_product()
    {
        if (!empty($_POST['type'])) {
            $type = $_POST['type'];
            $name = $_POST['name_startsWith'];
            $result = DB::table('products')
                ->selectraw('products.id as product_id, products.unitsell_price, products.unitbuy_price, products.title as fulltitle, units.title as utitle')
                ->join('units', 'units.id', '=', 'products.unit_id')
                ->Where('products.' . $type, 'like', "%" . strtoupper($name) . "%")
                ->get();
            $data = array();
            foreach ($result as $value) {
                $name = $value->product_id . '|' . $value->fulltitle . '|' . $value->unitsell_price . '|' . $value->unitbuy_price . '|' . $value->utitle;
                array_push($data, $name);
            }
            echo json_encode($data);
            exit;
        }
    }

    public
    function store(Request $request)
    {
        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        if ($request->transaction_type == 'Purchase') { //Purchase
            $supplier_count = User::where('user_type_id', 4)->count();
            $supplier = User::where('id', $request->supplier_id)->first();

            $td1 = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $td = new DateTime($td1);
            $jd = new DateTime($supplier->profile->joining_date);

            if ($supplier_count <= 0) {
                return redirect('/addSupplier')->withErrors(['error' => 'Please Create Suppliers First']);
            }
            if ($request->productId[0] == null) {
                return redirect('/purchaseCreate')->withErrors(['error' => 'Minimum one Product required']);
            }
            if ($td < $jd) {
                return redirect()->back()->withErrors(['error' => 'Transaction date must be latest than  Supplier Joining Date']);
            }
            if ($request->paid_amount > 0) {
                $this->validate($request, [
                    'supplier_id' => 'required',
                    'transaction_method' => 'required',
                    'total_amount' => 'required',
                    'less_amount' => 'required',
                    'invoice_total' => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'supplier_id' => 'required',
                    'total_amount' => 'required',
                    'less_amount' => 'required',
                    'invoice_total' => 'required',
                ]);
            }
            $ProductID_a = [];
            $unitBuyPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
            $mrpTotal_a = [];

            foreach ($request['productId'] as $ProductID_) {
                $ProductID_a[] = $ProductID_;
            }
            $ProductID_e = $ProductID_a;
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

            $inventory_transaction_account = new Invoice();
            $inventory_transaction_account->transaction_date = $td;
            $inventory_transaction_account->transaction_code = autoTimeStampCode('TAP');
            $inventory_transaction_account->sl_no = invoiceSl('TA-PUR-','Purchase', $td);
            $inventory_transaction_account->reference = $request->reference;
            $inventory_transaction_account->user_id = $request->supplier_id;
            $inventory_transaction_account->branch_id = $request->branch;
            $inventory_transaction_account->transaction_type = $request->transaction_type;
            $inventory_transaction_account->vat = $request->tax_amount;
            $inventory_transaction_account->vat_per = $request->vat_per;
            $inventory_transaction_account->discount = $request->discount_amount;
            $inventory_transaction_account->disc_per = $request->disc_per;
            $inventory_transaction_account->less_amount = $request->less_amount;
            $inventory_transaction_account->product_total = $request->product_total;
            $inventory_transaction_account->total_amount = $request->total_amount;
            $inventory_transaction_account->invoice_total = $request->invoice_total;
            $inventory_transaction_account->notes = $request->notes;
            $inventory_transaction_account->entry_by = Auth::id();
            $inventory_transaction_account->updated_by = Auth::id();
            $inventory_transaction_account->save();

            $mrpTotal_e = $mrpTotal_a;
            $count_ids = count($ProductID_e);
            if (count($unitBuyPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {
                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new InvoiceDetail();
                $inventory_transaction->invoice_id = $inventory_transaction_account->id;
                $inventory_transaction->branch_id = $request->branch;
                $inventory_transaction->product_id = $ProductID_e[$i];
                $inventory_transaction->ubuy_price = $unitBuyPrice_e[$i];
                $inventory_transaction->qty = $Qty_e[$i];
                $inventory_transaction->unit_name = $unit_name_e[$i];
                $inventory_transaction->line_total = $mrpTotal_e[$i];
                $inventory_transaction->transaction_type = $request->transaction_type;
                $inventory_transaction->save();
            }

            if ($request->paid_amount > 0) {

                $transaction_code=autoTimeStampCode('LPP');
                $ledger = new Ledger();
                $ledger->transaction_date = $td;
                $ledger->user_id = $request->supplier_id;
                $ledger->branch_id = $request->branch;
                $ledger->transaction_type_id = 4;//'Payment'
                $ledger->amount = $request->paid_amount;
                $ledger->transaction_method_id = $request->transaction_method;
                $ledger->transaction_code = $transaction_code;
                $ledger->comments = $request->comments;
                $ledger->entry_by = Auth::id();
                $ledger->save();

                $ledger_banking=new BranchLedger();
                $ledger_banking->branch_id = $request->branch;
                $ledger_banking->transaction_date = $td;
                $ledger_banking->amount = $request->paid_amount;
                $ledger_banking->transaction_method_id = $request->transaction_method;
                $ledger_banking->comments = $request->comments;
                $ledger_banking->entry_by = Auth::user()->id;
                $ledger_banking->approve_status = 'Approved';
                $ledger_banking->transaction_code = $transaction_code;
                $ledger_banking->transaction_type_id = 4;//4=payment
                $ledger_banking->save();
            }

            \Session::flash('flash_message', 'Successfully Added');
            return redirect('invoice/' . $inventory_transaction_account->id);
        } elseif ($request->transaction_type == 'Sales') { //Sales
            $customer_count = User::where('user_type_id', 3)->count();
            $customer = User::where('id', $request->customer_id)->first();
            if ($customer_count <= 0) {
                return redirect('/addClient')->withErrors(['error' => 'Please Create Customer First']);
            }
            if ($request->productId[0] == null) {
                return redirect('purchaseCreate')->withErrors(['error' => 'Minimum one Product required']);
            }
            $td1 = date('Y-m-d', strtotime($request->transaction_date)) . date(' H:i:s');
            $td = new DateTime($td1);
            $jd = new DateTime($customer->profile->joining_date);
            if ($td < $jd) {
                return redirect()->back()->withErrors(['error' => 'Transaction date must be latest than  Customer Joining Date']);
            }
            if ($request->customer_id == 6 && $request->invoice_total != $request->received_amount) {
                \Session::flash('flash_error', 'Received Amount must be same while Customer is Walking Customer');
                return redirect()->back();
            }
            if ($request->paid_amount > 0) {
                $this->validate($request, [
                    'customer_id' => 'required',
                    'transaction_method' => 'required',
                    'total_amount' => 'required',
                    'less_amount' => 'required',
                    'invoice_total' => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'customer_id' => 'required',
                    'total_amount' => 'required',
                    'less_amount' => 'required',
                    'invoice_total' => 'required',
                ]);
            }
            $ProductID_a = [];
            $unitBuyPrice_a = [];
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
            foreach ($request['unitBuyPrice'] as $unitBuyPrice_) {
                $unitBuyPrice_a[] = $unitBuyPrice_;
            }
            $unitBuyPrice_e = $unitBuyPrice_a;
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

            $count_ids = count($ProductID_e);
//            dd($count_ids);
            $inventory_transaction_account = new Invoice();
            $inventory_transaction_account->transaction_date = $td;
            $inventory_transaction_account->transaction_code = autoTimeStampCode('TAS');
            $inventory_transaction_account->sl_no = invoiceSl('TA-SAL-','Sales',$td);
            $inventory_transaction_account->reference = $request->reference;
            $inventory_transaction_account->user_id = $request->customer_id;
            $inventory_transaction_account->branch_id = $request->branch;
            $inventory_transaction_account->transaction_type = $request->transaction_type;
            $inventory_transaction_account->vat = $request->tax_amount;
            $inventory_transaction_account->discount = $request->discount_amount;
            $inventory_transaction_account->vat_per = $request->vat_per;
            $inventory_transaction_account->disc_per = $request->disc_per;
            $inventory_transaction_account->less_amount = $request->less_amount;
            $inventory_transaction_account->product_total = $request->product_total;
            $inventory_transaction_account->total_amount = $request->total_amount;
            $inventory_transaction_account->invoice_total = $request->invoice_total;
            $inventory_transaction_account->notes = $request->notes;
            $inventory_transaction_account->entry_by = Auth::id();
            $inventory_transaction_account->updated_by = Auth::id();
            $inventory_transaction_account->save();

            if (count($unitSellPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {
                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new InvoiceDetail();
                $inventory_transaction->invoice_id = $inventory_transaction_account->id;
                $inventory_transaction->branch_id = $request->branch;
                $inventory_transaction->product_id = $ProductID_e[$i];
                $inventory_transaction->ubuy_price = $unitBuyPrice_e[$i];
                $inventory_transaction->usell_price = $unitSellPrice_e[$i];
                $inventory_transaction->qty = $Qty_e[$i];
                $inventory_transaction->unit_name = $unit_name_e[$i];
                $inventory_transaction->line_total = $mrpTotal_e[$i];
                $inventory_transaction->transaction_type = $request->transaction_type;
                $inventory_transaction->save();
            }

            if ($request->received_amount > 0) {

                $transaction_code=autoTimeStampCode('LSR');
                $ledger = new Ledger();
                $ledger->transaction_date = $td;
                $ledger->user_id = $request->customer_id;
                $ledger->branch_id = $request->branch;
                $ledger->transaction_type_id = 3;//Received
                $ledger->amount = $request->received_amount;
                $ledger->transaction_method_id = $request->transaction_method;
                $ledger->transaction_code = $transaction_code;
                $ledger->comments = $request->comments;
                $ledger->entry_by = Auth::id();
                $ledger->save();

                $ledger_banking=new BranchLedger();
                $ledger_banking->branch_id = $request->branch;
                $ledger_banking->transaction_date = $td;
                $ledger_banking->amount = $request->received_amount;
                $ledger_banking->transaction_method_id = $request->transaction_method;
                $ledger_banking->comments = $request->comments;
                $ledger_banking->entry_by = Auth::user()->id;
                $ledger_banking->approve_status = 'Approved';
                $ledger_banking->transaction_code = $transaction_code;
                $ledger_banking->transaction_type_id = 3;//Received
                $ledger_banking->save();

                if ($request->customer_id == 6) {
                    $customer = new WalkingCustomer();
                    $customer->invoice_id = $inventory_transaction_account->id;
                    $customer->ledger_id = $ledger->id;
                    $customer->name = $request->name;
                    $customer->mobile = $request->mobile;
                    $customer->address = $request->address;
                    $customer->save();
                }
            }
            $last_insert_id = $inventory_transaction_account->id;

            \Session::flash('flash_message', 'Successfully Added');
            return redirect('invoice/' . $last_insert_id);
        } elseif ($request->transaction_type == 'Order') { //Order create
//            dd($request);
            $customer_count = User::where('user_type', 'Customer')->count();
            $customer = User::where('id', $request->customer_id)->first();
            if ($customer_count <= 0) {
                return redirect('user/create')->withErrors(['error' => 'Please Create Customer First']);
            }
            if ($request->productId[0] == null) {
                return redirect('inventory_transaction/purchaseCreate')->withErrors(['error' => 'Minimum one Product required']);
            }
            $td = new DateTime($request->transaction_date);
            $jd = new DateTime($customer->profile->joining_date);
            if ($td < $jd) {
                return redirect()->back()->withErrors(['error' => 'Order date must be latest than  Customer Joining Date']);
//                return redirect()->back()->with('flash_message', 'Transaction date must be latest than  Customer Joining Date');
            }
            if ($request->paid_amount > 0) {
                $this->validate($request, [
                    'customer_id' => 'required',
//                    'supplied_by' => 'required',
                    'transaction_method' => 'required',
//                    'paid_amount' => 'required|numeric|between:0,999999.99',
                ]);
            } else {
                $this->validate($request, [
//                    'supplied_by' => 'required',
                    'customer_id' => 'required',
                ]);
            }
            $s_year = date('Y-m') . '-00 00:00:00';
            $e_year = date('Y-m') . '-31 23:59:59';
            $count_sale = (DB::table('inventory_transaction_accounts')
//                    ->where('transaction_type', 'Order')
                    ->whereBetween('created_at', [$s_year, $e_year])->max('id')) + 1;
            $transaction_code = 'OSE-' . date('ym') . '- ' . str_pad($count_sale, 6, '0', STR_PAD_LEFT);
            $ProductID_a = [];
            $mrpUnitPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
//            $discountPercentage_a = [];
//            $discountUnit_a = [];
//            $discountedMrp_a = [];
            $mrpTotal_a = [];
//            $discountTotal_a = [];
//            $discountedTotalMrp_a = [];
            $unitBuyPrice_a = [];
            $totalBuyPrice_a = [];

            foreach ($request['productId'] as $ProductID_) {
                $ProductID_a[] = $ProductID_;
            }
            $ProductID_e = $ProductID_a;
            foreach ($request['unit_name'] as $unit_name_) {
                $unit_name_a[] = $unit_name_;
            }
            $unit_name_e = $unit_name_a;
            foreach ($request['mrpUnitPrice'] as $mrpUnitPrice_) {
                $mrpUnitPrice_a[] = $mrpUnitPrice_;
            }
            $mrpUnitPrice_e = $mrpUnitPrice_a;
            foreach ($request['quantity'] as $Qty_) {
                $Qty_a[] = $Qty_;
            }
            $Qty_e = $Qty_a;
//            foreach ($request['discountPercentage'] as $discountPercentage_) {
//                $discountPercentage_a[] = $discountPercentage_;
//            }
//            $discountPercentage_e = $discountPercentage_a;
//            foreach ($request['discountUnit'] as $discountUnit_) {
//                $discountUnit_a[] = $discountUnit_;
//            }
//            $discountUnit_e = $discountUnit_a;
//            foreach ($request['discountedMrp'] as $discountedMrp_) {
//                $discountedMrp_a[] = $discountedMrp_;
//            }
//            $discountedMrp_e = $discountedMrp_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;
//            foreach ($request['discountTotal'] as $discountTotal_) {
//                $discountTotal_a[] = $discountTotal_;
//            }
//            $discountTotal_e = $discountTotal_a;
//            foreach ($request['discountedTotalMrp'] as $discountedTotalMrp_) {
//                $discountedTotalMrp_a[] = $discountedTotalMrp_;
//            }
//            $discountedTotalMrp_e = $discountedTotalMrp_a;
            foreach ($request['unitBuyPrice'] as $unitBuyPrice_) {
                $unitBuyPrice_a[] = $unitBuyPrice_;
            }
            $unitBuyPrice_e = $unitBuyPrice_a;
            foreach ($request['totalBuyPrice'] as $totalBuyPrice_) {
                $totalBuyPrice_a[] = $totalBuyPrice_;
            }
            $totalBuyPrice_e = $totalBuyPrice_a;

            $count_ids = count($ProductID_e);
            if (count($mrpUnitPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {
                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new Inventory_transaction();
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
                $inventory_transaction->unitBuyPrice = $unitBuyPrice_e[$i];
                $inventory_transaction->totalBuyPrice = $totalBuyPrice_e[$i];

                $inventory_transaction->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
                $inventory_transaction->user_id = $request->customer_id;
//                $inventory_transaction->supplied_by = $request->supplied_by;
                $inventory_transaction->transaction_type = $request->transaction_type;
                $inventory_transaction->transaction_code = $transaction_code;
                $inventory_transaction->status = 2;
//                $inventory_transaction->supplied_by = $request->supplied_by;
                $inventory_transaction->save();

            }
            $inventory_transaction_account = new Inventory_transaction_account();
            $inventory_transaction_account->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
//            $inventory_transaction_account->payment_date = $request->payment_date;
            $inventory_transaction_account->transaction_code = $transaction_code;
            $inventory_transaction_account->reference = $request->reference;
            $inventory_transaction_account->user_id = $request->customer_id;
            $inventory_transaction_account->transaction_type = $request->transaction_type;
            $inventory_transaction_account->vat = $request->tax_amount;
            $inventory_transaction_account->discount = $request->discount_amount;
            $inventory_transaction_account->less_amount = $request->less_amount;
            $inventory_transaction_account->product_total = $request->product_total;
            $inventory_transaction_account->total_amount = $request->total_amount;
            $inventory_transaction_account->invoice_total = $request->invoice_total;
//            $inventory_transaction_account->paid_amount = $request->paid_amount;
            $inventory_transaction_account->notes = $request->notes;
            $inventory_transaction_account->entry_by = Auth::id();
            $inventory_transaction_account->updated_by = Auth::id();
//            $inventory_transaction_account->supplied_by = $request->supplied_by;
            $inventory_transaction_account->vat_per = $request->vat_per;
            $inventory_transaction_account->disc_per = $request->disc_per;
//            $inventory_transaction_account->received_amount = $request->received_amount;
//            $inventory_transaction_account->change_amount = $request->change_amount;
            $inventory_transaction_account->save();

            if ($request->paid_amount > 0) {
                $s_year = date('Y-m') . '-00 00:00:00';
                $e_year = date('Y-m') . '-31 23:59:59';
                $count_ledger = (DB::table('ledgers')
                        ->whereBetween('created_at', [$s_year, $e_year])->max('id')) + 1;
                $transaction_code = 'LRSE-' . date('ym') . '- ' . str_pad($count_ledger, 6, '0', STR_PAD_LEFT);

                $ledger = new Ledger();
                $ledger->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
                $ledger->user_id = $request->customer_id;
                $ledger->transaction_type = 'Receipt';
                $ledger->amount = $request->paid_amount;
                $ledger->transaction_method = $request->transaction_method;
                $ledger->transaction_code = $transaction_code;
                $ledger->comments = $request->comments;
                $ledger->entry_by = Auth::id();
//                $ledger->supplied_by = $request->supplied_by;
                $ledger->save();
            }


            $last_insert_id = $inventory_transaction_account->id;

            \Session::flash('flash_message', 'Successfully Added');
            return redirect('inventory_transaction_account/' . $last_insert_id);
        } elseif ($request->transaction_type == 21) { //Order Process
//            dd($request);
            $s_year = date('Y-m') . '-00 00:00:00';
            $e_year = date('Y-m') . '-31 23:59:59';
            $count_sale = (DB::table('inventory_transaction_accounts')
//                    ->where('transaction_type', 'Sales')
                    ->whereBetween('created_at', [$s_year, $e_year])->max('id')) + 1;
            $transaction_code = 'SSE-' . date('ym') . '- ' . str_pad($count_sale, 6, '0', STR_PAD_LEFT);

            $rowId_a = [];
            $ProductID_a = [];
            $mrpUnitPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
//            $discountPercentage_a = [];
//            $discountUnit_a = [];
//            $discountedMrp_a = [];
            $mrpTotal_a = [];
//            $discountTotal_a = [];
//            $discountedTotalMrp_a = [];
            $unitBuyPrice_a = [];
            $totalBuyPrice_a = [];

            foreach ($request['rowId'] as $rowId_) {
                $rowId_a[] = $rowId_;
            }
            $rowId_e = $rowId_a;
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
//            foreach ($request['discountPercentage'] as $discountPercentage_) {
//                $discountPercentage_a[] = $discountPercentage_;
//            }
//            $discountPercentage_e = $discountPercentage_a;
//            foreach ($request['discountUnit'] as $discountUnit_) {
//                $discountUnit_a[] = $discountUnit_;
//            }
//            $discountUnit_e = $discountUnit_a;
//            foreach ($request['discountedMrp'] as $discountedMrp_) {
//                $discountedMrp_a[] = $discountedMrp_;
//            }
//            $discountedMrp_e = $discountedMrp_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;
//            foreach ($request['discountTotal'] as $discountTotal_) {
//                $discountTotal_a[] = $discountTotal_;
//            }
//            $discountTotal_e = $discountTotal_a;
//            foreach ($request['discountedTotalMrp'] as $discountedTotalMrp_) {
//                $discountedTotalMrp_a[] = $discountedTotalMrp_;
//            }
//            $discountedTotalMrp_e = $discountedTotalMrp_a;
            foreach ($request['unitBuyPrice'] as $unitBuyPrice_) {
                $unitBuyPrice_a[] = $unitBuyPrice_;
            }
            $unitBuyPrice_e = $unitBuyPrice_a;
            foreach ($request['totalBuyPrice'] as $totalBuyPrice_) {
                $totalBuyPrice_a[] = $totalBuyPrice_;
            }
            $totalBuyPrice_e = $totalBuyPrice_a;

            $count_ids = count($ProductID_e);
            if (count($mrpUnitPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            $all_order_number = [];
            for ($i = 0; $i < $count_ids; $i++) {

                $hw = Inventory_transaction::findOrFail($rowId_e[$i]);
                $hw->status = 1;
                $hw->save();
                $all_order_number[] = $hw->transaction_code;

                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new Inventory_transaction();
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
                $inventory_transaction->unitBuyPrice = $unitBuyPrice_e[$i];
                $inventory_transaction->totalBuyPrice = $totalBuyPrice_e[$i];

                $inventory_transaction->transaction_date = date('Y-m-d H:i:s');
                $inventory_transaction->user_id = $request->customer_id;
                $inventory_transaction->transaction_type = 'Sales'; //sales
                $inventory_transaction->transaction_code = $transaction_code;
//                $inventory_transaction->supplied_by = $request->supplied_by;
                $inventory_transaction->save();
            }
//            dd($all_order_number);

            $unique_order_number = array_unique($all_order_number);
//dd($unique_order_number);
            $inventory_transaction_account = new Inventory_transaction_account();
            $inventory_transaction_account->transaction_date = date('Y-m-d H:i:s');
            $inventory_transaction_account->transaction_code = $transaction_code;
            $inventory_transaction_account->reference = implode(",", $unique_order_number);
            $inventory_transaction_account->user_id = $request->customer_id;
            $inventory_transaction_account->transaction_type = 'Sales'; //sales
            $inventory_transaction_account->vat = $request->tax_amount;
            $inventory_transaction_account->discount = $request->discount_amount;
            $inventory_transaction_account->less_amount = $request->less_amount;
            $inventory_transaction_account->product_total = $request->product_total;
            $inventory_transaction_account->total_amount = $request->total_amount;
            $inventory_transaction_account->invoice_total = $request->invoice_total;
            $inventory_transaction_account->notes = $request->notes;
            $inventory_transaction_account->entry_by = Auth::id();
            $inventory_transaction_account->updated_by = Auth::id();
//            $inventory_transaction_account->supplied_by = $request->supplied_by;
            $inventory_transaction_account->vat_per = $request->vat_per;
            $inventory_transaction_account->disc_per = $request->disc_per;
            $inventory_transaction_account->save();

            $last_insert_id = $inventory_transaction_account->id;

            \Session::flash('flash_message', 'Successfully Added');
            return redirect('inventory_transaction_account/' . $last_insert_id);
        } elseif ($request->transaction_type == 'Return') { //Return
            $customer = User::where('id', $request->customer_id)->first();
            $td = new DateTime($request->transaction_date);
            $jd = new DateTime($customer->profile->joining_date);

            if ($td < $jd) {
                return redirect()->back()->withErrors(['error' => 'Return date must be latest than  Customer Joining Date']);
//                return redirect()->back()->with('flash_message', 'Transaction date must be latest than  Customer Joining Date');
            }
            $this->validate($request, [
                'customer_id' => 'required',
//                'supplied_by' => 'required',

            ]);
            $s_year = date('Y-m') . '-00 00:00:00';
            $e_year = date('Y-m') . '-31 23:59:59';
            $count_purchase = (DB::table('inventory_transaction_accounts')
//                    ->where('transaction_type', 'Return')
                    ->whereBetween('created_at', [$s_year, $e_year])->max('id')) + 1;
            $transaction_code = 'RSE-' . date('ym') . '- ' . str_pad($count_purchase, 6, '0', STR_PAD_LEFT);
            $ProductID_a = [];
            $mrpUnitPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
//            $discountPercentage_a = [];
//            $discountUnit_a = [];
//            $discountedMrp_a = [];
            $mrpTotal_a = [];
//            $discountTotal_a = [];
//            $discountedTotalMrp_a = [];

            foreach ($request['productId'] as $ProductID_) {
                $ProductID_a[] = $ProductID_;
            }
            $ProductID_e = $ProductID_a;
            foreach ($request['unit_name'] as $unit_name_) {
                $unit_name_a[] = $unit_name_;
            }
            $unit_name_e = $unit_name_a;
            foreach ($request['mrpUnitPrice'] as $mrpUnitPrice_) {
                $mrpUnitPrice_a[] = $mrpUnitPrice_;
            }
            $mrpUnitPrice_e = $mrpUnitPrice_a;
            foreach ($request['quantity'] as $Qty_) {
                $Qty_a[] = $Qty_;
            }
            $Qty_e = $Qty_a;
//            foreach ($request['discountPercentage'] as $discountPercentage_) {
//                $discountPercentage_a[] = $discountPercentage_;
//            }
//            $discountPercentage_e = $discountPercentage_a;
//            foreach ($request['discountUnit'] as $discountUnit_) {
//                $discountUnit_a[] = $discountUnit_;
//            }
//            $discountUnit_e = $discountUnit_a;
//            foreach ($request['discountedMrp'] as $discountedMrp_) {
//                $discountedMrp_a[] = $discountedMrp_;
//            }
//            $discountedMrp_e = $discountedMrp_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;
//            foreach ($request['discountTotal'] as $discountTotal_) {
//                $discountTotal_a[] = $discountTotal_;
//            }
//            $discountTotal_e = $discountTotal_a;
//            foreach ($request['discountedTotalMrp'] as $discountedTotalMrp_) {
//                $discountedTotalMrp_a[] = $discountedTotalMrp_;
//            }
//            $discountedTotalMrp_e = $discountedTotalMrp_a;

            $count_ids = count($ProductID_e);
            if (count($mrpUnitPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {

                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new Inventory_transaction();
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
                $inventory_transaction->user_id = $request->customer_id;
                $inventory_transaction->transaction_type = 'Return';
                $inventory_transaction->transaction_code = $transaction_code;
//                $inventory_transaction->supplied_by = $request->supplied_by;
                $inventory_transaction->save();
            }
            $inventory_transaction_account = new Inventory_transaction_account();
            $inventory_transaction_account->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
//            $inventory_transaction_account->payment_date = $request->payment_date;
            $inventory_transaction_account->transaction_code = $transaction_code;
            $inventory_transaction_account->reference = $request->reference;
            $inventory_transaction_account->user_id = $request->customer_id;
            $inventory_transaction_account->transaction_type = 'Return';
            $inventory_transaction_account->vat = $request->tax_amount;
            $inventory_transaction_account->discount = $request->discount_amount;
            $inventory_transaction_account->less_amount = $request->less_amount;
            $inventory_transaction_account->product_total = $request->product_total;
            $inventory_transaction_account->total_amount = $request->total_amount;
            $inventory_transaction_account->invoice_total = $request->invoice_total;
//            $inventory_transaction_account->paid_amount = $request->paid_amount;
            $inventory_transaction_account->notes = $request->notes;
            $inventory_transaction_account->entry_by = Auth::id();
            $inventory_transaction_account->updated_by = Auth::id();
//            $inventory_transaction_account->supplied_by = $request->supplied_by;
            $inventory_transaction_account->vat_per = $request->vat_per;
            $inventory_transaction_account->disc_per = $request->disc_per;
            $inventory_transaction_account->save();

            $last_insert_id = $inventory_transaction_account->id;

            \Session::flash('flash_message', 'Successfully Added');
            return redirect('inventory_transaction_account/' . $last_insert_id);
        } elseif ($request->transaction_type == 'Put Back') { //Put Back
//            dd($request);
            $supplier_count = User::where('user_type', 'Supplier')->count();
            $supplier = User::where('id', $request->supplier_id)->first();

            $td = new DateTime($request->transaction_date);
            $jd = new DateTime($supplier->profile->joining_date);
//            dd($jd);
            if ($supplier_count <= 0) {
                return redirect('user/create')->withErrors(['error' => 'Please Create Suppliers First']);
            }
            if ($request->productId[0] == null) {
                return redirect('inventory_transaction/purchaseCreate')->withErrors(['error' => 'Minimum one Product required']);
            }
            if ($td < $jd) {
                return redirect()->back()->withErrors(['error' => 'Transaction date must be latest than  Supplier Joining Date']);
            }
            $this->validate($request, [
                'supplier_id' => 'required',

            ]);
            $s_year = date('Y-m') . '-00 00:00:00';
            $e_year = date('Y-m') . '-31 23:59:59';
            $count_purchase = (DB::table('inventory_transaction_accounts')
                    ->whereBetween('created_at', [$s_year, $e_year])->max('id')) + 1;
            $transaction_code = 'PbSE-' . date('ym') . '- ' . str_pad($count_purchase, 6, '0', STR_PAD_LEFT);
//            dd($transaction_code);
            $ProductID_a = [];
            $mrpUnitPrice_a = [];
            $Qty_a = [];
            $unit_name_a = [];
//            $discountPercentage_a = [];
//            $discountUnit_a = [];
//            $discountedMrp_a = [];
            $mrpTotal_a = [];
//            $discountTotal_a = [];
//            $discountedTotalMrp_a = [];

            foreach ($request['productId'] as $ProductID_) {
//                if (companyInfo($request->supplier_id)['id'] != productInfo($ProductID_)->company_name_id) {
//                    \Session::flash('flash_error', 'Product mitch match with the Supplier');
//                    return redirect::back();
//                }
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
//            foreach ($request['discountPercentage'] as $discountPercentage_) {
//                $discountPercentage_a[] = $discountPercentage_;
//            }
//            $discountPercentage_e = $discountPercentage_a;
//            foreach ($request['discountUnit'] as $discountUnit_) {
//                $discountUnit_a[] = $discountUnit_;
//            }
//            $discountUnit_e = $discountUnit_a;
//            foreach ($request['discountedMrp'] as $discountedMrp_) {
//                $discountedMrp_a[] = $discountedMrp_;
//            }
//            $discountedMrp_e = $discountedMrp_a;
            foreach ($request['mrpTotal'] as $mrpTotal_) {
                $mrpTotal_a[] = $mrpTotal_;
            }
            $mrpTotal_e = $mrpTotal_a;
//            foreach ($request['discountTotal'] as $discountTotal_) {
//                $discountTotal_a[] = $discountTotal_;
//            }
//            $discountTotal_e = $discountTotal_a;
//            foreach ($request['discountedTotalMrp'] as $discountedTotalMrp_) {
//                $discountedTotalMrp_a[] = $discountedTotalMrp_;
//            }
//            $discountedTotalMrp_e = $discountedTotalMrp_a;

            $count_ids = count($ProductID_e);
            if (count($mrpUnitPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
            for ($i = 0; $i < $count_ids; $i++) {

                if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                $inventory_transaction = new Inventory_transaction();
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
                $inventory_transaction->transaction_code = $transaction_code;
//                $inventory_transaction->supplied_by = $request->supplier_id;
                $inventory_transaction->save();
            }
            $inventory_transaction_account = new Inventory_transaction_account();
            $inventory_transaction_account->transaction_date = date('Y-m-d H:i:s', strtotime($request->transaction_date));
            $inventory_transaction_account->transaction_code = $transaction_code;
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
            $inventory_transaction_account->entry_by = Auth::id();
            $inventory_transaction_account->updated_by = Auth::id();
//            $inventory_transaction_account->supplied_by = $request->supplier_id;
            $inventory_transaction_account->vat_per = $request->vat_per;
            $inventory_transaction_account->disc_per = $request->disc_per;
            $inventory_transaction_account->save();

            $last_insert_id = $inventory_transaction_account->id;

            \Session::flash('flash_message', 'Successfully Added');
            return redirect('inventory_transaction_account/' . $last_insert_id);
        }
    }

    public function createSlug($id = 0)
    {
        $monthly_count_invoice=Invoice::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $prefix = 'SERIAL'; // Prefix for the serial number
        $date = date('ym'); // Current month and year
        $slug = $prefix . $date . '-' . str_pad($monthly_count_invoice+1, 4, '0', STR_PAD_LEFT);

        $allSlugs = $this->getRelatedSlugs($slug, $id);

        if (! $allSlugs->contains('transaction_code', $slug)){
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Invoice::select('transaction_code')->where('transaction_code', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    function auto_sl_no(){
        $monthly_count_invoice=Invoice::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $prefix = 'SERIAL'; // Prefix for the serial number
        $date = date('ym'); // Current month and year
        $serialNumber = $prefix . $date . '-' . str_pad($monthly_count_invoice, 4, '0', STR_PAD_LEFT);
        $duplicate_check=Invoice::where('transaction_code', $serialNumber)->exists();
    }


//// Function to generate a sequential serial number
//    function generateSequentialSerialNumber($invoiceCount) {
//        $prefix = 'SERIAL'; // Prefix for the serial number
//        $date = date('ym'); // Current month and year
//        $serialNumber = $prefix . $date . '-' . str_pad($invoiceCount, 4, '0', STR_PAD_LEFT);
//        return $serialNumber;
//    }
//
//// Function to get the count of invoices for the current month
//    function getInvoiceCountForCurrentMonth() {
//        $s_year = date('Y-m') . '-00 00:00:00';
//        $e_year = date('Y-m') . '-31 23:59:59';
//        $invoiceCount = DB::table('invoices')
//            ->whereBetween('created_at', [$s_year, $e_year])->count('id');
//        return $invoiceCount;
//    }
//// Function to check if a serial number has been used before
//    function isSerialNumberUsed($serialNumber) {
//        // Query your database to check if the serial number exists in the used_serial_numbers table
//        // Example query: SELECT COUNT(*) FROM used_serial_numbers WHERE serial_number = '$serialNumber'
//        // Execute the query and check if the count is greater than 0
//        $invoiceSerial = DB::table('invoices')->where('transaction_code',$serialNumber)->count();
//        $isUsed = true; // Replace this with the actual query result
//        return $isUsed;
//    }
//// Generate and store serial number
//    function generateAndStoreSerialNumber() {
//        $invoiceCount = $this->getInvoiceCountForCurrentMonth();
//        do {
//            $serialNumber = $this->generateSequentialSerialNumber($invoiceCount + 1);
//        } while ($this->isSerialNumberUsed($serialNumber));
//        // Store the serial number along with the invoice record in your database
//        return $serialNumber;
//    }
//// Generate and store a new ser

}
