<?php

namespace App\Http\Controllers\Admin\UserContact;

use App\Models\User;
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
        $currentUser = User::find(auth()->user()->id); 
        $contacts = $currentUser->contacts;
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
    public function destroy(string $id)
    {
        //
    }
}
