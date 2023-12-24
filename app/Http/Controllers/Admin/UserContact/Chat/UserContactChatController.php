<?php

namespace App\Http\Controllers\Admin\UserContact\Chat;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UserContact\SendFileRequest;

class UserContactChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(SendFileRequest $request)
    {
        $authUser = auth()->user();
        $data = $request->all();
        if($request->hasFile('file'))
        {
            $fileloader = new FileUploadService();
            $path = $fileloader->UploadFile($request->file('file'));
            $file = File::create([
                'path' => $path,
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category']

            ]);
            $file->sender()->attach($authUser);
            $authUser->files()->attach($file);
            return redirect()->route('admin.contacts.dashboard')->with('success', 'File Uploaded Successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userToSendTo = User::find($id);
        $userSending = User::find(auth()->id());
        if(Gate::allows('start-chat', [$userToSendTo, $userSending])){
           return view('admin.contacts.show', ['contact_user' => $userToSendTo]);
        }
        else{
           abort(403);
        }
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
