<?php

namespace App\Http\Controllers\Api;


// use App\Models\User;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	// use AuthenticatesUsers;

	public function login(Request $request)
	{
		if (!Auth::attempt($request->only('email', 'password'))) {
			return response()->json([
				'message' => 'Invalid login details'
			], 401);
		}

        $token = $request->user()->createToken($request->token_name);

        return ['token' => $token->plainTextToken];
	}
}
