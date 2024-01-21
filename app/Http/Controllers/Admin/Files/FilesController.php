<?php

namespace App\Http\Controllers\Admin\Files;

use App\Services\FileService;
use App\Models\File;
use App\Models\Message;
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
       $files = FileService::getAllFiles();
        return view('admin.files.index', ['files' => $files]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        FileService::createFiles($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $user)
    {
        FileService::sendFile($request, $user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $file = File::find($id);
        $file_path = public_path('storage\\' . $file->path);
        $file_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file_path); 
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
        $file = File::find($id);
        if(!$file){
            abort(404);
        }
        $receiver = $file->receiver;
        $sender = $file->sender;
        if($file->sender->id === auth()->id() xor $file->receiver->id === auth()->id())
        {
            $fileloader = new FileUploadService();
            $fileloader->deleteFile(str_replace( ['/', '\\'] ,DIRECTORY_SEPARATOR , $file->path));
            $file->delete();
            
           
            if($file->sender->id === auth()->id() && $file->receiver->id !== auth()->id())
            {

                $message_to_sender = Message::create([
                    'text' => "You Deleted A File That Was Sent To $receiver->email By You",
                    'system' => true,
                    'userReceiver' => $sender->id
                ]);
                $message_to_receiver = Message::create([
                    'text' => "User $sender->email Have Deleted The File They Sent You",
                    'system' => true,
                    'userReceiver' => $receiver->id
                ]);
            }

            if($file->receiver->id === auth()->id() && $file->sender->id !== auth()->id())
            {

                $message_to_sender = Message::create([
                    'text' => "User $receiver->email Have Deleted A File That Was Sent To Them By You",
                    'system' => true,
                    'userReceiver' => $sender->id
                ]);
                $message_to_receiver = Message::create([
                    'text' => "You Have Deleted The File That Was Sent To You By $sender->email",
                    'system' => true,
                    'userReceiver' => $receiver->id
                ]);
            }
            $receiver->messages()->attach($message_to_receiver);
            $sender->messages()->attach($message_to_sender);
            return redirect(route('admin.files.dashboard'))->with('success', 'File Deleted Successfully');
        }
        else if($file->receiver->id === auth()->id() && $file->sender->id === auth()->id())
        {
             
            $fileloader = new FileUploadService();
            $fileloader->deleteFile(str_replace( ['/', '\\'] ,DIRECTORY_SEPARATOR , $file->path));
            $file->delete();
            $message_to_yourself = Message::create([
                'text' => 'You Have Deleted Your Personal File',
                'system' => true,
                'userReceiver' => $sender->id
            ]);
        $sender->messages()->attach($message_to_yourself);
        return redirect(route('admin.files.personal.index'))->with('success', 'File Deleted Successfully');

        }
        
        else{
            abort(403, 'You Are Neither File\'s Sender Nor It\'s Receiver. But You Tried :)');
        }
    }
}
