<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Customer as ResourcesCustomer;
use App\Http\Resources\CustomerResources;
use App\Models\Customer;
use App\Models\User;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardController extends Controller
{
    function index($id)
    {
        $user = Customer::where('id', $id)->first();
        if ($user) {
            $response['bill_amount'] = number_format((float) $user->bill_amount, 2, '.', '') ?? '';
            $response['name'] = $user->name ?? '';
            $response['status'] = $user->status ?? '';
            $response['advanced_payment'] = number_format((float)$user->advanced_payment, 2, '.', '') ?? '';
            $response['exp_date'] = $user->exp_date ? date('d-F-Y', strtotime($user->exp_date)) : '';
            $response['package_name'] = $user->getMProfile->name ?? '';
            return $response;
        } else {
            return appResponse(false, "User does not exist");
        }
    }

    public function edashboard($id)
    {

        $user = User::where('id', $id)->with(['billingPerson', 'billing'])->first();


        if ($user) {
            $response['name'] = $user->name ?? '';
            $response['status'] = 'Employee';
            // $response['monthly_bill'] = 0; // Initialize the sum
            $response['collected_bill'] = 0; // Initialize the sum
            // dd($user->billing);
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();


            $response['monthly_bill'] = $user->billingPerson->sum("bill_amount") ?? 0; // Accumulate the sum



            // dd($startOfMonth);
            foreach ($user->billing as $bill) {
                if ($bill->created_at >= $startOfMonth && $bill->created_at <= $endOfMonth) {
                    $response['collected_bill'] += $bill->pay_amount ?? 0;
                }
            }
            $response['due_bill'] = $response['monthly_bill'] -  $response['collected_bill'];
            $response['discount'] = '0';

            return response()->json($response);
        } else {
            return appResponse(false, "User does not exist");
        }
    }



    public function dataProcessing($id)
    {

        $billingData = [];

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $customers = Customer::where('billing_person', $id)->get();


        foreach ($customers as $customer) {
            $billing = Billing::where('customer_id', $customer->id)
                ->where('status', 'unpaid')
                ->whereDate('created_at', '>=', $startOfMonth)
                ->whereDate('created_at', '<=', $endOfMonth)
                ->with('getCustomer')
                ->get(); // Execute the query



            foreach ($billing as $bill) {

                // Accumulate the unpaid amount for each

                $billData = [
                    'name' => $bill->getCustomer->name ?? 0,
                    'phone_number' => $bill->customer_phone  ?? 0,
                ];

                $billingData[] = $billData;
            }
        }


        if ($billingData) {
            return response()->json($billingData);
        } else {
            return appResponse(false, "Your Unpaid Bill Not Avaliable");
        }
    }
}
