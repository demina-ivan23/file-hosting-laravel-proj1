<div class="card mt-3">
    <div class="card-body">

            <div class="row">

                    <div class="col-sm-4">
                        <h5>{{$file->title}}</h5>
                        
                        
                        <h6> {{$file->path}}</h6>
                        
                        
                    </div>
                
            <div class="col-sm-4 ">
                <ul>
<li>
    <strong>Sender: {{$file->user->name}}</strong>
</li>
<li>
    <strong>Sender's email: {{$file->user->email}}</strong>
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
            </div>

            <div class="col-sm-4 d-flex justify-content-center align-items-center">
                <input class="btn btn-outline-primaryy" type="submit" value="Download">
            </div>
        </div>
    </div>
</div>