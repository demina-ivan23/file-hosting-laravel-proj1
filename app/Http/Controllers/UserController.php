<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index');  
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
    public function show(string $publicId)
    {
        $user = UserService::findUserByPublicId($publicId);
        return view('user.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $publicId)
    {
        $user = UserService::findUserByPublicId($publicId);
        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $publicId)
    {
        $result = UserService::updateUser($request, $publicId);
        if(str_contains($result, 'Successfully')){
            return redirect()->route('user.profile' , ['user' => $publicId])->with('success', $result);
        } else {
            return redirect()->route('user.edit', ['user' => $publicId])->with('error', $result);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $publicId)
    {
        //
    }
}
