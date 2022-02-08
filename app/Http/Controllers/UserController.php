<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = User::all();
        return response()->json([
            'user' => $user,
        ]);
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

    //
}
