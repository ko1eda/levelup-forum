@extends('layouts.app') 

@section('content')
  <div class="card">
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
            required>
        </div>
  
        <div class="form-group">
          <label for="thread-body">Content</label>
          <textarea 
            class="form-control" 
            id="thread-body" 
            rows="6" 
            name="body"
            required></textarea>
        </div>
        
        <button type="submit" class="btn btn-sm btn-outline-primary">Submit Thread</button>
        <button type="submit" class="btn btn-sm btn-outline-dark">Cancel</button>
      </form>
    </div>
  </div>
@endsection
