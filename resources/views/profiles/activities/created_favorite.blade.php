@isset($activity->subject)
  @component('profiles.activities.activity')

    @slot('backgroundColor')
      bulma-warning-light
    @endslot

    @slot('link')
      {{-- {{ $activity->subject->favoritable->thread->path() }} --}}
    @endslot

    @slot('header')
      Favorited:
    @endslot

    @slot('body')
      A Reply
    @endslot

  @endcomponent
@endif