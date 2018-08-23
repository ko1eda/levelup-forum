@extends('layouts.app') 
@section('content')

{{-- There should not be trending threads if there are no threads --}}
@if(count($trendingThreads) && count($threads))
  <div class="columns tw-flex tw-flex-col tw-flex-col-reverse md:tw-flex md:tw-flex-row ">
    <div class="column is-7 is-8-desktop "> 
      {{-- if there are trending threads --}}
@else
  <div class="columns is-centered">
    <div class="column is-8 "> 
      {{-- if there are not trending threads --}}
@endif
      {{-- if the thread filter returns no results --}}
      @if(count($threads) === 0)
        <p class="tw-text-3xl tw-font-light tw-text-bulma-darker tw-text-center">
          These are not the <a href="{{ route('threads.index') }}"><strong>threads</strong></a> you are looking for...
        </p>

      @else
      
      @include('threads.partials._list', ['threads' => $threads, 'displayOwner' => true])
      
      <div class="tw-flex tw-mt-2 tw-full-width tw-justify-end ">
          {{ $threads->links() }}
      </div>{{-- end pagination links --}}
      @endif

    </div>
    {{-- end threads column --}}

    <div class="column">
      <div class="columns tw-mb-2 hide-sb-widget">
        <div class="column">
            <div class="lu-card tw-px-4 hide-sb">
              <div class="lu-card-header tw-px-4 tw-text-center ">
                <span class=""> 
                  Search
                </span>
              </div>{{-- end card header --}}
    
              <div class="tw-px-4 tw-py-6">
                <form action="{{ route('search.threads')}}" >
                  <div class="field has-addons tw-w-full">
      
                    <div class="control has-icons-left tw-w-4/5 ">
                      <input class="input is-small " type="text" placeholder="Search" name='q'>
                      <span class="icon is-small is-left">
                          <i class="fas fa-search"></i>
                      </span>
                    </div>
                    
                    <div class="control">
                      <button class="button is-small is-light">Submit</button>
                    </div>
                    
                  </div>
                </form>
              </div>{{-- end search bar --}}

            </div> 
        </div>{{-- end searchbar column --}}
      </div>{{-- end search bar row --}}

      @if(count($trendingThreads) && count($threads))
        <div class="columns">
          <div class="column">

            @include('threads.partials._trending')

          </div>
        </div>
      @endif {{-- end trending row --}}

    </div>{{-- end right column --}}


  </div>{{-- end row --}}
@endsection
