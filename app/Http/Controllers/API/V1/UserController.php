<?php

namespace App\Http\Controllers\API\V1;

use App\User;
use Exception;
use App\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index()
    {
        return new ApiResponse(true, 'List User', User::with('user_detail')->latest()->get());
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return new ApiResponse(true, 'User', $user);
        }
        return response()->json(new ApiResponse(false, 'User tidak ditemukan', $user), 404);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $request->validated();
        $user = User::find($id);
        if (Auth()->user()->id == $id) {
            try {
                DB::transaction(function () use ($user, $id) {
                    $user->update([
                        'name' => request('name'),
                        'email' => request('email'),
                    ]);

                    UserDetail::where('id', $id)
                        ->update([
                            'phone_number' => request('phone_number'),
                            'security_question' => request('security_question'),
                        ]);
                });
            } catch (Exception $e) {
                return new ApiResponse(false, 'error', $e);
            }
            return new ApiResponse(true, 'Berhasil Update User', null);
        }
        return response()->json(new ApiResponse(false, 'Gagal Update User', null), 403);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return new ApiResponse(true, 'Berhasil Menghapus User');
    }
}