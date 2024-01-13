<?php

namespace App\Http\Controllers\Admin\Files;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;

class PersonalFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::where(function ($query) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', auth()->id());
        })->latest()->get();
   
        return view('admin.files.personal.index', ['files' => $files]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.files.personal.create');
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
                'sender_id' => $authUser->id,
                'receiver_id' => $authUser->id
            ]);
            $authUser->sentFiles()->attach($file, ['userReceiver' => $authUser->id]);
                
            return redirect()->route('admin.files.personal.index')->with('success', 'File Uploaded Successfully');
        }
        if(array_key_exists('path', $data))
        {
            if($data['path']){

                $oldFile = File::where(['path' => $data['path']])->first();
                if($oldFile->receiver->id !== $oldFile->sender->id)
                {
                    $oldFile->delete();
                    $file = File::create([
                        'path' => $data['path'],
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'category' => $data['category'],
                        'sender_id' => $authUser->id,
                        'receiver_id' => $authUser->id
                    ]);
                    $authUser->sentFiles()->attach($file, ['userReceiver' => $authUser->id]);
                    
                    return redirect()->route('admin.files.personal.index')->with('success', 'File Added Successfully');
                }
                else{
                    abort(403, 'This File Is Already In Your Personal Files');
                }
            }
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
