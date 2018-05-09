@extends('layouts.app') 
@section('content')

  <div class="card">

    <div class="card-header">Forum Threads</div>
    <div class="card-body">

      @foreach($threads as $thread) 
        <article>

          <a href={{ $thread->path() }}>
            <h4>{{ $thread->title }}</h4>
          </a>

          <p>{{ $thread->body }}</p>
        </article>
        
        <hr>
      @endforeach

    </div>
  </div>
 
@endsection


