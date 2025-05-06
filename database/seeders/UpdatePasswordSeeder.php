<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = DB::table('customers')->get();

        foreach ($customers as $customer) {
            // تحقق مما إذا كانت كلمة المرور نصية وليست مشفرة
            if (strlen($customer->password) < 60) {
                DB::table('customers')
                    ->where('id', $customer->id)
                    ->update(['password' => Hash::make($customer->password)]);
            }
        }

        $lenders = DB::table('lenders')->get();
        foreach ($lenders as $lender) {
            // تحقق مما إذا كانت كلمة المرور نصية وليست مشفرة
            if (strlen($lender->password) < 60) {
                DB::table('lenders')
                    ->where('id', $lender->id)
                    ->update(['password' => Hash::make($lender->password)]);
            }
        }
    }







}
