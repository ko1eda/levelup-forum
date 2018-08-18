@extends('layouts.app')

@section('content')
  <div class="columns">
    <div class="column is-8">

      <div class="lu-card">
        <div class="lu-card-header tw-flex tw-justify-between tw-items-center">
          <p>
            {{ $thread->title }}
          </p>

          <div class="tw-flex tw-justify-end tw-items-center tw-w-24 ">
            <lu-lock-button 
              :endpoint={{ json_encode(route('threads.lock.store', $thread)) }}
              :locked={{ json_encode($thread->locked) }}>
            </lu-lock-button>
  
            {{-- if the user has permission to update/delete the thread  --}}
            @can('delete', $thread)
              <form action="{{ route('threads.destroy', [$thread->channel, $thread]) }}" method="POST">
                @method('delete'){{-- delete method spoofing --}}
                @csrf
                <button type="submit" class="tw-flex tw-items-center">
                  <a class="delete hover:tw-bg-red-light "></a>
                </button>
              </form>
            @endcan {{-- end delete button --}}
           
  
            @auth {{-- if user is authenticated and it they do not own the thread  --}}
              @if($thread->user_id !== \Auth::user()->id)
  
                <lu-subscribe-button 
                  :subscribed="{{ $thread->makeHidden('user') }}" 
                  :endpoint="{{ json_encode(route('subscriptions.threads.store', $thread)) }}">
                </lu-subscribe-button>
                
              @endif
            @endauth {{-- end Vue SubscribeButton component --}}

          </div>{{-- end header buttons --}}
        </div>{{-- end header --}}
    
        <div class="lu-card-body tw-leading-loose"> 
          {{ $thread->body }}
        </div> {{-- end body --}}
    
        <div class="lu-card-section tw-py-0 ">
          @auth
            @include('threads.partials.reply-form', ['thread' => $thread])
          @endauth

          @guest
          <article class="message is-warning">
            <div class="message-body tw-p-4">
              Please <a href="/login" class="tw-font-semibold tw-no-underline">login</a>
              or <a href="/register" class="tw-font-semibold tw-no-underline">register</a>
              to join this discussion.
            </div>
          </article>
          @endguest{{-- end guest login notification --}}
              
          @isset($bestReply)
            <lu-best-reply-divider :has-best={{ json_encode(isset($bestReply)) }}></lu-best-reply-divider>
            <div class="tw-px-2 tw-mb-4">
              <div class="tw-my-4" id="reply-{{ $bestReply->id }}">
                @include('threads.partials.reply', ['reply' => $bestReply, 'thread' => $thread, 'hasBest' => true])
              </div>
            </div>
          @endif {{-- end best reply --}}

          <lu-reply-divider :initial-count={{ $thread->replies_count - ($bestReply ? 1 : 0) }}></lu-reply-divider> {{-- divider between post and replies + replies heading--}}
          
          {{-- Note the id is so that we can navigate to a given reply --}}
          {{-- on the page using a hash link ex /#reply-4 --}}
          <div class="tw-px-2 tw-mb-4">

            @foreach($replies as $reply)
              <div class="tw-my-4" id="reply-{{ $reply->id }}">
                @include('threads.partials.reply', ['hasBest' => false]) {{-- has best distingushes theses replies from the marked best reply --}}
              </div>
            @endforeach

            {{ $replies->links() }}
          </div> {{-- end replies and pagination --}}

        </div> {{-- end replies section --}}
      </div>{{-- end pannel --}}
    </div>{{-- end column is-8 --}}



    <div class="column">
      <div class="lu-card tw-text-center">
        <div class="lu-pannel-header">
          <p class="lu-pannel-text">
            Thread published on {{ $thread->created_at->toFormattedDateString() }}
          </p>{{-- end date --}}

          <p class="lu-pannel-text">
            By
            <a href={{ route('profiles.show', $thread->user) }} class="tw-text-green hover:tw-text-green-dark tw-font-semibold">
              {{ $thread->user->name}}
            </a>
          </p>{{-- end user info --}}

        <lu-counter :initial-count={{ $thread->replies_count }}></lu-counter> {{-- end Vue reply count component --}}

        </div>
      </div>{{-- end side widget --}}

    </div>{{-- end column --}}


  </div>
@endsection