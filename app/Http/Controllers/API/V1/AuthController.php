<?php

namespace App\Http\Controllers\API\V1;

use App\User;
use Exception;
use App\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthRegisterRequest;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $request->validated();
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => request('name'),
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);

                UserDetail::create([
                    'id' => $user->id,
                    'phone_number' => request('phone_number'),
                    'security_question' => request('security_question'),
                ]);
            });
        } catch (Exception $e) {
            return new ApiResponse(false, 'Error', $e);
        }
        return new ApiResponse(true, 'Berhasil Registrasi', 201);
    }

    public function login()
    {
        $this->validate(request(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = [
            'email' => request('email'),
            'password' => request('password')
        ];

        if (!auth()->attempt($data)) {
            return new ApiResponse(false, 'Username / password tidak cocok', null);
        }

        $token = auth()->user()->createToken('token')->accessToken;

        $data = [
            'token' => $token,
            'user' => auth()->user(),
        ];
        return new ApiResponse(true, 'Berhasil login.', $data);
    }

    public function logout()
    {
        if (auth()->user()) {
            auth()->user()->tokens->each(function ($token, $key) {
                $token->delete();
            });
        }
        return new ApiResponse(true, 'Berhasil logout.');
    }
}