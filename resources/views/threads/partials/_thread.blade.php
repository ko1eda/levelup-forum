<lu-thread :thread="{{ $thread->makeHidden(['user', 'channel']) }}" :endpoint={{ json_encode(route('threads.update', [$thread->channel, $thread, $thread->slug], false)) }} inline-template>
  <div class="lu-card">
    <div class="lu-card-header tw-text-lg md:tw-text-xl tw-flex tw-justify-between tw-items-center">
      <p>
        {{ $thread->title }}
      </p>{{-- end thread title --}}
  
      <div class="tw-flex tw-justify-end tw-items-center tw-w-24 tw-text-sm ">
        @can('update', $thread)
          <span class="tw-mr-4 tw-cursor-pointer" @click="handleCancel" v-show="editing">
            <i class="fas fa-edit tw-text-bulma-link" title="Close the editor"></i>
          </span>

          <span class="tw-mr-4 tw-cursor-pointer" @click="setEditing" v-show="!editing">
            <i class="fas fa-edit tw-text-bulma-light hover:tw-text-bulma-link" title="Edit the thread"></i>
          </span>
        @endcan
        {{-- edit thread button --}}

        @can('lock', $thread)
          <lu-lock-button 
            :endpoint={{ json_encode(route('threads.lock.store', $thread)) }}
            :locked={{ json_encode($thread->locked) }}>
          </lu-lock-button>
        @endcan
  
        {{-- if the user has permission to update/delete the thread  --}}
        @can('delete', $thread)
          <form action="{{ route('threads.destroy', [$thread->channel, $thread, $thread->slug]) }}" method="POST">
            @method('delete'){{-- delete method spoofing --}}
            @csrf
            <button type="submit" class="tw-flex tw-items-center">
              <a class="delete is-small hover:tw-bg-red-light "></a>
            </button>
          </form>
        @endcan {{-- end delete button --}}
       
        @can('subscribe', $thread)
          <lu-subscribe-button 
            :subscribed="{{ $thread->makeHidden('user') }}" 
            :endpoint="{{ json_encode(route('subscriptions.threads.store', $thread)) }}">
          </lu-subscribe-button>
        @endcan {{-- end Vue SubscribeButton component --}}
      </div>{{-- end header buttons --}}

    </div>{{-- end header --}}
    
    <div v-if="editing" class="lu-card-body" v-cloak>
      <div class="field tw-mb-0">
        <div class="control">
          {{-- note :body.sync is the same as passing body as a prop and then listning for a @update:body event from the text-editor --}}
          <lu-text-editor :height="['tw-h-64']" :body.sync="body" ></lu-text-editor>

        </div>
      </div>{{-- end textarea --}}

      <div class="field">
        <div class="control is-grouped">
          <button type="submit" class="button is-small is-primary" @click="handleUpdate">Update</button>
          <button type="submit" class="button is-small is-grey" @click="handleCancel">Cancel</button>
        </div>
      </div>{{-- end update/cancel buttons --}}
    </div>{{-- edit form for the thread --}}

    <div class="lu-card-body tw-leading-loose" v-html="editedBody" v-if="!editing"></div>{{-- end thread body --}}



    <div class="lu-card-section tw-py-0 ">
      @auth
        @include('threads.partials.reply-form', ['thread' => $thread])
      @endauth
  
      @guest
      <article class="message is-warning">
        <div class="message-body tw-p-4 tw-text-sm md:tw-text-base">
          Please <a href="/login" class="tw-font-semibold tw-no-underline">login</a>
          or <a href="/register" class="tw-font-semibold tw-no-underline">register</a>
          to join this discussion.
        </div>
      </article>
      @endguest{{-- end guest login notification --}}
          
      @isset($bestReply)
        <lu-best-reply-divider :has-best={{ json_encode(isset($bestReply)) }}></lu-best-reply-divider>
        <div class="tw-px-2 tw-mb-4">
          <div class="tw-my-4" id="reply-{{ $bestReply->id }}">
            @include('threads.partials.reply', ['reply' => $bestReply, 'thread' => $thread, 'hasBest' => true])
          </div>
        </div>
      @endif {{-- end best reply --}}
  
      <lu-reply-divider :initial-count={{ $thread->replies_count - ($bestReply ? 1 : 0) }}></lu-reply-divider> {{-- divider between post and replies + replies heading--}}
      
      {{-- Note the id is so that we can navigate to a given reply --}}
      {{-- on the page using a hash link ex /#reply-4 --}}
      <div class="tw-px-2 tw-mb-4">
  
        @foreach($replies as $reply)
          <div class="tw-my-4" id="reply-{{ $reply->id }}">
            @include('threads.partials.reply', ['hasBest' => false]) {{-- has best distingushes theses replies from the marked best reply --}}
          </div>
        @endforeach
  
        {{ $replies->links() }}
      </div> {{-- end replies and pagination --}}
  
    </div> {{-- end replies section --}}
  </div>{{-- end pannel --}}
</lu-thread>