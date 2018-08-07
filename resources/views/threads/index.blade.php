@extends('layouts.app') 
@section('content')


  <div class="columns tw-flex tw-flex-col tw-flex-col-reverse md:tw-flex md:tw-flex-row ">
    <div class="column is-8"> 

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



    <div class="column">
      <div class="lu-card tw-px-4">
        
       {{-- <div class="lu-card-header tw-px-4 tw-flex tw-justify-around tw-items-center">
          <i class="fab fa-hotjar"></i>
         <span class="tw-text-center tw-w-full"> Trending Threads </span>
         <span></span>
       </div> --}}
       <div class="lu-card-header tw-px-4 tw-text-center ">

        <span class=""> 
          Trending Threads 
        </span>

       </div>

       <div class="tw-px-4 tw-my-4 ">
        <i class="fab fa-hotjar tw-mr-2"></i> 
        <span>Title for thread one</span>
       </div>

       <hr class="tw-m-0">

       <div class="tw-px-4 tw-my-4">
        <i class="fab fa-hotjar tw-mr-2"></i> 
        <span>Title for thread one</span>
       </div>


      </div>

    </div>
    {{-- end trending column --}}



  </div>

 
@endsection


