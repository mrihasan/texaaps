<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::fallback([\App\Http\Controllers\SettingController::class, 'fallback']);

Route::get('switchBranch/{branch}', [\App\Http\Controllers\HomeController::class, 'switchBranch'])->name('branchSwitch');
Route::get('lang/{lang}', [\App\Http\Controllers\HomeController::class, 'switchLang'])->name('lang.switch');

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::get('/error', [\App\Http\Controllers\SettingController::class, 'error']);
Route::get('/cache_clear', [\App\Http\Controllers\SettingController::class, 'cache_clear']);
Route::get('/config_clear', [\App\Http\Controllers\SettingController::class, 'config_clear']);
Route::get('/view_clear', [\App\Http\Controllers\SettingController::class, 'view_clear']);
Route::get('/route_clear', [\App\Http\Controllers\SettingController::class, 'route_clear']);
Route::get('/clear_all', [\App\Http\Controllers\SettingController::class, 'clear_all']);
Route::get('/storage_link', [\App\Http\Controllers\SettingController::class, 'storage_link']);
Route::get('/getSeeder/{table}', [\App\Http\Controllers\SettingController::class, 'getSeeder']);
Route::get('/backupDatabase{code}', [\App\Http\Controllers\SettingController::class, 'backupDatabase']);
Route::get('/reftbl_fix/{table}', [\App\Http\Controllers\SettingController::class, 'reftbl_fix'])->name('reftbl_fix');

Route::post('/select_user_action', [\App\Http\Controllers\UserController::class, 'select_user_action'])->name('select_user_action');
Route::patch('/user/Auth::user()', [\App\Http\Controllers\UserController::class, 'password_update'])->name('password_update');
Route::get('/myprofile', [\App\Http\Controllers\UserController::class, 'myprofile'])->name('myprofile');
Route::post('user_balance', '\App\Http\Controllers\UserController@user_balance')->name('user_balance');
Route::post('user_invoices', '\App\Http\Controllers\UserController@user_invoices')->name('user_invoices');
Route::get('addSupplier', [\App\Http\Controllers\UserController::class, 'addSupplier'])->name('addSupplier');
Route::get('manageSupplier', [\App\Http\Controllers\UserController::class, 'manageSupplier'])->name('manageSupplier');
Route::get('addClient', [\App\Http\Controllers\UserController::class, 'addClient'])->name('addClient');
Route::get('manageClient', [\App\Http\Controllers\UserController::class, 'manageClient'])->name('manageClient');
Route::resource('user', \App\Http\Controllers\UserController::class);

Route::resource('permission', \App\Http\Controllers\PermissionController::class);
Route::resource('role', \App\Http\Controllers\RoleController::class);
Route::resource('profile', \App\Http\Controllers\ProfileController::class);
Route::resource('imageprofile', \App\Http\Controllers\ImageProfileController::class);
Route::resource('user-type', \App\Http\Controllers\UserTypeController::class);
Route::resource('company_name', \App\Http\Controllers\CompanyNameController::class);
Route::resource('product_type', \App\Http\Controllers\ProductTypeController::class);
Route::resource('brand', \App\Http\Controllers\BrandController::class);
Route::resource('unit', \App\Http\Controllers\UnitController::class);

Route::get('lowStockProduct', '\App\Http\Controllers\ProductController@lowStockProduct')->name('lowStockProduct');
Route::get('product_stock_report', '\App\Http\Controllers\ProductController@product_stock_report')->name('product_stock_report');
Route::resource('product', \App\Http\Controllers\ProductController::class);
Route::resource('branch', \App\Http\Controllers\BranchController::class);
Route::get('bankac/{tr_type}', '\App\Http\Controllers\BankAccountController@bankac')->name('bankac');
Route::resource('bank_account', \App\Http\Controllers\BankAccountController::class);

Route::put('checked_acledger/{id}','\App\Http\Controllers\BankLedgerController@checked_acledger')->name('checked_acledger');
Route::put('approve_acledger/{id}','\App\Http\Controllers\BankLedgerController@approve_acledger')->name('approve_acledger');
Route::get('/account_transfer', '\App\Http\Controllers\BankLedgerController@account_transfer')->name('account_transfer');
Route::get('/deposit/{t_type}', '\App\Http\Controllers\BankLedgerController@deposit')->name('deposit');
Route::get('/withdraw/{t_type}', '\App\Http\Controllers\BankLedgerController@withdraw')->name('withdraw');
Route::get('/investment_statement', '\App\Http\Controllers\BankLedgerController@investment_statement')->name('investment_statement');
Route::get('/account_statement', '\App\Http\Controllers\BankLedgerController@account_statement')->name('account_statement');
Route::get('/manage_account_ledger', '\App\Http\Controllers\BankLedgerController@manage_account_ledger')->name('manage_account_ledger');
Route::resource('bank_ledger', \App\Http\Controllers\BankLedgerController::class);

