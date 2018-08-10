<div class="lu-card tw-px-2 sm:tw-px-6">
    <div class="lu-card-header tw-p-4">
      <h1 class="tw-text-center tw-text-2xl sm:tw-text-3xl">
        @isset($isSignup)
          Let's get you signed up
        @else
          Sign in 
        @endisset
      </h1>
    </div>
    <form class="tw-p-4 tw-pb-0" action="/{{ isset($isSignup) ? 'register' : 'login' }}" method="POST">
      @csrf

      @isset($isSignup)
        <div class="field">
          <label class="label">Name</label>
          <div class="control has-icons-left">
            <input 
            class="input" 
            type="text" 
            placeholder="Kevin Malone" 
            required
            name="name"
            >
            <span class="icon is-small is-left">
                <i class="fas fa-user"></i>
            </span>
          </div>

          @if ($errors->has('name'))
            <p class="help is-danger">
              {{ $errors->first('name') }}
            </p>
          @endif
        </div>
      @endisset

      <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left">
          <input 
          class="input" 
          type="email" 
          placeholder="kmalone@dundermifflin.com" 
          required
          name="email"
          >
          <span class="icon is-small is-left">
            <i class="fas fa-envelope"></i>
          </span>
        </div>

        @if ($errors->has('email'))
          <p class="help is-danger">
              {{ $errors->first('email') }}
          </p>
        @endif
      </div>


    @isset($isSignup)
      <div class="field">
        <label class="label">Username</label>
        <div class="control has-icons-left">
          <input 
          class="input" 
          type="text" 
          placeholder="cookie.monster" 
          required
          name="username"
          >
          <span class="icon is-small is-left">
              <i class="fas fa-at"></i>
          </span>
        </div>

        @if ($errors->has('username'))
          <p class="help is-danger">
            {{ $errors->first('username') }}
          </p>
        @endif
      </div>
     @endisset

      <div class="field">
        <label class="label">Password</label>
        <div class="control has-icons-left">
          <input 
          class="input" 
          type="password" 
          placeholder="Password" 
          required
          name="password"
          >
          <span class="icon is-small is-left">
            <i class="fas fa-key"></i>
          </span>
        </div>

        @if ($errors->has('password'))
          <p class="help is-danger">
            {{ $errors->first('password') }}
          </p>
        @endif
      </div>

      @if(!isset($isSignup))
        <div class="field">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="remember" {{ old( 'remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
            </label>
          </div>
        </div>
      @endif

      
      @isset($isSignup)
        <div class="field">
          <label class="label">Confirm Password</label>
          <div class="control has-icons-left">
            <input 
            class="input" 
            type="password" 
            placeholder="Password" 
            required
            name="password_confirmation"
            id="password_confirmation"
            >
            <span class="icon is-small is-left">
              <i class="fas fa-key"></i>
            </span>
          </div>

          @if ($errors->has('password_confirmation'))
            <p class="help is-danger">
              {{ $errors->first('password_confirmation') }}
            </p>
          @endif
        </div>
      @endisset

      <div class="field is-grouped is-grouped-centered tw-my-8">
        <div class="control">
          <button class="button is-primary is-outlined">Submit</button>
        </div>

        @if(!isset($isSignup))
          <div class="control">
            <a class="button is-dark is-outlined" href="{{ route('password.request') }}">Forgot password?</a>
          </div>
        @endif

      </div>
    </form>
</div>

