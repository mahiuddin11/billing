<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \RouterOS\Query;

class RunFunController extends Controller
{

    public function runScheduler()
    {
        try {
            Artisan::call('mikpppprofilesync:update');
            Artisan::call('maccustomerbill:update');
            Artisan::call('billingeneratemessage:update');
            Artisan::call('poopsync:update');
            Artisan::call('vlaninterfaceSync:update');
            Artisan::call('vlanSync:update');
            Artisan::call('ipaddresssync:update');
            Artisan::call('macresellerbill:update');
            Artisan::call('queueSync:update');
            return back()->with('success', 'Sync was Successfully Completed');
        } catch (\Throwable $th) {
            return back()->with('failed', $this->getError($th));
        }
    }

    public function addMacAddress(Request $request)
    {
        if (!$request->deleteselectitem) {
            return back()->with('failed', 'Please Select Customer');
        }

        $customers = Customer::whereIn('id', $request->deleteselectitem)->get();
        foreach ($customers as $customer) {
            $client = $this->client($customer->server_id);
            $activeUsers = $client->query('/ppp/active/print', ['name', $customer->username])->read();

            if ($activeUsers[0]['caller-id']) {
                $query =  new Query('/ppp/secret/set');
                $query->equal('.id', $customer->mid);
                $query->equal('caller-id', $activeUsers[0]['caller-id']);
                $client->query($query)->read();

                $customer->update([
                    'mac_address' => $activeUsers[0]['caller-id'] ?? "",
                ]);
            }
        }

        return back()->with('success', 'Successfully Done');
    }

    public function resetpass()
    {
        try {
            $customers = Customer::get();
            foreach ($customers as $customer) {
                    $arr =   [
                        "password" => Hash::make($customer->m_password),
                    ];
                $customer->update($arr);
            }

            return back()->with('success', 'Successfully Done');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
