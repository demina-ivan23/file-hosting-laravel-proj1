<div class="card mt-3">
    <div class="card-body">
      {{$message->text}}
      <div class="d-flex justify-content-between pt-3">

@if ($message->sender)
<div class="d-flex justify-content-start">
    From: {{$message->sender}}
</div>
@elseif ($message->system)
<div class="d-flex justify-content-start">
    System message
</div>
@else
<div class="d-flex justify-content-start">
    Anonymous message
</div>
@endif
            <div class="d-flex justify-content-end">
                <small>{{$message->dateOfCreation}}</small>
            </div>
        </div>
    <div class="d-flex justify-content-end pt-2">
        <form action="{{route('admin.messages.delete', ['message' => $message->id])}}" method="post">
@csrf
@method('DELETE')
            <button class="btn btn-outline-primary" type="submit">Delete</button>
        </form>
    </div>
    </div>
  </div>