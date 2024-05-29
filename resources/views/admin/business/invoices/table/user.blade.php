@if($invoice->user)
    <a href="{{ route('admin.customers.show', $invoice->user) }}">{{ $invoice->user->name }}</a>
@else
    {{ trans('Customer not exists') }}
@endif
