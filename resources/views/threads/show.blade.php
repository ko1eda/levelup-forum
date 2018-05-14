@extends('layouts.app')

@section('content')
  <div class="columns">
    <div class="column is-8">

      <div class="lu-card">
        <div class="lu-card-header tw-flex tw-justify-between tw-items-center">
          <p>
            {{ $thread->title }}
          </p>
          <form action="{{ route('threads.destroy', [$thread->channel, $thread]) }}" method="POST">
            @csrf
            @method('delete'){{-- delete method spoofing --}}

            <button type="submit" class="tw-flex tw-items-center">
              <a class="delete"></a>
            </button>
          </form>{{-- end delete button --}}

        </div>{{-- end header --}}
    
        <div class="lu-card-body tw-leading-loose"> 
          {{ $thread->body }}
        </div> {{-- end body --}}
    
        <div class="lu-card-section tw-py-0 ">
          @auth
            @include('threads.partials.reply-form')
            @if(count($replies))
            <hr>{{-- line break if replies --}}
            @endif
          @endauth

          @guest
          <article class="message is-warning">
            <div class="message-body tw-p-4">
              Please <a href="/login" class="tw-font-semibold tw-no-underline">login</a>
              or <a href="/register" class="tw-font-semibold tw-no-underline">register</a>
              to join this discussion.
            </div>
          </article>
          @endguest
              
          @if(count($replies))
            <h3 class="tw-text-xl sm:tw-text-2xl">Replies:</h3>
          @endif{{-- replies heading--}}

          <div class="tw-px-2 tw-mb-4 sm:tw-mb-8">
            @foreach($replies as $reply)
              <div class="tw-my-4">
                @include('threads.partials.reply')
              </div>
            @endforeach

            {{ $replies->links() }}
          </div> {{-- end replies and pagination --}}

        </div> {{-- end replies section --}}
    
      </div>{{-- end pannel --}}
      
    </div>

    {{-- side widget --}}
    <div class="column">
      <div class="lu-card tw-text-center">
        <div class="lu-pannel-header">
          
          <p class="lu-pannel-text">
            Thread published on {{ $thread->created_at->toFormattedDateString() }}
          </p>

          <p class="lu-pannel-text">
            By
            <a href={{ route('profiles.show', $thread->user) }} class="tw-text-green hover:tw-text-green-dark tw-font-semibold">
              {{ $thread->user->name}}
            </a>
          </p>

          <p class="lu-pannel-text">
            Replies: {{ $thread->replies_count }}
          </p>

        </div>
      </div>{{-- end side widget --}}
    </div>{{-- end column --}}

  </div>
@endsection