<?php

namespace App\Console\Commands;

use App\Helpers\Billing;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use \RouterOS\Query;

class Billingexpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billingexpired:update';

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
        $customerExpired = Customer::whereDate('exp_date', today()->format('Y-m-d'))->get();
        if ($customerExpired->isNotEmpty()) {
            foreach ($customerExpired as $customer) {
                $message = "Dear Sir, Your connection wash disconnected";
                $customer->update(['disabled' => 'true', 'billing_status_id' => 4]);

                $client = $this->client($customer->server_id);
                $query =  new Query('/ppp/secret/set');
                $query->equal('.id', $customer->mid);
                $query->equal('disabled', 'true');
                $client->query($query)->read();

                $this->smsSend($customer->phone, $message);
            }
        }

        return "Billing expired update successfully";
    }
}
