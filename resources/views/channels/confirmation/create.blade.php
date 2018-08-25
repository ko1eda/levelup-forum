@extends('layouts.app')


@section('content')
<div class="columns is-centered">
  <div class="column is-9-tablet is-6-desktop">
    <lu-card :data="{{ json_encode($data) }}">

    </lu-card>
  </div>
</div>
@endsection