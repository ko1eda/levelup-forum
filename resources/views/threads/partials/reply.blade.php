
<lu-reply :attributes="{{ $reply->makeHidden('user') }}" inline-template>

  <div class="tw-flex tw-flex-col tw-w-full tw-border tw-border-bulma " v-if="!deleted">
    <div class="tw-py-2 sm:tw-px-4 tw-px-2 tw-border-b tw-bg-bulma-lightest">
      <div class="tw-flex tw-justify-between tw-items-center">

        <a href="{{ route('profiles.show', $reply->user) }}" class="username">
          <h1 class="sm:tw-text-base tw-text-sm tw-font-light">
            {{ $reply->user->name }}
          </h1>
        </a>{{-- end header left-side (username) --}}
        
        @can('delete', $reply)
          <button class="delete is-small hover:tw-bg-red-light" @click="handleReplyDelete"></button>
        @endcan{{-- only show the delete button for the user who owns the reply --}}

      </div>{{-- end level --}}
    </div>{{-- end header --}}
  
    <div class="sm:tw-px-4 tw-px-2 tw-py-2">
      <div class=" tw-mb-1 sm:tw-text-base tw-text-sm" v-cloak>
        <div v-if="editing">

          <div class="field">
            <div class="control">
              <textarea class="textarea" :class="this.error ? 'tw-text-red' : '' " v-model="body"></textarea>
            </div>
          </div>{{-- end textarea --}}

          <div class="field">
            <div class="control is-grouped">
              <button type="submit" class="button is-small is-primary" @click="handleReplyUpdate">Update</button>
              <button type="submit" class="button is-small is-grey" @click="handleReplyCancel">Cancel</button>
            </div>
          </div>{{-- end update/cancel buttons --}}
          
        </div>{{-- end v-if --}}

        <div v-else>
          <div class="tw-break-words" v-text="body"></div>
        </div>

      </div>{{-- end body text / Vue Reply edit form --> v-cloak to hide this form until the whole component is fully loaded --}}
    
      <div class="tw-flex tw-items-center" v-if="!editing">
        <div class="tw-mr-2 tw-text-xs sm:tw-text-sm ">
          {{ $reply->created_at->diffForHumans() }}
        </div>{{-- end time --}}
        

        @can('update', $reply)
          <div class="tw-mr-2">
            <button type="submit" class="tw-flex tw-items-center tw-text-bulma-dark" @click="handleEditing">
              <i class="fas fa-edit tw-text-xs hover:tw-text-blue"></i>
              <span class="tw-ml-1 tw-text-xs sm:tw-text-sm">edit</span>
            </button>
          </div>
        @endif {{-- end edit/ Vue Edit Button --}}
        
      @auth
        <lu-favorite :reply-data="{{ $reply }}"></lu-favorite>{{-- end favorites widget --}}
      @endauth

      </div>{{-- end info level --}}
  
    </div>{{-- end reply body --}}
  </div>

</lu-reply>


{{-- 
<div class="tw-relative tw-w-6">
    <i class="far fa-star tw-text-xs sm:tw-text-sm"></i>
    <span class="tw-absolute tw-text-xs reply-pos">1124</span>
</div> 

<div class="lu-level sm:tw-px-4 tw-px-2 ">
  <div class="lu-level-last">
    <i class="far fa-star"></i>
    <span class="tw-text-xs">1124</span>
  </div>
</div>

--}}
