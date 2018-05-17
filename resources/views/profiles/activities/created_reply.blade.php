@isset($activity->subject)
  @component('profiles.activities.activity')

    @slot('backgroundColor')
      bulma-lightest
    @endslot

    @slot('link')
      #
    @endslot

    @slot('header')
      Replied:
    @endslot

    @slot('body')
      {{$activity->subject->body}}
    @endslot

  @endcomponent

@endif

  {{-- <div class="lu-activity">
    <div class="lu-activity-body tw-bg-bulma-lightest">

        <a href="#" class="">
          <span class="lu-activity-header">Replied:</span>
        </a>
        <span class="tw-italic">{{ $record->subject->body }}</span>

    </div>
  </div> --}}

{{-- 
  
    <div class="tw-">
      <a href="#" class="">
        <span class="lu-activity-header">Replied:</span>
      </a>
      <span class="tw-italic">{{ $activity->subject->body }}</span>
    </div>
    
     <div class="tw-">
       {{ $activity->subject->created_at->diffForHumans() }}
     </div>

  --}}