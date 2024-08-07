<?php

namespace App\Console\Commands;

use App\Helpers\Billing;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use \RouterOS\Query;

class BillinGenerateMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billingeneratemessage:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Is for billing expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */


    public function smsSend($number, $message)
    {
        $url = "https://sms.solutionsclan.com/api/sms/send";
        $data = [
            "apiKey" => "A000429c348a5db-bab2-4189-a0ad-813e136ccaa4",
            "contactNumbers" => $number,
            "senderId" => "8809612441117",
            "textBody" => $message
        ];

        Http::post($url, $data);
    }

    public function handle()
    {
        $customerList =  Customer::where('billing_status_id', 5)->whereDate('exp_date', today()->addDays(2)->format('Y-m-d'))->get();
        if ($customerList->isNotEmpty()) {
            foreach ($customerList as $customer) {
                $message = "Dear Sir, Please Pay Your Internet Bill, Otherwise Your connection will be disconnected.";

                $this->smsSend($customer->phone, $message);
            }
        }

        $customerExpire = Customer::where('billing_status_id', 5)->whereDate('exp_date', '=', today()->addDays(1)->format('Y-m-d'))->get();
        if ($customerExpire->isNotEmpty()) {
            foreach ($customerExpire as $customer) {
                $message = "Dear Sir, Please Pay Your Internet Bill, Otherwise Your connection will be disconnected tomorrow.";

                $this->smsSend($customer->phone, $message);
            }
        }

        return "Billing expired update successfully";
    }
}
