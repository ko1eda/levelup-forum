@component('mail::message')
# You're almost there

Just click the button below and you're good to go.

@component('mail::button', ['url' => route('register.confirm', "tokenID={$user->confirmation_token}"), 'color' => 'green'])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
