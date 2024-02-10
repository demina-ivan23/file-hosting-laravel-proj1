@extends('layouts.app')

@section('content')

<div class="container">
    @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
@endif
@if (session('error'))
<div class="alert alert-danger">
  {{ session('error') }}
</div>
@endif


@if (auth()->id() === $user->id)
<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <div class="">
                    <div class="">Created At: {{$user->created_at}}</div>
                    <div class="">Updated At: {{$user->updated_at}}</div>
                </div>
                
            </div>
            <div>
                    <a href="{{route('user.edit', ['user' => $user->publicId])}}">
                        <img src="{{asset('storage/icons/edit.png')}}" alt="" width="40" title="edit profile">
                    </a>
            </div>
            
        </div>
    <div class="d-flex justify-content-center align-items-center mt-5">
        @if ($user->profileImage)
        <img src="{{asset('storage/'.$user->profileImage)}}" alt="user image" width="100" height="100">
            @else
        <img src="/users/profiles/images/user.png" alt="user image placeholder" width="100" height="100">
        @endif
    </div>
    <div class="d-flex justify-content-center mt-3">
            <h4>
                Your Name: {{$user->name}}
            </h4>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <h5>
                Your Email: {{$user->email}}
            </h5>
        </div>
        <div class="d-flex justify-content-between mt-10">
            <div>
                <h5>
                    Your public ID: {{$user->publicId}}
                </h5>
                
            </div>
            <div>
                <form action="{{route('user.reset_public_id', ['user' => $user->publicId])}}" method="POST">
                    @csrf 
                    @method('PUT')
                    <input type="text" name="name" value="{{$user->name}}" hidden>
                    <input type="text" name="email" value="{{$user->email}}" hidden>
                    <button type="submit" onclick="return confirm('Do You Really Want To Reset Your Public Id?')">
                        <img src="{{asset('storage/icons/circle-of-two-clockwise-arrows-rotation.png')}}" alt="" width="30">
                    </button>
                </form>
            </div>
            
        </div>
        <div class="mt-3" style="font-size:0.9em" >
            (When someone wants to add you as a contact, they need to paste or type it in a certain field. So make sure you show it only to the people you trust.)
        </div>
       
        <div class="d-flex justify-content-between mt-10">
            @if (0)
                
            @else
            <div>
            
                <h5>You have no channel yet.</h5>
            </div>
            <div>
                <form action="#">
                    @csrf
                        <button type="submit">
                            <img src="{{asset('storage/icons/add-button.png')}}" alt="" width="40" title="create a channel">
                        </button>
                    </form>
            </div>
                @endif
        </div>
    </div>
</div>
    @else
    
  
        <div style="position: fixed; top:50%; right:42%">
        <div class="d-flex justify-content-center ">
                <h5>
                    This IS NOT your Profile page.
                </h5>
            </div>
        </div>
    @endif


</div>

@endsection
{{-- @section('styles')
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
@endsection --}}

