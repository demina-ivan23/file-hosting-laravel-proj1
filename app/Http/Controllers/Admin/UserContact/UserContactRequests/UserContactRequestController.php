<?php

namespace App\Http\Controllers\Admin\UserContact\UserContactRequests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserContactRequestService;

class UserContactRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = UserContactRequestService::getAllRequests(auth()->id());
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
        $result = UserContactRequestService::sendRequest($request);
        if(str_contains($result, 'Sent Successfully'))
        {
            return redirect()->route('admin.contacts.requests.dashboard')->with('success', $result);
        }
        else {
            return redirect()->route('admin.contacts.requests.dashboard')->with('error', $result);
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
    public function destroy(string $id, string $state)
    {
        $currentRoute = request()->route()->getName();
        if($currentRoute === 'admin.contacts.requests.delete')
        {
            $result = UserContactRequestService::deleteRequest($id, $state);
        }
        if($currentRoute === 'admin.contacts.requests.delete-block')
        {
            $result = UserContactRequestService::deleteRequestAndBlockUser($id, $state);
        }
        
        if(str_contains($result, 'Successfully')){
            return redirect()->route('admin.contacts.requests.dashboard')->with('success', $result);
        }
        else{
            return redirect()->route('admin.contacts.requests.dashboard')->with('error', $result);
        }
    }
}
