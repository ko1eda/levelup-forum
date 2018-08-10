@component('mail::message')
# You're almost there

Just click the button below and you're good to go.

@component('mail::button', ['url' => '', 'color' => 'green'])
Confirm 
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
