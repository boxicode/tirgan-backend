<?php

namespace Modules\Auth\App\Http\Controllers\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
            'password' => bcrypt($request->password),
            'register_ip' => $request->getClientIp(),
        ]);

        $user->save();

        $tokenResult = $user->createToken('accessToken');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addSeconds(config("auth.password_timeout"));
        $token->save();

        return response()->json([
            'message' => 'Register Successful',
            'user' => $user,
            'accessToken' => $tokenResult->accessToken,

        ], 201);

    }
}
