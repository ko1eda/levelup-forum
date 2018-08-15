
<lu-reply :attributes="{{ $reply->makeHidden('user') }}" :best-Uri={{ json_encode(route('replies.best.store', $reply, false)) }} :has-Best={{ json_encode($hasBest) }} inline-template>

  <div :class="['tw-flex tw-flex-col tw-w-full', isMarkedBest ? 'tw-border-green-lighter tw-border-4' :'tw-border-bulma tw-border'] " v-if="!deleted">
    <div class="tw-py-2 sm:tw-px-4 tw-px-2 tw-border-b tw-bg-bulma-lightest">
      <div class="tw-flex tw-justify-between tw-items-center">

        <div class="tw-flex tw-items-center">

          <img src="{{ $reply->user->profile->avatar_path }}" alt="avatar" class="tw-mr-2">

          <a href="{{ route('profiles.show', $reply->user) }}" class="username ">
            <h1 class="sm:tw-text-base tw-text-sm tw-font-light tw-inline">
              <span class=''>{{ $reply->user->username }}</span>
            </h1>
          </a>

        </div>
        {{-- end header left-side (username) --}}

        <div class='tw-flex tw-items-center tw-justify-end tw-w-12'>
          @can('update', $thread)
            <span v-show="isMarkedBest" disabled>
              <i class="fas fa-check-circle tw-text-green tw-cursor-pointer " title="Marked as best reply" ></i>
            </span>

            <span @click="handleMarkBest"  v-show="!isMarkedBest">
              <i class="fas fa-check-circle tw-text-bulma-light hover:tw-text-green tw-cursor-pointer " title="Mark as best reply" ></i>
            </span>
          @endcan{{-- only the thread owner can mark best --}}

          @can('delete', $reply)
            <button class="delete is-small hover:tw-bg-red-light tw-ml-2 " @click="handleReplyDelete" title="Delete your reply"></button>
          @endcan{{-- only show the delete button for the user who owns the reply --}}

        </div>{{-- end delete and mark best buttons --}}
        
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

        <div id="style-link" v-else>
          <div class="tw-break-words" v-html="anchoredBody"></div>
        </div>{{-- end reply body --}}

      </div>{{-- end body text / Vue Reply edit form --> v-cloak to hide this form until the whole component is fully loaded --}}
    
      <div class="tw-flex tw-items-center" v-if="!editing">
        <div class="tw-mr-2 tw-text-xs sm:tw-text-sm ">
          {{ $reply->created_at->diffForHumans() }}
        </div>{{-- end time --}}
        

        @can('update', $reply)
          <div class="tw-mr-2">
            <button type="submit" class="tw-flex tw-items-center tw-text-bulma-dark" @click="handleEditing">
              <i class="fas fa-edit tw-text-xs hover:tw-text-bulma-link"></i>
              <span class="tw-ml-1 tw-text-xs sm:tw-text-sm">edit</span>
            </button>
          </div>
        @endif {{-- end edit/ Vue Edit Button --}}
        
      @auth
        {{-- <lu-favorite :reply-data="{{ $reply }}" v-if="!this.hasBest"></lu-favorite>end favorites widget --}}
      @endauth

      </div>{{-- end info level --}}
  
    </div>{{-- end reply body --}}
  </div>

</lu-reply>
