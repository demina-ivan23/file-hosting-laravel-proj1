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
    static function incrementLikes($id){
            $authUser = static::findUser(auth()->id());
            $comment = static::findComment($id);
            if(!$authUser->likedComments->contains($comment->id))
            {
                $likes = $comment->likes + 1;
                $comment->update(['likes' => $likes]);
                $authUser->likedComments()->attach($comment->id);
                return 'Comment Liked Successfully';
            } else {
                return 'You Have Already Liked That Comment';
            }
    }
    static function findComment($id){
        $comment = Comment::find($id);
        if(!$comment){
            abort(404);
        }
        return $comment;
    }
    static function findUser($id){
        $user = User::find($id);
        if(!$user){
            abort(404);
        }
        return $user;
    }
}

