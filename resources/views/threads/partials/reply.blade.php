
<div class="tw-flex tw-flex-col tw-w-full tw-rounded tw-border tw-border-grey-dark ">

  <div class="tw-flex tw-justify-between tw-px-8 tw-py-2 tw-border-b has-background-grey-lighter">
    <a href="#" class="username">
      <h1 class="tw-font-light tw-text-lg">
        {{$reply->user->name}}
      </h1>
    </a>
    <div class=" time-right tw-font-light">
      {{ $reply->created_at->diffForHumans() }}
    </div>
  </div>

  <div class="tw-px-8 tw-py-2 tw-leading-loose">
    {{ $reply->body }}
  </div>

</div>
