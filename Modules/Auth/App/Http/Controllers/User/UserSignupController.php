<?php

namespace Modules\Auth\App\Http\Controllers\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Auth\App\Events\SignedupProcessed;
use Modules\Auth\App\Models\User;

class UserSignupController extends Controller
{

    public function userSignup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'gender' => ['string', 'required'],
            'email' => ['string', 'required', 'max:255', 'unique:users'],
            'password' => ['string', 'required', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $emailExist = User::where('email', $request->email)->first();

            if ($emailExist) {
                return response()->json([
                    'error' => 'Email has Existed'
                ], 401);
            }

        }


        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'email' => $request->email,
            'activation_token' => Str::random(60),
            'password' => bcrypt($request->password),
            'register_ip' => $request->getClientIp(),
        ]);

        $user->save();


        $tokenResult = $user->createToken('accessToken');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addSeconds(config("auth.password_timeout"));
        $token->save();

        SignedupProcessed::dispatch($user);

        return response()->json([
            'message' => 'Register Successful',
            'user' => $user,
            'accessToken' => $tokenResult->accessToken,

        ], 201);

    }


    public function emailSignupActive($token)
    {

        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'This activation token is Invalid!!'

            ], 404);
        }

        $user->update([
            $user->activation_token = '',
            $user->email_verified_at = Carbon::now()
        ]);


        return response()->json([
            'message' => 'Email has ben verified'

        ], 200);

    }
}
