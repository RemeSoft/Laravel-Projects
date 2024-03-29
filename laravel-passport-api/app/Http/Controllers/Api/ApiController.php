<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    // Register API ( POST )
    public function register(Request $request){

        // Data Validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        // Create User
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'user created successfully'
        ]);
    }

    // Login API ( POST )
    public function login(Request $request){

        // Data Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Checking User Login
        if(Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])){

            // User Exists
            $user = Auth::user();
            $token = $user->createToken('myToken')->accessToken;
            return response()->json([
                'status'  => true,
                'message' => 'User login successfully!',
                'token'   => $token
            ]);

        }else{

            return  response()-> json([
                'status' => false,
                'message' => 'invalid login details'
            ]);
        }

    }

    // Login API ( GET )
    public function profile(){
        
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'Profile Information',
            'data' => $user,
        ]);
    }

    // Logout API ( GET )
    public function logout(){

        // $user = Auth::user(); Same
        auth()->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'User Logged Out'
        ]);
    }

}
