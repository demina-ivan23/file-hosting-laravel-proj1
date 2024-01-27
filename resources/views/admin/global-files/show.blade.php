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
        <div class="mt-2">Views: {{$file->views}}</div>
    </div>

   </div>
</div>

@endsection