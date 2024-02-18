@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{route('admin.global-files.protected')}}" class="btn btn-light">Go Back To Contacts-only Files</a>
        @if ($errors->count())
            
        <div class="alert alert-danger">
           <ul>
            @foreach ($errors->all() as $message)
           
            <li>{{ $message }}</li>
            @endforeach
           </ul>
        </div>
        @endif
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex">

                    <h2>Post A Contacts-only Global File</h2>
                    
                    <div class="ml-auto" style="margin-left: auto">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('admin.global-files.public')}}">Contacts-only Files</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
          
            <form action="{{ route('admin.global-files.store') }}" method="POST" enctype="multipart/form-data" name="file-sending-form" id="file-sending-form" @submit.prevent="handleFormSubmit">
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
        <label for="file" class="form-label">Select Files</label>
        <input class="form-control" type="file" name="files[]" id="files" multiple @change="toggleFileFormatDropdown">
    </div>
    <div class="mb-3" v-if="showFileFormatDropdown">
        <label for="fileFormat" class="form-label">Select File Format</label>
        <select class="form-control" name="fileCompressionFormat" id="fileCompressionFormat">
            <option value="zip">zip</option>
            <option value="tar">tar (.tar)</option>

        </select>
    </div>
    <div class="mb-3">
        <label for="isPublic" class="form-label">How Accessible Do You Want To Make This File?</label>
        <select class="form-control" name="fileAccessibility" id="fileAccessibility">
            <option value="protected">Contacts-only</option>
            <option value="public">Public</option>
            <option value="private">Private</option>

        </select>
    </div>
    <button class="btn btn-primary float-end mb-2" type="submit">
        Save
    </button>
</div>
</form>

</div>
</div>

@endsection