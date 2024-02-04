@extends('layouts.app')
@section('content')
<div class="container">
    @if (session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
@endif
    <div class="card mt-4">
    <div class="card-body">
        <div class="d-flex">
          <h2>Contacts <small class="text-muted">Find A User By Their Id And Add Them To Your Contacts</small></h2>
          <div class="ml-auto" style="margin-left: auto">
          
          </div>
          <div class="ml-auto" style="margin-left: auto">
      
            <div class="dropdown">
              <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.contacts.dashboard')}}">Go To The Dashboard</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <form action="{{ route('admin.contacts.requests.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
      <div class="p-3">
  
          <div class="mb-3">
              <label for="id" class="form-label">User's ID</label>
              <input class="form-control" type="text" name="publicId" id="publicId" placeholder="Write the user's id here...">
          </div>
        
          <button class="btn btn-primary float-end mb-2" type="submit">
            Send contact Request
        </button>
      </div>
    </form>
    </div>
    

</div>

@endsection