//Employee Route________________________________________________________________________
Route::resource('employee', \App\Http\Controllers\EmployeeController::class);
Route::post('payslip_single_employee', '\App\Http\Controllers\EmployeeSalaryController@payslip_single_employee')->name('payslip_single_employee');
Route::post('payslip_all_employee', '\App\Http\Controllers\EmployeeSalaryController@payslip_all_employee')->name('payslip_all_employee');

Route::get('/create_bonus', '\App\Http\Controllers\EmployeeSalaryController@create_bonus')->name('create_bonus');
Route::get('/create_payslip', '\App\Http\Controllers\EmployeeSalaryController@create_payslip')->name('create_payslip');
Route::post('employee_salary/employ_salary_rest', '\App\Http\Controllers\EmployeeSalaryController@employ_salary_rest')->name('employ_salary_rest');
Route::post('employee_salary/employ_salary_value', '\App\Http\Controllers\EmployeeSalaryController@employ_salary_value')->name('employ_salary_value');
Route::resource('employee_salary', '\App\Http\Controllers\EmployeeSalaryController');

//Expense Route________________________________________________________________________
Route::prefix('{module}')->group(function () {
    Route::resource('items', '\App\Http\Controllers\ExpenseTypeController')->parameters(['items' => 'item'])->names([
        'index' => 'module.index',
        'create' => 'module.create',
        'store' => 'module.store',
        'show' => 'module.show',
        'edit' => 'module.edit',
        'update' => 'module.update',
        'destroy' => 'module.destroy',
    ]);
});
//Route::resource('expense_type', '\App\Http\Controllers\ExpenseTypeController');
//Route::get('/expense_approved', '\App\Http\Controllers\ExpenseController@expense_approved')->name('expense_approved');
Route::get('/expense_dt', '\App\Http\Controllers\ExpenseController@expense_dt')->name('expense_dt');
Route::put('checked_expense/{id}','\App\Http\Controllers\ExpenseController@checked_expense')->name('checked_expense');
Route::put('approve_expense/{id}','\App\Http\Controllers\ExpenseController@approve_expense')->name('approve_expense');

Route::prefix('{efa}')->group(function () {
    Route::get('/date_wise_expense', '\App\Http\Controllers\ExpenseController@date_wise_expense')->name('efa.date_wise_expense');
    Route::get('/expense_approved', '\App\Http\Controllers\ExpenseController@expense_approved')->name('efa.expense_approved');
    Route::resource('expense', '\App\Http\Controllers\ExpenseController')->parameters(['expense' => 'item'])->names([
        'index' => 'efa.expenseList',
        'create' => 'efa.expenseCreate',
        'store' => 'efa.store',
        'show' => 'efa.show',
        'edit' => 'efa.edit',
        'update' => 'efa.update',
        'destroy' => 'efa.destroy',
    ]);
});

//Route::resource('expense', '\App\Http\Controllers\ExpenseController')
//    ->name('create', 'expenseCreate')
//    ->name('index', 'expenseList');

Route::resource('setting', '\App\Http\Controllers\SettingController');

Route::patch('attachment_update', [\App\Http\Controllers\InvoiceController::class, 'attachment_update'])->name('attachment_update');
Route::get('invoice_due_report/{type}', '\App\Http\Controllers\InvoiceController@invoice_due_report')->name('invoice_due_report');
Route::resource('invoice', \App\Http\Controllers\InvoiceController::class);
Route::get('invoice/{id}', '\App\Http\Controllers\InvoiceController@show')->name('invoice');
Route::post('/invoice', '\App\Http\Controllers\InvoiceDetailController@store')->name('invoice');
Route::post('/auto_product', '\App\Http\Controllers\InvoiceDetailController@auto_product')->name('auto_product');
Route::post('invoice/{id}/auto_product', '\App\Http\Controllers\InvoiceDetailController@auto_product')->name('auto_product_edit');
Route::post('price_quotation/{id}/auto_product', '\App\Http\Controllers\InvoiceDetailController@auto_product')->name('auto_product_edit');
//Route::post('/auto_sales', '\App\Http\Controllers\InvoiceDetailController@auto_sales')->name('auto_sales');
Route::get('/in_stock_qty', '\App\Http\Controllers\InvoiceDetailController@in_stock_qty')->name('in_stock_qty');
Route::get('/salesTransaction', '\App\Http\Controllers\InvoiceController@salesTransaction')->name('salesTransaction');
Route::get('/salesCreate', '\App\Http\Controllers\InvoiceDetailController@salesCreate')->name('salesCreate');

