<lu-reply-form :api-path={{ json_encode(route('api.users.index')) }} :locked={{ json_encode($thread->locked) }} inline-template>
    <form action="{{ route('replies.store', $thread) }}" method="POST" v-if="!threadIsLocked" v-cloak>
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
    
    </form>{{-- end reply form --}}

    <article class="message is-danger" v-else v-cloak>
      <div class="message-body tw-p-4 tw-text-sm md:tw-text-base">
        Sorry, it looks like the thread has been locked by an administrator.
      </div>
    </article>{{-- end reply locked message --}}
  
</lu-reply-form>