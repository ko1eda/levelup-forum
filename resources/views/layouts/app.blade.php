<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet"> --}}

    <!-- Recaptcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
  <div id="app">
    @include('shared.navbar')

    @hasSection('hero')

      @yield('hero')

    @endif

    <main class="section tw-px-2 md:tw-px-6">
      <div class="container">
        @yield('content')
      </div>
    </main>
    
    {{-- flash messages --}}
    @include('shared.flash')

  </div>

  <script src="{{ mix('js/app.js') }}" ></script>
</body>
</html>
