<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      {{ config('app.name') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">
        <li>
          <a class="nav-link" href="/threads">All Threads</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Channels
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">

            @foreach ($channels as $channel)
            <a class="dropdown-item" href="/threads/{{$channel->slug}}">
              {{$channel->name}}
            </a>
            @endforeach

          </div>
        </li>
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        @guest
          <li>
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          <li>
            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          </li>
        @else
        <li class="nav-item dropdown">
          <a id="navbarDropdown" 
            class="nav-link dropdown-toggle" 
            href="#" role="button" 
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false" v-pre>

            {{ Auth::user()->name }}
            <span class="caret"></span>
          </a>

          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a href="{{route('threads.create')}}" class="dropdown-item">
              New thread
            </a>
            <div class="dropdown-divider"></div>
            
            <a class="dropdown-item" 
              href="{{ route('logout') }}" 
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>

        </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>