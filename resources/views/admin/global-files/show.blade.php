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
   <div class="card mt-3">
    <div class="card-body">
        <div class="d-flex justify-content-center align-items-center">
            @if ($file->title)
            <h3>{{$file->title}}</h3>
            @else
                <h3>Untitled</h3>
            @endif
        </div>
        <div class="mt-3"><h5>Path: {{$file->path}}</h5></div>
        @if ($file->category)
        <div class="mt-2">Category: {{$file->category}}</div> 
        @else
        <div class="mt-2">No category</div> 
        @endif 
        @if ($file->description)
        <div class="mt-2">Description: {{$file->description}}</div>   
        @else
        <div class="mt-2">No description</div> 
        @endif
        @if ($file->mimeType === 'video/mp4')
        <div class="d-flex justify-content-center align-items-center mt-3 ">
          <video width="750" height="450" controls autoplay muted>
            <source src="{{ asset('storage/' . $file->path) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        </div>
        @endif
        @if (str_contains($file->mimeType, 'image'))
        <div class="d-flex justify-content-center align-items-center mt-3">
          <img src="{{ asset('storage/' . $file->path)}}" alt="" style="max-width: 90%; height: auto;">
        </div>
        @endif
   
        <div class="d-flex justify-content-end align-items-center">
          <div class="mt-3">Views: {{$file->views}}</div>
        </div>
    </div>

   </div>
</div>

@endsection