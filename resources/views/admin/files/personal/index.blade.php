@extends('layouts.app')
@section('content')
<div class="container">
  @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
  @endif
    <div class="card mt-4">
        <div class="card-body">
          <div class="d-flex">
            <h2>Files <small class="text-muted">Showing Personal Files</small></h2>
            <div class="ml-auto" style="margin-left: auto">
            
            </div>
            <div class="ml-auto" style="margin-left: auto">
        
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Actions
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('admin.files.personal.create')}}">Save A File For A Personal Use</a></li>
                  <li><a class="dropdown-item" href="{{route('admin.files.dashboard')}}">All Files</a></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>                
                </ul>
              </div>
            </div>
          </div>
        </div>
          </div>

           @if ($files->count())
          @foreach ($files as $file)
                  @include('admin.files.personal.components.personal-file-card', ['file' => $file])     
          @endforeach
          @endif 
    
</div>

@endsection