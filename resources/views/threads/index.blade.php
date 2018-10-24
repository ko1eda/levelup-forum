@extends('layouts.app') 
@section('content')

{{-- There should not be trending threads if there are no threads --}}
  <div class="columns is-centered tw-flex tw-flex-col tw-flex-col-reverse md:tw-flex md:tw-flex-row">
    <div class="column is-8 "> 

      @if(count($threads) === 0)
        <p class="tw-text-3xl tw-font-light tw-text-bulma-darker tw-text-center">
          These are not the <a href="{{ route('threads.index') }}"><strong>threads</strong></a> you are looking for...
        </p>
      @else{{-- if the thread filter returns no results display message else display threads--}}
      
      @include('threads.partials._list', ['threads' => $threads, 'displayOwner' => true]) {{-- end threads list --}}
      
      <div class="tw-flex tw-mt-2 tw-full-width tw-justify-end ">
          {{ $threads->links() }}
      </div>{{-- end pagination links --}}  
      @endif

    </div>{{-- end threads column --}}

    @if(count($trendingThreads) && count($threads))
      <div class="column">
        <div class="columns tw-mb-2">
          <div class="column">

            @include('threads.partials._trending')

          </div>
        </div>
      </div>{{-- end right column --}}
    @endif 

  </div>{{-- end row --}}
@endsection
