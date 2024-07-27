<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        {{--<img src="{!! asset('images/eisLogoTaefc05.png')!!}" alt="Logo"--}}
        {{--class="brand-image img-circle elevation-3"--}}
        {{--style="opacity: .8">--}}
        <span class="brand-text font-weight-light">{{ config('app.name', 'EIS') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact " data-widget="treeview"
                role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview d-none">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-language " style="color: yellow"></i>
                        <p style="color: yellow">
                            {{ Config::get('languages')[App::getLocale()] }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach (Config::get('languages') as $lang => $language)
                            @if ($lang != App::getLocale())
                                <li class="nav-item">
                                    <a href="{{ route('lang.switch', $lang) }}" class="nav-link">
                                        <i class="fa fa-language nav-icon"></i>
                                        <p>{{$language}}</p>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item @yield('dashboard_mo')">
                    <a href="{{ route('home') }}" class="nav-link @yield('dashboard')">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            {{ __('all_settings.Dashboard') }}
                        </p>
                    </a>
                </li>


                <li class="nav-item has-treeview @yield('supply_mo')">
                    <a href="#" class="nav-link @yield('supply')">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>
                            {{ __('all_settings.Manage Supply') }}
                            <i class="fas fa-angle-left right"></i>
                            {{--<span class="badge badge-info right">6</span>--}}
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('pqCreate') }}" class="nav-link @yield('add_price_quotation')">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Price Quotation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('price_quotation') }}" class="nav-link @yield('manage_price_quotation')">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Price Quotation</p>
                            </a>
                        </li>

                    @can('SupplyAccess')
                            <li class="nav-item">
                                <a href="{{ url('salesCreate') }}" class="nav-link @yield('add_sales')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('all_settings.Add Sales') }}
                                        <small style="color: orange"> (Alt+S)</small>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('salesTransaction') }}" class="nav-link @yield('manage_sales')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('all_settings.Manage Sales') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('purchaseCreate') }}" class="nav-link @yield('add_purchase')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('all_settings.Add Purchase') }}
                                        <small style="color: orange"> (Alt+P)</small>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('purchaseTransaction') }}" class="nav-link @yield('manage_purchase')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('all_settings.Manage Purchase') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('returnTransaction') }}" class="nav-link @yield('manage_return')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Return</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="nav-item has-treeview @yield('product_mo')">
                    <a href="#" class="nav-link @yield('product')">
                        {{--                            <i class="nav-icon fa fa-check-square"></i>--}}
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>
                            {{--{{ __('all_settings.Product') }}--}}
                            Inventory management
                            <i class="fas fa-angle-left right"></i>
                            {{--<span class="badge badge-info right">6</span>--}}
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('ProductMgtAccess')
                            <li class="nav-item">
                                <a href="{{ url('product/create') }}" class="nav-link @yield('add_product')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('all_settings.Add Product') }}</p>
                                </a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a href="{{ url('product') }}" class="nav-link @yield('manage_product')">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('all_settings.Manage Product') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('product_type') }}" class="nav-link @yield('manage_product_type')">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Product Type</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('company_name') }}" class="nav-link @yield('manage_company_name')">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Company</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('brand') }}" class="nav-link @yield('manage_brand')">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Brand</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('unit') }}" class="nav-link @yield('manage_unit')">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Unit</p>
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>

                        @can('UserAccess')
                            <li class="nav-item">
                                <a href="{{ url('addClient') }}" class="nav-link @yield('add_Client')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Client</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('manageClient') }}" class="nav-link @yield('manage_Client')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Client</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('addSupplier') }}" class="nav-link @yield('add_Supplier')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Supplier</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('manageSupplier') }}" class="nav-link @yield('manage_Supplier')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Supplier</p>
                                </a>
                            </li>
                        @endcan

                    </ul>
                </li>
                @can('PaymentMgtAccess')
                    <li class="nav-item has-treeview @yield('paymentmgt_mo')">
                        <a href="#" class="nav-link @yield('paymentmgt')">
                            <i class="nav-icon fas fa-money-bill"></i>

                            <p>
                                Payment Management
                                <i class="fas fa-angle-left right"></i>
                                {{--<span class="badge badge-info right">6</span>--}}
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('payment_request/create') }}" class="nav-link @yield('add_payment_request')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Payment request</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('payment_request') }}" class="nav-link @yield('manage_payment_request')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Payment request</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('payment') }}" class="nav-link @yield('payment')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Payment</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('payment_index') }}" class="nav-link @yield('manage_payment')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Payment</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('receipt') }}" class="nav-link @yield('receipt')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Receipt</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('receipt_index') }}" class="nav-link @yield('manage_receipt')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Receipt</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('invoice_due_report/'.'c') }}" class="nav-link @yield('Customer_due_report')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Customer Due Report</p>
                                </a>
                                <a href="{{ url('invoice_due_report/'.'s') }}" class="nav-link @yield('Supplier_due_report')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Supplier Due Report</p>
                                </a>
                            </li>
                            {{--<li class="nav-item">--}}
                                {{--<a href="{{ url('ledger') }}" class="nav-link @yield('manage_ledger')">--}}
                                    {{--<i class="far fa-circle nav-icon"></i>--}}
                                    {{--<p>Manage User's Ledger</p>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        </ul>
                    </li>
                @endcan
                @can('AccountMgtAccess')
                    <li class="nav-item has-treeview @yield('accounting_mo')">
                        <a href="#" class="nav-link @yield('accounting')">
                            {{--<i class="nav-icon fas fa-file-import"></i>--}}
                            <i class="nav-icon fas fa-calculator"></i>

                            <p>
                                Accounting
                                <i class="fas fa-angle-left right"></i>
                                {{--<span class="badge badge-info right">6</span>--}}
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('bank_account') }}" class="nav-link @yield('manage_account')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Bank Account</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('deposit/'.'Deposit') }}" class="nav-link @yield('deposit')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Deposit (Account)</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('withdraw/'.'Withdraw') }}" class="nav-link @yield('withdraw')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Withdraw (Account)</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('account_transfer') }}" class="nav-link @yield('account_transfer')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Transfer (AC to AC)</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('bank_ledger') }}" class="nav-link @yield('manage_bank_ledger')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Account Ledger</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('manage_account_ledger') }}" class="nav-link @yield('manage_account_ledger')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Account Ledger</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('account_statement') }}" class="nav-link @yield('manage_account_statement')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Account Statement</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('branch_ledger') }}" class="nav-link @yield('manage_branch_ledger')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Branch Ledger</p>
                                </a>
                            </li>
                            <li class="nav-item @yield('loan_mo')">
                                <a href="#" class="nav-link @yield('loan_ma')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Loan & Investment
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    <li class="nav-item">
                                        <a href="{{ url('deposit/'.'Loan') }}" class="nav-link @yield('loan')">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Loan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('withdraw/'.'Loan Payment') }}" class="nav-link @yield('loan_payment')">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Loan/Interest Payment</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('bankac/'.'Loan Account') }}" class="nav-link @yield('loan_account')">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Manage Loan Account</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('deposit/'.'Investment') }}" class="nav-link @yield('investment')">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Investment</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('withdraw/'.'Profit Share') }}" class="nav-link @yield('profit_share')">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Profit Share</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('investment_statement') }}" class="nav-link @yield('investment_statement')">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Statement</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>


                        </ul>
                    </li>
                @endcan
                @can('ExpenseAccess')
                    <li class="nav-item has-treeview @yield('expense_mo')">
                        <a href="#" class="nav-link @yield('expense')">
                            <i class="nav-icon far fa-minus-square"></i>
                            <p>
                                Expense
                                <i class="fas fa-angle-left right"></i>
                                {{--<span class="badge badge-info right">6</span>--}}
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{--@can('expense-create')--}}
                            <li class="nav-item">
                                <a href="{{ route('efa.expenseCreate', ['efa' => 'expense']) }}" class="nav-link @yield('add_expense')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Expense
                                        <small style="color: orange"> (Alt+X)</small>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('efa.expenseList', ['efa' => 'expense']) }}" class="nav-link @yield('manage_expense')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Expense (Non Approved)</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('expense_approved') }}"
                                   class="nav-link @yield('manage_expense_approved')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Expense (Approved)</p>
                                </a>
                            </li>
                            {{--<li class="nav-item">--}}
                                {{--<a href="{{ url('expense_type') }}" class="nav-link @yield('manage_expense_type')">--}}
                                    {{--<i class="far fa-circle nav-icon"></i>--}}
                                    {{--<p>Manage Expense Type</p>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li class="nav-item">
                                <a href="{{ route('module.index', ['module' => 'expense_type']) }}" class="nav-link @yield('manage_expense_type')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Expense Type</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('ExpenseAccess')
                    <li class="nav-item has-treeview @yield('fixed_asset_mo')">
                        <a href="#" class="nav-link @yield('fixed_asset')">
                            <i class="nav-icon fa fa-building"></i>
                            <p>
                                Fixed Asset
                                <i class="fas fa-angle-left right"></i>
                                {{--<span class="badge badge-info right">6</span>--}}
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{--@can('fixed_asset-create')--}}
                            <li class="nav-item">
                                <a href="{{ route('efa.expenseCreate', ['efa' => 'fixed_asset']) }}" class="nav-link @yield('add_fixed_asset')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Fixed Asset
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('efa.expenseList', ['efa' => 'fixed_asset']) }}" class="nav-link @yield('manage_fixed_asset')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Non Approved</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('expense_approved') }}"
                                   class="nav-link @yield('manage_fixed_asset_approved')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Approved</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('module.index', ['module' => 'fixed_asset_type']) }}" class="nav-link @yield('manage_fixed_asset_type')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Fixed Asset Type</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li class="nav-item has-treeview @yield('user_mo')">
                    <a href="#" class="nav-link @yield('user')">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            {{--                            {{ __('all_settings.User Administration') }}--}}
                            System Settings
                            <i class="fas fa-angle-left right"></i>
                            {{--<span class="badge badge-info right">6</span>--}}
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('UserAccess')
                            <li class="nav-item">
                                <a href="{{ url('user') }}" class="nav-link @yield('manage_Admin')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Admin</p>
                                </a>
                            </li>
                        @endcan
                        @can('RoleAccess')
                            <li class="nav-item">
                                <a href="{{ url('role') }}" class="nav-link @yield('manage_role')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Role</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @can('EmployeeAccess')
                    <li class="nav-item has-treeview @yield('employee_mo')">
                        <a href="#" class="nav-link @yield('employee')">
                            <i class="nav-icon fa fa-user-circle"></i>
                            <p>
                                Employee
                                <i class="fas fa-angle-left right"></i>
                                {{--<span class="badge badge-info right">6</span>--}}
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('employee/create') }}"
                                   class="nav-link @yield('add_employee')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Employee</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('employee') }}" class="nav-link @yield('manage_employee')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Employee</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('create_payslip') }}"
                                   class="nav-link @yield('add_payslip')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Generate Payslip </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('employee_salary/create') }}"
                                   class="nav-link @yield('add_employee_salary')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pay Employee Salary</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('create_bonus') }}"
                                   class="nav-link @yield('add_employee_bonus')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pay Employee Bonus</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('employee_salary') }}"
                                   class="nav-link @yield('employee_salary')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Employee Salary</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('ReportAccess')
                    <li class="nav-item has-treeview @yield('report_mo')">
                        <a href="#" class="nav-link @yield('report')">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                Report
                                <i class="fas fa-angle-left right"></i>
                                {{--<span class="badge badge-info right">6</span>--}}
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('product_stock_report') }}"
                                   class="nav-link @yield('product_stock_report' )">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Product Stock</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('datewise_expense_summary_home') }}"
                                   class="nav-link @yield((request()->segment(1) == 'datewise_expense_details'||request()->segment(1) == ('datewise_expense_summary_home'||'datewise_expense_summary')) ? 'report_expense_date' : '' )">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Date Wise Expense</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('typewise_expense_summary_home') }}"
                                   class="nav-link @yield((request()->segment(1) == 'typewise_expense_details'||request()->segment(1) == ('typewise_expense_summary_home'||'typewise_expense_summary')) ? 'report_expense_type' : '' )">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Type Wise Expense</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('expense_details_home') }}"
                                   class="nav-link @yield('expense_details' )">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Details Expense</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('ledger_report_home') }}" class="nav-link @yield('ledger_report' )">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ledger Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('customer_report') }}" class="nav-link @yield('customer_report' )">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Customer Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('supplier_report') }}" class="nav-link @yield('supplier_report' )">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Supplier Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('balance_sheet') }}" class="nav-link @yield('balance_report' )">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Balance Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <div class="dropdown-divider"></div>

                @if(Auth::user()->email=='superadmin@eidyict.com')
                    <li class="nav-item has-treeview @yield('superadmin_mo')">
                        <a href="#" class="nav-link @yield('superadmin')">
                            <i class="nav-icon fa fa-lock-open"></i>
                            <p>
                                Super Admin Menu
                                <i class="fas fa-angle-left right"></i>
                                {{--<span class="badge badge-info right">6</span>--}}
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('setting') }}" class="nav-link @yield('manage_setting')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Settings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('user-type') }}" class="nav-link @yield('manage_user_type')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage User Type</p>
                                </a>
                            </li>

                            {{--                        @can('permission-access')--}}
                            <li class="nav-item">
                                <a href="{{ url('permission') }}" class="nav-link @yield('manage_permission')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('all_settings.Permission') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('branch') }}" class="nav-link @yield('manage_branch')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('all_settings.Branch') }}</p>
                                </a>
                            </li>
                            {{--@endcan--}}
                        </ul>
                    </li>
                @endif


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
