@extends('layouts.app') 

@section('content')
  <div class="columns is-centered">
    <div class="column is-8">
      <div class="lu-card">
        <div class="lu-card-header ">
          <p>
            Publish A Thread
          </p>
        </div>
        <form action="/threads" method="POST" class="lu-card-body">
          {{ csrf_field() }}
    
            <div class="field">
              <label class="label">Title</label>
              <div class="control">
                <input 
                type="text"
                class="input" 
                name="title"
                value="{{ old('title') }}"
                required>
              </div>
              @if($errors->has('title'))
                <p class="help is-danger">
                  {{$errors->first('title')}}   
                </p>  
              @endif
            </div>
    
            <div class="field">
              <label class="label">Channel</label>
              <div class="control">
                <div class="select">
                  <select name="channel_id">
                    <option>Choose one...</option>
                    
                    @foreach($channels as $channel)
                      {{-- this makes the select option the old value --}}
                      <option value="{{ $channel->id }}" {{ old('channel_id') === $channel->id ? 'selected' : ''}}>
                        {{ $channel->name }}
                      </option>
                    @endforeach
    
                  </select>
                </div>  
              </div>
              @if($errors->has('channel_id'))
                <p class="help is-danger">
                  {{$errors->first('channel_id')}}   
                </p>  
              @endif
            </div>
    
            <div class="field">
              <label class="label">Content</label>
              <div class="control">
                <textarea 
                class="textarea" 
                id="thread-body" 
                rows="6" 
                name="body"
                required>{{ old('body') }}</textarea>
      
                @if($errors->has('body'))
                  <p class="help is-danger">
                    {{$errors->first('body')}}   
                  </p>  
                @endif
              </div>
            </div>
    
          <div class="field">
            <div class="control is-grouped">
            
              <button type="submit" class="button is-small is-primary">Publish</button>
              <a href="{{\URL::previous()}}" class="button is-small is-grey" role="button">Cancel</a>
    
            </div>
          </div>
    
        </form>
      </div>
    </div>
  </div>
@endsection
