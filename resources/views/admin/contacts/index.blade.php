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
  <form method="GET" action="{{route('admin.contacts.dashboard')}}">
    <div class="input-group mb-3">
      <input type="text" class="form-control" name="search">
      <button class="input-group-text" type="submit">Search</button>
    </div>
    </form>
    <div class="card mt-4">
        <div class="card-body">
          <div class="d-flex">
            <h2>Contacts <small class="text-muted">Showing All Contacts</small></h2>
            <div class="ml-auto" style="margin-left: auto">
            
            </div>
            <div class="ml-auto" style="margin-left: auto">
        
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Actions
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('admin.contacts.requests.create')}}">Send A Contact Request</a></li>
                  <li><a class="dropdown-item" href="{{ route('admin.contacts.requests.dashboard')}}">Show Contact Requests</a></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
          </div>

          @if ($contacts->count())
          @foreach ($contacts as $contact)
                  @include('admin.contacts.components.contact-card', ['contact' => $contact])     
          @endforeach
          @endif
    
</div>

@endsection