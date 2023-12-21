<x-mail::message>
<p>CLICK THE BELOW BUTTON TO REDIRECT FORGET PASSWORD LINK</p>
<x-mail::button :color="'red'" :url="'https://tiktok-swipe--poc.web.app/reset?token='.$token.'&email='.$email">
Forget Password
</x-mail::button>
<p>Note : Link will expire in next 5 min!</p>
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>