<?php

namespace App\Http\Controllers\Admin\Files;

use Exception;
use Illuminate\Http\Request;
use App\Services\FileService;
use App\Services\GlobalFileService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = FileService::getAllCategories();   
        $files = FileService::getAllFiles();
        return view('admin.files.index', ['files' => $files, 'categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($publicId)
    {
        $contact = FileService::createFiles($publicId);
        return view('admin.files.create', ['contact_user' => $contact]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $user)
    {
        try {
            Log::info($request);
            if ($request['uuid'] && $request['fileUploadMode'] === 'bigSize' && $request['extension']) {
                $result = FileService::sendFileViaPlupload($request, $user);
            }
            if ($request->hasFile('files')) {
                $result = FileService::sendFile($request, $user);
            }
            if (str_contains($result, 'Successfully')) {
                return redirect()->route('admin.files.dashboard')->with('success', $result);
            } else {
                return redirect()->route('admin.files.dashboard')->with('error', $result);
            }
        } catch (Exception $e) {
            Log::error('Error Uploading File: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $currentRoute = request()->route()->getName();
        if ($currentRoute === 'admin.files.show') {
            $path = FileService::getPath($id);
            if (!$path) {
                $message = 'File Not Found';
                return redirect()->route('admin.files.dashboard')->with('error', $message);
            }
            return response()->download($path);
        } else if ($currentRoute === 'admin.global-files.pubid.show.public') {
            $path = FileService::getPathByPubId($id);
            if (!$path) {
                $message = 'File Not Found';
                return redirect()->route('admin.global-files.public')->with('error', $message);
            }
            $file = GlobalFileService::getFileByPubId($id);
            GlobalFileService::incrementDownloads($file);
            return response()->download($path);
        } else if ($currentRoute === 'admin.files.pubid.show.protected') {
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
