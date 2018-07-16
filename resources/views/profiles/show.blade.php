@extends('layouts.app') 

@section('hero')

  @include('profiles.partials.hero')

@endsection

@section('content')

  <div class="columns">
    <div class="column is-4-desktop">
      <div class="lu-card tw-pb-4 offset-t">
        <div class="lu-card-header tw-px-4 tw-text-center">
          {{ $user->name }}
        </div>{{-- end header --}}

        <div class="tw-px-4 tw-pt-4 tw-mx-auto">
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
                <i class="fas fa-at tw-text-green"></i>
              </div>
              <div class="tw-inline tw-align-middle">
               {{ $user->username  }}
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
      </div>{{-- end lu-card  --}}
    </div>{{-- end left column --}}

    <div class="column is-offset-1-desktop">

      <h1 class="tw-mb-2 tw-text-center tw-text-2xl tw-font-light">
        Recent Activity
      </h1>{{-- end feed header --}}

      <div class="lu-card tw-py-4 tw-mb-6">
        <ul class="tw-px-6">
          @forelse($activities as $date => $activity)
            <span class="tw-text-sm">{{$date}}</span>

            @foreach($activity as $record)
              <li>
                @include("profiles.activities.{$record->type}", ['activity' => $record])
              </li>
            @endforeach
          
          @empty
              <p class="tw-text-sm">There is no recent activity for this user :-(</p>
          @endforelse
        </ul> 
      </div>{{-- end activity feed widget --}}


      
      <h1 class="tw-mb-2 tw-text-center tw-text-2xl tw-font-light">
        Top Threads
      </h1>

      @include('shared._thread-list', ['threads' => $threads])

    </div>{{-- end threads column--}}

  </div>
@endsection
