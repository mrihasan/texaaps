<?php

namespace App\Http\Controllers;

use App\Models\Expense;
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
            $previous_startYear = $startYear-1;
            $previous_endYear = $endYear-1;
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

            $previous_startYear = $startYear-1;
            $previous_endYear = $endYear-1;
            $previous_startDate = "$previous_startYear-07-01"; // Start date: July 1st
            $previous_endDate = "$previous_endYear-06-30";     // End date: June 30th

        }
        $header_title = 'From ' . Carbon::parse($startDate)->format('d-M-Y') . ' To ' . Carbon::parse($endDate)->format('d-M-Y');

        if (session()->get('branch') != 'all') {

            $total['salesamount'] = DB::table('invoices')
                ->where('branch_id', session()->get('branch'))
                ->where('transaction_type', 'Sales')
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
        $total['otherGain']=00;
        $total['totalSales']=$total['salesamount']+$total['otherGain'];

        $total['purchaseRawmat']=00;
        $total['otherLoss']=00;
        $total['totalPurchases']=$total['purchaseamount']+$total['purchaseRawmat']+$total['otherLoss'];

        $total['paidBankInterest']=00;
        $total['totalExpense']=$total['expense']+$total['paidBankInterest']+$total['salary'];

        $total['salesMargin']=$total['totalSales']-$total['totalPurchases']-$total['totalExpense'];
        $total['incomeTaxvat']=00;
        $total['netsalesMargin']=$total['totalSales']-$total['totalPurchases']-$total['totalExpense'];
        $total['netIncome']=$total['totalSales']-$total['totalPurchases']-$total['totalExpense']-$total['incomeTaxvat'];

        $total['pre_otherGain']=00;
        $total['pre_totalSales']=$total['pre_salesamount']+$total['pre_otherGain'];

        $total['pre_purchaseRawmat']=00;
        $total['pre_otherLoss']=00;
        $total['pre_totalPurchases']=$total['pre_purchaseamount']+$total['pre_purchaseRawmat']+$total['pre_otherLoss'];

        $total['pre_paidBankInterest']=00;
        $total['pre_totalExpense']=$total['pre_expense']+$total['pre_paidBankInterest']+$total['pre_salary'];

        $total['pre_salesMargin']=$total['pre_totalSales']-$total['pre_totalPurchases']-$total['pre_totalExpense'];
        $total['pre_incomeTaxvat']=00;
        $total['pre_netsalesMargin']=$total['pre_totalSales']-$total['pre_totalPurchases']-$total['pre_totalExpense'];
        $total['pre_netIncome']=$total['pre_totalSales']-$total['pre_totalPurchases']-$total['pre_totalExpense']-$total['pre_incomeTaxvat'];
//        dd($total);

        return view('report.income_statement', compact('fiscalYears', 'fiscalYear','startDate','endDate','header_title','total'));
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
            ->union(
                DB::table('invoices')->select(DB::raw('MIN(transaction_date) as min_date'))
            )
            ->union(
                DB::table('expenses')->select(DB::raw('MIN(expense_date) as min_date'))
            )
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
//dd($start);
        // Fetch all expenses within the date range
        $expenses = Expense::where('type','Fixed Asset')->where('expense_date', '>=', $startDate)
            ->where('expense_date', '<=', $endDate)
            ->get();
//dd($expenses);
        $totalDepreciatedExpense = 0;

        // Loop through each expense and calculate its depreciated value
//        foreach ($expenses as $expense) {
//            // Call calculateDepreciation on each expense instance
//            $totalDepreciatedExpense += $expense->calculateDepreciation($startDate, $endDate);
//        }

        foreach ($expenses as $expense) {
            if (method_exists($expense, 'calculateDepreciation')) {
                $totalDepreciatedExpense += $expense->calculateDepreciation($startDate, $endDate);
            } else {
                dd('Method calculateDepreciation does not exist on Expense model');
            }
        }

        // Set additional variables for the balance sheet
        $total['all_assets'] = 00;
        $total['total_stock'] = 00;
        $total['customer_receivable'] = 00;
        $total['fixedAssets'] = $totalDepreciatedExpense;
        // Format the header title and end date for the view
        $header_title = ' Balance Sheet as on ' . Carbon::parse($endDate)->format('d-M-Y');
        $header_subtitle = ' From '.Carbon::parse($startDate)->format('d-M-Y').' to ' . Carbon::parse($endDate)->format('d-M-Y');
        $end_date = Carbon::parse($endDate)->format('d-M-Y');

//        dd($total);
        // Return the view with the required variables
        return view('report.sbalance_sheet', compact('fiscalYears', 'fiscalYear', 'header_title','header_subtitle', 'end_date', 'total'));
    }




}
