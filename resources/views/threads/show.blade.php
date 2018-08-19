@extends('layouts.app')

@section('content')
  <div class="columns">

    <div class="column is-8">

      @include('threads.partials._thread')
     
    </div>{{-- end th thread column --}}



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