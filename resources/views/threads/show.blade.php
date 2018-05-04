@extends('layouts.app')
@section('content')

    <div class="row justify-content-center">
      <div class="col">

        <div class="card">
          <div class="card-header">
            {{ $thread->title }}
          </div>
          <div class="card-body">
            <h5 class="card-title">user->name</h5>
            <p class="card-text">
              {{ $thread->body }}
            </p>
          </div>
        </div>
      </div>

    </div>

@endsection