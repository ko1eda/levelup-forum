
<form action="{{ $thread->path('/replies') }}" method="POST">
  {{ csrf_field() }}
  <div class="form-group">
    <label for="reply">Post a reply</label>
    <textarea 
      class="form-control" 
      id="reply"
      rows="3"
      name="body"
      required></textarea>
  </div>
 
  <button type="submit" class="btn btn-outline-primary">Post</button>
</form>