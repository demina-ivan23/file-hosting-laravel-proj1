<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $authUser = User::find(auth()->id());
        $messages = $authUser->messages()->latest()->get();
       
        return view('admin.messages.index', ['messages' => $messages]);
    }

    public function destroy(string $id)
    {
        $message = Message::find($id);
        if(!$message){
            abort(404);
        } 
        $message->delete();
        return redirect(route('admin.messages.dashboard'))->with('success', 'Message Deleted');
    }
}
