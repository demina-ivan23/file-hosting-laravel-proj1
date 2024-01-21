<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class MultipleMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = User::find(auth()->id());
        $messages = $authUser->messages()->latest()->get();
       
        return view('admin.messages.multiple.index', ['messages' => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
            if($request['delete_messages']){
                if($request['delete_messages'] == 'all'){
                    $messages = Message::where('userReceiver', auth()->id())->get();
                    $messagesToDelete = [];
                    foreach($messages as $message){
                        $messagesToDelete[] = $message->id;
                    }
                }
                else{
                    $messagesToDelete = $request['delete_messages'];
                }
                foreach($messagesToDelete as $message)
                {
                    $message = Message::find($message);
                    if(!$message){
                        abort(404);
                    } 
                    $message->delete();
                }
                return redirect(route('admin.messages.dashboard'))->with('success', 'Messages Deleted');

            }
            else
            {
                return redirect(route('admin.messages.dashboard'))->with('error', 'No Messages Selected');

            }
        
    }
}
