<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class WebAuthController extends Controller
{


    public function signin(Request $request){

        $validated = $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        if (! $token = JWTAuth::attempt(['phone' => $request->phone, 'password' => $request->password, 'active' => 1])) {

            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes']
            ]);
        }

        $user = Auth::user();
        if ($user->hasRole('livreur')) {
            throw ValidationException::withMessages([
                'role' => ['Vous n\'êtes pas autorisé à vous connecter']
            ]);
        }

        return $this->createNewToken($token);
    }


    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
        ]);
    }

    public function me()
    {
        $user = Auth::user();

        return response()->json(['data'=> new UserResource($user)]);
    }


    public function user_auth_change_password(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['required','same:new_password'],
        ]);

        auth()->user()->update(['password' => Hash::make($request->input('new_confirm_password'))]);

        if ($request->expectsJson()) {
            return response()->json($request->all());
        }

    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }


}
