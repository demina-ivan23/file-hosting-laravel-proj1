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
        $files = $authUser->files()->latest()->get();
        
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
    public function store(Request $request)
    {
        $authUser = User::find(auth()->user()->id);
        $data = $request->all();
        if($request->hasFile('file'))
        {
            $fileloader = new FileUploadService();
            $path = $fileloader->UploadFile($request->file('file'));
            $file = File::create([
                'path' => $path,
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category'],
                'user_id' => $authUser->id

            ]);
            $authUser->files()->attach($file);
            return redirect()->route('admin.contacts.dashboard')->with('success', 'File Uploaded Successfully');
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
