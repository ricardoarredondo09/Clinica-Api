<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
    	$validateData = $request->validate([
    		'name' => 'required|string|max:255',
    		'email' => 'email|required|string|max:255|unique:users',
    		'password' =>'required|string|min:8'
    	]);

    	$user = User::create([
    		'name' => $validateData['name'],
    		'email' => $validateData['email'],
    		'password' => Hash::make($validateData['password'])
    	]);


    	$token = $user->createToken('auth_token')->plainTextToken;

    	return response()->json([
    		'access_token' => $token,
    		'token_type' => 'Bearer'
    	]);


    }


    public function login(Request $request){
    	if(!Auth::attempt($request->only('email', 'password'))){
    		return response()->json([
    			'message' => 'Invalid Login Details'
    		], 401);
    	}

    	$user = User::where('email', $request['email'])->firstOrFail();

    	$token = $user->createToken('auth_token')->plainTextToken;

    	return response()->json([
    		'access_token' => $token,
    		'token_type' => 'Bearer'
    	]);
    }
}
