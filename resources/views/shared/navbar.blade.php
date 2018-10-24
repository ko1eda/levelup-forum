<lu-navbar inline-template>
  <nav class="navbar tw-shadow" role="navigation">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('/') }}">
          {{ config('app.name') }}
        </a>
        <div :class="['navbar-burger burger', {'is-active' : isActive }]"  @click="isActive ? isActive = false :  isActive = true">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
  
      <div id="navbar" :class="['navbar-menu tw-shadow-none', {'is-active' : isActive }, {'tw-border-t tw-border-bulma-lighter' : isActive}]" v-cloak>
        <div class="navbar-start">
          <div class="navbar-item has-dropdown is-hoverable ">
            @if(Auth::check() && count( Auth::user()->unreadNotifications))
              {{-- it is completely hidden on anything with a screen size above 1025px --}}
              <lu-notification-widget v-if="isActive" class="hidden-non-touch"
                :user-data="{{ \Auth::user()->makeHidden('email') }}"
                :index-route={{ json_encode(route('users.notifications.index', \Auth::user(), false )) }}
                :mark-route={{ json_encode(route('users.notifications.update', \Auth::user(), false)) }}
                :navbar-Active="isActive">
              </lu-notification-widget>
            @endif

            <a href="{{ route('threads.index') }}" class="navbar-link">
              Browse
            </a>
            <div class="navbar-dropdown is-boxed">
              <a class="navbar-item" href="{{ route('threads.index') }}">All Threads</a>
              <a class="navbar-item" href="{{ route('threads.index', '?popular=1') }}">Popular Threads</a>
              <a class="navbar-item" href="{{ route('threads.index', '?active=1') }}">Active Threads</a>
              <a class="navbar-item" href="{{ route('threads.index', '?unresponded=1') }}">Unresponded Threads</a>
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
            @if(count(Auth::user()->unreadNotifications))
              <lu-notification-widget class="hidden-touch"
                  :user-data="{{ \Auth::user()->makeHidden('email') }}"
                  :index-route={{ json_encode(route('users.notifications.index', \Auth::user(), false )) }}
                  :mark-route={{ json_encode(route('users.notifications.update', \Auth::user(), false)) }}
                  :navbar-Active="isActive"
                  >
              </lu-notification-widget>
            @endif
            {{-- end notifications vue component --}}
  
            <div class="navbar-item has-dropdown is-hoverable ">
              <a class="navbar-link tw-text-green tw-font-bold lg:tw-text-bulma-dark lg:tw-font-normal hover:tw-text-bulma-link">
                {{ Auth::user()->name }}
              </a>
              <div class="navbar-dropdown is-boxed">
                <a href="{{ route('threads.create') }}" class="navbar-item">
                  New Thread
                </a>
                <a href="{{ route('channels.create') }}" class="navbar-item">
                  New Channel
                </a>
                
                <a class="navbar-item" href="{{ route('profiles.show', Auth::user()) }}">
                  My Profile
                </a>

                <a class="navbar-item" href="/threads/?by={{ Auth::user()->name }}">
                  My Threads
                </a>
  
                <div class="navbar-divider"></div>
  
                <a class="navbar-item" href="{{ route('profiles.settings.edit', Auth::user()) }}">
                   Settings
                </a>{{-- profile settings --}}
  
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
</lu-navbar>