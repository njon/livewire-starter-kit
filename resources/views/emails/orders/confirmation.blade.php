{{-- resources/views/emails/orders/confirmation.blade.php --}}
@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    # Order Confirmation #{{ $order->reference }}

    Thank you for your order! Here are your order details:

    @component('mail::table')
        | Product       | Quantity | Price    |
        | ------------- | -------- | -------- |
        @foreach($order->lines as $line)
        | {{ $line->description }} | {{ $line->quantity }} | {{ $line->subTotal->formatted }} |
        @endforeach
    @endcomponent

    **Total:** {{ $order->total->formatted }}

    @component('mail::button', ['url' => route('order.view', $order->reference), 'color' => 'success'])
        View Your Order
    @endcomponent

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent