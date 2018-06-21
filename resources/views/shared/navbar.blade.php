<nav class="navbar tw-shadow" role="navigation">
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
            <a class="navbar-item" href="{{ route('threads.index') }}">All Threads</a>
            <a class="navbar-item" href="{{ route('threads.index', '?unresponded=1') }}">Unresponded Threads</a>
            <a class="navbar-item" href="/threads/?popular=1">Popular Threads</a>
            <a class="navbar-item" href="/threads/?trending=1">Trending Threads</a>
          </div>
        </div>{{-- Browse dropdown --}}

        <div class="navbar-item has-dropdown is-hoverable ">
          <a class="navbar-link">
            Channels
          </a>
          <div class="navbar-dropdown is-boxed">
            @foreach ($channels as $channel)
            <a class="navbar-item" href="{{ route('threads.index', $channel) }}">
              {{$channel->name}}
            </a>
            @endforeach
          </div> 
        </div>{{-- Channels dropdown --}}

      </div>{{-- end navbar-start --}}

      <div class="navbar-end">
        @guest
        <a class="navbar-item" href="{{ route('login') }}">{{ __('Login') }}</a>
        <a class="navbar-item" href="{{ route('register') }}">{{ __('Register') }}</a>
        @else

          <lu-notification-widget 
              :user-data="{{ \Auth::user()->makeHidden('email') }}"
              :index-route={{ json_encode(route('users.notifications.index', \Auth::user(), false )) }}
              :mark-route={{ json_encode(route('users.notifications.update', \Auth::user(), false)) }}>
          </lu-notification-widget>
          {{-- end notifications vue component --}}

          <div class="navbar-item has-dropdown is-hoverable ">
            <a class="navbar-link">
              {{ Auth::user()->name }}
            </a>
            <div class="navbar-dropdown is-boxed">
              <a href="{{ route('threads.create') }}" class="navbar-item">
                New Thread
              </a>
              <a class="navbar-item" href="{{ route('profiles.show', Auth::user()) }}">
                My Profile
              </a>
              <a class="navbar-item" href="/threads/?by={{ Auth::user()->name }}">
                My Threads
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
          </div>{{-- User dropdown --}}

        @endguest
      </div>{{-- navbar-end ends --}}
      
    </div>
  </div>
</nav>