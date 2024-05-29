<x-mail::message>
# Hello,

Your review export is ready. Please click the button below to download it.

<x-mail::button :url="$url">
Download
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
