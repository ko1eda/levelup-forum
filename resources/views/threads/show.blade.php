@extends('layouts.app')
@section('content')

  <div class="tw-flex tw-flex-col tw-w-full tw-shadow-md tw-rounded tw-bg-white">

    <div class="tw-px-8 tw-py-4 tw-border-b tw-bg-green">
      <a href="#">
      <span class="tw-font-bold tw-text-white tw-mr-2 hover:tw-underline">
        {{ $thread->user->name }}:
      </span>
      </a> 
      <span class="tw-text-white">{{ $thread->title }}</span>
    </div>{{-- end header --}}


    <div class="tw-py-6 tw-px-8 tw-leading-loose "> 
      {{ $thread->body }}
    </div> {{-- end body --}}

    <div class="tw-px-8">
      @auth
        @include('threads.partials.reply-form')
        <hr>
      @endauth

      @guest
      <article class="message is-warning">
        <div class="message-body tw-p-4">
          Please <a href="/login" class="has-text-primary tw-font-semibold tw-no-underline">login</a>
          or <a href="/register" class="has-text-primary tw-font-semibold tw-no-underline">register</a>
          to join this discussion.
        </div>
      </article>
      @endguest

      <div class="tw-mb-4">
        <h3 class="tw-text-2xl">Replies:</h3>
      </div>
      
      <div class="tw-px-2">
        @foreach($replies as $reply)
        <div class="tw-my-6">
          @include('threads.partials.reply')
        </div>
        @endforeach
      </div>

    </div> {{-- end replies section --}}

  </div>{{-- end pannel --}}


@endsection







@section('sidebar')

  <div class="lu-pannel tw-text-center tw-max-w-lg">
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