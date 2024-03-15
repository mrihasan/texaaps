<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\Product;
use App\Models\TransactionMethod;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

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
        $product=product_list();
        $expected_days =[30=>30,45=>45,60=>60,75=>75,90=>90];
        $to_accounts = DB::table('bank_accounts')->where('status', 'Active')->pluck('account_name', 'id')->prepend('Select Account', '')->toArray();
        $transaction_methods = TransactionMethod::orderBy('title')->pluck('title', 'id')->prepend('Select Transaction Method', '')->toArray();
        return view('payment_request.create',compact('customer','supplier','product','to_accounts','expected_days','transaction_methods','branches'));
    }

    public function store(Request $request)
    {
//        dd($request);
//        abort_if(Gate::denies('superadmin-access'), redirect('error'));
//        $this->validate($request, [
//            'title' => 'required|unique:payment_requests',
//            'code_no' => 'nullable|unique:payment_requests',
//        ]);

        $pr = new PaymentRequest();
        $pr->branch_id = $request->branch;
        $pr->user_id = Auth::user()->id;
        $pr->req_no = autoTimeStampCode('PR');
        $pr->req_date = date('Y-m-d', strtotime($request->request_date)) . date(' H:i:s');

        $pr->customer_id = $request->customer;
        $pr->product_id = $request->product;
        $pr->model = $request->contact_no1;
        $pr->workorder_refno = $request->workorder_refno;
        $pr->workorder_date = date('Y-m-d', strtotime($request->workorder_date));
        $pr->workorder_amount = $request->workorder_amount;
        
        $pr->supplier_id = $request->supplier;
        $pr->contact_person = $request->contact_person;
        $pr->contact_no = $request->contact_no;
        $pr->amount = $request->amount;
        $pr->bank_account_id = $request->bank_account;
        $pr->transaction_method_id = $request->transaction_method;
        $pr->expected_bill = $request->expected_bill;
        $pr->expected_day = $request->expected_day;
        $pr->save();
        
        \Session::flash('flash_message', 'Successfully Added');
        return redirect('payment_request');
    }


    public function edit(PaymentRequest $pr)
    {
        return view('payment_request.edit',compact('payment_request'));
    }

    public function update(Request $request, PaymentRequest $pr)
    {
//        dd($request);
        $this->validate($request, [
            'title' => 'required|unique:payment_requests,title,' . $pr->id . ',id',
            'code_no' => 'nullable|unique:payment_requests,code_no,' . $pr->id . ',id',
        ]);
        $pr->title = $request->title;
        $pr->code_no = $request->code_no;
        $pr->address = $request->address;
        $pr->contact_no1 = $request->contact_no1;
        $pr->contact_no2 = $request->contact_no2;
        $pr->status = $request->status;
        $pr->update();

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('payment_request');
    }

    public function show(PaymentRequest $payment_request)
    {
        return view('payment_request.show', compact('payment_request'));
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

}
