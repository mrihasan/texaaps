<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use DB;
use Config;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => ['clear_all','cache_clear','config_clear','view_clear','view_cache',
            'route_clear','config_cache','route_cache','storage_link','backupDatabase','error','fallback']]);
    }

    public function switchLang($lang)
    {
//        dd($lang);
        if (array_key_exists($lang, Config::get('languages'))) {
            \Session::put('applocale', $lang);
//        dd($lang);
            \Session::flash('flash_success', trans('all_settings.language_changedmsg'));

        }
        return Redirect::back();
    }
    public function switchBranch($branch)
    {
        session()->put('branch', $branch);
        return redirect()->back();
    }
    public function home()
    {
        $user = Auth::user();
        if ($user->user_type_id == 1 ||$user->user_type_id == 2) {
            $settings = DB::table('settings')->first();

            $admin_db['total_product'] = Product::count();
            $admin_db['sales'] = DB::table('invoices')
                ->where('transaction_type', 'Sales')
                ->whereDate('transaction_date', \Carbon\Carbon::now()->format('Y-m-d'))
                ->sum('invoice_total');
            $admin_db['collect'] = DB::table('ledgers')
                ->where('transaction_type_id', 3)
                ->whereDate('transaction_date', \Carbon\Carbon::now()->format('Y-m-d'))
                ->where('reftbl', null )
                ->orWhere('reftbl', 'ledgers')
                ->sum('amount');
            $admin_db['purchase'] = DB::table('invoices')
                ->where('transaction_type', 'Purchase')
                ->whereDate('transaction_date', \Carbon\Carbon::now()->format('Y-m-d'))
                ->sum('invoice_total');
            $admin_db['paid'] = DB::table('ledgers')
                ->where('transaction_type_id', 4)
                ->whereDate('transaction_date', \Carbon\Carbon::now()->format('Y-m-d'))
                ->where('reftbl', null )
                ->orWhere('reftbl', 'ledgers')
                ->sum('amount');
            $admin_db['expense'] = DB::table('expenses')
                ->whereDate('expense_date', \Carbon\Carbon::now()->format('Y-m-d'))
                ->sum('expense_amount');

            $bc = $this->dashboard_barchart();
            $bestSalesQty = $this->bestSaleQty(10,90);
            $bestSalesPrice = $this->bestSalePrice(10,90);
            $lowStockProduct = $this->lowStock(20);
            return view('dashboard.admin', compact(  'bestSalesQty','bestSalesPrice', 'admin_db', 'bc','lowStockProduct'));
        } elseif ($user->user_type_id == 3 ||$user->user_type_id == 4) {
//            return view('dashboard.user');
            return redirect('myprofile');
        } else
            return view('layouts.al305_main');
    }
    private function dashboard_barchart()
    {
        function getLastNDays($days, $format = 'Y-m-d')
        {
            $m = date("m");
            $de = date("d");
            $y = date("Y");
            $dateArray = array();
            for ($i = 0; $i <= $days - 1; $i++) {
                $dateArray[] = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
            }
            return array_reverse($dateArray);
        }

        $day30 = getLastNDays(30);
        $bc['date'] = getLastNDays(30, $format = 'd-M');
        $bar_chart['sales'] = DB::table('invoices')
            ->select(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as td"), DB::raw('sum(invoice_total) as totalAmount'))
            ->where('transaction_type', 'Sales')
            ->groupBy(DB::raw('transaction_date'))
            ->orderBy('transaction_date', 'desc')
            ->get()->toArray();
        $sales_date = [];
        $sales_amount = [];
        $bc['sales'] = [];
        for ($i = 0; $i < count($day30); $i++) {
            foreach ($bar_chart['sales'] as $otp) {
                $sales_date[] = $otp->td;
                $sales_amount[] = $otp->totalAmount;
            }
            if (in_array($day30[$i], $sales_date)) {
                $ii = array_search($day30[$i], $sales_date);
                $bc['sales'][] = $sales_amount[$ii];

            } else
                $bc['sales'][] = 0;
        }
        $bar_chart['purchase'] = DB::table('invoices')
            ->select(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as td"), DB::raw('sum(invoice_total) as totalAmount'))
            ->where('transaction_type', 'Purchase')
//            ->whereIn('transaction_date', $day30)
            ->groupBy(DB::raw('transaction_date'))
            ->orderBy('transaction_date', 'desc')
            ->get()->toArray();
        $purchase_date = [];
        $purchase_amount = [];
        $bc['purchase'] = [];
        for ($i = 0; $i < count($day30); $i++) {
            foreach ($bar_chart['purchase'] as $otp) {
                $purchase_date[] = $otp->td;
                $purchase_amount[] = $otp->totalAmount;
            }
            if (in_array($day30[$i], $purchase_date)) {
                $ii = array_search($day30[$i], $purchase_date);
                $bc['purchase'][] = $purchase_amount[$ii];
            } else
                $bc['purchase'][] = 0;
        }
        $bar_chart['payment'] = DB::table('ledgers')
            ->select(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as td"), DB::raw('sum(amount) as totalAmount'))
            ->where('transaction_type_id', 4)
//            ->whereIn('transaction_date', $day30)
            ->groupBy(DB::raw('transaction_date'))
            ->orderBy('transaction_date', 'desc')
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->get()->toArray();
        $payment_date = [];
        $payment_amount = [];
        $bc['payment'] = [];
        for ($i = 0; $i < count($day30); $i++) {
            foreach ($bar_chart['payment'] as $otp) {
                $payment_date[] = $otp->td;
                $payment_amount[] = $otp->totalAmount;
            }
            if (in_array($day30[$i], $payment_date)) {
                $ii = array_search($day30[$i], $payment_date);
                $bc['payment'][] = $payment_amount[$ii];

            } else
                $bc['payment'][] = 0;
        }
        $bar_chart['receipt'] = DB::table('ledgers')
            ->select(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as td"), DB::raw('sum(amount) as totalAmount'))
            ->where('transaction_type_id', 3)
//            ->whereIn('transaction_date', $day30)
            ->groupBy(DB::raw('transaction_date'))
            ->orderBy('transaction_date', 'desc')
            ->where('reftbl', null )
            ->orWhere('reftbl', 'ledgers')
            ->get()->toArray();
        $receipt_date = [];
        $receipt_amount = [];
        $bc['receipt'] = [];
        for ($i = 0; $i < count($day30); $i++) {
            foreach ($bar_chart['receipt'] as $otp) {
                $receipt_date[] = $otp->td;
                $receipt_amount[] = $otp->totalAmount;
            }
            if (in_array($day30[$i], $receipt_date)) {
                $ii = array_search($day30[$i], $receipt_date);
                $bc['receipt'][] = $receipt_amount[$ii];

            } else
                $bc['receipt'][] = 0;
        }

//        dd($bc);
        return $bc;
//end dashboard chart bar
    }

    private function bestSaleQty($rowLimit, $bestSale_day)
    {
        $bestSalesQty = DB::table('invoice_details')
            ->select(DB::raw('invoice_details.product_id'), DB::raw('sum(invoice_details.qty) as total_qty'), 'products.title')
            ->join('products', 'products.id', '=', 'invoice_details.product_id')
            ->groupBy(DB::raw('invoice_details.product_id'))
            ->where('invoice_details.transaction_type', 'Sales')
            ->whereDate('invoice_details.created_at', '>', Carbon::now()->subDays($bestSale_day))
            ->orderBy('total_qty', 'desc')
            ->orderBy('invoice_details.product_id', 'desc')
            ->limit($rowLimit)
            ->get();
        return $bestSalesQty;
    }
    private function bestSalePrice($rowLimit, $bestSale_day)
    {
        $bestSalesPrice = DB::table('invoice_details')
            ->select(DB::raw('invoice_details.product_id'), DB::raw('sum(invoice_details.line_total) as total_price'), 'products.title')
            ->join('products', 'products.id', '=', 'invoice_details.product_id')
            ->groupBy(DB::raw('invoice_details.product_id'))
            ->where('invoice_details.transaction_type', 'Sales')
            ->whereDate('invoice_details.created_at', '>', Carbon::now()->subDays($bestSale_day))
            ->orderBy('total_price', 'desc')
            ->orderBy('invoice_details.product_id', 'desc')
            ->limit($rowLimit)
            ->get();
        return $bestSalesPrice;
    }

    private function lowStock($rowLimit)
    {
        $active_product = Product::where('status', 'Active')
            ->get();
        $lowStock['product'] = [];
        $lowStock['stock'] = [];
        for ($i = 0; $i < count($active_product); $i++) {
            $in_stock = (DB::table('invoice_details')->where('product_id', $active_product[$i]->id)
                    ->where('transaction_type', 'Purchase')->sum('qty'))
                - (DB::table('invoice_details')->where('product_id', $active_product[$i]->id)
                    ->where('transaction_type', 'Sales')->sum('qty'))
                - (DB::table('invoice_details')->where('product_id', $active_product[$i]->id)
                    ->where('transaction_type', 'Order')->where('status', 2)->sum('qty'));
            $low_stock_alert = DB::table('products')->where('id', $active_product[$i]->id)->sum('low_stock');
            if ($in_stock <= $low_stock_alert) {
                $lowStock['product'][] = $active_product[$i]->title;
                $lowStock['stock'][] = $in_stock;
            }
        }
        array_multisort($lowStock['stock'],$lowStock['product']);
        $lowStockProduct1 = array_combine($lowStock['product'], $lowStock['stock']);
        $lowStockProduct = array_slice($lowStockProduct1, 0, $rowLimit);
        return $lowStockProduct;
    }


}
