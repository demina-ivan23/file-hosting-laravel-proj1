<div class="card mt-3">
    <div class="card-body">

            <div class="row">

                    <div class="col-sm-4">
                        <h5>{{$file->title}}</h5>
                        
                        
                        <h6> {{$file->path}}</h6>
                        <div>

                            <a class="btn btn-outline-primary" href="{{route('admin.files.show', ['file' => $file->id])}}">Download</a>
                        </div>
                        
                    </div>
                
                    <div class="col-sm-4 ">
                @if(auth()->user()->id === $file->sender->id)
                <ul>
                    <li>
                        <strong>Sender: You</strong>
                    </li>
                    <li>
                    <strong>Reciever: {{$file->receiver->name}}</strong>
                    </li>
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
                @else

                <ul>
                    <li>
                        <strong>Sender: {{$file->sender->name}}</strong>
                    </li>
                    <li>
                        <strong>Sender's email: {{$file->sender->email}}</strong>
                    </li>
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
                @endif
            </div>
            
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
                @if ($file->sender->id === auth()->id())
                <div  class="col-sm-2 d-flex justify-content-center align-items-center">
                    <form action="{{route('admin.files.delete', ['id' => $file->id])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Do You Want To Delete This File? It Will Also Be Deleted For The Receiver')">Delete</button>
                    </form>
                </div>
                    
                @else
                <div  class="col-sm-2 d-flex justify-content-center align-items-center">
                    <form action="{{route('admin.files.delete', ['id' => $file->id])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Do You Want To Deleter This File? It Will Also Be Deleted For The File\'s Sender.')">Delete</button>
                    </form>
                </div>
                @endif

        </div>
    </div>
</div>