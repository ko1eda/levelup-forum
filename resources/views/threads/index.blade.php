@extends('layouts.app') 
@section('content')

@if(count($trendingThreads))
  <div class="columns tw-flex tw-flex-col tw-flex-col-reverse md:tw-flex md:tw-flex-row ">
    <div class="column is-7 is-8-desktop "> 
      {{-- if there are trending threads --}}
@else
  <div class="columns is-centered">
    <div class="column is-10 "> 
      {{-- if there are not trending threads --}}
@endif
      {{-- if the thread filter returns no results --}}
      @if(count($threads) === 0)
        <p class="tw-text-3xl tw-font-light tw-text-bulma-darker tw-text-center">
          These are not the <a href="{{ route('threads.index') }}"><strong>threads</strong></a> you are looking for...
        </p>

      @else
      
      @include('shared._thread-list', ['threads' => $threads, 'displayOwner' => true])
      
      <div class="tw-flex tw-mt-2 tw-full-width tw-justify-end ">
          {{ $threads->links() }}
      </div>{{-- end pagination links --}}
      @endif

    </div>
    {{-- end threads column --}}


  @if(count($trendingThreads))
    <div class="column">
      <div class="lu-card tw-px-4">

       <div class="lu-card-header tw-px-4 tw-text-center ">
        <span class=""> 
          Trending Threads 
        </span>
       </div>{{-- end card header --}}

       @foreach($trendingThreads as $thread)
        <div class="tw-py-4 tw-px-2 lg:tw-p-4 tw-text-sm tw-flex tw-justify-between tw-items-center">
          <div class="tw-flex tw-flex-col tw-w-full">    

            <span class="tw-pr-4 md:tw-pr-0 lg:tw-pr-4 tw-mb-2">
              <a href="{{ $thread->uri }}">{{ $thread->title }}</a>
            </span>


            <div class="lu-level tw-px-0 tw-py-0 tw-text-xs tw-p-2 ">
              <div class="lu-level-item tw-text-grey-darker lg:tw-w-32 tw-w-24 tw-mr-0" title="Thread Owner">
                <i class="fas fa-user tw-mr-1 tw-align-middle "></i>
                <a href={{ route('profiles.show', $thread->username) }}>
                  <span class="tw-text-green hover:tw-text-green-dark tw-font-semibold tw-align-middle">
                    {{ $thread->username }}
                  </span>
                </a>
              </div>
              {{-- end user name --}}
        
              <div class="lu-level-item tw-mr-0">
                <i class="fas fa-reply tw-mr-1 tw-text-grey-darker tw-align-middle "></i>
                <span class="tw-align-middle">
                  {{ $thread->replies_count }} replies
                </span>
              </div>
              {{-- end replies count --}}
               
            </div>{{-- end level --}}
          </div>{{-- end text column with level --}}

          {{-- <i class="fab fa-hotjar tw-mr-2"></i>  --}}
          <span class="tw-text-center lg:tw-mr-2">
            <span class="">
              {{ $thread->view_count }}
            </span>

            <br>
            views
          </span>{{-- end view count --}}
          
         </div>
         {{-- end thread list --}}

        @endforeach
      
      </div>
      {{-- end lu-card --}}

    </div>{{-- end trending column --}}
  @endif


  </div>
  
 
@endsection


{{-- <style>

  .trending-tablet-sizing {
    @media (max-width: 767) {
      width: 50%;
    }
  }

</style> --}}

