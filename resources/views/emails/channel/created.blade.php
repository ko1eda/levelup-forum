@component('mail::message')
# Woah, User {{ $user->username }} has proposed a new channel

Click the button below to go to the confirmation page.

You have one week before the request expires. 

@component('mail::button', ['url' => $uri, 'color' => 'green'])
Go To Confirmation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
