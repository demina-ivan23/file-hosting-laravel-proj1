<?php 

namespace App\Services;

use Exception;
use App\Models\User;

use function PHPUnit\Framework\isEmpty;

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
    static function updateUser($request, $publicId)
    {
        $user = static::findUserByPublicId($publicId);
        if($user->id !== auth()->id())
        {
            return 'This Is NOT Your Profile. So Be Kind And Don\'t Try To Be A Wiseass.';
        }
        $name = $request['name'];
        $email = $request['email'];
        if($request['password'] != '' || $request['password_confirmation'] != '')
        {
            $password = $request['password'];
            $password_confirm = $request['password_confirmation'];
            if($password === $password_confirm)
            {
                $newPassword = bcrypt($password);
            }
            else{
                return 'Password And Password Confirm Do Not Match';
            }
        }
        else{
            $newPassword = $user->password;
        }
        $user->update([
            'name' => $name,
            'email' => $email,
            'password' => $newPassword
        ]);

        return 'User Updated Successfully';

    }
}