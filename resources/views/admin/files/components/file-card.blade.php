<div class="card mt-3">
    <div class="card-body">

            <div class="row">
                <div class="col-sm-3 d-flex justify-content-center align-items-center">
<img src="/users/profiles/images/user.png" width="105" height="105"  alt="">
            </div>
            <div class="col-sm-6 ">
                <h5>{{$file->title}}</h5>
                <strong>Description: {{$file->description}}</strong>
                <strong>Category: {{$file->category}}</strong>
                <strong>Sender: {{$file->sender->name}}</strong>
                           
            </div>
            <div class="col-sm-3 d-flex justify-content-center align-items-center">
                Download 
            </div>
        </div>
    </div>
</div>