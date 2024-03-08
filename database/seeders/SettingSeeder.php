<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $company = [
            [
                'title' => 'Walking Company',
                'address' => 'Dhaka',
            ]
        ];
        \DB::table('company_names')->insert($company);
        $brand = [
            [
                'title' => 'Brand1', 'company_name_id' => 1,
            ]
        ];
        \DB::table('brands')->insert($brand);
        $product_type = [
            ['title' => 'type1'], ['title' => 'category2']
        ];
        \DB::table('product_types')->insert($product_type);
        $product = [
            [ 'id'=>'1', 'title'=>'Product1', 'company_name_id'=>'1', 'product_type_id'=>'1', 'brand_id'=>'1', 'unit_id'=>'1', 'unitbuy_price'=>'45', 'unitsell_price'=>'50', 'low_stock'=>'10', 'description'=>NULL, 'status'=>'Active']
        ];
        \DB::table('products')->insert($product);

        $unit = [
            ['title' => 'Pcs'], ['title' => 'Box'], ['title' => 'Pack'], ['title' => 'Bag'], ['title' => 'Kg'], ['title' => 'Litre'], ['title' => 'Feet'], ['title' => 'Yard'],
        ];
        \DB::table('units')->insert($unit);

        $transaction_type = [
            ['id' => 1, 'title' => 'Credited', 'nature' => 'Positive(+)'],
            ['id' => 2, 'title' => 'Debited', 'nature' => 'Negative(-)'],
            ['id' => 3, 'title' => 'Receipt', 'nature' => 'Positive(+)'],
            ['id' => 4, 'title' => 'Payment', 'nature' => 'Negative(-)'],
            ['id' => 5, 'title' => 'Loaned', 'nature' => 'Negative(-)'],
            ['id' => 6, 'title' => 'Loaned to', 'nature' => 'Positive(+)'],
            ['id' => 7, 'title' => 'Payslip', 'nature' => 'Positive(+)'],
            ['id' => 8, 'title' => 'Deposit', 'nature' => 'Positive(+)'],
            ['id' => 9, 'title' => 'Withdraw', 'nature' => 'Negative(-)'],
        ];
        \DB::table('transaction_types')->insert($transaction_type);

        $transaction_method = [
            ['title' => 'Cash'], ['title' => 'Bank Cheque'], ['title' => 'Bank Transfer'], ['title' => 'Mobile Banking'], ['title' => 'Others'],
        ];
        \DB::table('transaction_methods')->insert($transaction_method);
        $branch = [
            ['title' => 'Branch-1']
        ];
        \DB::table('branches')->insert($branch);

        $branch_user = [
            ['user_id' => 3, 'branch_id' => 1],
        ];
        \DB::table('branch_user')->insert($branch_user);

        $branch_ledger = [
            ['branch_id' => 1, 'transaction_date' => Carbon::now()->format('Y-m-d H:i:s'), 'transaction_code' => 'LB01', 'transaction_type_id' => 1, 'transaction_method_id' => 1, 'amount' => 0, 'comments' => 'Opening', 'entry_by' => 1,'created_at'=>Carbon::now()->format('Y-m-d H:i:s')]
        ];
        \DB::table('branch_ledgers')->insert($branch_ledger);

        \DB::table('expense_types')->insert([
            ['expense_name' => 'Office Rent'],
            ['expense_name' => 'Utility Bill'],
            ['expense_name' => 'Guest Entertainment'],
            ['expense_name' => 'Conveyance'],
            ['expense_name' => 'Photo copy'],
            ['expense_name' => 'Printing Paper'],
            ['expense_name' => 'Stationary Items'],
            ['expense_name' => 'IT Equipments'],
            ['expense_name' => 'Miscellaneous'],
        ]);

        $setting = [
            ['id' => '1', 'org_name' => 'Eidy ICT Solutions Ltd.', 'linked_user' => '1', 'org_slogan' => 'Your total ICT Solution',
                'address_line1' => 'House#360, Bornomala School Road,', 'address_line2' => 'Noddapara, Dakshinkhan, Dhaka-1230',
                'contact_no1' => '01716383038', 'contact_no2' => NULL, 'email' => 'info@eidyict.com', 'web' => 'https://www.eidyict.com',
                'vat_reg_no' => NULL, 'language' => 'en', 'logo' => 'no-foto.png', 'logo_base64' => 'data:image/png;base64,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',
                'default_password' => 'Eis_777', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ];
        \DB::table('settings')->insert($setting);


    }
}