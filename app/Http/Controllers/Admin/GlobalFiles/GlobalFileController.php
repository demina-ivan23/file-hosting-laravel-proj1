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
            $publicFiles = GlobalFileService::getAllPublicFiles();
            return view('admin.global-files.public.index', ['files' => $publicFiles]);
        } else if ($currentRoute === 'admin.global-files.protected') {
            $protectedFiles = GlobalFileService::getAllProtectedFiles();
            return view('admin.global-files.protected.index', ['files' => $protectedFiles]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $currentRoute = $request->route()->getName();
        if ($currentRoute === 'admin.global-files.public.create') {
            return view('admin.global-files.public.create');
        } else if ($currentRoute === 'admin.global-files.protected.create') {
            return view('admin.global-files.protected.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request['fileAccessibility'] == 'protected') {
            $result = GlobalFileService::storeProtectedFile($request);
            if (str_contains($result, 'Successfully')) {
                return redirect()->route('admin.global-files.protected')->with('success', $result);
            } else {
                return redirect()->route('admin.global-files.protected')->with('error', $result);
            }
        } else if ($request['fileAccessibility'] == 'public') {
            $result = GlobalFileService::storePublicFile($request);
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
            GlobalFileService::incrementViews($file);
            return view('admin.global-files.show', ['file' => $file]);
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
        //
    }
}
