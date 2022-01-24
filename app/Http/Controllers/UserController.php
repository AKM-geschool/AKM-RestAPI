<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

    public function updatePhoto(Request $request)
    {
        $this->validate($request, [
            'photo' => 'mimes:png,jpg|max:1024'
        ]);

        $photo = $request->input('photo');
        $token = $request->input('token');

        $user = User::where('token', $token)->first();
        $user->update([
            'photo' => $photo
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

        $generateToken = bin2hex(random_bytes(40));
        $user->update([
            'token' => $generateToken
        ]);

        return response()->json($user);
    }
}
