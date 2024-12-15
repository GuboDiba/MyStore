<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
</head>
<body style="margin-bottom: 70px; margin-top: 50px; margin-right: 100px; margin-left: 100px;">
    <h2 style="font-size: 28px; font-weight: bold; margin-bottom: 20px; color: #333;">Place your order</h2>

    @foreach ($cart as $item)
        <div class="cart-item" style="display: flex; align-items: center; border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 10px; background-color: #f9f9f9;">
            <img src="{{ asset($item['image']) }}" alt="{{ $item['description'] }}" class="card-img-top" style="height: 180px; object-fit: cover; border-radius: 8px; width: 180px; margin-right: 20px;">
            <div style="flex-grow: 1;">
                <p style="font-size: 20px; font-weight: bold; margin: 0; color: #333;">{{ $item['name'] }}</p>
                <p style="margin: 5px 0; color: #555; font-size: 16px;">Quantity: {{ $item['quantity'] }}</p>
                <p style="margin: 5px 0; color: #555; font-size: 16px;">Price: Ksh{{ number_format($item['price'], 2) }}</p>
                <p style="margin: 5px 0; color: #555; font-size: 16px;">Total: Ksh{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
            </div>
        </div>
    @endforeach

    <hr style="border: 0; border-top: 2px solid #e0e0e0; margin: 40px 0;">

    <h3 style="font-size: 24px; font-weight: bold; text-align: right; color: #333;">Total Price: Ksh{{ number_format($totalPrice, 2) }}</h3>

    <form action="{{ route('process-payment') }}" method="POST" style="text-align: center; margin-top: 40px;">
        @csrf
        <button type="button" id="openPopupBtn" class="btn btn-primary" style="padding: 10px 20px; font-size: 1.1rem; background-color: #4caf50; border-radius: 5px; text-decoration: none; transition: background-color 0.3s;">
            Place Order
        </button>
    </form>

   

    <!-- Popup Modal -->
    <div id="paymentPopup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
        <div class="popup-content" style="background-color: #fff; padding: 20px; border-radius: 8px; width: 300px; text-align: center;">
            <form method="POST" action="{{ route('process-payment') }}">
                @csrf
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" placeholder="Enter phone number" required style="margin: 10px 0; padding: 8px; width: 100%;">

                <input type="hidden" id="amount" name="amount" value="{{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)), 2) }}">

                <p id="amountDisplay" style="font-weight: bold;">Total: Ksh {{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)), 2) }}</p>

                <button type="submit" style="padding: 10px 20px; background-color: #28a745; border: none; color: #fff; border-radius: 5px; cursor: pointer;">Submit</button>
                <button type="button" id="closePopupBtn" style="padding: 10px 20px; background-color: #e63946; border: none; color: #fff; border-radius: 5px; cursor: pointer;">Close</button>
            </form>
        </div>
    </div>

    <script>
        // Open popup when the button is clicked
        document.getElementById('openPopupBtn').onclick = function() {
            document.getElementById('paymentPopup').style.display = 'flex'; 
        };

        // Close the popup
        document.getElementById('closePopupBtn').onclick = function() {
            document.getElementById('paymentPopup').style.display = 'none';
        };
    </script>

</body>
</html>
