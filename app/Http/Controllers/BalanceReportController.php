<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

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
        }
        $header_title = 'Income Statement From ' . Carbon::parse($startDate)->format('d-M-Y') . ' To ' . Carbon::parse($endDate)->format('d-M-Y');
        return view('report.income_statement', compact('fiscalYears', 'fiscalYear','startDate','endDate','header_title'));
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

}
