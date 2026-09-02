<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    use ApiResponse;

     public function index() {
        $users = User::all();
        return $this->successResponse($users);
    }

    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id); 

        return $this->successResponse($user);
    }
    public function register(Request $request){
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'age'      => 'nullable|integer|min:0',
            'role'     => 'nullable|string|in:admin,customer,owner',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'age'      => $validated['age'] ?? null,
            'role'     => $validated['role'] ?? 'customer',
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->createdResponse([
            'user'  => $user,
            'token' => $token
        ], "Registrasi berhasil");
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user  = User::where('email', $validated['email'])->first();

        if(!$user || !Hash::check($validated['password'], $user->password)) {
            return  $this->validationErrorResponse("email atau password salah");
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        return $this->successResponse([
            'user' => $user,
            'token' => $token
        ],"Login Berhasil");

    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(message:"Logout Berhasil");
    }
}
