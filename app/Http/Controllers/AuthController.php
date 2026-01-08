<?php

namespace App\Http\Controllers;

use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validation = $request->validate([
            'email' =>  'required|exists:tbl_users,email',
            'password' => 'required|min:8',
        ]);
        if (Auth::attempt($validation)) {
            $token = Auth::user()->createToken('user_c')->plainTextToken;
            $cookie = Cookie("user_c", $token);
            $user = Auth::user();
            return response()->json(compact('user'))->withCookie($cookie);
        } 
        throw new Error('Invalid Credentials');
    }

    public function logout (Request $request) {
        $cookie = Cookie::forget('user_c');
        return response()->json()->withCookie($cookie);
    }
}
