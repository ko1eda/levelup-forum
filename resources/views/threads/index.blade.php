@extends('layouts.app') 
@section('content')

  <div class="lu-pannel">

    @foreach($threads as $thread) 

      <div class="lu-pannel-header tw-text-3xl">
        <a href={{ $thread->path() }}>
          <h4 class="tw-font-light">{{ $thread->title }}</h4>
        </a>
      </div>
      
      <div class="lu-pannel-body tw-text-grey-darker">
        <p>{{ $thread->body }}</p>
      </div>

      <hr>
    @endforeach

  </div>

 
@endsection


