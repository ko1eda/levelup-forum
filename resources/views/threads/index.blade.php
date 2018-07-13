@extends('layouts.app') 
@section('content')


  <div class="columns is-centered">
    <div class="column is-10"> 

      {{-- if the thread filter returns no results --}}
      @if(count($threads) === 0)
        <p class="tw-text-3xl tw-font-light tw-text-bulma-darker tw-text-center">
          These are not the <a href="{{ route('threads.index') }}"><strong>threads</strong></a> you are looking for...
        </p>

      @else
        <div class="tw-flex tw-mb-2 tw-full-width tw-justify-end ">
            {{ $threads->links() }}
        </div>{{-- end pagination links --}}

        @include('shared._thread-list', ['threads' => $threads, 'displayOwner' => true])
        
      @endif

    </div>
  </div>

 
@endsection


