<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;

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
            if(auth()->user()->hasAnyRole(['root', 'super_admin'])) {
                return response()->json(['status' => true, 'role' => 'admin'], 200);
            } 
            else if (auth()->user()->hasAnyRole(['cashier'])) {
                return response()->json(['status' => true, 'role' => 'cashier'], 200);
            } 
            else if (auth()->user()->hasAnyRole(['offline_storage'])) {
                return response()->json(['status' => true, 'role' => 'offline_storage'], 200);
            } 
            else if (auth()->user()->hasAnyRole(['online_storage'])) {
                return response()->json(['status' => true, 'role' => 'online_storage'], 200);
            }
            else {
                return response()->json(['status' => false, 'role' => 'guest'], 401);
            }
        }
    }

    public function logout () {
        Auth::logout();
        return redirect()->route('page.login');
    }

    public function redirect_login () {
        return redirect()->route('page.login');
    }

    public function change_user_credentials (Request $request) {
        $user = User::find(auth()->user()->id);
        if(Hash::check($request->old_password, auth()->user()->password, [])) {
            $user->update([
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            return response()->json(['status' => true, 'message' => 'User credentials updated']);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid old password'], 400);
        }
    }
}
