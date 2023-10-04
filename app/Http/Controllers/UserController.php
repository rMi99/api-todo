<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        header('Access-Control-Allow-Origin:*');
        // Get the authenticated user using the provided token
        $user = Auth::user();

        if ($user) {
            // User is authenticated, return user information
            return response()->json([
                'status' => true,
                'user' => $user,
            ], 200);
        } else {
            // User is not authenticated
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
    }
}
