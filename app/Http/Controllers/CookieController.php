<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\APIservices\CookieService;

class CookieController extends Controller
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'imageData' => 'required|string',
        ]);
        $binaryData = base64_decode($request->input('imageData')['base64']);
        $result = CookieService::createCookie(json_encode($binaryData));
        return response()->json(['message' => $result]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'imageData' => 'required|string',
        ]);
        $binaryData = base64_decode($request->input('imageData')['base64']);
        $exists = CookieService::checkIfCookieExists(json_encode($binaryData));
        return response()->json(['exists' => $exists]);
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
