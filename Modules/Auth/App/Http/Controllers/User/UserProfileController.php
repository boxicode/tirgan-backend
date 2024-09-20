<?php

namespace Modules\Auth\App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function userProfile()
    {
        $user = Auth::user();

        // check user for existed in DB
        if (!$user) {
            return response()->json([
                'error' => 'user has not existed'
            ], 402);
        }

        return response()->json([
            'data' => [
                'user' => $user,

            ]
        ], 200);
    }
}
