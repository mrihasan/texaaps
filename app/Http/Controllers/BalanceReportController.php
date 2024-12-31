<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class BalanceReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function income_statement(Request $request)
    {
//        dd($request->fiscal_year);

        if ($request->fiscal_year == null) {
            // Calculate the current fiscal year based on today's date
            $currentMonth = date('n'); // Get the current month (1 for January, 12 for December)
            $currentYear = date('Y');
// Determine the current fiscal year and set default start and end dates
            if ($currentMonth >= 7) {
                // If month is July (7) or later, fiscal year starts in the current year
                $startYear = $currentYear;
                $endYear = $currentYear + 1;
                $fiscalYear = 'FY ' . $currentYear . "-" . ($currentYear + 1);
            } else {
                // If month is before July, fiscal year started in the previous year
                $startYear = $currentYear - 1;
                $endYear = $currentYear;
                $fiscalYear = 'FY ' . ($currentYear - 1) . "-" . $currentYear;
            }
            // Generate fiscal years starting from the current fiscal year
            $fiscalYears = $this->getFiscalYears($currentYear, 10); // Generate 10 fiscal years for example

            // Set the start and end dates for the fiscal year
            $startDate = "$startYear-07-01"; // Start date: July 1st
            $endDate = "$endYear-06-30";     // End date: June 30th
            $previous_startYear = $startYear - 1;
            $previous_endYear = $endYear - 1;
            $previous_startDate = "$previous_startYear-07-01"; // Start date: July 1st
            $previous_endDate = "$previous_endYear-06-30";     // End date: June 30th

        } else {
            $selectedFiscalYear = $request->input('fiscal_year');
            // Extract the start and end year from the selected fiscal year
            list($startYear, $endYear) = sscanf($selectedFiscalYear, 'FY %d-%d');
            // Create start and end dates
            $startDate = "$startYear-07-01"; // Start date: July 1st of start year
            $endDate = "$endYear-06-30"; // End date: June 30th of end year

            $fiscalYear = 'FY ' . ($startYear) . "-" . $endYear;
            $currentYear = date('Y');
            $ny = $currentYear - $startYear + 10;
            $fiscalYears = $this->getFiscalYears($currentYear, $ny); // Generate 10 fiscal years for example

            $previous_startYear = $startYear - 1;
            $previous_endYear = $endYear - 1;
            $previous_startDate = "$previous_startYear-07-01"; // Start date: July 1st
            $previous_endDate = "$previous_endYear-06-30";     // End date: June 30th

        }
        $header_title = 'From ' . Carbon::parse($startDate)->format('d-M-Y') . ' To ' . Carbon::parse($endDate)->format('d-M-Y');

//        dd($startDate.'---'. $endDate.'---'.$previous_startDate.'---'. $previous_endDate);
//        dd($previous_startDate.'---'. $previous_endDate);
//dd(Invoice::invoiceSmallestDate());

        $total = $this->lossProfitCalculation($startDate, $endDate, $previous_startDate, $previous_endDate);

        return view('report.income_statement', compact('fiscalYears', 'fiscalYear', 'startDate', 'endDate', 'header_title', 'total'));
    }

    function lossProfitCalculation($startDate, $endDate, $previous_startDate, $previous_endDate)
    {
        $total['openingStock'] = $this->productStockReportAi(Invoice::invoiceSmallestDate(), $previous_endDate);
        $total['closingStock'] = $this->productStockReportAi($startDate, $endDate);

        $total['pre_openingStock'] = $this->productStockReportAi(Invoice::invoiceSmallestDate(), Carbon::parse($previous_endDate)->subYear()->format('Y-m-d'));
        $total['pre_closingStock'] = $this->productStockReportAi($previous_startDate, $previous_endDate);
//        dd($total['openingStock']);
        if (session()->get('branch') != 'all') {

            $total['salesamount'] = DB::table('invoices')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Sales')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('invoice_total');
            $total['returnamount'] = DB::table('invoices')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Return')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('invoice_total');
            $total['purchaseamount'] = DB::table('invoices')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Purchase')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('invoice_total');
            $total['expense'] = DB::table('expenses')
                ->where('branch_id', session()->get('branch'))
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->sum('expense_amount');
            $total['salary'] = DB::table('employee_salaries')
                ->where('branch_id', session()->get('branch'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('paidsalary_amount');

            $total['pre_salesamount'] = DB::table('invoices')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Sales')
                ->whereBetween('transaction_date', [$previous_startDate, $previous_endDate])
                ->sum('invoice_total');
            $total['pre_returnamount'] = DB::table('invoices')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Return')
                ->whereBetween('transaction_date', [$previous_startDate, $previous_endDate])
                ->sum('invoice_total');
            $total['pre_purchaseamount'] = DB::table('invoices')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Purchase')
                ->whereBetween('transaction_date', [$previous_startDate, $previous_endDate])
                ->sum('invoice_total');
            $total['pre_expense'] = DB::table('expenses')
                ->where('branch_id', session()->get('branch'))
                ->whereBetween('expense_date', [$previous_startDate, $previous_endDate])
                ->sum('expense_amount');
            $total['pre_salary'] = DB::table('employee_salaries')
                ->where('branch_id', session()->get('branch'))
                ->whereBetween('created_at', [$previous_startDate, $previous_endDate])
                ->sum('paidsalary_amount');
        } else {
            $total['salesamount'] = DB::table('invoices')
                ->where('transaction_type', 'Sales')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('invoice_total');
            $total['returnamount'] = DB::table('invoices')
                ->where('transaction_type', 'Return')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('invoice_total');
            $total['purchaseamount'] = DB::table('invoices')
                ->where('transaction_type', 'Purchase')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('invoice_total');
            $total['expense'] = DB::table('expenses')
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->sum('expense_amount');
            $total['salary'] = DB::table('employee_salaries')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('paidsalary_amount');

            $total['pre_salesamount'] = DB::table('invoices')
                ->where('transaction_type', 'Sales')
                ->whereBetween('transaction_date', [$previous_startDate, $previous_endDate])
                ->sum('invoice_total');
            $total['pre_returnamount'] = DB::table('invoices')
                ->where('transaction_type', 'Return')
                ->whereBetween('transaction_date', [$previous_startDate, $previous_endDate])
                ->sum('invoice_total');
            $total['pre_purchaseamount'] = DB::table('invoices')
                ->where('transaction_type', 'Purchase')
                ->whereBetween('transaction_date', [$previous_startDate, $previous_endDate])
                ->sum('invoice_total');
            $total['pre_expense'] = DB::table('expenses')
                ->whereBetween('expense_date', [$previous_startDate, $previous_endDate])
                ->sum('expense_amount');
            $total['pre_salary'] = DB::table('employee_salaries')
                ->whereBetween('created_at', [$previous_startDate, $previous_endDate])
                ->sum('paidsalary_amount');
        }

        $total['netSales'] = $total['salesamount'] + $total['returnamount'];
        $total['totalStock'] = $total['purchaseamount'] + $total['openingStock'];
        $total['balanceStock'] = $total['totalStock'] - $total['closingStock'];
        $total['grossProfit'] = $total['netSales'] - $total['balanceStock'];
        $total['totalExpense'] = $total['expense'] + $total['salary'];
        $total['operatingProfit'] = $total['grossProfit'] - $total['totalExpense'];
        $total['bankCharge'] = 00;
        $total['netProfitBeforeTax'] = $total['operatingProfit'] - $total['bankCharge'];
        $total['incomeTaxPaid'] = 00;
        $total['netProfitForTheYear'] = $total['netProfitBeforeTax'] - $total['incomeTaxPaid'];

        $total['pre_netSales'] = $total['pre_salesamount'] + $total['pre_returnamount'];
        $total['pre_totalStock'] = $total['pre_purchaseamount'] + $total['pre_openingStock'];
        $total['pre_balanceStock'] = $total['pre_totalStock'] - $total['pre_closingStock'];
        $total['pre_grossProfit'] = $total['pre_netSales'] - $total['pre_balanceStock'];
        $total['pre_totalExpense'] = $total['pre_expense'] + $total['pre_salary'];
        $total['pre_operatingProfit'] = $total['pre_grossProfit'] - $total['pre_totalExpense'];
        $total['pre_bankCharge'] = 00;
        $total['pre_netProfitBeforeTax'] = $total['pre_operatingProfit'] - $total['pre_bankCharge'];
        $total['pre_incomeTaxPaid'] = 00;
        $total['pre_netProfitForTheYear'] = $total['pre_netProfitBeforeTax'] - $total['pre_incomeTaxPaid'];
        $total['balanceTransferred'] = $total['netProfitForTheYear'] + $total['pre_netProfitForTheYear'];

        return $total;

    }

    // Function to get fiscal years
    function getFiscalYears($startYear, $numberOfYears)
    {
        $fiscalYears = [];
        for ($i = 0; $i < $numberOfYears; $i++) {
            $fiscalYears[] = "FY " . ($startYear - $i) . "-" . ($startYear - $i + 1);
        }
        return $fiscalYears;
    }

    function generateFiscalYears($smallDate)
    {
        // Parse the smallest date
        $startYear = Carbon::parse($smallDate)->year;
        $startMonth = Carbon::parse($smallDate)->month;
        if ($startMonth < 7) {
            $startYear -= 1;
        }
        $currentYear = Carbon::now()->year;
        // If the current month is before July, we consider the previous fiscal year as current
        $currentMonth = Carbon::now()->month;
        if ($currentMonth < 7) {
            $currentYear -= 1;
        }
        $fiscalYears = [];
        // Generate fiscal years from the start year to the current fiscal year
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $fiscalYears[] = "FY $year-" . ($year + 1);
        }
        return array_reverse($fiscalYears); // Reverse so that the most recent year appears first
    }


    public function sbalance_sheet(Request $request)
    {
        // Retrieve the smallest transaction date
        $smallestDate = DB::table('ledgers')
            ->select(DB::raw('MIN(transaction_date) as min_date'))
            ->union(DB::table('invoices')->select(DB::raw('MIN(transaction_date) as min_date')))
            ->union(DB::table('expenses')->select(DB::raw('MIN(expense_date) as min_date')))
            ->orderBy('min_date', 'asc')
            ->first();
        // Set the smallest start date
        $startDate = Carbon::parse($smallestDate->min_date)->format('Y-m-d');
        // Generate fiscal years starting from the smallest date
        $fiscalYears = $this->generateFiscalYears($startDate);
        // Handle fiscal year selection or use current fiscal year by default
        if ($request->fiscal_year == null) {
            // Get the current month and year
            $currentMonth = date('n');
            $currentYear = date('Y');
            // Determine the current fiscal year
            if ($currentMonth >= 7) {
                $endYear = $currentYear + 1;
                $fiscalYear = 'FY ' . $currentYear . "-" . $endYear;
            } else {
                $endYear = $currentYear;
                $fiscalYear = 'FY ' . ($currentYear - 1) . "-" . $currentYear;
            }
            // Set default end date
            $endDate = "$endYear-06-30";
        } else {
            // If fiscal year is selected, process it
            $selectedFiscalYear = $request->input('fiscal_year');
            list($startYear, $endYear) = sscanf($selectedFiscalYear, 'FY %d-%d');
            // Create end date for the selected fiscal year
            $endDate = "$endYear-06-30";
            $fiscalYear = 'FY ' . $startYear . "-" . $endYear;
        }
        // Convert to Carbon instances for comparison
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        // Fetch all expenses within the date range
        $query = Expense::where('type', 'Fixed Asset')->where('expense_date', '>=', $startDate)
            ->where('expense_date', '<=', $endDate);
//            ->get();
        if (session()->get('branch') != 'all') {
            $query->where('branch_id', session()->get('branch'))->get();
        }
        $expenses = $query->get();

        $totalDepreciatedExpense = 0;
        // Loop through each expense and calculate its depreciated value
        foreach ($expenses as $expense) {
            if (method_exists($expense, 'calculateDepreciation')) {
                $totalDepreciatedExpense += $expense->calculateDepreciation($startDate, $endDate);
            } else {
                dd('Method calculateDepreciation does not exist on Expense model');
            }
        }
        // Set additional variables for the balance sheet
        $total['fixedAssets'] = $totalDepreciatedExpense;
        $total['total_stock'] = $this->productStockReportAi($startDate, $endDate);
        $total['customer_receivable'] = $this->customerReceivable($startDate, $endDate);
        $total['cash_balance'] = $this->bankBalance('Petty Cash', $startDate, $endDate);
        $total['bank_balance'] = $this->bankBalance('Bank Account', $startDate, $endDate);
        $total['all_assets'] = $total['fixedAssets'] + $total['total_stock'] + $total['customer_receivable'] + $total['cash_balance'] + $total['bank_balance'];

        $total['investment'] = $this->totalInvestment($startDate, $endDate);
        $total['drawingbyDirectors'] = $this->profitShare($startDate, $endDate);
        $total['accumulatedProfit'] = $this->accumulated_loss_profit($fiscalYear);
        $total['total_equity'] = $total['investment'] + $total['accumulatedProfit']+$total['drawingbyDirectors'];

        $total['bank_loan'] = $this->bankLoan($startDate, $endDate);
        $total['director_loan'] = 0;
        $total['non_current_liability'] = $total['bank_loan']['loan'] + $total['director_loan'];

        $total['supplier_payable'] = $this->supplierPayable($startDate, $endDate);
        $total['liabilitiesForExpenses'] = 0;
        $total['current_liability'] = $total['liabilitiesForExpenses'] + $total['supplier_payable'];

        $total['total_liability'] = $total['non_current_liability'] + $total['current_liability'];

        $total['total_equity_liability'] = $total['total_equity'] + $total['total_liability'];

        // Format the header title and end date for the view
        $header_title = ' Balance Sheet as on ' . Carbon::parse($endDate)->format('d-M-Y');
        $header_subtitle = ' From ' . Carbon::parse($startDate)->format('d-M-Y') . ' to ' . Carbon::parse($endDate)->format('d-M-Y');
        $end_date = Carbon::parse($endDate)->format('d-M-Y');

//        dd($total['bank_loan']);
        // Return the view with the required variables
        return view('report.sbalance_sheet', compact('fiscalYears', 'fiscalYear', 'header_title', 'header_subtitle', 'end_date', 'total'));
    }

    function productStockReportAi($startDate, $endDate)
    {
        $branchId = session()->get('branch');
        $hasBranchFilter = $branchId !== 'all';

        $totalValueSum = 0;
        $products = Product::with('inventory_details')->orderBy('title', 'asc')->get();

        foreach ($products as $product) {
            $invoiceFilter = function ($query) use ($startDate, $endDate, $branchId, $hasBranchFilter) {
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
                if ($hasBranchFilter) {
                    $query->where('branch_id', $branchId);
                }
            };

            $totalPurchase = $product->inventory_details()
                ->where('transaction_type', 'Purchase')
                ->whereHas('invoice', $invoiceFilter)
                ->sum('qty');

            $totalSales = $product->inventory_details()
                ->where('transaction_type', 'Sales')
                ->whereHas('invoice', $invoiceFilter)
                ->sum('qty');

            $valuePurchase = $product->inventory_details()
                ->where('transaction_type', 'Purchase')
                ->whereHas('invoice', $invoiceFilter)
                ->sum('line_total');

            $valueSales = $product->inventory_details()
                ->where('transaction_type', 'Sales')
                ->whereHas('invoice', $invoiceFilter)
                ->sum('line_total');

            $lastPurchaseValue = $product->inventory_details()
                    ->where('transaction_type', 'Purchase')
                    ->whereHas('invoice', $invoiceFilter)
                    ->with(['invoice' => function ($query) {
                        $query->orderBy('transaction_date', 'desc');
                    }])
                    ->orderBy('id', 'desc')
                    ->first()
                    ->ubuy_price ?? 0;

            $product->lastPurchaseValue = $lastPurchaseValue;
            $product->totalPurchase = $totalPurchase;
            $product->totalSales = $totalSales;
            $product->stock = $totalPurchase - $totalSales;
            $product->totalPurchaseValue = $valuePurchase;
            $product->totalSalesValue = $valueSales;
            $product->totalValue = $product->stock * $lastPurchaseValue;

            $totalValueSum += $product->totalValue;
        }

        return $totalValueSum;
    }

    function customerReceivable($startDate, $endDate)
    {
        $branch = session()->get('branch');
        $branchCondition = $branch !== 'all' ? ['branch_id' => $branch] : [];

        $ledgers_receipt = DB::table('ledgers')
            ->join('users', 'users.id', '=', 'ledgers.user_id')
            ->where('users.user_type_id', 3)
            ->whereBetween('ledgers.transaction_date', [$startDate, $endDate])
            ->whereIn('ledgers.transaction_type_id', [1, 3])
            ->where($branchCondition)
            ->sum('amount');

        $ledgers_payment = DB::table('ledgers')
            ->join('users', 'users.id', '=', 'ledgers.user_id')
            ->where('users.user_type_id', 3)
            ->whereBetween('ledgers.transaction_date', [$startDate, $endDate])
            ->whereIn('ledgers.transaction_type_id', [2, 4])
            ->where($branchCondition)
            ->sum('amount');

        $invoice_sales = DB::table('invoices')
            ->join('users', 'users.id', '=', 'invoices.user_id')
            ->where('users.user_type_id', 3)
            ->whereBetween('invoices.transaction_date', [$startDate, $endDate])
            ->where('invoices.transaction_type', 'Sales')
            ->where($branchCondition)
            ->sum('invoices.invoice_total');

        $invoice_return = DB::table('invoices')
            ->join('users', 'users.id', '=', 'invoices.user_id')
            ->where('users.user_type_id', 3)
            ->whereBetween('invoices.transaction_date', [$startDate, $endDate])
            ->where('invoices.transaction_type', 'Return')
            ->where($branchCondition)
            ->sum('invoices.invoice_total');

        $receivable = $invoice_sales - $invoice_return - $ledgers_receipt + $ledgers_payment;
        return $receivable;
    }

    function supplierPayable($startDate, $endDate)
    {
        $branch = session()->get('branch');
        $branchCondition = $branch !== 'all' ? ['branch_id' => $branch] : [];

        $ledgers_receipt = DB::table('ledgers')
            ->join('users', 'users.id', 'ledgers.user_id')
            ->where('users.user_type_id', 4)
            ->where('ledgers.transaction_date', '>=', $startDate)
            ->where('ledgers.transaction_date', '<=', $endDate)
            ->whereIn('ledgers.transaction_type_id', [1, 3])
            ->where($branchCondition)
            ->sum('amount');
        $ledgers_payment = DB::table('ledgers')
            ->join('users', 'users.id', 'ledgers.user_id')
            ->where('users.user_type_id', 4)
            ->where('ledgers.transaction_date', '>=', $startDate)
            ->where('ledgers.transaction_date', '<=', $endDate)
            ->whereIn('ledgers.transaction_type_id', [2, 4])
            ->where($branchCondition)
            ->sum('amount');
        $invoice_purchase = DB::table('invoices')
            ->join('users', 'users.id', 'invoices.user_id')
            ->where('users.user_type_id', 4)
            ->where('invoices.transaction_date', '>=', $startDate)
            ->where('invoices.transaction_date', '<=', $endDate)
            ->where('invoices.transaction_type', 'Purchase')
            ->where($branchCondition)
            ->sum('invoices.invoice_total');
        $invoice_return = DB::table('invoices')
            ->join('users', 'users.id', 'invoices.user_id')
            ->where('users.user_type_id', 4)
            ->where('invoices.transaction_date', '>=', $startDate)
            ->where('invoices.transaction_date', '<=', $endDate)
            ->where('invoices.transaction_type', 'Put Back')
            ->where($branchCondition)
            ->sum('invoices.invoice_total');
        $receivable = $invoice_purchase + $invoice_return + $ledgers_receipt - $ledgers_payment;
        return $receivable;
    }

    function bankBalance($accType, $startDate, $endDate)
    {
        $branch = session()->get('branch');
        $branchCondition = $branch !== 'all' ? ['bank_ledgers.branch_id' => $branch] : [];

        $bd_bank_credit = DB::table('bank_ledgers')
            ->join('bank_accounts', 'bank_accounts.id', '=', 'bank_ledgers.bank_account_id')
            ->where('bank_accounts.account_type', $accType)
            ->whereIn('transaction_type_id', [1, 3, 5, 8, 10])
            ->where('bank_ledgers.transaction_date', '>=', $startDate)
            ->where('bank_ledgers.transaction_date', '<=', $endDate)
            ->where($branchCondition)
            ->sum('bank_ledgers.amount');
        $bd_bank_debit = DB::table('bank_ledgers')
            ->join('bank_accounts', 'bank_accounts.id', '=', 'bank_ledgers.bank_account_id')
            ->where('bank_accounts.account_type', $accType)
            ->whereIn('transaction_type_id', [2, 4, 6, 9, 11])
            ->where('bank_ledgers.transaction_date', '>=', $startDate)
            ->where('bank_ledgers.transaction_date', '<=', $endDate)
            ->where($branchCondition)
            ->sum('bank_ledgers.amount');
        $balance = $bd_bank_credit - $bd_bank_debit;

        return $balance;
    }

    function totalInvestment($startDate, $endDate)
    {
        $branch = session()->get('branch');
        $branchCondition = $branch !== 'all' ? ['bank_ledgers.branch_id' => $branch] : [];
        $investment = DB::table('bank_ledgers')
            ->join('bank_accounts', 'bank_accounts.id', '=', 'bank_ledgers.bank_account_id')
            ->where('bank_ledgers.transaction_type_id', 10)
            ->where('bank_ledgers.transaction_date', '>=', $startDate)
            ->where('bank_ledgers.transaction_date', '<=', $endDate)
            ->where($branchCondition)
            ->sum('bank_ledgers.amount');
        return $investment;
    }
    function profitShare($startDate, $endDate)
    {
        $branch = session()->get('branch');
        $branchCondition = $branch !== 'all' ? ['bank_ledgers.branch_id' => $branch] : [];
        $investment = DB::table('bank_ledgers')
            ->join('bank_accounts', 'bank_accounts.id', '=', 'bank_ledgers.bank_account_id')
            ->where('bank_ledgers.transaction_type_id', 11)
            ->where('bank_ledgers.transaction_date', '>=', $startDate)
            ->where('bank_ledgers.transaction_date', '<=', $endDate)
            ->where($branchCondition)
            ->sum('bank_ledgers.amount');
        return $investment;
    }

    function accumulated_loss_profit($fiscal_year)
    {
//        dd($fiscal_year);

        $selectedFiscalYear = $fiscal_year;
        // Extract the start and end year from the selected fiscal year
        list($startYear, $endYear) = sscanf($selectedFiscalYear, 'FY %d-%d');
        // Create start and end dates
        $startDate = "$startYear-07-01"; // Start date: July 1st of start year
        $endDate = "$endYear-06-30"; // End date: June 30th of end year

        $previous_startYear = $startYear - 1;
        $previous_endYear = $endYear - 1;
        $previous_startDate = "$previous_startYear-07-01"; // Start date: July 1st
        $previous_endDate = "$previous_endYear-06-30";     // End date: June 30th
//dd($startDate.' - '.$endDate.'------'.$previous_startDate.'--'.$previous_endDate);

        $accumulated_loss_profit=$this->lossProfitCalculation($startDate, $endDate, $previous_startDate, $previous_endDate);
//        dd($accumulated_loss_profit['balanceTransferred']);
        return $accumulated_loss_profit['balanceTransferred'];
    }

    function bankLoan($startDate, $endDate)
    {
        $branch = session()->get('branch');
        $branchCondition = $branch !== 'all' ? ['bank_ledgers.branch_id' => $branch] : [];

        $bank['loan'] = DB::table('bank_ledgers')
            ->join('bank_accounts', 'bank_accounts.id', '=', 'bank_ledgers.bank_account_id')
            ->where('bank_accounts.account_type', 'Loan Account')
            ->whereIn('transaction_type_id', [5])
            ->where('bank_ledgers.transaction_date', '>=', $startDate)
            ->where('bank_ledgers.transaction_date', '<=', $endDate)
            ->where($branchCondition)
            ->sum('bank_ledgers.amount');
        $bank['loan_payment'] = DB::table('bank_ledgers')
            ->join('bank_accounts', 'bank_accounts.id', '=', 'bank_ledgers.bank_account_id')
            ->where('bank_accounts.account_type', 'Loan Account')
            ->whereIn('transaction_type_id', [6])
            ->where('bank_ledgers.transaction_date', '>=', $startDate)
            ->where('bank_ledgers.transaction_date', '<=', $endDate)
            ->where($branchCondition)
            ->sum('bank_ledgers.amount');
        $bank['loan_payable'] = $bank['loan'] - $bank['loan_payment'];
        return $bank;
    }


}
