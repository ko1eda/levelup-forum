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
      {{ strip_tags(substr($activity->subject->body, 0, 50)) . '...' }}
    @endslot

  @endcomponent

@endif
