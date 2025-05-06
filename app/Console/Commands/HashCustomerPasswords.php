<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class HashCustomerPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:hash-password {customerId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash the password of a single customer by their ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
             // جلب الـ ID من الـ argument
        $customerId = $this->argument('customerId');

        // العثور على العميل
        $customer = Customer::find($customerId);

        if ($customer) {
            // تشفير كلمة المرور
            $customer->password = Hash::make($customer->password);
            $customer->save();

            // طباعة رسالة نجاح
            $this->info("Password hashed for customer with ID: {$customer->id}");
        } else {
            // طباعة رسالة خطأ إذا العميل مش موجود
            $this->error("Customer with ID {$customerId} not found.");
        }
    }
}

