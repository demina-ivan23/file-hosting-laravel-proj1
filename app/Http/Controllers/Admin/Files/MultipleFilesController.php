<?php

namespace App\Http\Controllers\Admin\Files;

use App\Models\File;
use App\Models\User;
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
    public function destroy(string $id)
    {
        //
    }
}
