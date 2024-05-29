<x-mail::message :layout="$layout">
# Hello {{ $review->name }},


Thank you for taking the time to review **<a href="{{ $review->product->url }}">{{ $review->product->name }}</a>** in
our store.

To ensure the authenticity of your review, we kindly request you to click on the link below and verify it.

Don't worry, we will not display your email address.

By doing so, you will help us maintain the trust of our customers in the reviews they read.


<x-mail::table>
| Image  | Rating | Review |
|:---|:---:|:---|
|<img src="{{ $review->product->image }}" style="width: 150px" alt="{{ $review->product->name }}" /> | {{ $review->rating }} | {{ $review->comment }} |
</x-mail::table>

<x-mail::button :url="route('email.confirm-review-request', $review->uuid)">
Confirm Email Address
</x-mail::button>

Thanks,<br>
{{ $review->store->name }}
</x-mail::message>
