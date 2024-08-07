<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert = [
            [
                'account_name' => 'Assets',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 0,
            ],
            [
                'account_name' => 'Mobile Banking',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 1,
            ],
            [
                'account_name' => 'Cash',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 1,
            ],
            [
                'account_name' => 'Bank',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 1,
            ],
            [
                'account_name' => 'Account receivable',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 1,
            ],
            [
                'account_name' => 'Expenses',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 0,
            ],
            [
                'account_name' => 'Salary',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 6,
            ],
            [
                'account_name' => 'Discount',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 6,
            ],
            [
                'account_name' => 'Income',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 0,
            ],
            [
                'account_name' => 'Internet Bill',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 9,
            ],
            [
                'account_name' => 'Liabilities',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 0,
            ],
            [
                'account_name' => 'Equity',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 0,
            ],
            [
                'account_name' => 'Account Payable',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 6,
            ],
            [
                'account_name' => 'Discount',
                'company_id' => 1,
                'status' => 'Active',
                'parent_id' => 9,
            ],
        ];
        Account::insert($insert);
    }
}
