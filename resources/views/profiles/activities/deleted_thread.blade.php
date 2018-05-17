@component('profiles.activities.activity')

  @slot('backgroundColor')
    bulma-danger-light
  @endslot

  @slot('link')
    {{ route('threads.index') }}
  @endslot

  @slot('header')
    Deleted:
  @endslot

  @slot('body')
    A Thread
  @endslot

@endcomponent


  
{{--   
<div class="lu-activity">
    <div class="lu-activity-body tw-bg-bulma-danger-light">
  
      <a href="{{ route('threads.index') }}" class="">
        <span class="lu-activity-header">Deleted:</span>
      </a>
      <span class="tw-italic">A thread</span>
  
    </div>
  </div> --}}
    