<?php

namespace App\Http\Controllers\Admin\Files;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\GlobalFile;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;
use App\Services\PersonalFileService;

class PersonalFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $files = PersonalFileService::getAllPersonalFiles(auth()->id());
        return view('admin.files.personal.index', ['files' => $files]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.files.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request['uuid'] && $request['fileUploadMode'] === 'bigSize') {
            if($request['extension'])
            {
                $result = PersonalFileService::saveFileViaPlupload($request);
            }
        } else {
            if($request['global'] && $request['global'] == true)
            {
                $result = PersonalFileService::copyGlobalFile($request['publicId']);
            } else {
                $result = PersonalFileService::storeFile($request);
            }
        }
        if(str_contains($result, 'Successfully'))
        {
            return redirect()->route('admin.files.personal.dashboard')->with('success', $result);
        }
        else
        {
            return redirect()->route('admin.files.personal.dashboard')->with('error', $result);
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
