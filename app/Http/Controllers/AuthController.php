<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'string|email|unique:users',
            'phone' => 'string|unique:users',
            'password' => 'required|string|confirmed',
            'level' => 'required|in:admin,customer',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => $request->level,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!',
        ], 201);
    }
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $credentials = request(['phone', 'password']);
        if (is_null($request->phone)) {
            $credentials = request(['email', 'password']);
        }
        if (!Auth::attempt($credentials))
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        $name = User::where("phone", $request->phone)->get();
        if (is_null($request->phone)) {
            $name = User::where("email", $request->email)->get();
        }
        return response()->json([
            'data' => $name,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
