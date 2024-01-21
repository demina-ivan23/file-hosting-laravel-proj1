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
            <h2>Files <small class="text-muted">Select Files To Delete</small></h2>
            <div class="ml-auto" style="margin-left: auto">
            
            </div>
            <div class="ml-auto" style="margin-left: auto">
        
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Actions
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('admin.messages.dashboard')}}">Go To Main Messages Page</a></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
    </div>
    
    @if ($files->count())
    <form action="{{route('admin.files.multiple.delete')}}" method="POST">
        @csrf
        @method('DELETE')
          @foreach ($files as $file)
                  @include('admin.files.delete-multiple.components.file-check-card', ['file' => $file])     
          @endforeach
          
          <div class="d-flex justify-content-end mt-3">
              <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Do You Want To Delete Selected Files?') ">Delete</button>
            </div>
        </form>
        @endif
</div>

@endsection