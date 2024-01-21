<div class="card mt-3">
    <div class="card-body">

            <div class="row">

                    <div class="col-sm-4">
                        <h5>{{$file->title}}</h5>
                        
                        
                        <h6> {{$file->path}}</h6>
       
                        
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
            
          <div class="col-sm-4">
            <div class="d-flex justify-content-end pt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="delete_files[]" value="{{$file->id}}" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                      Select
                    </label>
                  </div>
        
            </div>
          </div>
             
        </div>
    </div>
</div>