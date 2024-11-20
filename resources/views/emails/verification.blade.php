@component('mail::message')
# Verification Code

Your verification code is {{ $code }}.

This code will expire in 10 minutes.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
