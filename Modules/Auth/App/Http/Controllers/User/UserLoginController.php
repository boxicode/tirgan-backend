<?php

namespace Modules\Auth\App\Http\Controllers\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\App\Models\User;

class UserLoginController extends Controller
{
    public function userLogin(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => ['email', 'required', 'max:255',],
            'password' => ['string', 'required'],
            'remember_me' => ['boolean'],
        ]);

        // If Validator has been Failed
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);

            // Check user Existed with email Address
        } else {
            $emailExist = User::where('email', $request->email)->first();

            if (!$emailExist) {
                return response()->json([
                    'error' => 'User has not Existed with this email address'
                ], 422);
            }
        }


        $cerdenentals = request(['email', 'password']);

        if (!Auth::attempt($cerdenentals)) {
            return response()->json([
                'error' => 'email and password wrong or whatever'
            ], 402);
        }

        $user = Auth::user();

        $tokenResult = $user->createToken('accessToken');
        $token = $tokenResult->token;

        if ($request->remember_me) {

            $token->expires_at = Carbon::now()->addWeek(1);


        } else {
            $token->expires_at = Carbon::now()->addSeconds(config("auth.password_timeout"));
        }

        $token->save();


        return response()->json([
            'data' => [
                'user' => $user,
                'accessToken' => $tokenResult->accessToken
            ]
        ], 200);

    }


    public function UserLogout()
    {

        $user = Auth::user();

        $user->token()->revoke();

        return response()->json([
            'data' => [
                'message' => 'User Successfully log out ',
            ]
        ], 200);

    }
}
