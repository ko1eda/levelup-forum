@extends('layouts.app') 

@section('content')
  <div class="columns is-centered">
    <div class="column is-8">
      <div class="lu-card">
        <div class="lu-card-header ">
          <p>
            Submit a channel for review
          </p>
        </div>
        <form action="/channels" method="POST" class="lu-card-body">
          {{ csrf_field() }}
    
            <div class="field">
              <label class="label">Channel Name</label>
              <div class="control">
                <input 
                type="text"
                class="input" 
                name="name"
                value="{{ old('name') }}"
                required>
              </div>
              @if($errors->has('name'))
                <p class="help is-danger">
                  {{$errors->first('name')}}   
                </p>  
              @endif
            </div>
    
          
    
            <div class="field ">
              <label class="label">Description</label>
              <div class="control">
                <lu-text-editor 
                name="description" 
                :animation="false"
                :height="['tw-h-24']" 
                :body={{ json_encode(old('body'))  }} 
                placeholder="A brief summary of your channel..." 
                :label="false"></lu-text-editor>
              </div>

                @if($errors->has('body'))
                <p class="help is-danger tw-mb-2">
                  {{$errors->first('body')}}   
                </p>  
              @endif

              @if($errors->has('g-recaptcha-response'))
                <p class="help is-danger tw-mb-2">
                  {{$errors->first('g-recaptcha-response')}}   
                </p>  
              @endif
            </div>

          <div class="field">
            <div class="control is-grouped">
            
              <button type="submit" class="button is-small is-primary">Submit</button>

              <a href="{{ route('threads.index') }}" class="button is-small is-grey" role="button">Cancel</a>
    
            </div>
          </div>
        
          <div class="tw-flex tw-justify-center tw-mt-4 md:tw-mt-8">
            <div class="g-recaptcha" data-sitekey={{ config('services.recaptcha.site') }} name='g-recaptcha-response'></div>
          </div>
          {{-- recaptcha --}}
        </form>
      </div>
    </div>
  </div>
@endsection

