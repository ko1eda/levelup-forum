<lu-reply-form :api-path={{ json_encode(route('api.users.index')) }} inline-template>
    <form action="{{ route('replies.store', $thread) }}" method="POST">
      {{ csrf_field() }}
      
      <div class="field">
        <label class="label">
          <h1 class="tw-text-lg">
            Reply:
          </h1>
        </label>
        
        <div class="control">
          <at-ta :members="members" >
            <textarea class="textarea" name="body" @input="debounceInput"></textarea>
          </at-ta>
        </div>
      </div>
      
      <div class="field">
        <div class="control">
    
          <p class="help is-danger">
            @if(count($errors->all()))
              {{ $errors->first() }}  
            @endif
          </p>
          {{-- end spam dection error check --}}
    
        </div>
      </div>
    
      <div class="field">
        <div class="control">
          <button type="submit" class="button is-small is-outlined is-primary">Submit</button>
        </div>
      </div>
    
    </form>
  
</lu-reply-form>