<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user_id = User::where ("email",$request->email)->select('id')->first();
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'username' => $request->name,
                'user_id' => $user_id->id,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
            return 'reg';
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */

    public function loginUser(Request $request){
        $validator = Validator::make($request->all(), [
             'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => "Bad Request"
            ],400);
        }

        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'status' => 401,
                'message' => "Unautherized"
            ],401);
        }
            $user = User::where ("email",$request->email)->select('id','name','email')->first();
           $token= $user->createToken("API TOKEN")->plainTextToken;

           Arr::add($user,'token',$token);
            return response()->json($user);
    }

public function logout(Request $request){

    $user = $request->user();
    $user->currentAccessToken()->delete();
    return response()->json([
        'status' => 200,
        'message' => "Successfully Logout!!"
    ],200);
}

/**  Get User By Token
* @param Request
* @return User $user
*/
public function getUser(Request $request){
    return response()->json(['user'=>$request->user()]);
}
}
