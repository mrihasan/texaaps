<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\Product;
use App\Models\Setting;
use App\Models\TransactionMethod;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use DateTime;

class PaymentRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $payment_requests = PaymentRequest::get();
        return view('payment_request.index', compact('payment_requests'));
    }

    public function create()
    {
//        abort_if(Gate::denies('superadmin-access'), redirect('error'));
        $branches = branch_list();
        $customer = customer_list();
        $supplier = supplier_list();
        $product = product_list();
        $brands = brand_list();
        $expected_days = [30 => 30, 45 => 45, 60 => 60, 75 => 75, 90 => 90];
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        return view('payment_request.create', compact('customer', 'supplier', 'product', 'expected_days', 'transaction_methods', 'branches','brands'));
    }

    public function store(Request $request)
    {
//        dd($request);
//        abort_if(Gate::denies('superadmin-access'), redirect('error'));
//        $this->validate($request, [
//            'title' => 'required|unique:payment_requests',
//            'code_no' => 'nullable|unique:payment_requests',
//        ]);

        $td1 = date('Y-m-d', strtotime($request->request_date)) . date(' H:i:s');
        $td = new DateTime($td1);

        $pr = new PaymentRequest();
        $pr->branch_id = $request->branch;
        $pr->user_id = Auth::user()->id;
        $pr->tracking_code = autoTimeStampCode('PR');
        $pr->req_no = prSl('TA-PR-', $td);
        $pr->req_date = $td;

        $pr->customer_id = $request->customer;
        $pr->product_id = $request->product;
        $pr->brand_id = $request->brand;
        $pr->model = $request->model;
        $pr->workorder_refno = $request->workorder_refno;
        $pr->workorder_date = date('Y-m-d', strtotime($request->workorder_date));
        $pr->workorder_amount = $request->workorder_amount;

        $pr->supplier_id = $request->supplier;
        $pr->contact_person = $request->contact_person;
        $pr->contact_no = $request->contact_no;
        $pr->amount = $request->amount;

        $pr->account_name = $request->account_name;
        $pr->bank_name = $request->bank_name;
        $pr->account_no = $request->account_no;
        $pr->transaction_method_id = $request->transaction_method;
        $pr->expected_bill = $request->expected_bill;
        $pr->expected_day = $request->expected_day;
        $pr->save();

        \Session::flash('flash_message', 'Successfully Added');
        return redirect('payment_request');
    }


    public function edit(PaymentRequest $payment_request)
    {
        $branches = branch_list();
        $customer = customer_list();
        $supplier = supplier_list();
        $product = product_list();
        $brands = brand_list();
        $expected_days = [30 => 30, 45 => 45, 60 => 60, 75 => 75, 90 => 90];
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();

        return view('payment_request.edit', compact('payment_request', 'customer', 'supplier', 'product', 'expected_days', 'transaction_methods', 'branches','brands'));
    }

    public function update(Request $request, PaymentRequest $payment_request)
    {
//        dd($request);
        $this->validate($request, [
//            'title' => 'required|unique:payment_requests,title,' . $pr->id . ',id',
//            'code_no' => 'nullable|unique:payment_requests,code_no,' . $pr->id . ',id',
        ]);
        $payment_request->branch_id = $request->branch;
        $payment_request->req_date = date('Y-m-d', strtotime($request->request_date)) . date(' H:i:s');

        $payment_request->customer_id = $request->customer;
        $payment_request->product_id = $request->product;
        $payment_request->brand_id = $request->brand;
        $payment_request->model = $request->model;
        $payment_request->workorder_refno = $request->workorder_refno;
        $payment_request->workorder_date = date('Y-m-d', strtotime($request->workorder_date));
        $payment_request->workorder_amount = $request->workorder_amount;

        $payment_request->supplier_id = $request->supplier;
        $payment_request->contact_person = $request->contact_person;
        $payment_request->contact_no = $request->contact_no;
        $payment_request->amount = $request->amount;

        $payment_request->account_name = $request->account_name;
        $payment_request->bank_name = $request->bank_name;
        $payment_request->account_no = $request->account_no;
        $payment_request->transaction_method_id = $request->transaction_method;
        $payment_request->expected_bill = $request->expected_bill;
        $payment_request->expected_day = $request->expected_day;
        $payment_request->update();

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('payment_request');
    }

    public function show(PaymentRequest $payment_request)
    {
        $settings=Setting::first();
        return view('payment_request.show', compact('payment_request','settings'));
    }

    public function payment_request_checked($id)
    {
//        dd($id);
//        abort_if(Gate::denies('$payment_request-approval'), redirect('error'));
        $payment_request = PaymentRequest::find($id);
        $payment_request->checked_by = Auth::user()->id;
        $payment_request->save();

        \Session::flash('flash_message', 'Successfully Saved');

        return redirect()->back();
    }

    public function payment_request_approved($id)
    {
//        dd($id);
//        abort_if(Gate::denies('$payment_request-approval'), redirect('error'));
        $payment_request = PaymentRequest::find($id);
        $payment_request->approved_by = Auth::user()->id;
        $payment_request->save();

        \Session::flash('flash_message', 'Successfully Saved');

        return redirect()->back();
    }

    public function destroy(PaymentRequest $payment_request)
    {
//        abort_if(Gate::denies('ProductMgtDelete'), redirect('error'));
        $payment_request->delete();
        \Session::flash('flash_message', 'Successfully Deleted');

        return back();
    }

}
