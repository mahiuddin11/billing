<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Expiremessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiremessage:update';

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

    public function handle()
    {
        $customerExpired = Customer::whereDate('exp_date', today()->format('Y-m-d'))->get();
        if ($customerExpired->isNotEmpty()) {
            foreach ($customerExpired as $customer) {
                $message = "Dear client, your account has been deactivated. Client Code: " . str_pad(($customer->id), 4, "0", STR_PAD_LEFT) . " User Name: " . $customer->username . " Password: " . $customer->m_password . " Customer Name: " . $customer->name . " Package: " . $customer->getMProfile->name . " Monthly Bill: " . $customer->bill_amount . " Billing Last Date: " . Carbon::parse($customer->exp_date)->format('d-M-Y') . " Thanks & Regards Sabuj Bangla Online";
                $url = "https://sms.solutionsclan.com/api/sms/send";
                $data = [
                    "apiKey" => "A000429c348a5db-bab2-4189-a0ad-813e136ccaa4",
                    "contactNumbers" => $customer->phone,
                    "senderId" => "8809612441117",
                    "textBody" => $message
                ];
                Http::post($url, $data);
            }
        }
        return "Expire Message Send Successfully";
    }
}
