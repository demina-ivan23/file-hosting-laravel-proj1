<div class="card mt-3">
    <div class="card-body">

            <div class="row">

                    <div class="col-sm-3 d-flex align-items-center justify-content-center">
<img src="/users/profiles/images/user.png" width="105" height="105"  alt="">  
                    </div>
@if($request->receiver->id === auth()->id())
    
<div class="col-sm-5">

<li>
    <strong>Sender: {{ $request->sender->name }}</strong>
</li>
<li>
    <strong>Sender's Email: {{ $request->sender->email }}</strong>
</li>

     
</div>

<div class="col-sm-4 d-flex justify-content-center align-items-center justify-content-between">
    <form action="{{route('admin.contacts.store')}}" method="POST">
        @csrf
        <input type="hidden" value="{{$request->sender_id}}" name="id">
        <button class="btn btn-outline-primary" type="submit">Accept</button>
    </form>
    <form action="{{route('admin.contacts.requests.delete', ['id' => $request->id, 'state' => 'declined'])}}" method="POST">
    @csrf
    @method('DELETE')
    
    <button class="btn btn-outline-primary" type="submit">Decline</button>
    </form>
    <a class="btn btn-outline-primary" href="#">Decline And Block User</a>
</div>
@endif
@if($request->sender->id === auth()->id())
<div class="col-sm-6">

    {{-- Enshure that request sender doesn't
    get any personal information about the
    receiver unless receiver accepts the request --}}
    <li>
        <strong>Reciever's Id: {{ $request->receiver->id }}</strong>
    </li>
    
         
    </div>
    
    <div class="col-sm-3 d-flex justify-content-center align-items-center justify-content-between">
         
    <form action="{{route('admin.contacts.requests.delete', ['id' => $request->id, 'state' => 'canceled'])}}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn btn-outline-primary" type="submit">Cancel Request</button>
        </form>
    </div>
@endif
        </div>
    </div>
</div>