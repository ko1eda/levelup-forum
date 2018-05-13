@extends('layouts.app') 
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

        <hr class="tw-mx-4 tw-my-4">

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
      </div>

    </div>{{-- end info widget column --}}

    <div class="column is-8">

      <div class="lu-card">
        <div class="lu-card-section">

        </div>

      </div>

    </div>

  </div>

@endsection