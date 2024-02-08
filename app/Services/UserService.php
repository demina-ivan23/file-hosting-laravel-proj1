<?php 

namespace App\Services;

use Exception;
use App\Models\User;

class UserService{
    static function findUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404);
        }
        return $user;
    }
    static function findUserByPublicId($publicId)
    {
        // $user_exists = User::where('publicId', $publicId)->exists();
        $user = User::where('publicId', $publicId)->first();
        if (!$user) {
            abort(404);
        }
        return $user;
    }
}