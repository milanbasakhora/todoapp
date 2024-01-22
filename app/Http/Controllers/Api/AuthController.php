<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //register
    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);

        if ($validator->fails()) {
            $data = [
                "success" => false,
                "message" => $validator->errors(),
            ];
            return response()->json($data, 400);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        User::create($input);

        $data = [
            "success" => true,
            "message" => "Registration success",
        ];

        return response()->json($data, 200);
    }
    //login
    function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $data = [
                "success" => false,
                "message" => "unauthorized"
            ];
            return response()->json($data, 400);
        } else {
            $token = $user->createToken('MyApp')->plainTextToken;
            $data = [
                "success" => true,
                "token" => $token,
                "message" => "Login success"
            ];
            return response()->json($data, 200);
        }
    }

    //logout
    function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $data = [
            "success" => true,
            "message" => "logout success"
        ];
        return response()->json($data, 200);
    }
}
