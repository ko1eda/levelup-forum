<form action="{{ $thread->path('/replies') }}" method="POST">
  {{ csrf_field() }}
  
  <div class="field">
    <label class="label">
      <h1 class="twtext-lg">
        Reply:
      </h1>
    </label>
    
    <div class="control">
      <textarea class="textarea" name="body" ></textarea>
    </div>
  </div>

  <div class="field">
    <div class="control">
      <button type="submit" class="button is-outlined is-primary">Submit</button>
    </div>
  </div>

</form>