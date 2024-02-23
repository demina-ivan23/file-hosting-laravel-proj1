@extends('layouts.app')

@section('content')
<div class="container">
                    @if(request()->route()->getName() === 'admin.files.create')
                        <a href="{{route('admin.contacts.dashboard')}}" class="btn btn-light">Go Back To Contacts</a>
                    @endif
                    @if(request()->route()->getName() === 'admin.files.personal.create')
                        <a href="{{route('admin.files.personal.dashboard')}}" class="btn btn-light">Go Back To Personal Files</a>
                    @endif
                    @if(request()->route()->getName() === 'admin.global-files.public.create')
                        <a href="{{route('admin.global-files.public')}}" class="btn btn-light">Go Back To Public Files</a>
                    @endif
                    @if(request()->route()->getName() === 'admin.global-files.protected.create')
                        <a href="{{route('admin.global-files.protected')}}" class="btn btn-light">Go Back To Contacts-only Files</a>
                    @endif
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
                    @if(request()->route()->getName() === 'admin.files.create')
                    <h2>Send A File To {{$contact_user->name}}</h2>
                    @endif
                    @if(request()->route()->getName() === 'admin.files.personal.create')
                    <h2>Save A File for A Personal Use</h2>
                    @endif
                    @if(request()->route()->getName() === 'admin.global-files.public.create')
                    <h2>Post A Public Global File</h2>
                    @endif
                    @if(request()->route()->getName() === 'admin.global-files.protected.create')
                    <h2>Post A Contacts-only Global File</h2>
                    @endif
                                 
                    <div class="ml-auto" style="margin-left: auto">
                    @if(request()->route()->getName() === 'admin.files.create')

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.contacts.dashboard')}}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                    @endif
                    @if(request()->route()->getName() === 'admin.files.personal.create')
                     <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.files.personal.dashboard')}}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                     </div>
                    @endif
                    @if(request()->route()->getName() === 'admin.global-files.public.create')
                    <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.global-files.public')}}">Public Files</a></li>
                                <li><a class="dropdown-item" href="{{route('admin.global-files.protected')}}">Contacts-only Files</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    @endif
                    @if(request()->route()->getName() === 'admin.global-files.protected.create')
                    <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('admin.global-files.protected')}}">Contacts-only Files</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.global-files.public')}}">Public Files</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    @endif
                </div>
                </div>
            </div>
            
            <hr>
            @if(request()->route()->getName() === 'admin.files.create')
              <form action="{{ route('admin.files.store', ['user' => $contact_user->id]) }}" method="POST" enctype="multipart/form-data" id="file-sending-form" @submit.prevent="handleFormSubmit">
            @endif
            @if(request()->route()->getName() === 'admin.files.personal.create')
            <form action="{{ route('admin.files.personal.store') }}" method="POST" enctype="multipart/form-data" name="file-sending-form" id="file-sending-form" @submit.prevent="handleFormSubmit">
            @endif
            @if(request()->route()->getName() === 'admin.global-files.public.create' || request()->route()->getName() === 'admin.global-files.protected.create')
            <form action="{{ route('admin.global-files.store') }}" method="POST" enctype="multipart/form-data" name="file-sending-form" id="file-sending-form" @submit.prevent="handleFormSubmit">
            @endif
                @csrf
                @if(request()->route()->getName() === 'admin.files.create')
                <input type="hidden" value="{{$contact_user->publicId}}" id="contact_user_publicId"/> 
                @endif
                
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
        <label for="fileUoladType">Select The File Upload Type</label>
        <select class="form-control" name="fileUploadType" id="fileUploadType" @change="handleFileUploadModeChange">
            <option value="normal">For small and medium size files</option>
            <option value="bigSize">For big files (archivation is unavailable)</option>
        </select>
    </div>
    <div id="bigFilesUploadMode" hidden>
        <div class="mb-3">
            <label for="filelist" class="form-label">Select Files</label>
            <ul id="filelist"></ul>
            <br />
            <pre id="console"></pre>
            <a id="browse" href="javascript:;">[Browse...]</a> 
        </div>
    </div>
    <div id="normalUploadMode">
        <div class="mb-3">
            <input class="form-control" type="file" name="files[]" id="files" multiple @change="toggleFileFormatDropdown">
        </div>
        <div class="mb-3" v-if="showFileFormatDropdown">
          <label for="fileFormat" class="form-label">Select File Format</label>
          <select class="form-control" name="fileCompressionFormat" id="fileCompressionFormat">
              @if(request()->route()->getName() === 'admin.files.create' || request()->route()->getName() === 'admin.files.personal.create')
              <option value="none">none</option>
              <option value="zip">zip</option>
              <option value="tar">tar (.tar)</option>
              @else
              <option value="zip">zip</option>
              <option value="tar">tar (.tar)</option>
              @endif
          </select>
        </div>
    </div>
    @if(request()->route()->getName() !== 'admin.files.create')
    <div class="mb-3">
        <label for="isPublic" class="form-label">How Accessible Do You Want To Make This File?</label>
        <select class="form-control" name="fileAccessibility" id="fileAccessibility">
            @if(request()->route()->getName() === 'admin.files.personal.create')
            <option value="private">Private</option>
            <option value="protected">Contacts-only</option>
            <option value="public">Public</option>
            @endif
            @if(request()->route()->getName() === 'admin.global-files.public.create')
            <option value="public">Public</option>
            <option value="protected">Contacts-only</option>
            <option value="private">Private</option>
            @endif
            @if(request()->route()->getName() === 'admin.global-files.protected.create')
            <option value="protected">Contacts-only</option>
            <option value="public">Public</option>
            <option value="private">Private</option>
            @endif
        </select>
    </div>
    @endif    
        <button class="btn btn-primary float-end mb-2" type="submit" id="formSubmit" @click="handleFormSubmit">
            Send
        </button>

    </div>

</form>


</div>
</div>
@endsection
@section('scripts')
@vite(['resources/js/plupload-2.3.9/js/plupload.full.min.js'])
@endsection
@section('scripts')

<script type="text/javascript">
var uploader = new plupload.Uploader({
  browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
  url: 'upload.php'
});
uploader.init();
uploader.bind('FilesAdded', function(up, files) {
  var html = '';
  plupload.each(files, function(file) {
    html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
  });
  document.getElementById('filelist').innerHTML += html;
});
 
</script>
@endsection