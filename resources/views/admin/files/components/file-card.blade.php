<div class="card mt-3">
    <div class="card-body">

            <div class="row">

                    <div class="col-sm-4">
                        <h5>{{$file->title}}</h5>
                        
                        
                        <h6> {{$file->path}}</h6>
                        
                        
                    </div>
                
                    @if(auth()->user()->id === $file->sender->id)
            <div class="col-sm-4 ">
                <ul>
                    <li>
                        <strong>Sender: You</strong>
                    </li>
                    <li>
                    <strong>Reciever: {{$file->reciever->name}}</strong>
                    </li>
                
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
            
            <div class="col-sm-4 d-flex justify-content-center align-items-center">
                <a class="btn btn-outline-primary" href="{{route('admin.files.show', ['file' => $file->id])}}">Download</a>
            </div>
        </div>
    </div>
</div>