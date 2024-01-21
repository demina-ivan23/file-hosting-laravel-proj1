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
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="delete_messages[]" value="{{$message->id}}" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
              Select
            </label>
          </div>

    </div>
    </div>
  </div>