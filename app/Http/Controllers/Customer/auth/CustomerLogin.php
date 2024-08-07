<?php

namespace App\Http\Controllers\Customer\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerLogin extends Controller
{
    public function customer(Request $request)
    {
        if($request->method() == "POST"){
          $this->validate($request,[
              'username'=> "required",
              'password'=> "required",
          ]);

        if(Auth::guard('customer')->attempt(['username' => $request->username,'password' => $request->password])){
          return redirect()->route('customer.dashboard');
        }else{
            return back()->with('failed','Invalid Username Or Password');
        }
        }
        return view('auth.customer_login');
    }

    public function dashboard()
    {
        return view('customer.pages.dashboard');
    }


    public function customerlogout()
    {
        //logout user
        auth()->logout();
        // redirect to homepage
        return redirect()->route('customer.login');
    }

}
