<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $user_type = [
            ['id' => '1','title' => 'Admin', 'status'=>'Active'],
            ['id' => '2','title' => 'Employee', 'status'=>'Active'],
            ['id' => '3','title' => 'Customer', 'status'=>'Active'],
            ['id' => '4','title' => 'Supplier', 'status'=>'Active'],
        ];
        \App\Models\UserType::insert($user_type);

        $user = [
            [
                'id' => '1',
                'name' => 'SuperAdmin',
                'email' => 'superadmin@eidyict.com',
                'password' => bcrypt('123456'),
                'user_type_id' => 1,
                'web_access' => '1',
            ],
            [
                'id' => '2',
                'name' => 'SystemAdmin',
                'email' => 'systemadmin@eidyict.com',
                'password' => bcrypt('123456'),
                'user_type_id' => 1,
                'web_access' => '1',
            ],
            [
                'id' => '3',
                'name' => 'Employee1',
                'email' => 'employee1@eidyict.com',
                'password' => bcrypt('123456'),
                'user_type_id' => 2,
                'web_access' => '1',
            ],
            [
                'id' => '4',
                'name' => 'Customer1',
                'email' => 'customer1@eidyict.com',
                'password' => bcrypt('123456'),
                'user_type_id' => 3,
                'web_access' => '1',
            ],
            [
                'id' => '5',
                'name' => 'Supplier1',
                'email' => 'supplier1@eidyict.com',
                'password' => bcrypt('123456'),
                'user_type_id' => 4,
                'web_access' => '1',
            ],
            [
                'id' => '6',
                'name' => 'WalkingCustomer',
                'email' => 'wc@eidyict.com',
                'password' => bcrypt('123456'),
                'user_type_id' => 3,
                'web_access' => '0',
            ]

        ];
        foreach ($user as $key => $value) {
            \App\Models\User::create($value);
        }
        $profile = [
            ['user_id' => '1','gender' => 'Male','address' => 'Dhaka','designation' => 'Admin'],
            ['user_id' => '2','gender' => 'Male','address' => 'Dhaka','designation' => 'Admin'],
            ['user_id' => '3','gender' => 'Male','address' => 'Dhaka','designation' => 'Employee'],
            ['user_id' => '4','gender' => 'Male','address' => 'Dhaka','designation' => 'Customer'],
            ['user_id' => '5','gender' => 'Male','address' => 'Dhaka','designation' => 'Supplier'],
            ['user_id' => '6','gender' => 'Male','address' => 'Dhaka','designation' => 'Customer'],
        ];
        \DB::table('profiles')->insert($profile);
        $image_profile = [
            ['user_id' => '1','image' => 'default_image.png'],
            ['user_id' => '2','image' => 'default_image.png'],
            ['user_id' => '3','image' => 'default_image.png'],
            ['user_id' => '4','image' => 'default_image.png'],
            ['user_id' => '5','image' => 'default_image.png'],
            ['user_id' => '6','image' => 'default_image.png'],
        ];
        \DB::table('image_profiles')->insert($image_profile);
        $ledger = [
            ['user_id' => '1','branch_id' => 1,'transaction_type_id' => 1,'transaction_code' => 'LOB2401-000001','transaction_date' => Carbon::now()->format('Y-m-d H:i:s'),'transaction_method_id' => 5,'comments' => 'Opening','entry_by'=>'1','created_at'=>Carbon::now()->format('Y-m-d H:i:s')],
            ['user_id' => '2','branch_id' => 1,'transaction_type_id' => 1,'transaction_code' => 'LOB2401-000002','transaction_date' => Carbon::now()->format('Y-m-d H:i:s'),'transaction_method_id' => 5,'comments' => 'Opening','entry_by'=>'1','created_at'=>Carbon::now()->format('Y-m-d H:i:s')],
            ['user_id' => '3','branch_id' => 1,'transaction_type_id' => 1,'transaction_code' => 'LOB2401-000003','transaction_date' => Carbon::now()->format('Y-m-d H:i:s'),'transaction_method_id' => 5,'comments' => 'Opening','entry_by'=>'1','created_at'=>Carbon::now()->format('Y-m-d H:i:s')],
            ['user_id' => '4','branch_id' => 1,'transaction_type_id' => 1,'transaction_code' => 'LOB2401-000004','transaction_date' => Carbon::now()->format('Y-m-d H:i:s'),'transaction_method_id' => 5,'comments' => 'Opening','entry_by'=>'1','created_at'=>Carbon::now()->format('Y-m-d H:i:s')],
            ['user_id' => '5','branch_id' => 1,'transaction_type_id' => 1,'transaction_code' => 'LOB2401-000005','transaction_date' => Carbon::now()->format('Y-m-d H:i:s'),'transaction_method_id' => 5,'comments' => 'Opening','entry_by'=>'1','created_at'=>Carbon::now()->format('Y-m-d H:i:s')],
            ['user_id' => '6','branch_id' => 1,'transaction_type_id' => 1,'transaction_code' => 'LOB2401-000006','transaction_date' => Carbon::now()->format('Y-m-d H:i:s'),'transaction_method_id' => 5,'comments' => 'Opening','entry_by'=>'1','created_at'=>Carbon::now()->format('Y-m-d H:i:s')]
        ];
        \DB::table('ledgers')->insert($ledger);

        $permissions = [
            ['title' => 'UserAccess'],
            ['title' => 'UserDelete'],
            ['title' => 'RoleAccess'],
            ['title' => 'SupplyAccess'],
            ['title' => 'SupplyDelete'],
            ['title' => 'ProductMgtAccess'],
            ['title' => 'ProductMgtDelete'],
            ['title' => 'AccountMgtAccess'],
            ['title' => 'AccountMgtDelete'],
            ['title' => 'ExpenseAccess'],
            ['title' => 'ExpenseDelete'],
            ['title' => 'EmployeeAccess'],
            ['title' => 'EmployeeDelete'],
            ['title' => 'ReportAccess'],
        ];
        \App\Models\Permission::insert($permissions);

        $roles = [
            ['id'=>1,'title' => 'Admin Role'],
            ['id'=>2,'title' => 'Employee Role'],
            ['id'=>3,'title' => 'Customer Role'],
            ['id'=>4,'title' => 'Supplier Role'],
            ['id'=>5,'title' => 'Approval'],
        ];
        \App\Models\Role::insert($roles);

        $role_user_type = [
            ['user_type_id' => 1, 'role_id' => 1],
            ['user_type_id' => 2, 'role_id' => 2],
            ['user_type_id' => 3, 'role_id' => 3],
            ['user_type_id' => 4, 'role_id' => 4],
        ];
        \DB::table('role_user_type')->insert($role_user_type);


//        Permission Roll Table
        $admin_permissions = \App\Models\Permission::all();
        \App\Models\Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
//        \App\Models\Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
//        $user_permissions = $admin_permissions->filter(function ($permission) {
//            return substr($permission->title, 0, 5) != 'user-' && substr($permission->title, 0, 5) != 'role-'
//                && substr($permission->title, 0, 5) != 'permission-';
//        });
//        \App\Models\Role::findOrFail(2)->permissions()->sync($user_permissions);

//      Roll User Table
        \App\Models\User::findOrFail(1)->roles()->sync(1);
        \App\Models\User::findOrFail(2)->roles()->sync(1);
        \App\Models\User::findOrFail(3)->roles()->sync(2);
        \App\Models\User::findOrFail(4)->roles()->sync(3);
        \App\Models\User::findOrFail(5)->roles()->sync(4);
        \App\Models\User::findOrFail(6)->roles()->sync(3);
    }
}
//php artisan db:seed --class=PermissionSeeder

