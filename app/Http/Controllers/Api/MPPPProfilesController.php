<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MPPPProfile;
use App\Models\Customer;
use Illuminate\Http\Request;

class MPPPProfilesController extends Controller
{
    function index($id)
    {
        $customer = Customer::find($id);

        $users = MPPPProfile::where("server_id", $customer->server_id)->get();
        $responses = [];
        // $responses['current_package'] = '';

        foreach ($users as $user) {
            $response = [];

            // $response['customer_id'] = $user->id?? '';
            $response['id'] = $user->id ?? '';
            $response['name'] = $user->name ?? '';
            $response['speed'] = $user->speed ?? '';
            $response['amount'] = $user->amount ?? '';
            $responses[] = $response;
        }
        return $responses;
    }
}
