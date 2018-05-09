@extends('layouts.app') 
@section('content')

  <div class="lu-pannel">

    @foreach($threads as $thread) 

      <div class="lu-pannel-header tw-text-2xl">
        <a href={{ $thread->path() }}>
          <h4>{{ $thread->title }}</h4>
        </a>
      </div>
      
      <div class="lu-pannel-body">
        <p>{{ $thread->body }}</p>
      </div>

      <hr>
    @endforeach

  </div>

 
@endsection


