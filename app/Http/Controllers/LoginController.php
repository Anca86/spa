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

        if($request->has('username') && $request->has('password')) {
            $inputUser = Helpers::clean_user_input(request('username'));
            $inputPass = Helpers::clean_user_input(request('password'));
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

        if($request->ajax()) {
            return ['logout' => true];
        } else {
            return Redirect::to('login');
        }
    }

    public function isSession(Request $request)
    {
        if (session('admin')) {
            if($request->ajax()) {
                return session('admin');
            } else {
                return redirect()->route('products');
            }
        } else {
            if($request->ajax()) {
                return ['success' => false, 'message' => 'Error'];
            } else {
                return view('products.login', compact('loginMsg'));
            }
        }
    }

}
