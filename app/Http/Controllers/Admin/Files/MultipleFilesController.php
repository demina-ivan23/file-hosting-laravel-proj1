<?php

namespace App\Http\Controllers\Admin\Files;

use App\Models\File;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\UploadedFile;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Services\ArchiveMakers\RarArchiveMaker;
use App\Services\ArchiveMakers\TarArchiveMaker;
use App\Services\ArchiveMakers\ZipArchiveMaker;
use App\Http\Requests\Files\MultipleFilesRequest;
use App\Services\ArchiveMakers\SevenZipArchiveMaker;

class MultipleFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $authUser = User::find(auth()->user()->id);
        $files = File::where(function ($query) {
            $query->where('sender_id', auth()->id());
        })
        ->orWhere(function ($query) {
            $query->where('receiver_id', auth()->id());
        })
        ->filter() 
        ->latest()
        ->get();
        return view('admin.files.delete-multiple.index', ['files' => $files]);

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
    public function store(MultipleFilesRequest $request, $user)
{
    $authUser = User::find(auth()->user()->id);
    $userReceiver = User::find($user);
    $data = $request->all();
    $fileCompressionFormat = $data['fileCompressionFormat'];

    if ($request->hasFile('files')) {
        $files = $request->file('files');
    if ($fileCompressionFormat === 'none') {
            
            foreach ($files as $file) {
                $fileloader = new FileUploadService();
                $path = $fileloader->uploadFile($file);

                $createdFile = File::create([
                    'path' => $path,
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'category' => $request->input('category'),
                    'sender_id' => $authUser->id,
                    'receiver_id' => $userReceiver->id,
                ]);

                $authUser->sentFiles()->attach($createdFile, ['userReceiver' => $userReceiver->id]);
            }

            return redirect()->route('admin.contacts.dashboard')->with('success', 'Files Uploaded Successfully');
        }
        if($fileCompressionFormat === 'zip')
        {
            $archivemaker = new ZipArchiveMaker(new FileUploadService());
            $archive_name = Str::random(20) . '.zip';
            $archivePath = $archivemaker->makeArchive($files, $archive_name);
            // ddd(file_exists('C:\xampp\htdocs\file-hosting-laravel-proj1\public\storage\files\archives\8gvqw2TxLY4ZGZ4DKOQ9.zip'), file_exists(public_path('storage\files\archives\\' . $archive_name)));
            // $archive = new UploadedFile(Storage::path($archivePath), $archive_name);
            $file = File::create([
                'path' =>  str_replace(['/', '\\'], DIRECTORY_SEPARATOR ,  'files/archives/' . $archive_name),
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category'],
                'sender_id' => $authUser->id,
                'receiver_id' => $userReceiver->id
            ]);
            $authUser->sentFiles()->attach($file, ['userReceiver' => $userReceiver->id]);
            return redirect()->route('admin.contacts.dashboard')->with('success', '.zip Archive Uploaded Successfully');
            
        }
   
    
    if($fileCompressionFormat === 'tar')
    {
        $archivemaker = new TarArchiveMaker(new FileUploadService());
        $archive_name = Str::random(20) . '.tar';
        $archive = $archivemaker->makeArchive($files, $archive_name);
        $file = File::create([
            'path' =>  str_replace(['/', '\\'], DIRECTORY_SEPARATOR ,  'files/archives/' . $archive_name),
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'sender_id' => $authUser->id,
            'receiver_id' => $userReceiver->id
        ]);
        $authUser->sentFiles()->attach($file, ['userReceiver' => $userReceiver->id]);
        return redirect()->route('admin.contacts.dashboard')->with('success', '.tar Archive Uploaded Successfully');
            
    }
    
    // Handle other compression formats or return a response accordingly
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
    public function destroy(Request $request)
    {
         if($request['delete_files']){
            if($request['delete_files'] == 'all'){
                $files = File::where('sender_id', auth()->id())->get();
                $filesToDelete = [];
                foreach($files as $file){
                    $filesToDelete[] = $file->id;
                }
            }
            else{
                $filesToDelete = $request['delete_files'];
            }
            foreach($filesToDelete as $file)
            {
                $file = File::find($file);
                if(!$file){
                    abort(404);
                }
                $receiver = User::find($file->receiver_id);
                $sender = User::find($file->sender_id); 
                $file->delete();
                if($file->sender->id === auth()->id() xor $file->receiver->id === auth()->id()){

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
            return redirect(route('admin.files.dashboard'))->with('success', 'Files Deleted');

        }
        else
        {
            return redirect(route('admin.files.dashboard'))->with('error', 'No Files Selected');

        }
    }
}
