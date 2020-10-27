<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;

class AuthenticationController extends Controller
{
    public function login_page () {
        return view('pages.authentication.login-page');
    }

    public function process_login () {
         $credentials = request(['email', 'password']);
        
        if(!auth()->attempt($credentials))
        {
            return back()->withErrors(['message' => 'Invalid Credentials']);
        }
        else {
            if(auth()->user()->role == 'user')
                return redirect()->route('orders.cashier_page');
            return redirect()->route('dashboard.index');
        }
    }

    public function logout () {
        Auth::logout();
        return redirect()->route('page.login');
    }
}
