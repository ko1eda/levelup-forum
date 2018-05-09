<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    @include('shared.navbar')

    <main class="py-4">
      <div class="container">

        @hasSection('sidebar')
          <div class="row">
            
            {{-- pos right on medium+ stacked top on small screens --}}
            <div class="col-md-4 mb-4 order-md-2">
                @yield('sidebar')
            </div>

            <div class="col-md-8">
                @yield('content')
            </div>

          </div>
        @else
          <div class="row justify-content-center">
            
            <div class="col-10">
                @yield('content')
            </div>            

          </div>
        @endif
      </div>
    </main>
    
  </div>
</body>
</html>
