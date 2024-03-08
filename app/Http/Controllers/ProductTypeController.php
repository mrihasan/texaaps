<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class ProductTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $product_types = ProductType::orderBy('title','asc')->get();
        return view('product_type.index', compact('product_types'));
    }

    public function create()
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        return view('product_type.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:product_types',
        ]);
        $product_type = ProductType::create($request->all());
        \Session::flash('flash_message', 'Successfully Added');

        return redirect()->route('product_type.index');
    }

    public function edit(ProductType $product_type)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        return view('product_type.edit', compact('product_type'));
    }

    public function update(Request $request, ProductType $product_type)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:product_types,title,'. $product_type->id . ',id',
        ]);
        $product_type->update($request->all());

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect()->route('product_type.index');
    }

    public function show(ProductType $product_type)
    {
        return view('product_type.show', compact('product_type'));
    }

    public function destroy(ProductType $product_type)
    {
//        dd($product_type->products);
        abort_if(Gate::denies('ProductMgtDelete'), redirect('error'));
        if ($product_type->products->count()) {
            \Session::flash('error_message', 'Can\'t Delete this, ' . $product_type->products->count() . ' nos used');
            return redirect()->back();
        } else {
            $product_type->delete();
        \Session::flash('flash_message', 'Successfully Deleted');
            return redirect()->route('product_type.index');
    }
    }

}
