<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'school' => 'required',
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $hashPassword = Hash::make($password);
        $school = $request->input('school');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $hashPassword,
            'school' => $school,
        ]);

        return response()->json(['message' => 'Success'], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email salah, pastikan email anda sudah benar!'], 401);
        }

        $isValidPassword = Hash::check($password, $user->password);
        if (!$isValidPassword) {
            return response()->json(['message' => 'Password salah, pastikan password anda sudah benar!'], 401);
        }

        $credentials = $request->only('email', 'password');

        // $generateToken = bin2hex(random_bytes(40));
        $token = auth()->setTTL(300)->attempt($credentials);
        $user->update([
            'token' => $token
        ]);

        return response()->json([
            'success' => true,
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL()
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'token' => NULL
        ]);

        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($removeToken) {
            return response()->json([
                'succes' => true,
                'message' => 'Logout Berhasil!'
            ]);
        }
    }

}
