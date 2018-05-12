@extends('layouts.app') 
@section('content')

  <div class="lu-pannel">

    @foreach($threads as $thread) 

      <div class="lu-pannel-header tw-text-2xl">
        <a href={{ $thread->path() }}>
          <h4 class="tw-font-light">{{ $thread->title }}</h4>
        </a>
      </div>
      
      <div class="lu-pannel-body">
        <p>{{ $thread->body }}</p>
      </div>

      <div class="tw-pb-2 tw-px-4 tw-flex tw-items-center tw-text-grey-darker">
        
        <div class="sm:tw-mr-6 tw-mr-4">
          <i class="fas fa-user sm:tw-text-sm tw-text-xs tw-mr-1 tw-text-grey-darker"></i>
          <a href="">
            <span class="tw-text-green hover:tw-text-green-dark tw-font-semibold sm:tw-text-sm tw-text-xs">
               {{ $thread->user->name }}
            </span>
          </a>
        </div>

        <div class="sm:tw-mr-6 tw-mr-4 ">
          <i class="fas fa-clock sm:tw-text-sm tw-text-xs tw-mr-1 tw-text-grey-darker"></i>
          <span class="sm:tw-text-sm tw-text-xs">
            {{ $thread->created_at->diffForHumans()}}
          </span>
        </div>

        <div>
          <i class="fas fa-reply sm:tw-text-sm tw-text-xs tw-mr-1 tw-text-grey-darker"></i>
          <span class="sm:tw-text-sm tw-text-xs">
            {{ $thread->replies_count }}
          </span>
        </div>
        
      </div>


      <hr>
    @endforeach

  </div>

 
@endsection


