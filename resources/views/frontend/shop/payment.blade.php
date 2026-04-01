@extends('frontend.shop.layouts.app')
@section('title', __('messages.products_list'))
@section('content')
        <h1>Payment</h1>

        <form action="confirmation.html" method="post" class="payment-form">
            <h2>Payment Details</h2>
            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="card-number" required>

            <label for="exp-date">Expiration Date</label>
            <input type="text" id="exp-date" name="exp-date" required>

            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" required>

            <button type="submit" class="payment-btn">Complete Payment</button>
        </form>
@endsection
