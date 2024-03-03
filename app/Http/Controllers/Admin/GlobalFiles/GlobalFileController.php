<?php

namespace App\Http\Controllers\Admin\GlobalFiles;

use App\Models\GlobalFile;
use Illuminate\Http\Request;
use App\Services\GlobalFileService;
use App\Http\Controllers\Controller;

class GlobalFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentRoute = $request->route()->getName();
        if ($currentRoute === 'admin.global-files.public') {
            $categories = GlobalFileService::getAllCategories(true);
            $publicFiles = GlobalFileService::getAllPublicFiles();
            return view('admin.global-files.public.index', ['files' => $publicFiles, 'categories' => $categories]);
        } else if ($currentRoute === 'admin.global-files.protected') {
            $categories = GlobalFileService::getAllCategories(false);
            $protectedFiles = GlobalFileService::getAllProtectedFiles();
            return view('admin.global-files.protected.index', ['files' => $protectedFiles, 'categories' => $categories]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('admin.files.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request['uuid'])
        {
            $result = GlobalFileService::storeFileViaPlupload($request);
            if (str_contains($result, 'Successfully')) {
                return redirect()->route('admin.global-files.public')->with('success', $result);
            } else {
                return redirect()->route('admin.global-files.public')->with('error', $result);
            }
        } else {
            $result = GlobalFileService::storeGlobalFile($request);
            if (str_contains($result, 'Successfully')) {
                return redirect()->route('admin.global-files.public')->with('success', $result);
            } else {
                return redirect()->route('admin.global-files.public')->with('error', $result);
            }
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $currentRoute = $request->route()->getName();
        if($currentRoute === 'admin.global-files.show')
        {
            $file = GlobalFileService::getFileByPubId($id);
            if($file->mimeType === "application/zip"){
                $files = GlobalFileService::getFilesFromArchive($file);
            return view('admin.global-files.show', ['file' => $file, 'extracted_files' => $files]);
            }
            GlobalFileService::incrementViews($file);
            return view('admin.global-files.show', ['file' => $file, 'extracted_files' => null]);
        }
        if($currentRoute === 'admin.global-files.show.like')
        {
            $file = GlobalFileService::getFileByPubId($id);
            
            $message = GlobalFileService::incrementLikes($file);
            return redirect()->back()->with('success', $message);
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
        $result = GlobalFileService::deleteFile($id);
        if(str_contains($result, 'Successfully')){
            return redirect()->back()->with('success', $result);
        } else {
            return redirect()->back()->with('error', $result);
        }
    }
}
