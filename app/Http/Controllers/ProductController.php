<?php

namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Models\Brand;
use App\Models\CompanyName;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Unit;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(ProductsDataTable $dataTable)
    {
//        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
//        $products = Product::orderBy('title', 'asc')->get();
//        return view('product.index', compact('products'));
        $title = __('all_settings.Product List');
        return $dataTable->render('product.index', compact(['title']));
    }
    public function product_stock_report()
    {
        $products = Product::with('inventory_details')->orderBy('title','asc')->get();
        foreach ($products as $product) {
//            $totalStock = $product->inventory_details()->where('transaction_type', 'Purchase')->sum('qty');
//            $totalStock -= $product->inventory_details()->where('transaction_type', 'Sales')->sum('qty');
//            $product->stock = $totalStock;
            $totalPurchase = $product->inventory_details()->where('transaction_type', 'Purchase')->sum('qty');
            $totalSales = $product->inventory_details()->where('transaction_type', 'Sales')->sum('qty');
            $product->totalPurchase = $totalPurchase;
            $product->totalSales = $totalSales;
            $product->stock = $totalPurchase-$totalSales;
        }
        $header_title='Product stock report';
        return view('report.product_stock_report', compact('products','header_title'));
    }

    public function create()
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $company_names = CompanyName::where('status', 'Active')->get();
        $brands = Brand::where('status', 'Active')->get();
        $product_types = ProductType::where('status', 'Active')->get();
        $units = Unit::where('status', 'Active')->get();
        return view('product.create', compact('company_names', 'brands', 'product_types', 'units'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:products',
            'product_type_id' => 'required',
            'unit_id' => 'required',
            'low_stock' => 'required',
//            'company_name_id' => 'required',
        ]);
        $product = Product::create($request->all());
        \Session::flash('flash_message', 'Successfully Added');

        return redirect()->route('product.index');
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $company_names = CompanyName::where('status', 'Active')->orderBy('title')->pluck('title', 'id');
        $brands = Brand::where('status', 'Active')->orderBy('title')->pluck('title', 'id');
        $product_types = ProductType::where('status', 'Active')->orderBy('title')->pluck('title', 'id');
        $units = Unit::where('status', 'Active')->orderBy('title')->pluck('title', 'id');
        return view('product.edit', compact('product', 'company_names', 'brands', 'product_types', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $this->validate($request, [
            'title' => 'required|unique:products,title,' . $product->id . ',id',
            'product_type_id' => 'required',
            'unit_id' => 'required',
            'low_stock' => 'required',
        ]);
        $product->update($request->all());

        \Session::flash('flash_message', 'Successfully Updated');
        return redirect('product/' . $product->id);
    }

    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('ProductMgtDelete'), redirect('error'));
        if ($product->inventory_details->count()) {
            \Session::flash('error_message', 'Can\'t Delete this, ' . $product->inventory_details->count() . ' nos used');
            return redirect()->back();
        } else {

            $product->delete();
            \Session::flash('flash_message', 'Successfully Deleted');

            return redirect()->route('product.index');
        }
    }

//    private function lowStockProduct($rowLimit)

    public function lowStockProduct()
    {
        abort_if(Gate::denies('ProductMgtAccess'), redirect('error'));
        $active_product = Product::where('status', 'Active')->get();
        $product['title'] = [];
//        $product['company_name'] = [];
        $product['type'] = [];
//        $product['brand'] = [];
        $product['low_stock'] = [];
        $product['stock'] = [];
        for ($i = 0; $i < count($active_product); $i++) {
            $in_stock = (DB::table('invoice_details')->where('product_id', $active_product[$i]->id)
                    ->where('transaction_type', 'Purchase')->sum('qty'))
                - (DB::table('invoice_details')->where('product_id', $active_product[$i]->id)
                    ->where('transaction_type', 'Sales')->sum('qty'))
                - (DB::table('invoice_details')->where('product_id', $active_product[$i]->id)
                    ->where('transaction_type', 'Order')->where('status', 2)->sum('qty'));
            $product_info = \App\Models\Product::where('id', $active_product[$i]->id)->first();
            if ($in_stock <= $product_info->low_stock) {
                $product['title'][] = $product_info->title;
//                $product['company_name'][] = $product_info->company_name->title??'';
                $product['type'][] = $product_info->product_type->title??'';
//                $product['brand'][] = $product_info->brand->title??'';
                $product['low_stock'][] = $product_info->low_stock;
                $product['stock'][] = $in_stock;
            }
        }
        array_multisort($product['stock'], $product['title'], $product['type']);
//        dd($product);
        $products = [];
        for ($j = 0; $j < count($product['title']); $j++) {
            $products[] = ['title' => $product['title'][$j], 'type' => $product['type'][$j],
                 'low_stock' => $product['low_stock'][$j], 'stock' => $product['stock'][$j]];
        }
//        dd($products);
        return view('product.index_lowstock', compact('products'));

    }

}
