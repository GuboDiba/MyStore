<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Your Cart</title>
    <script src="{{ asset('js/removefromcart.js') }}"></script>
    <!-- <script src="https://f177-154-159-237-238.ngrok-free.app/js/removefromcart.js"></script> -->

</head>
@extends('layouts.app')

@section('title', 'Products')

@section('content')
<body>
<div class="container d-flex justify-content-center align-items-center mt-2" style="min-height: 100vh; background-color: #f9f9f9; padding: 30px; border-radius: 8px;">
    <div class="w-100" style="max-width: 900px;">
        <h1 class="mb-4 text-center text-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Your Shopping Cart</h1>

        @if(count($cart) > 0)
            <div class="row col-md-12">
                @foreach($cart as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-light rounded" style="transition: transform 0.3s ease-in-out;">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['description'] }}" class="card-img-top" style="height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                            <div class="card-body" style="padding: 20px;">
                                <h5 class="card-title" style="font-size: 1.2rem; font-weight: bold; color: #333;">{{ $item['name'] }}</h5>
                                <h5 class="card-title" style="font-size: 1.2rem; font-weight: bold; color: #333;">{{ Str::limit($item['description'], 50) }}</h5>
                                <p class="card-text text-muted" style="font-size: 1rem;">Price: Ksh {{ number_format($item['price'], 2) }}</p>
                                <p class="card-text text-muted" style="font-size: 1rem;">Quantity: 
                                    <button class="btn btn-sm btn-decrease" data-id="{{ $item['product_id'] }}">−</button>
                                    <input type="text" value="{{ $item['quantity'] }}" readonly style="width: 50px; text-align: center;">
                                    <button class="btn btn-sm btn-increase" data-id="{{ $item['product_id'] }}">+</button>
                                </p>
                                <p class="card-text" style="font-size: 1.2rem; color: #28a745;">Total: Ksh {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <h3 style="font-size: 1.5rem; color: #333;">Total: Ksh {{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)), 2) }}</h3>
                <a href="{{ route('checkout') }}" class="btn btn-primary" style="padding: 10px 20px; font-size: 1.1rem; background-color: #4caf50; border-radius: 5px; text-decoration: none; transition: background-color 0.3s;">
                    Proceed to Checkout
                </a>
                <form action="{{ url('/cart/clear') }}" method="POST">
                @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%; border-radius: 5px; background-color: #e63946; border: none; padding: 10px; font-size: 1rem; transition: background-color 0.3s;">
                        Clear Cart
                    </button>
                </form>
            </div>
        @else
            <p class="text-center" style="font-size: 1.2rem; color: #f1c40f;">Your cart is empty. Add some products to your cart!</p>
        @endif
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .btn:hover {
        background-color: #ff4c4c !important;
    }

    .btn-primary:hover {
        background-color: #45a049 !important;
    }
</style>

</body>
</html>
@endsection
