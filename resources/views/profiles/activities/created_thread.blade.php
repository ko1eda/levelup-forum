@isset($activity->subject)
  @component('profiles.activities.activity')

    @slot('backgroundColor')
      bulma-primary-light
    @endslot

    @slot('link')
      {{ $activity->subject->path() }}
    @endslot

    @slot('header')
      Published:
    @endslot

    @slot('body')
      {{$activity->subject->title}}
    @endslot

  @endcomponent
@endif




{{-- <div class="lu-activity">
  <div class="lu-activity-body tw-bg-bulma-primary-light">

    <a href="{{ $record->subject->path() }}" class="">
      <span class="lu-activity-header">Published:</span>
    </a>
    <span class="tw-italic">{{ $record->subject->body }}</span>

  </div>
</div> --}}