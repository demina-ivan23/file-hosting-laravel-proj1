<?php

namespace App\Http\Controllers\Admin\Files;

use App\Services\FileService;
use App\Models\File;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;
use App\Services\GlobalFileService;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentRoute = request()->route()->getName();
        if ($currentRoute === 'admin.files.dashboard') {
            $files = FileService::getAllFiles();
        }
        if ($currentRoute === 'admin.files.received') {
            $files = FileService::getReceivedFiles();
        }
        if ($currentRoute === 'admin.files.sent') {
            $files = FileService::getSentFiles();
        }
        return view('admin.files.index', ['files' => $files]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $contact = FileService::createFiles($id);
        return view('admin.files.create', ['contact_user' => $contact]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $user)
    {
        $result = FileService::sendFile($request, $user);
        if (str_contains($result, 'Uploaded Successfully')) {
            return redirect()->route('admin.files.dashboard')->with('success', $result);
        } else {
            return redirect()->route('admin.files.dashboard')->with('error', $result);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $currentRoute = request()->route()->getName();
        if($currentRoute === 'admin.files.show')
        {
            $path = FileService::getPath($id);
        if(!$path){
            $message = 'File Not Found';
            return redirect()->route('admin.files.dashboard')->with('error', $message);   
        }
            return response()->download($path);
        }
        else if($currentRoute === 'admin.global-files.pubid.show.public'){
            $path = FileService::getPathByPubId($id);            
            if (!$path) {
                $message = 'File Not Found';
                return redirect()->route('admin.global-files.public')->with('error', $message);
            }
            $file = GlobalFileService::getFileByPubId($id);
            GlobalFileService::incrementDownloads($file);
            return response()->download($path);
            
        }
        else if($currentRoute === 'admin.files.pubid.show.protected'){
            $path = FileService::getPathByPubId($id);            
            if (!$path) {
                $message = 'File Not Found';
                return redirect()->route('admin.global-files.protected')->with('error', $message);
            } 
            $file = GlobalFileService::getFileByPubId($id);
            GlobalFileService::incrementDownloads($file);
            return response()->download($path);
        }
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
        $result = FileService::deleteFile($id);
        if (str_contains($result, 'Deleted Successfully')) {
            return redirect()->route('admin.files.dashboard')->with('success', $result);
        } else {
            return redirect()->route('admin.files.dashboard')->with('error', $result);
        }
    }
}
