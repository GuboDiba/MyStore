<!DOCTYPE html>
<html lang="en">

@extends('layouts.app')

@section('title', 'Products')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="{{ asset('js/product-details.js') }}"></script>

</head>
<body style="margin-bottom: 70px; margin-top: 50px; margin-right: 50px; margin-left: 50px;"> 
  <!-- Fixed Header with Products and Search -->
  <div style="position: fixed; top: 80px; left: 10px; right: 10px; width: 100%; background-color: rgba(255, 255, 255, 0.8); z-index: 1000; padding: 15px 0; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">

            <h1 style="font-size: 28px; font-weight: bold; color: #333; margin: 0;">Our Products</h1>

            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="text" id="search-input" placeholder="I'm looking for..." 
                    style="width: 300px; padding: 10px; font-size: 16px; border: 2px solid #ddd; border-radius: 25px; outline: none; transition: border-color 0.3s ease;" />
                <div onclick="searchFunction()" style="background-color: #007bff; padding: 10px; border-radius: 50%; cursor: pointer; transition: background-color 0.3s ease;">
                    <img src="https://img.kilimall.com/c/h5/imgs/search-icon@2x.png?x-image-process=image/format,webp/resize,w_80" alt="Search Icon" style="width: 20px; height: 20px;" />
                </div>
            </div>

            
        </div>
    </div>

    <!-- JavaScript to handle the search function -->
    <script>
        function searchFunction() {
            var query = document.getElementById('search-input').value;
            if(query) {
                alert("Searching for: " + query);
                // Add your search logic here (e.g., redirect or API call)
            } else {
                alert("Please enter a search term.");
            }
        }
    </script>


</div>

            <div class="row">
            @foreach ($products as $product)
                <div class="col-md-2">
                    <div class="card mb-4 shadow-sm">
                    <a href="{{ route('product.show', $product->id) }}" style="position: relative; display: inline-block;">
                            <!-- Product Image -->
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}" class="card-img-top" alt="{{ $product->name }}" style="width: 100%; height: auto;">

                            <!-- Add to Cart Button -->
                       
                      </a>
                    
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}
                    
                            </h5>
                            <p class="card-text">{{ Str::limit($product->description, 50) }}</p>
                            <p class="card-text"><strong>Price:</strong> Ksh {{ $product->price }}</p>
                           
                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary">More Details</a>
                            
                            </div>
                    </div>
                </div>
            @endforeach
            </div>
    </div>
   

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection