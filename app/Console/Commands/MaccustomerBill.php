<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\MacCustomerBill as ModelsMacCustomerBill;
use App\Models\MacReseller;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MaccustomerBill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maccustomerbill:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Mac Customer Bill';

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
        try {
            $macResellers = MacReseller::get();
            foreach ($macResellers as $macReseller) {
                $customers = Customer::where('company_id', $macReseller->getUser->company_id)->whereIn('disabled', ['false', 10])->get();
                foreach ($customers  as  $customer) {
                    $charge = 0;
                    if ($macReseller->reseller_type == "prepaid") {

                        if ($customer->protocol_type_id == 3) {
                            $charge = $macReseller->tariff->package->where('m_profile_id', $customer->m_p_p_p_profile)->pluck('rate')->first();
                        } elseif ($customer->protocol_type_id == 1) {
                            $charge = $macReseller->tariff->package->where('m_static_id', $customer->queue_id)->pluck('rate')->first();
                        }

                        $dateAsString = Carbon::parse($macReseller->created_at)->format('Y-m');

                        if ($dateAsString == date('Y-m')) {
                            $dateAsString = Carbon::parse($macReseller->created_at)->format('Y-m-d');
                            $date = Carbon::parse(Carbon::now()->endOfMonth()->format('Y-m-d'));
                            $diff = $date->diffInDays($dateAsString);
                            $charge = (int) (($charge / 30) * $diff);
                        }

                        $checkbill = ModelsMacCustomerBill::where('customer_id', $customer->id)->whereMonth('date_', date('m'))->whereYear('date_', date('Y'));
                        if (!$checkbill->first()) {
                            if ($macReseller->recharge_balance < $charge) {
                                DB::table('customers')->where('company_id', $macReseller->getUser->company_id)->where('disabled', 'false')->update(['disabled' => '10']);
                            } else {
                                DB::table('customers')->where('company_id', $macReseller->getUser->company_id)->where('disabled', '10')->update(['disabled' => 'false']);
                                $macReseller->update(['recharge_balance' => $macReseller->recharge_balance - $charge]);
                                $checkbill->create([
                                    'customer_id' => $customer->id,
                                    'date_' => today()->format('Y-m-d'),
                                    'charge' => $charge,
                                    'company_id' =>  $customer->company_id ?? 0,
                                ]);
                            }
                        }
                    }
                }
            }
            return "Billing expired update successfully";
        } catch (\Throwable $th) {
            DB::rollback();
            return "Billing expired update successfully";
        }
    }
}
