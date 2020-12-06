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
            return response()->json(['status' => false, 'message' => 'Invalid Credentials'], 401);
        }
        else {
            if(auth()->user()->role == 'user')
                return response()->json(['status' => true, 'role' => 'user'], 200);
            return response()->json(['status' => true, 'role' => 'admin'], 200);
        }
    }

    public function logout () {
        Auth::logout();
        return redirect()->route('page.login');
    }

    public function redirect_login () {
        return redirect()->route('page.login');
    }
}
