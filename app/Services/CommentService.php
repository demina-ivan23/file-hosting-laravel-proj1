<?php

namespace App\Services;

use App\Models\User;
use App\Models\Comment;
use Exception;

class CommentService
{

    static function storeComment($file, $request)
    {
        try{

            $authUser = static::findUser(auth()->id());
            $comment = Comment::create([
                'text' => $request['text'],
                'user_id' => $authUser->id,
                'global_file_id' => $file->id
            ]);
            $file->comments()->attach($comment);  
            return 'Comment Created Successfully'; 
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }
    static function findUser($id){
        $user = User::find($id);
        if(!$user){
            abort(404);
        }
        return $user;
    }
}

