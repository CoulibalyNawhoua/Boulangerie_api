<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MobileAuthController extends Controller
{
    public function mobile_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if (! $token = JWTAuth::attempt(['phone' => $request->phone, 'password' => $request->password, 'active' => 1])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }


    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::user(),
            'backhouse' => Auth::user()->bakehouse()->select("id","name","address","phone")->first()

        ]);
    }


    public function get_auth_user(Request $request)
    {
        return response()->json($request->user());
    }


    public function mobile_auth_refresh() {
        return $this->createNewToken(auth()->refresh());
    }
}
