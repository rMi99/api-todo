<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        // header('Access-Control-Allow-Origin:*');

        $user = Auth::user();

        if ($user) {

            return response()->json([
                'status' => true,
                'user' => $user,
            ], 200);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
    }
}
