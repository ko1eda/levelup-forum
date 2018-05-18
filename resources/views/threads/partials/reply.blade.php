
<div class="tw-flex tw-flex-col tw-w-full tw-border tw-border-bulma ">
  <div class="tw-py-2 sm:tw-px-4 tw-px-2 tw-border-b tw-bg-bulma-lightest">

    <a href="{{ route('profiles.show', $reply->user) }}" class="username tw-py-2">
      <h1 class="sm:tw-text-base tw-text-sm tw-font-light">
        {{ $reply->user->name }}
      </h1>
    </a>
  </div>{{-- end header --}}

  <div class="sm:tw-px-4 tw-px-2 tw-pt-2 sm:tw-text-base tw-text-sm tw-leading-loose">
    {{ $reply->body }}
  </div>{{-- end body --}}

  <div class="sm:tw-px-4 tw-px-2 tw-pb-2 tw-flex tw-items-center ">
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

      </form>
  
    <div class="tw-text-xs sm:tw-text-sm ">
      {{ $reply->favorites_count }}
    </div>

  </div>{{-- end info bar --}}
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
