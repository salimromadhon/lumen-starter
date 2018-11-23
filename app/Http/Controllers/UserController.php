<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function index(Request $request, $id = null)
    {
        if ($id != null and $id != 'me')
            $user = User::find($id);
        elseif ($id == 'me')
            $user = $request->user();
        else
            $user = User::all();

        if ($user == null)
            $status = 'failed';
        else
            $status = 'success';

        return response([
            'status' => $status,
            'message' => $user
        ]);
    }

    public function register(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user == null)
        {
            User::create([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            return response([
                'status' => 'success',
                'message' => 'Successfully registered.'
            ]);
        }

        return response([
            'status' => 'failed',
            'message' => 'Failed to register.'
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user != null and Hash::check($request->input('password'), $user->password))
        {
            $token = sha1(time());

            $register = User::find($user->id)->update([
                'api_token' => $token
            ]);

            if ($register)
            {
                return response([
                    'status' => 'success',
                    'message' => $token
                ]);
            }
        }

        return response([
            'status' => 'failed',
            'message' => 'Username or password is incorrect.'
        ]);
    }
}
