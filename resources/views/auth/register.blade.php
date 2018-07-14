@extends('layouts.app')

@section('content')
<div class="columns is-centered tw-flex tw-items-center">
  <div class="column is-5-desktop is-8-tablet">

    @include('auth.partials._registration', ['isSignup' => true])
    
  </div>
</div>
@endsection
