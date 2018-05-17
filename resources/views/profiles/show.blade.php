@extends('layouts.app') 

@section('hero')

  @include('profiles.partials.hero')

@endsection

@section('content')

  <div class="columns">
    <div class="column">
      <div class="lu-card tw-pb-4">
        <div class="lu-card-header tw-px-4 tw-text-center">
          {{ $user->name }}
        </div>{{-- end header --}}

        <div class="tw-px-4 tw-pt-4">
          <img src="https://imgplaceholder.com/420x320" alt="profile picture">
        </div>{{-- end profile pic --}}

        <hr class="tw-mx-4 tw-mt-4 tw-mb-0">

        <h1 class="tw-mb-2 tw-text-center tw-text-2xl tw-font-light">
          Contact
        </h1> {{-- end contact header --}}
        
        <ul class="tw-px-6">
          <li class="tw-">
            <div class="lu-level">
              <div class="tw-inline tw-mr-2 tw-align-middle">
                <i class="fas fa-user tw-text-green"></i>
              </div>
              <div class="tw-inline tw-align-middle">
                username
              </div>
            </div>
          </li>

          <li class="tw-">
            <div class="lu-level">
              <div class="tw-inline tw-mr-2 tw-align-middle">
                <i class="fas fa-envelope tw-text-green"></i>
              </div>
              <div class="tw-inline tw-align-middle">
                {{$user->email}}
              </div>
            </div>
          </li>
        </ul>{{-- end icons section --}}
        
        <hr class="tw-mx-4 tw-mt-4 tw-mb-0">

        <h1 class="tw-mb-2 tw-text-center tw-text-2xl tw-font-light">
          Recent Activity
        </h1>

        <ul class="tw-px-6">
          @foreach($activities as $date => $activity)
          <span class="tw-text-xs">{{$date}}</span>
            @foreach($activity as $record)
              <li>
                @include("profiles.activities.{$record->type}", ['activity' => $record])
              </li>
            @endforeach
          @endforeach
        </ul> 
        
        {{-- end activity feed --}}


      </div>{{-- end lu-card  --}}
    </div>{{-- end info widget column --}}

    <div class="column is-8">

      <div class="lu-pannel">
        @foreach($threads as $thread)

        <div class="lu-pannel-header tw-text-2xl">
          <a href={{ $thread->path() }}>
            <h4 class="tw-font-light">{{ $thread->title }}</h4>
          </a>
        </div>

        <div class="lu-pannel-body">
          {{$thread->body}}
        </div>
        <div class="lu-level">
           
          <div class="lu-level-item tw-text-grey-darker ">
            <i class="fas fa-clock tw-mr-1 tw-text-grey-darker tw-align-middle"></i>
            <span class="tw-align-middle">
              {{ $thread->created_at->diffForHumans()}}
            </span>
          </div>

          <div class="lu-level-item">
            <i class="fas fa-reply tw-mr-1 tw-text-grey-darker tw-align-middle "></i>
            <span class="tw-align-middle">
                {{ $thread->replies_count }}
            </span>
          </div>
        </div>{{-- end level --}}

        <hr>
        @endforeach
      </div>

      @if(count($threads) >= 10)
        <div class="tw-flex tw-mt-4 tw-full-width tw-justify-end">
          {{ $threads->links() }}
        </div>
      @endif {{-- pagination --}}

    </div>{{-- end threads column--}}

  </div>

@endsection
