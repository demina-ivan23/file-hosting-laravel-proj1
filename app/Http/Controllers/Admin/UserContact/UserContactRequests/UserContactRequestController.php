<?php

namespace App\Http\Controllers\Admin\UserContact\UserContactRequests;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\UserContactRequest;
use App\Http\Controllers\Controller;

class UserContactRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = UserContactRequest::where(['sender_id' => auth()->id()])
        ->orWhere(['receiver_id' => auth()->id()])->get();
        return view('admin.contacts.requests.index', ['requests' => $requests]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.contacts.requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $receiver = User::find($data['id']);
        // ddd($receiver);
        if(!$receiver){
         abort(403, "No User With Id Of {$data['id']}");
        }
        $sender = User::find(auth()->id());
        $contact_request = UserContactRequest::where([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id
        ])->first();
    if($contact_request){
        abort(403, 'You Have Already Sent A Request To This User');
    }
    if($receiver->contacts->contains($sender))
    {
        abort(403, 'You Are Already In The User\'s Contact List');
    }
    $contact_request = UserContactRequest::where([
        'sender_id' => $receiver->id,
        'receiver_id' => $sender->id
    ])->first();
if($contact_request){
    abort(403, 'This Person Have Already Sent You The Request. Check Your Requests Page');
}
        $contact_request = UserContactRequest::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id
        ]);
        $message_to_receiver = Message::create([
            'text' => "User With Email $sender->email Have Sent You A Contact Request. Check Your Requests Page",
            'system' => true,
            'userReceiver' => $receiver->id
        ]);
        $message_to_sender = Message::create([
            'text' => "You Sent A Contact Request To User With Id Of $receiver->id",
            'system' => true, 
            'userReceiver' => $sender->id
        ]);
$receiver->messages()->attach($message_to_receiver);
$sender->messages()->attach($message_to_sender);
        return redirect(route('admin.contacts.requests.dashboard'))->with('success', 'Your Contact Request Was Sent Successfully');
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
    public function destroy(string $id, string $state)
    {
        $contact_request = UserContactRequest::find($id);
        if(!$contact_request){
            abort(404, 'No Request Found');
        }
        if($state === 'canceled'){
            $message_to_sender = Message::create([
                'text' => "You Have Canceled The Request To The User With Id Of {$contact_request->receiver->id}",
                'system' => true,
                'userReceiver' => $contact_request->sender->id  
            ]);
            $sender = User::find($contact_request->sender->id);
            $sender->messages()->attach($message_to_sender);
            $contact_request->delete();
            return redirect(route('admin.contacts.requests.dashboard'))->with('success', 'Request Canceled Successfully');
        }
        if($state === 'declined'){
            $message_to_sender = Message::create([
                'text' => "Your Request Was Denied By The User With Id Of {$contact_request->receiver->id}",
                'system' => true,
                'userReceiver' => $contact_request->sender->id  
            ]);
            $message_to_receiver = Message::create([
                'text' => "You Have Denied The Request Of {$contact_request->sender->email}",
                'system' => true,
                'userReceiver' => $contact_request->receiver->id  
            ]);
            $sender = User::find($contact_request->sender->id);
            $sender->messages()->attach($message_to_sender);
            $receiver = User::find($contact_request->receiver->id);
            $receiver->messages()->attach($message_to_receiver);
            $contact_request->delete();
            return redirect(route('admin.contacts.requests.dashboard'))->with('success', 'Request Declined Successfully');
        }
    }
}
