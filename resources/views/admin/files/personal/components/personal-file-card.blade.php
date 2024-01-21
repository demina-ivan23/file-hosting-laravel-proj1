<div class="card mt-3">
    <div class="card-body">

            <div class="row">

                    <div class="col-sm-4">
                        <h5>{{$file->title}}</h5>
                        
                        
                        <h6> {{$file->path}}</h6>
                        
                        
                    </div>
                
                    <div class="col-sm-4 ">
                <strong>It's Your personal file</strong>
                        <ul>
                    
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
            
            <div class="col-sm-2 d-flex justify-content-center align-items-center">
                <a class="btn btn-outline-primary" href="{{route('admin.files.show', ['file' => $file->id])}}">Download</a>
            </div>
            <div  class="col-sm-2 d-flex justify-content-center align-items-center">
                <form action="{{route('admin.files.delete', ['id' => $file->id])}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Do You Want To Delete This Personal File?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>