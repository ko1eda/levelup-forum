
<div class="tw-flex tw-flex-col tw-w-full tw-border tw-border-bulma ">

  <div class="tw-py-2 sm:tw-px-4 tw-px-2 tw-border-b tw-bg-bulma-lightest">
    <div class="tw-flex tw-justify-between tw-items-center">
      <a href="{{ route('profiles.show', $reply->user) }}" class="username">
        <h1 class="sm:tw-text-base tw-text-sm tw-font-light">
          {{ $reply->user->name }}
        </h1>
      </a>{{-- end header left-side (username) --}}
      
      @can('delete', $reply)
        <form class="tw-mr-1 tw-flex tw-items-center" action="{{ route('replies.destroy', $reply) }}" method="POST">
            @csrf
            @method('delete')
          <button class="delete is-small hover:tw-bg-red-light"></button>
        </form>
      @endcan{{-- only show the delete button for the user who owns the reply --}}
    </div>{{-- end level --}}
  </div>{{-- end header --}}

  <div class="sm:tw-px-4 tw-px-2 tw-py-2">
    <div class=" tw-mb-1 sm:tw-text-base tw-text-sm">
      {{ $reply->body }}
    </div>{{-- end body text --}}
  
    <div class="tw-flex tw-items-center ">
      <div class="tw-mr-2 tw-text-xs sm:tw-text-sm ">
        {{ $reply->created_at->diffForHumans() }}
      </div>{{-- end time --}}

      <form class="tw-mr-1" action="/replies/{{$reply->id}}/favorites" method="POST">
        @csrf     
        @if($reply->isFavorited())
          <button class="tw-flex tw-items-center" type="submit" disabled>
            <i class="fas fa-star tw-text-xs tw-text-yellow-dark"></i>
          </button>
        @else
          <button type="submit" class="tw-flex tw-items-center">
            <i class="far fa-star tw-text-xs tw-text-bulma-dark hover:tw-text-yellow-dark"></i>
          </button>
        @endif
      </form>{{-- end favorites button --}}

      <div class="tw-text-xs sm:tw-text-sm ">
        {{ $reply->favorites_count }}
      </div>{{-- end favorites count --}} 

    </div>{{-- end info level --}}
  </div>{{-- end reply body --}}

</div>


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
