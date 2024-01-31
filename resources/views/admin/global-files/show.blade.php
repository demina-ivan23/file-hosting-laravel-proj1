@extends('layouts.app')
@section('content')
<div class="container">
  @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
  @endif
  @if (session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
@endif
   <div class="card mt-3">
    <div class="card-body">
        <div class="d-flex justify-content-center align-items-center">
            @if ($file->title)
            <h3>{{$file->title}}</h3>
            @else
                <h3>Untitled</h3>
            @endif
        </div>
        <div class="mt-3"><h5>Path: {{$file->path}}</h5></div>
        @if ($file->category)
        <div class="mt-2">Category: {{$file->category}}</div> 
        @else
        <div class="mt-2">No category</div> 
        @endif 
        @if ($file->description)
        <div class="mt-2">Description: {{$file->description}}</div>   
        @else
        <div class="mt-2">No description</div> 
        @endif
        @if ($file->mimeType === 'video/mp4')
        <div class="d-flex justify-content-center align-items-center mt-3 ">
          <video width="750" height="450" controls autoplay muted>
            <source src="{{ asset('storage/' . $file->path) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        </div>
        @endif
        @if (str_contains($file->mimeType, 'image'))
        <div class="d-flex justify-content-center align-items-center mt-3">
          <img src="{{ asset('storage/' . $file->path)}}" alt="" style="max-width: 90%; height: auto;">
        </div>
        @endif
   
        <div class="d-flex justify-content-end align-items-center">
          <div class="mt-3">Views: {{$file->views}}</div>
        </div>
    </div>
    <form method="POST" action="{{route('admin.global-files.comments.store', ['file' => $file->publicId])}}" class="mt-20 ml-3 mr-3">
      @csrf
   
      <header class="d-flex justify-content-center">
        <img src="/users/profiles/images/user.png" width="50" height="50"  alt="" class="mr-5">

        <h2 class="ml-3">
        Leave Your Comment About This File
      </h2>
      </header>
   
      <div class="mt-6">
        <textarea
        class="mt-4 rounded-xl border border-gray-200 bg-gray-50 w-full p-2 text-sm focus:outline-none focus:ring"
        name="text"
        rows="5"
        placeholder="type here..."
        required></textarea>
          
      </div>
      <div class="flex justify-end pt-6">
        <button class="btn btn-primary mr-5">Post</button>
      </div>
   
    </form>
    <div class="mb-5">
      @foreach ($file->comments()->latest()->get() as $comment)
      <div class="card-body border border-gray-200 rounded-xl mt-5 ml-3 mr-3">
      <div class="d-flex">
        <img src="/users/profiles/images/user.png" width="30" height="30"  alt="" class="mr-5">
        <div>
          <h5>
            {{$comment->author->name}}
          </h5>
        </div>
      </div>

      <div class="d-flex mt-4">
        {{$comment->text}}
      </div>
      <div class="d-flex justify-content-start mt-4">
        Likes: {{ $comment->likes}}
      </div>
      @if (!auth()->user()->likedComments->contains($comment->id))
      <div class="d-flex justify-content-end">
            
        <form action="{{ route('admin.global-files.comments.show.like', ['comment' => $comment->id]) }}" method="POST">
          @csrf
          <button type="submit">
            <img src="{{asset('storage/icons/thumbs-up.png')}}" alt="" width="25">
          </button>
        </form>
      </div>
      @endif
    </div>
    @endforeach
  </div>

   </div>
</div>

@endsection