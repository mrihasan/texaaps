<?php

namespace App\Http\Controllers;

use App\Models\CompanyName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class CompanyNameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        abort_if(Gate::denies('company_name-access'), redirect('error'));
        $company_names = CompanyName::all();
        return view('company_name.index', compact(['company_names']));
    }

    public function create()
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        return view('company_name.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:company_names',
        ]);

        $company_name = CompanyName::create($request->all());
        return redirect('company_name');
    }

    public function edit(CompanyName $company_name)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        return view('company_name.edit', compact('company_name'));
    }

    public function update(Request $request, CompanyName $company_name)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:company_names,title,'. $company_name->id . ',id',
        ]);

        $company_name->update($request->all());

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect()->route('company_name.index');
    }

    public function show(CompanyName $company_name)
    {
        return view('company_name.show', compact('company_name'));
    }

    public function destroy(CompanyName $company_name)
    {
        abort_if(Gate::denies('ProductMgtDelete'), redirect('error'));
        if ($company_name->products->count()) {
            \Session::flash('flash_error', 'Can\'t Delete this, ' . $company_name->products->count() . ' nos used in product');
            return redirect()->back();
        } else {
            $company_name->delete();
            \Session::flash('flash_message', 'Successfully Deleted');
            return redirect('company_name');
        }
    }
    protected $rules =
        [
            'title' => 'required|unique:company_names',
        ];

    public function api_add_company(Request $request)
    {
//        if ($request->ajax()) {
//            return $request;
//        }
//        return response()->json(['http']);
//        dd($request);
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        } else {
            $data = new CompanyName();
            $data->title = ($request->title);
            $data->save();
//            $data=CompanyName::orderBy('created_at','desc')->lists('id','title');
            return response()->json($data);
        }
    }

}
