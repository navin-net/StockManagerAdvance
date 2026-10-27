<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\{Auth, Hash};
use App\Http\Controllers\Controller;
use App\Http\Requests\{LoginRequest, RegisterRequest};
use App\Http\Resources\UserResource;
use App\Models\User;



class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'group_id' => 4,
            'status' => 'active',
            // 'company_id' => $request->company_id,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login'], 401);
        }
        if (!$user->isActive()) {
            return response()->json(['message' => 'Account disabled'], 403);
        }

        $user->ip_address = $request->ip();
        $user->save();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'login_ip' => $user->ip_address,

        ]);
    }

    public function me()
    {
        return new UserResource(auth()->user());
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
