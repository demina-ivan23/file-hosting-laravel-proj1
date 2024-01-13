@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{route('admin.contacts.dashboard')}}" class="btn btn-light">Go Back To Contacts</a>
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex">

                    <h2>Save A File for A Personal Use</h2>
                    
                    <div class="ml-auto" style="margin-left: auto">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.files.dashboard')}}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            @if ($errors->count())
                
            <div class="alert alert-danger">
               <ul>
                @foreach ($errors->all() as $message)
               
                <li>{{ $message }}</li>
                @endforeach
               </ul>
            </div>
            @endif
            <form action="{{ route('admin.files.personal.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

<div class="p-3">

    <div class="mb-3">
        <label for="name" class="form-label">Add A Title</label>
        <input class="form-control" type="text" name="title" id="title" placeholder="Title...">
    </div>
    
    <div class="mb-3">
        
        <label for="email" class="form-label">Add A Description</label>
        <input class="form-control" type="text" name="description" id="description" placeholder="No description yet...">
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Add A Category</label>
        <input class="form-control" type="text" name="category" id="category" placeholder="Category...">
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Add A File</label>
        <input class="form-control" type="file" name="file" id="file">
    </div>
        <button class="btn btn-primary float-end mb-2" type="submit">
            Send
        </button>

    </div>
</div>

</div>
</form>


</div>
</div>
@endsection