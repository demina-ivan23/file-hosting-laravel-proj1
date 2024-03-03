<?php

namespace App\Http\Controllers\Admin\Files;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserContactService;
use App\Services\MultipleFilesService;
use App\Http\Requests\Files\MultipleFilesRequest;
use App\Services\UserService;

class MultipleFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = MultipleFilesService::getAllFiles(auth()->id());
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
      $user = UserService::findUserByPublicId($user);
      if(!$user->contacts->contains(auth()->id())){
          abort(403, 'You Are Not Contacts With This User');
      }
      $result = MultipleFilesService::sendFiles($request, $user);
      if(str_contains($result, 'Successfully'))
      {
        return redirect()->route('admin.contacts.show', ['user' => $user])->with('success', $result);
      }
      else{
        return redirect()->route('admin.contacts.show', ['user' => $user])->with('error', $result);
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
       $result = MultipleFilesService::deleteFiles($request);
       if(str_contains($result, 'Successfully'))
       {
        return redirect()->route('admin.files.dashboard')->with('success', $result);
       } else {
        return redirect()->route('admin.files.dashboard')->with('error', $result);
       }
    }
}
