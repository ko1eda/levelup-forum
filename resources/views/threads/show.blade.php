@extends('layouts.app')
@section('content')

  <div class="lu-card">

    <div class="lu-card-header">
      <p>
        {{ $thread->title }}
      </p>
    </div>{{-- end header --}}


    <div class="lu-card-body tw-leading-loose"> 
      {{ $thread->body }}
    </div> {{-- end body --}}

    <div class="lu-card-section tw-py-0">
      @auth
        @include('threads.partials.reply-form')
        
        @if(count($replies))
        <hr>
        @endif

      @endauth

      @guest
      <article class="message is-warning">
        <div class="message-body tw-p-4">
          Please <a href="/login" class="tw-text-green tw-font-semibold tw-no-underline">login</a>
          or <a href="/register" class="tw-text-green tw-font-semibold tw-no-underline">register</a>
          to join this discussion.
        </div>
      </article>
      @endguest

      
      <div class="tw-mb-4">

        @if(count($replies))
          <h3 class="tw-text-2xl">Replies:</h3>
        @endif

      </div> {{-- margin between the body and replies --}}
     
      
      <div class="tw-px-2">
        @foreach($replies as $reply)
        <div class="tw-my-6">
          @include('threads.partials.reply')
        </div>
        @endforeach
      </div> {{-- end replies --}}

    </div> {{-- end replies section --}}

  </div>{{-- end pannel --}}


@endsection







@section('sidebar')

  <div class="lu-pannel tw-text-center">
    <div class="lu-pannel-header">
      <p class="lu-pannel-text">
        Thread published on {{ $thread->created_at->toFormattedDateString() }}
      </p>

      <p class="lu-pannel-text">
        By <a href="#" class="has-text-primary tw-font-semibold">{{ $thread->user->name}}</a>
      </p>

      <p class="lu-pannel-text">
        Replies: {{ $thread->replies()->count() }}
      </p>

    </div>
  </div>

@endsection