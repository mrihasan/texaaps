<?php

namespace App\Http\Controllers;

use App\Models\PqDetails;
use App\Models\PriceQuotation;
use App\Models\WalkingCustomer;
use Illuminate\Http\Request;
use DB;
use carbon\carbon;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class PriceQuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $price_quotations = PriceQuotation::latest()->get();
        $title_date_range = 'List of Price quotations';
        return view('price_quotation.index', compact('price_quotations', 'title_date_range'));
    }

    public function pqCreate()
    {
//        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        $customers = customer_list();
        $branch = branch_list();
        $brands = brand_list();
        return view('price_quotation.create', compact('customers', 'branch', 'brands'));
    }

    public
    function store(Request $request)
    {
//        dd($request);
//        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        $customer_count = User::where('user_type_id', 3)->count();
        if ($customer_count <= 0) {
            return redirect('/addClient')->withErrors(['error' => 'Please Create Customer First']);
        }
        if ($request->productId[0] == null) {
            return redirect('purchaseCreate')->withErrors(['error' => 'Minimum one Product required']);
        }

        $this->validate($request, [
            'customer_id' => 'required',
            'quotation_date' => 'required',
            'product_total' => 'required',
        ]);
        try {
            DB::transaction(function () use ($request) {
                $td1 = date('Y-m-d', strtotime($request->quotation_date)) . date(' H:i:s');
                $td = new DateTime($td1);
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
                foreach ($request['unitSellPrice'] as $unitSellPrice_) {
                    $unitSellPrice_a[] = $unitSellPrice_;
                }
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
                $price_quotation = new PriceQuotation();
                $price_quotation->pq_date = $td1;
                $price_quotation->tracking_code = autoTimeStampCode('PQ');
                $price_quotation->ref_no = pqSl('TA-PQ-', $td);
                $price_quotation->reference = $request->reference;
                $price_quotation->user_id = $request->customer_id;
                $price_quotation->branch_id = $request->branch;
                $price_quotation->invoice_total = $request->product_total;
                $price_quotation->additional_notes = $request->additional_notes;
                $price_quotation->entry_by = Auth::id();
                $price_quotation->updated_by = Auth::id();
                $price_quotation->terms = $request->terms;
                $price_quotation->save();

                if (count($unitSellPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
                for ($i = 0; $i < $count_ids; $i++) {
                    if (empty($ProductID_e[$i])) continue; // skip all the blank ones
                    $pq_details = new PqDetails();
                    $pq_details->price_quotation_id = $price_quotation->id;
                    $pq_details->product_id = $ProductID_e[$i];
                    $pq_details->brand_id = $BrandID_e[$i];
                    $pq_details->model = $Model_e[$i];
                    $pq_details->product_details = $Details_e[$i];
                    $pq_details->unit_price = $unitSellPrice_e[$i];
                    $pq_details->qty = $Qty_e[$i];
                    $pq_details->unit_name = $unit_name_e[$i];
                    $pq_details->line_total = $mrpTotal_e[$i];
                    $pq_details->save();
                }

                if ($request->customer_id == 6) {
                    $customer = new WalkingCustomer();
                    $customer->type = 'PQ';
                    $customer->invoice_id = $price_quotation->id;
                    $customer->ledger_id = null;
                    $customer->name = $request->name;
                    $customer->mobile = $request->mobile;
                    $customer->address = $request->address;
                    $customer->save();
                }
            });
            \Session::flash('flash_message', 'Successfully Added');
            return redirect('price_quotation');

        } catch (\Exception $e) {
            \Session::flash('flash_error', 'Failed to save , Try again.');
            return redirect()->back();
        }


    }

    public function show($id)
    {
//        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        $price_quotation = PriceQuotation::where('id', $id)->first();
        if ((Auth::user()->user_type_id == 3 || Auth::user()->user_type_id == 4) && ($price_quotation->user_id != Auth::user()->id)) {
            \Session::flash('flash_error', 'You can not view this');
            return redirect('error');
        }
        if ($price_quotation->user_id == 6) {
            $related_customer = WalkingCustomer::where('type', 'PQ')->where('invoice_id', $price_quotation->id)->first();
        } else
            $related_customer = null;
        $transactionDetails = DB::table('pq_details')
            ->select('pq_details.id', 'pq_details.qty', 'pq_details.unit_name', 'pq_details.unit_price', 'pq_details.product_details',
                'pq_details.line_total', 'brands.title as brand_title',
                'products.title as product_title', 'product_types.title as product_type_title', 'pq_details.product_id', 'pq_details.model')
            ->join('products', 'products.id', '=', 'pq_details.product_id')
            ->join('brands', 'brands.id', '=', 'pq_details.brand_id')
            ->join('product_types', 'product_types.id', '=', 'products.product_type_id')
            ->groupBy(DB::raw('product_id'))
            ->where('pq_details.price_quotation_id', $price_quotation->id)
            ->get();
        $settings = DB::table('settings')->first();
        return view('price_quotation.show', compact('price_quotation', 'settings', 'transactionDetails', 'related_customer'));
    }

    public function edit(PriceQuotation $price_quotation)
    {
//        abort_if(Gate::denies('SupplyAccess'), redirect('error'));
        $user = User::where('id', Auth::user()->id)->first();
        if (($user->user_type_id == 1 || $user->user_type_id == 2)) {
            $inventory = PqDetails::where('price_quotation_id', $price_quotation->id)->get();
//            dd($inventory);
            $customers = customer_list();
            $branch = branch_list();
            $brands = brand_list();

            if ($price_quotation->user_id == 6) {
                $related_customer = WalkingCustomer::where('type', 'PQ')->where('invoice_id', $price_quotation->id)->first();
//                return view('price_quotation.editWalking', compact('price_quotation', 'inventory', 'customers', 'branch', 'brands', 'related_customer'));
            } else{
                $related_customer=['name'=>'','mobile'=>'','address'=>''] ;
            }
//            dd($related_customer);
//                return view('price_quotation.edit', compact('price_quotation', 'inventory', 'customers', 'branch', 'brands'));
            return view('price_quotation.editWalking', compact('price_quotation', 'inventory', 'customers', 'branch', 'brands', 'related_customer'));
        } else
            return view('errors.403');
    }

    public function update(Request $request, PriceQuotation $price_quotation)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'quotation_date' => 'required',
            'product_total' => 'required',
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

        $related_customer = WalkingCustomer::where('type', 'PQ')->where('invoice_id', $price_quotation->id)->first();
        $del_pq_details = DB::table('pq_details')
            ->where('price_quotation_id', $price_quotation->id)->delete();

        $price_quotation->pq_date = date('Y-m-d', strtotime($request->quotation_date)) . date(' H:i:s');
        $price_quotation->reference = $request->reference;
        $price_quotation->user_id = $request->customer_id;
        $price_quotation->branch_id = $request->branch;
        $price_quotation->invoice_total = $request->product_total;
        $price_quotation->additional_notes = $request->additional_notes;
        $price_quotation->updated_by = Auth::id();
        $price_quotation->terms = $request->terms;
        $price_quotation->save();

        $count_ids = count($ProductID_e);
        if (count($unitSellPrice_e) != $count_ids) throw new \Exception("Bad Request Input Array lengths");
        for ($i = 0; $i < $count_ids; $i++) {
            if (empty($ProductID_e[$i])) continue; // skip all the blank ones
            $inventory_transaction = new PqDetails();
            $inventory_transaction->price_quotation_id = $price_quotation->id;
            $inventory_transaction->product_id = $ProductID_e[$i];
            $inventory_transaction->brand_id = $BrandID_e[$i];
            $inventory_transaction->model = $Model_e[$i];
            $inventory_transaction->product_details = $Details_e[$i];
            $inventory_transaction->unit_price = $unitSellPrice_e[$i];
            $inventory_transaction->qty = $Qty_e[$i];
            $inventory_transaction->unit_name = $unit_name_e[$i];
            $inventory_transaction->line_total = $mrpTotal_e[$i];
            $inventory_transaction->save();
        }

        if ($related_customer && $request->customer_id == 6) {
            $del_walking_customer = DB::table('walking_customers')
                ->where('type', 'PQ')->where('invoice_id', $price_quotation->id)->delete();

            $customer = new WalkingCustomer();
            $customer->type = 'PQ';
            $customer->invoice_id = $price_quotation->id;
            $customer->ledger_id = null;
            $customer->name = $request->name;
            $customer->mobile = $request->mobile;
            $customer->address = $request->address;
            $customer->save();
        }elseif ($related_customer && $request->customer_id != 6){
            $del_walking_customer = DB::table('walking_customers')
                ->where('type', 'PQ')->where('invoice_id', $price_quotation->id)->delete();
        }elseif ($request->customer_id == 6){
            $customer = new WalkingCustomer();
            $customer->type = 'PQ';
            $customer->invoice_id = $price_quotation->id;
            $customer->ledger_id = null;
            $customer->name = $request->name;
            $customer->mobile = $request->mobile;
            $customer->address = $request->address;
            $customer->save();
        }

        $last_insert_id = $price_quotation->id;

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('price_quotation/' . $last_insert_id);

    }


    public function destroy(PriceQuotation $price_quotation)
    {
//        abort_if(Gate::denies('SupplyDelete'), redirect('error'));
        $del_walking_customer = DB::table('walking_customers')
            ->where('type', 'PQ')->where('invoice_id', $price_quotation->id)->delete();
        $del_pq_details = DB::table('pq_details')
            ->where('price_quotation_id', $price_quotation->id)->delete();
        $price_quotation->delete();
        \Session::flash('flash_message', 'Successfully Deleted');
        return redirect()->back();
    }

}
