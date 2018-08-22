<lu-reply-form :users-path={{ json_encode(route('api.users.index')) }} :locked={{ json_encode($thread->locked) }} inline-template>
    <div>
      <form action="{{ route('replies.store', $thread) }}" method="POST" v-if="!threadIsLocked && !threadIsEditing" v-cloak>
        {{ csrf_field() }}
        
        <div class="field tw-mb-0">
          <label class="label">
            <h1 class="tw-text-lg">
              Leave a Reply:
            </h1>
          </label>
          
          <div class="control ">
            <at :members="members" >
              <lu-text-editor name="body" @input="debounceInput" :height="['tw-h-24']"></lu-text-editor>
            </at>
          </div>
        </div>
        

        @if(count($errors->all()))
          <div class="field">
            <div class="control">
              <p class="help is-danger">
                {{ $errors->first() }}  
              </p>
            </div>
          </div>
        @endif
        {{-- end spam dection error check --}}
      
      
        <div class="field">
          <div class="control">
            <button type="submit" class="button is-small is-outlined is-primary">Submit</button>
          </div>
        </div>
      
      </form>{{-- end reply form --}}
  
      <article class="message is-danger" v-if="threadIsLocked" v-cloak>
        <div class="message-body tw-p-4 tw-text-sm md:tw-text-base">
          Sorry, it looks like the thread has been locked by an administrator.
        </div>
      </article>{{-- end threadlocked message --}}
    </div>
  
</lu-reply-form>