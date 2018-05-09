@extends('layouts.app')
@section('content')
  <div class="card">

      <h3 class="mb-3">Replies:</h3>
      <div class="px-2">
        @foreach($replies as $reply)

          @include('threads.partials.reply')

        @endforeach
      </div>
    </div>


  <div class="twflex twflex-col twbg-white twbr-1 twshadow-md twrounded tww-full ">
    <div class="twbg-grey-lighter twpx-8 twpy-2 ">
      <a href="#">
        <strong>
        {{ $thread->user->name }}:
        </strong>
      </a> 
      {{ $thread->title }}
    </div>

    <div class="twpy-6 twpx-8 twleading-loose twtext-grey-darker"> 
      {{ $thread->body }}
    </div>  
    <div class="twpx-8">
      @auth
        @include('threads.partials.reply-form')
        <hr>
      @endauth

      @guest
      <article class="message is-warning">
        <div class="message-body twp-4">
          Please <a href="/login" class="twtext-black twfont-semibold twno-underline">login</a>
          or <a href="/register" class="twtext-black twfont-semibold twno-underline">register</a>
          to join this discussion.
        </div>
      </article>
      @endguest

    </div>

  </div>


@endsection






{{-- 
@section('sidebar')

  <div class="card text-center">
    <div class="card-body">
      <p class="card-text">
        Thread published on {{ $thread->created_at->toFormattedDateString() }}
        
      </p>
      <p class="card-text">
        By <a href="#">{{ $thread->user->name}}</a>
      </p>
      <p class="card-text">
        Replies: {{ $thread->replies()->count() }}
      </p>
    </div>
  </div>

@endsection --}}