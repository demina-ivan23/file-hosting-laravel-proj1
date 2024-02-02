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
            <h2>Public Global Files <small class="text-muted">Showing Public Files</small></h2>
            <div class="ml-auto" style="margin-left: auto">
        
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Actions
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('admin.global-files.public.create')}}">Post A Public Global File</a></li>
                  <li><a class="dropdown-item" href="{{route('admin.global-files.protected.create')}}">Post A Contacts-only Global File</a></li>
                  <li><a class="dropdown-item" href="{{route('admin.global-files.protected')}}">Contacts-only Files</a></li>                
                  <li><a class="dropdown-item" href="#">Something else here</a></li>                
                </ul>
              </div>
            </div>
            <div class="ml-auto" style="margin-left: auto">
            <div class="dropdown">
              <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Sort
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('admin.global-files.public', ['sort_by' => 'latest'])}}">Show Latest</a></li>
                <li><a class="dropdown-item" href="{{route('admin.global-files.public', ['sort_by' => 'oldest'])}}">Show Oldest</a></li>
                <li><a class="dropdown-item" href="{{route('admin.global-files.public', ['sort_by' => 'most-viewed'])}}">Show Most Viewed</a></li> 
                <li><a class="dropdown-item" href="{{route('admin.global-files.public', ['sort_by' => 'most-liked'] )}}">Show Most Liked</a></li>
                <li><a class="dropdown-item" href="{{route('admin.global-files.public', ['sort_by' => 'most-downloaded'])}}">Show Most Downloaded</a></li>
                <li><a class="dropdown-item" href="{{route('admin.global-files.public', ['sort_by' => 'least-viewed'])}}">Show Least Viewed</a></li> 
                <li><a class="dropdown-item" href="{{route('admin.global-files.public', ['sort_by' => 'least-liked'])}}">Show Least Liked</a></li>
                <li><a class="dropdown-item" href="{{route('admin.global-files.public', ['sort_by' => 'least-downloaded'])}}">Show Least Downloaded</a></li>
                {{-- <li><a class="dropdown-item" href="{{route('admin.global-files.public.personalized')}}">Show Personalized</a></li> --}}
              </ul>
            </div>
            </div>
          </div>
        </div>
          </div>

           @if ($files->count())
          @foreach ($files as $file)             
          @include('admin.global-files.components.file-card', ['file' => $file])     
          @endforeach
          
          
          <div class="mt-5">
            {{$files->appends(request()->except('page'))->links()}}
          </div>
          @endif 
</div>

@endsection