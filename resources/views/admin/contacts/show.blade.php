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
  <a href="{{route('admin.contacts.dashboard')}}" class="btn btn-light">Go Back To Contacts</a>
    <div class="card mt-4">
        <div class="card-body">
          <div class="d-flex">
            <h2>Files <small class="text-muted">Showing  Files Related To {{$contact->name}}</small></h2>
            <div class="ml-auto" style="margin-left: auto">
            
            </div>
            <div class="ml-auto" style="margin-left: auto">
        
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Actions
                </button>
                <ul class="dropdown-menu">
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
                    <li><a class="dropdown-item" href="{{route('admin.contacts.show', ['user' => $contact->id, 'filter_sent_files' => $contact->id]) }}">Show Sent Files</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.contacts.show', ['user' => $contact->id, 'filter_received_files' => $contact->id])}}">Show Received Files</a></li>
                  <form action="#" method="GET">
                    <li><a class="dropdown-item" href="{{route('admin.contacts.show', ['user' => $contact->id])}}">Show All Files</a></li>
                  </form>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
          </div>

           @if ($files)
          @foreach ($files as $file)
                  @include('admin.files.components.file-card', ['file' => $file])     
          @endforeach
          
                <div class="mt-5">
                
                  {{$files->links()}}
                </div>
          @endif 
          
</div>

@endsection