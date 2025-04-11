<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class AuthRepository
{
    public function getUserByEmailPassword($email, $password)
    {
        $user = DB::table('users')
            ->select('email', 'password')
            ->where('email', $email)
            ->first();

        if ($user) {
            Hash::check($password, $user->password);
            return $user;
        } else {
            return false;
        }
    }

    public function getUser()
    {
        return DB::table('users')
            ->where("id", auth()->user()->id)
            ->first();
    }
}
