@extends('layouts.app')
@section('content')

<div class="container">

<div class="card mt-3">
    <div class="card-body">
        <div class="header">
            <div class="header__slideshow">
                <h1>This Is A Home Page</h1>
            </div>
            <div class="header__stats">
                <ul>
                    <li>
                        <h5>{{auth()->user()->name}}</h5>
                    </li>
                    <li>
                        <h5>{{auth()->user()->email}}</h5>
                    </li>
                    <li>
                        <h5>This Is A Stat Element</h5>
                    </li>
                    
                </ul>
            </div>
            <div class="header__text">
                
            </div>
        </div>
    </div>
</div>

</div>

@endsection
@section('styles')
    <link href="{{ asset('sass/home.css') }}" rel="stylesheet">
@endsection
