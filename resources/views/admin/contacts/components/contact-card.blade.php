<div class="card mt-3">
    <div class="card-body">

        <div class="row">
            <div class="col-sm-3 d-flex justify-content-center align-items-center">
                <img src="/users/profiles/images/user.png" width="105" height="105"  alt="">
            </div>
            <div class="col-sm-6 ">
                <h5>{{$contact->name}}</h5>
                <strong>Email: {{$contact->email}}</strong>
                
            </div>
            <div class="col-sm-3 d-flex justify-content-center align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.files.create', ['user' => $contact->id])}}">Send A File</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.contacts.show', ['user' => $contact->id])}}">Show Files Related To This Contact</a></li>
                        @php
                        use App\Models\User;
                            $authUser = User::find(auth()->id());
                        @endphp
                        @if (!$authUser->blacklist->contains($contact->id))
                        <li>
                                
                            <form action="{{route('admin.contacts.update', ['publicId' => $contact->publicId])}}" method="POST">
                                @csrf
                                <input type="hidden" name="blocking" value="{{true}}">
                                <button class="dropdown-item" type="submit" onclick="return confirm('Are You Sure You Want To Block The Contact?')">Block User</button>
                            </form>
                        </li>
                        @endif
                        @if ($authUser->blacklist->contains($contact->id))
                            
                        <li>
                            <form action="{{route('admin.contacts.update', ['publicId' => $contact->publicId])}}" method="POST">
                                @csrf
                                <input type="hidden" name="blocking" value="{{false}}">
                                <button class="dropdown-item" type="submit" >Unblock User</button>
                            </form>
                        </li>
                        @endif
                        <li>
                            <form action="{{route('admin.contacts.delete', ['publicId' => $contact->publicId])}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="dropdown-item" type="submit" onclick="return confirm('Do You Want To Delete This Contact?')">Delete Contact</button>
                                </form>
                            </li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>