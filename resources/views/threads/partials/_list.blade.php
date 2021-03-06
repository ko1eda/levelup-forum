{{-- set the the padding if  --}}
<div class="lu-pannel tw-px-4 lg:tw-px-6 tw-py-4 ">
  
  @forelse($threads as $thread)
    <div class="tw-pb-2 tw-text-xl lg:tw-text-2xl">
      <a href={{ route('threads.show', [$thread->channel, $thread, $thread->slug]) }}>
        <h4 class="tw-font-light">{{ $thread->title }}</h4>
      </a>
    </div>
    {{-- end thread title --}}
  
    <div class="tw-pb-2 tw-text-sm ">
      <lu-html-renderer :to-render="{{ json_encode( $thread->body) }}"></lu-html-renderer>
    </div>
    {{-- end thread body --}}
  
    <div class="lu-level tw-px-0 tw-py-0 lg:tw-w-full xl:tw-w-4/5 lg:tw-text-sm tw-justify-between tw-text-xs ">
  
      @isset($displayOwner)
        <div class="lu-level-item tw-text-grey-darker" title="Thread Owner">
          <i class="fas fa-user tw-mr-1 tw-align-middle"></i>
          <a href={{ route('profiles.show', $thread->user) }}>
            <span class="tw-text-green hover:tw-text-green-dark tw-font-semibold tw-align-middle">
              {{ $thread->user->username }}
            </span>
          </a>
        </div>
      @endisset
        {{-- end user name --}}
  
      <div class="lu-level-item tw-text-grey-darker ">
        <i class="fas fa-clock tw-mr-1 tw-text-grey-darker tw-align-middle"></i>
        <span class="tw-align-middle">
          {{ $thread->created_at->diffForHumans() }}
        </span>
      </div>
      {{-- end timestamp --}}

      <div class="lu-level-item tw-text-grey-darker ">
        <i class="fas fa-eye tw-mr-1 tw-text-grey-darker tw-align-middle"></i>
        <span class="tw-align-middle">
          {{ $thread->views()->count() }} views
        </span>
      </div>
      {{-- end timestamp --}}
  
      <div class="lu-level-item tw-mr-0">
        <i class="fas fa-reply tw-mr-1 tw-text-grey-darker tw-align-middle "></i>
        <span class="tw-align-middle">
          {{ $thread->replies_count }} replies
        </span>
      </div>
      {{-- end replies count --}}
      
      
    </div>{{-- end level --}}

    <hr class="tw-my-2 "> 
    @empty 

      <p class="tw-text-sm">There are no threads to display</p>

    @endforelse

  </div>

