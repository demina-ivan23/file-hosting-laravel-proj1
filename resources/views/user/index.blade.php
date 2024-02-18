@extends('layouts.app')
@section('content')

<div class="container">

<div class="card mt-3">
    <div class="card-body">
        <div class="header">
            <div class="header__headline">
                Headline Of This Website 
            </div>
            <div class="header__subheadline">
                Subheadline of the website
            </div>
            <div class="header__authentication">
                <div class="action">
                    <a class="action__register-btn" href="{{route('register')}}">Get Started</a>
                </div>
                <div class="header__authentication__text">
                    Or, if You already have an account
                </div>
                <div class="action">
                    <a class="action__login-btn" href="{{route('login')}}">Log In</a>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

@endsection
@section('styles')
    <link href="{{ asset('sass/home.css') }}" rel="stylesheet">
@endsection
