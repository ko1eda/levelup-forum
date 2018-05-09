<nav class="navbar is-primary" role="navigation">
  <div class="container">
    <div class="navbar-brand">
      <a class="navbar-item" href="{{ url('/') }}">
        {{ config('app.name') }}
      </a>
      <div class="navbar-burger burger" data-target="">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <div id="navbar" class="navbar-menu">

      <div class="navbar-start">
        <div class="navbar-item has-dropdown is-hoverable ">
          <a class="navbar-link">
            Browse
          </a>
          <div class="navbar-dropdown is-boxed">
            <a class="navbar-item" href="/threads">All Threads</a>
            @auth
            <div class="navbar-divider"></div>

            <a class="navbar-item" href="/threads/?by={{Auth::user()->name}}">My Threads</a>
            @endauth
          </div>
        </div>

        <div class="navbar-item has-dropdown is-hoverable ">
          <a class="navbar-link">
            Channels
          </a>
          <div class="navbar-dropdown is-boxed">
            @foreach ($channels as $channel)
            <a class="navbar-item" href="/threads/{{$channel->slug}}">
              {{$channel->name}}
            </a>
            @endforeach
          </div>
        </div>
      </div>
      {{-- end navbar-start --}}

      <div class="navbar-end">
        @guest
        <a class="navbar-item" href="{{ route('login') }}">{{ __('Login') }}</a>
        <a class="navbar-item" href="{{ route('register') }}">{{ __('Register') }}</a>
        @else
          <div class="navbar-item has-dropdown is-hoverable ">
            <a class="navbar-link">{{ Auth::user()->name }}</a>
            <div class="navbar-dropdown is-boxed">

              <a href="{{route('threads.create')}}" class="navbar-item">
                New thread
              </a>

              <div class="navbar-divider"></div>
              
              <a class="navbar-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
          </div>
        @endguest
      </div>
      {{-- navbar-end ends --}}
      
    </div>
  </div>
</nav>