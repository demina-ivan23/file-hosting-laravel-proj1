<?php

namespace App\Services;

use Exception;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
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
        try {

            $user = static::findUserByPublicId($publicId);
            if ($user->id !== auth()->id()) {
                return 'This Is NOT Your Profile. So Be Kind And Don\'t Try To Be A Wiseass.';
            }
            $profileImage = $user->profileImage; // Retain existing profile image path by default

            if ($request->hasFile('profileImage')) // Check if a new profile image is uploaded
            {
                static::deletePreviousProfileImage($profileImage);
                $profileImage = static::uploadProfileImage($request->file('profileImage'));
                // dd($profileImage); 
            }
            $name = $request['name'];
            $email = $request['email'];
            if ($request['password'] != '' || $request['password_confirmation'] != '') {
                $password = $request['password'];
                $password_confirm = $request['password_confirmation'];
                if ($password === $password_confirm) {
                    $newPassword = bcrypt($password);
                } else {
                    return 'Password And Password Confirm Do Not Match';
                }
            } else {
                $newPassword = $user->password;
            }
            $user->update([
                'name' => $name,
                'email' => $email,
                'password' => $newPassword,
                'profileImage' => $profileImage
            ]);
            // dd($user->profileImage, $profileImage); 

            return 'Profile Updated Successfully';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    static function uploadProfileImage(UploadedFile $profileImage, $disk = 'public', $filename = null)
    {
        $FileName = !is_null($filename) ? $filename : Str::random(10);
        return $profileImage->storeAs(
            'users/profiles/images',
            $FileName . "." . $profileImage->getClientOriginalExtension(),
            $disk
        );
    }
    static function deletePreviousProfileImage(?string $path,  $disk = 'public')
    {
        if($path){
            Storage::disk($disk)->delete($path);
        } 
    }
}
