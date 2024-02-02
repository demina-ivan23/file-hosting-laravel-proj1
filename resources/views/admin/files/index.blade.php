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
    <div class="card mt-4">
        <div class="card-body">
          <div class="d-flex">
            <h2>Files <small class="text-muted">Showing All Files (Not Global)</small></h2>
            <div class="ml-auto" style="margin-left: auto">
            
            </div>
            <div class="ml-auto" style="margin-left: auto">
        
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Actions
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('admin.files.personal.create')}}">Save A File For A Personal Use</a></li>
                  <li><a class="dropdown-item" href="{{route('admin.files.personal.dashboard')}}">Files For Personal Use</a></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>                
                </ul>
              </div>
            </div>
            <div class="ml-auto" style="margin-left: auto">

              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Filter
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('admin.files.received', ['id' => 'all'])}}">Received Files</a></li>
                  <li><a class="dropdown-item" href="{{route('admin.files.sent', ['id' => 'all'])}}">Sent Files</a></li>
                  <form action="#" method="GET">
                    <li><a class="dropdown-item" href="{{route('admin.files.dashboard')}}">Show All Files</a></li>
                  </form>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </div>
            </div>
            
          </div>
        </div>
          </div>

           @if ($files->count())
          @foreach ($files as $file)
          @if ($file->sender->id === $file->receiver->id)
          @include('admin.files.personal.components.personal-file-card', ['file' => $file])     
          @else              
          @include('admin.files.components.file-card', ['file' => $file])
          @endif
          @endforeach
          <div class="mt-5">
          
            {{$files->appends(request()->except('page'))->links()}}
          </div> 
          @endif
          
    
</div>

@endsection