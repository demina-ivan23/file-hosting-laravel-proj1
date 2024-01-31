<?php

namespace App\Http\Controllers;

use App\Models\GlobalFile;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Services\GlobalFileService;

class CommentController extends Controller
{
    public function store(string $id, Request $request){
        $file = GlobalFileService::getFileByPubId($id);
        $result = CommentService::storeComment($file, $request);
        if(str_contains($result, 'Successfully')){
            return redirect()->back()->with('success', $result);
        } else {
            return redirect()->back()->with('error', $result);

        }
    }
    public function show($id){
        $currentRoute = request()->route()->getName();
        if($currentRoute === 'admin.global-files.comments.show.like'){
            $result = CommentService::incrementLikes($id);
            if(str_contains($result, 'Successfully')){
                return redirect()->back()->with('success', $result);
            } else {
                return redirect()->back()->with('error', $result);
            }
        }
    }

    public function destroy($id){
        $result = CommentService::deleteComment($id);
        if(str_contains($result, 'Successfully')){
            return redirect()->back()->with('success', $result);
        } else {
            return redirect()->back()->with('error', $result);
        }
    }
}
