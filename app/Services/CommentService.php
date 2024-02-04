<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Str;

class CommentService
{

    static function storeComment($file, $request)
    {
        try{
            $authUser = static::findUser(auth()->user()->publicId);
            $comment = Comment::create([
                'text' => $request['text'],
                'user_id' => $authUser->publicId,
                'global_file_id' => $file->publicId
            ]);
            $file->comments()->attach($comment);  
            return 'Comment Created Successfully'; 
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }
    static function deleteComment($id)
    {
        $authUser = static::findUser(auth()->id());
        $comment = static::findComment($id);
        if($comment->author->id === $authUser->id)
        {
            $comment->delete();
            return 'Comment Deleted Successfully';
        } else {
            return 'You Are Not The Comment\'s Owner';
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
    static function findUser($publicId){
        $user = User::where('publicId', $publicId)->first();
        if(!$user){
            abort(404);
        }
        return $user;
    }
}

