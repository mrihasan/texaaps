<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CompanyName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        abort_if(Gate::denies('brand-access'), redirect('error'));
        $brands = Brand::orderBy('title', 'asc')->get();
        return view('brand.index', compact('brands'));
    }

    public function create()
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        return view('brand.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:brands',
//            'company_name_id' => 'required',
        ]);
        $brand = Brand::create($request->all());
        \Session::flash('flash_message', 'Successfully Added');

        return redirect()->route('brand.index');
    }

    public function edit(Brand $brand)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $company_names = CompanyName::where('status', 'Active')->orderBy('title')->pluck('title', 'id');
        return view('brand.edit', compact('brand', 'company_names'));
    }

    public function update(Request $request, Brand $brand)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:brands,title,' . $brand->id . ',id',
        ]);
        $brand->update($request->all());

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect()->route('brand.index');
    }

    public function show(Brand $brand)
    {
        return view('brand.show', compact('brand'));
    }

    public function destroy(Brand $brand)
    {
        abort_if(Gate::denies('ProductMgtDelete'), redirect('error'));
        if ($brand->products->count()) {
            \Session::flash('flash_error', 'Can\'t Delete this, ' . $brand->products->count() . ' nos used in product');
            return redirect()->back();
        } else {

            $brand->delete();
            \Session::flash('flash_message', 'Successfully Deleted');

            return back();
        }
    }

}
