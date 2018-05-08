@extends('layouts.app')
@section('content')
  <div class="card">
    
    <div class="card-header">
      <a href="#">
        <strong>
          {{ $thread->user->name }}:
        </strong>
      </a> 
        {{ $thread->title }}
    </div>

    <div class="card-body py-4">
      <p class="card-text">
        {{ $thread->body }}
      </p>
      @auth
        <div class="px-2 py-2 form">
          @include('threads.partials.reply-form')
        </div>
        <hr>
      @endauth
      @guest
        <div class="alert alert-warning" role="alert">
          Please <a href="/login" class="alert-link">login</a>
          or <a href="/register" class="alert-link">register</a>
          to join this discussion.
        </div>
      @endguest
      <h3 class="mb-3">Replies:</h3>
      <div class="px-2">
        @foreach($replies as $reply)

          @include('threads.partials.reply')

        @endforeach
      </div>
    </div>

  </div>

@endsection


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

@endsection