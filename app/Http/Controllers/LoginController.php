<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Config;
use Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $loginMsg = "";
        $inputUser =  request('username');
        $inputPass =  request('password');

        $inputUser = Helpers::clean_user_input($inputUser);
        $inputPass = Helpers::clean_user_input($inputPass);
        if(isset($inputUser, $inputPass)) {
            if($inputUser == Config::get('constants.admin_name') && $inputPass == Config::get('constants.admin_password')) {
                $request->session()->put('admin', $inputUser);
                if ($request->ajax()) {
                    return ['success' => true];
                } else {
                    return redirect()->route('products');
                }
            } else {
                $loginMsg = __('messages.loginMsg');
            }
        }
        if ($request->ajax()) {
            return ['success' => false, 'message' => $loginMsg];
        } else {
            return view('products.login', compact('loginMsg'));
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return Redirect::to('login');
    }

}
