@extends('layouts.app') 

@section('content')
  <div class="card">
    <div class="card-header bg-secondary text-center text-white">
      Create A Thread
    </div>
    <div class="card-body">
      <form action="/threads" method="POST">
        {{ csrf_field() }}
        
          <div class="form-group">
            <label for="thread-title">Title</label>
            <input 
              type="text"
              class="form-control" 
              id="thread-title"
              name="title"
              value="{{ old('title') }}"
              required>

            @if($errors->has('title'))
             <small class="form-text text-danger">
              {{$errors->first('title')}}   
            </small>  
            @endif
          </div>

          <div class="form-group">
            <label>Channel</label>
            <select class="form-control" name="channel_id" required>
              <option>Choose one...</option>

              @foreach($channels as $channel)
                {{-- this makes the select option the old value --}}
                <option value="{{ $channel->id }}" {{ old('channel_id') === $channel->id ? 'selected' : ''}}>
                  {{ $channel->name }}
                </option>
              @endforeach
            </select>  

            @if($errors->has('channel_id'))
              <small class="form-text text-danger">
                {{$errors->first('channel_id')}}   
              </small>  
            @endif
          </div>

  
        <div class="form-group">
          <label for="thread-body">Content</label>
          <textarea 
            class="form-control" 
            id="thread-body" 
            rows="6" 
            name="body"
            required>{{ old('body') }}</textarea>

          @if($errors->has('body'))
            <small class="form-text text-danger">
              {{$errors->first('body')}}   
            </small>  
          @endif
        </div>

        <button type="submit" class="btn btn-sm btn-outline-primary">Publish</button>
        <a href="{{\URL::previous()}}" class="btn btn-sm btn-outline-secondary" role="button">Cancel</a>
      </form>
    </div>
  </div>
@endsection
