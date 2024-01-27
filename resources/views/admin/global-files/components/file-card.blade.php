<div class="card mt-3">
    <div class="card-body">

            <div class="row">

                    <div class="col-sm-4">
                        <h5>{{$file->title}}</h5>
                        
                        
                        <h6> {{$file->path}}</h6>
                        <div>

                            @if ($file->isPublic == true)
                            <a class="btn btn-outline-primary" href="{{route('admin.files.pubid.show.public', ['filePubId' => $file->publicId])}}">Download</a>
                            @endif
                            @if ($file->isPublic == false)
                            <a class="btn btn-outline-primary" href="{{route('admin.files.pubid.show.protected', ['filePubId' => $file->publicId])}}">Download</a>
                            @endif
                        </div>
                        
                    </div>
                
                    <div class="col-sm-4 ">
                        <ul>
                            @auth
                            @if(auth()->id() === $file->owner->id)
                            <li>
                                <strong>Owner: You</strong>
                            </li>
                            @else
                            <li>
                                <strong>Owner: {{$file->owner->name}}</strong>
                            </li>
                            <li>
                                <strong>Owner's email: {{$file->owner->email}}</strong>
                            </li>
                            @endif
                            @else 
                            <li>
                                <strong>Owner: {{$file->owner->name}}</strong>
                            </li>
                            <li>
                                <strong>Owner's email: {{$file->owner->email}}</strong>
                            </li>
                            @endauth
                    
                    @if ($file->category)
                    <div>
                        <strong>Category: {{$file->category}}</strong>
                    </div>
                    @endif
                    @if ($file->description)
                    <div>
                        <strong>Description: {{$file->description}}</strong>                    
                    </div>
                    @endif
                </ul>
                
            </div>
            @if(auth()->id() !== $file->owner->id)
            <div class="col-sm-2 d-flex justify-content-center align-items-center">
                <form action="{{ route('admin.files.personal.store') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="file" value="{{ $file->id }}">
                    <input type="hidden" name="path" value="{{ $file->path }}">
                    <input type="hidden" name="title" value="{{ $file->title }}">
                    <input type="hidden" name="description" value="{{ $file->description }}">
                    <input type="hidden" name="category" value="{{ $file->category }}">
                    
                    <button class="btn btn-outline-primary" type="submit">Save To Personal</button>
                </form>
            </div>
            @endif
            @if ($file->owner->id === auth()->id())
                <div  class="col-sm-1 d-flex justify-content-center align-items-center">
                    <form action="{{route('admin.global-files.delete', ['id' => $file->id])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Do You Want To Delete This File? It Will Be Deleted For Everyone')">Delete</button>
                    </form>
                </div>
                @endif
                <div  class="col-sm-1 d-flex justify-content-center align-items-center">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.global-files.show', ['file' => $file->publicId])}}">Preview</a>
                </div>

        </div>
    </div>
</div>