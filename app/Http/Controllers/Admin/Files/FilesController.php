<?php

namespace App\Http\Controllers\Admin\Files;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;


class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $authUser = User::find(auth()->user()->id);
        $files = $authUser->recievedFiles()->latest()->get()->merge($authUser->sentFiles()->latest()->get());

        
        return view('admin.files.index', ['files' => $files]);
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
    public function store(Request $request, $user)
    {
        $authUser = User::find(auth()->user()->id);
        $data = $request->all();
        $userReciever = User::find($user);
        if($request->hasFile('file'))
        {
            $fileloader = new FileUploadService();
            $path = $fileloader->UploadFile($request->file('file'));
            $file = File::create([
                'path' => $path,
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category'],
                'sender_id' => $authUser->id,
                'reciever_id' => $userReciever->id
            ]);
            $authUser->sentFiles()->attach($file, ['userReciever' => $userReciever->id]);

            return redirect()->route('admin.contacts.dashboard')->with('success', 'File Uploaded Successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $file = File::find($id);
        $file_path = public_path('storage\\' . $file->path);
        $file_path = str_replace('/', DIRECTORY_SEPARATOR, $file_path); 
        return response()->download($file_path);
    }

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
