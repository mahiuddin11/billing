<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Models\MacCustomerBill;
use App\Models\MPPPProfile;

class MacBalanceCheckAndUpdate{

    public function checkbalance($getcustomer,$req) {
        $Mpppprofile = MPPPProfile::find($req->m_p_p_p_profile);
        if (auth()->user()->mac_reseler && auth()->user()->mac_reseler->reseller_type == "prepaid" && ($getcustomer->m_p_p_p_profile != $req->m_p_p_p_profile || date("Y-m-d",strtotime($getcustomer->start_date)) !=  date("Y-m-d",strtotime($req->start_date)))) {
            $macDetails = auth()->user()->mac_reseler;
            $charge = $macDetails->tariff->package->where('m_profile_id', $Mpppprofile->id)->pluck('rate')->first();
            $checkbill = MacCustomerBill::where('customer_id', $getcustomer->id)->where('company_id',auth()->user()->company_id)->whereMonth('date_', date('m'))->whereYear('date_', date('Y'));

            if ($checkbill->first()) {
            if ($macDetails->recharge_balance < $charge) {
                return 0;
            };

           $macDetails->update(['recharge_balance' => $macDetails->recharge_balance - $charge]);
                $checkbill->update([
                    'charge' => $charge,
                ]);
            }
            return 1;
        }

    }
}
