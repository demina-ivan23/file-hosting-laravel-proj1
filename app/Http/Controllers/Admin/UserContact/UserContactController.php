<?php

namespace App\Http\Controllers\Admin\UserContact;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserContactService;
use App\Http\Requests\UserContacts\StoreUserContactRequest;

class UserContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = UserContactService::getContacts();
        return view('admin.contacts.index', ['contacts' => $contacts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserContactRequest $request)
    {
        $contactCreated = UserContactService::storeContact($request);
        if ($contactCreated === true) {
            return redirect('/contacts')->with('success', 'Contact created successfully');
        }
        else {
            return redirect('/contacts/create')->with('error', $contactCreated);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $currentRoute = request()->route()->getName();
        $contact = UserContactService::findUser($id);
        if($currentRoute === 'admin.contacts.show'){
            $files = UserContactService::getRelatedFiles($id);
        }
        if($currentRoute === 'admin.contacts.show.received')
        {
            $files= UserContactService::getReceivedFiles($id);
        }
        if($currentRoute === 'admin.contacts.show.sent'){
            $files = UserContactService::getSentFiles($id);
        }
        return view('admin.contacts.show', ['contact' => $contact, 'files' => $files]);
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
       $result = UserContactService::blockUser($request, $id);

        
    if (str_contains($result, 'Blocked')) {
        return redirect()->route('admin.contacts.dashboard')->with('success', $result);
    } elseif (str_contains($result, 'Unblocked')) {
        return redirect()->route('admin.contacts.dashboard')->with('success', $result);
    } else {
        return redirect()->route('admin.contacts.dashboard')->with('error', $result);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        $result = UserContactService::deleteContact($id);
        if(str_contains($result, 'Deleted Successfully')){
            return redirect()->route('admin.contacts.deshboard')->with('success', $result);
        }
        else{
            return redirect()->route('admin.contacts.dashboard')->with('error', $result);
        }

    }
}
