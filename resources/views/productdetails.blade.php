<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product Details</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('js/product-details.js') }}"></script>
    <!-- <script src="https://f177-154-159-237-238.ngrok-free.app/js/product-details.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>
<body>
@extends('layouts.app')

@section('title', 'Products')

@section('content')

    <!-- Product Details Section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <!-- Product Image -->
                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}" class="card-img-top" alt="{{ $product->name }}">
            </div>
            <div class="col-md-6" style="margin-top: 150px; ">
                <!-- Product Details -->
                <h1 class="display-5">{{ $product->name }}</h1>
                <p class="lead">{{ $product->description }}</p>
                <h3 class="text-success">ksh {{ $product->price }}</h3>
                <!-- Add to Cart Button -->
                <button class="btn btn-primary btn-lg mt-3 add-to-cart-btn" 
                    data-product-id="{{ $product->id }}"
                    data-product-name="{{ $product->name }}"
                    data-product-image="{{ $product->image_url }}"
                    data-product-description="{{ $product->description }}"
                    data-product-price="{{ $product->price }}">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>

</body>
@endsection

</html>