Route::get('/returnCreate', '\App\Http\Controllers\InvoiceDetailController@returnCreate')->name('returnCreate');
Route::get('/returnTransaction', '\App\Http\Controllers\InvoiceController@returnTransaction')->name('returnTransaction');

Route::get('/getDataTable', '\App\Http\Controllers\InvoiceController@getDataTable')->name('getDataTable');
Route::get('/showDataTable', '\App\Http\Controllers\InvoiceController@showDataTable')->name('showDataTable');

//Route::post('/auto_purchase', '\App\Http\Controllers\InvoiceDetailController@auto_purchase')->name('auto_purchase');
Route::get('/purchaseTransaction', '\App\Http\Controllers\InvoiceController@purchaseTransaction')->name('purchaseTransaction');
Route::get('/purchaseCreate', '\App\Http\Controllers\InvoiceDetailController@purchaseCreate')->name('purchaseCreate');

//Ledger Route________________________________________________________________________
Route::put('checked_ledger/{id}','\App\Http\Controllers\LedgerController@checked_ledger')->name('checked_ledger');
Route::put('approve_ledger/{id}','\App\Http\Controllers\LedgerController@approve_ledger')->name('approve_ledger');

Route::get('/receipt_index', '\App\Http\Controllers\LedgerController@receipt_index')->name('receipt_index');
Route::get('/receipt', '\App\Http\Controllers\LedgerController@receipt')->name('receipt');
Route::get('/payment_index', '\App\Http\Controllers\LedgerController@payment_index')->name('payment_index');
Route::get('/payment', '\App\Http\Controllers\LedgerController@payment')->name('payment');
Route::resource('ledger', '\App\Http\Controllers\LedgerController');
Route::resource('branch_ledger', '\App\Http\Controllers\BranchLedgerController');

Route::put('payment_request_approved/{id}', '\App\Http\Controllers\PaymentRequestController@payment_request_approved')->name('payment_request_approved');
Route::put('payment_request_checked/{id}', '\App\Http\Controllers\PaymentRequestController@payment_request_checked')->name('payment_request_checked');
Route::resource('payment_request', '\App\Http\Controllers\PaymentRequestController');

Route::get('/pq2bill/{id}', '\App\Http\Controllers\PriceQuotationController@pq2bill')->name('pq2bill');
Route::get('/pqCreate', '\App\Http\Controllers\PriceQuotationController@pqCreate')->name('pqCreate');
Route::resource('price_quotation', '\App\Http\Controllers\PriceQuotationController');

//Report Route________________________________________________________________________
Route::get('/datewise_expense_summary_home', '\App\Http\Controllers\ReportController@datewise_expense_summary_home')->name('datewise_expense_summary_home');
Route::get('/datewise_expense_summary', '\App\Http\Controllers\ReportController@datewise_expense_summary')->name('datewise_expense_summary');
Route::get('/datewise_expense_details/{date}', '\App\Http\Controllers\ReportController@datewise_expense_details')->name('datewise_expense_details');
Route::get('/typewise_expense_summary_home', '\App\Http\Controllers\ReportController@typewise_expense_summary_home')->name('typewise_expense_summary_home');
Route::get('/typewise_expense_summary', '\App\Http\Controllers\ReportController@typewise_expense_summary')->name('typewise_expense_summary');
Route::get('/typewise_expense_details/{type}', '\App\Http\Controllers\ReportController@typewise_expense_details')->name('typewise_expense_details');
Route::get('/expense_details_home', '\App\Http\Controllers\ReportController@expense_details_home')->name('expense_details_home');
Route::get('/expense_details_daterange', '\App\Http\Controllers\ReportController@expense_details_daterange')->name('expense_details_daterange');
Route::get('/ledger_report_home', '\App\Http\Controllers\ReportController@ledger_report_home')->name('ledger_report_home');
Route::get('/ledger_report_user', '\App\Http\Controllers\ReportController@ledger_report_user')->name('ledger_report_user');
Route::get('/ledger_report_account', '\App\Http\Controllers\ReportController@ledger_report_account')->name('ledger_report_account');
Route::get('/customer_report', '\App\Http\Controllers\ReportController@customer_report')->name('customer_report');
Route::get('/supplier_report', '\App\Http\Controllers\ReportController@supplier_report')->name('supplier_report');
Route::get('/balance_report', '\App\Http\Controllers\ReportController@balance_report')->name('balance_report');
Route::get('/balance_sheet', '\App\Http\Controllers\ReportController@balance_sheet')->name('balance_sheet');

