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
        $currentRoute = request()->route()->getName();
        if($currentRoute == "save-canvas-cookie")
        {    
            $responce = CookieService::createCookie('canvasId', $request['canvasCookie']['canvasId']);
        }
       return $responce;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
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
    public function update(Request $request)
    {
        $currentRoute = request()->route()->getName();
        if($currentRoute === 'update-canvas-cookie')
        {
            $responce = CookieService::updateCookie('canvasId', request()->cookie('canvasId'));
        }
        return $responce;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
