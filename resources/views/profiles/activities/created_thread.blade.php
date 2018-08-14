@isset($activity->subject)
  @component('profiles.activities.activity')

    @slot('backgroundColor')
      bulma-primary-light
    @endslot

    @slot('link')
      {{ route('threads.show', [$activity->subject->channel, $activity->subject, $activity->subject->slug]) }}
    @endslot

    @slot('header')
      Published:
    @endslot

    @slot('body')
      {{$activity->subject->title}}
    @endslot

  @endcomponent
@endif
