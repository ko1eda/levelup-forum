
<div class="tw-flex tw-flex-col tw-w-full tw-border tw-border-bulma ">
  <div class="tw-py-2 sm:tw-px-4 tw-px-2 tw-border-b tw-bg-bulma-lightest">

    <a href="#" class="username tw-py-2">
      <h1 class="sm:tw-text-base tw-text-sm tw-font-light">
        {{$reply->user->name}}
      </h1>
    </a>

  </div>{{-- end header --}}

  <div class="sm:tw-px-4 tw-px-2 tw-pt-2 sm:tw-text-base tw-text-sm tw-leading-loose">
    {{ $reply->body }}
  </div>{{-- end body --}}

  <div class="sm:tw-px-4 tw-px-2 tw-pb-2 ">
    <div class="tw-inline tw-align-baseline tw-mr-1 tw-text-sm ">
      {{ $reply->created_at->diffForHumans() }}
    </div>

    <div class="tw-inline tw-align-baseline">
      <form action="/replies/{{$reply->id}}/favorites" method="POST" class="tw-inline tw-align-baseline">
        @csrf
        <button type="submit">
          <i class="far fa-star tw-text-xs tw-text-bulma-dark hover:tw-text-yellow-light"></i>
        </button>
      </form>
    </div>{{-- end favorites --}}

    <div class="tw-inline fav-pos tw-text-xs">
      <span>110</span>
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

<style>
  .reply-pos {
    top: 9px;
    right: -10px;
  }
  .icon-size {
    font-size: .65rem;
  }

  .fav-pos {
    vertical-align: -1px;
    margin-left: -1.5px;
  }
</style>