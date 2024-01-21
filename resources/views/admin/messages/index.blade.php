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
            <h2>Messages <small class="text-muted">Showing All Messages</small></h2>
            <div class="ml-auto" style="margin-left: auto">
            
            </div>
            <div class="ml-auto" style="margin-left: auto">
        
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Actions
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('admin.messages.multiple.index')}}">Delete Multiple Messages</a></li>
                  <li>
                    <form action="{{route('admin.messages.multiple.delete')}}" method="POST">
                      @csrf
                      @method('DELETE')
                      {{-- <input type="hidden" name="delete_messages[]" value='all'> --}}
                      <button class="dropdown-item" type="submit" value="all" name="delete_messages" onclick="return confirm('Do You Want To Delete All Messages?')">Delete All Messages</button>
                    </form>
                  </li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
          </div>

          @if ($messages->count())
          @foreach ($messages as $message)
                  @include('admin.messages.components.message-card', ['message' => $message])     
          @endforeach
          @endif
    
</div>

@endsection