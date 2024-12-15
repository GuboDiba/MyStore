
// Attach event listeners to each "Add to Cart" button
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners after the DOM is ready
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Collecting data from the button attributes
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productImage = this.getAttribute('data-product-image');
            const productDescription = this.getAttribute('data-product-description');
            const productPrice = this.getAttribute('data-product-price');
            const quantity = 1;  // Default quantity to 1

            // Send to the server via fetch
            fetch('/cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    name:productName,
                    image: productImage,
                    description: productDescription,
                    price: productPrice
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data);
                if (data.success) {
                    alert('Item successfully added to the cart');
                    updateCartIcon();
                } else {
                    alert(`Failed to add item to cart: ${data.message || 'Unknown error occurred'}`);
                }
            })
            .catch(error => {
                console.error('Error during the Add to Cart process:', error.message);
                alert(`An unexpected error occurred: ${error.message}. Please try again later.`);
            });
        });
    });
});

function updateCartIcon() {
fetch('/cart/count') 
    .then(response => {
        if (!response.ok) {
            throw new Error(`Failed to fetch cart count. Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Cart count:', data.cart_count);
        
        const cartCountElement = document.getElementById('cart-count');
        
        if (data.cart_count > 0) {
            cartCountElement.textContent = data.cart_count;
            cartCountElement.style.display = 'flex';
        } else {
            cartCountElement.style.display = 'flex'; 
        }
    })
    .catch(error => {
        console.error('Error updating cart icon:', error);
    });
}

updateCartIcon();

document.querySelectorAll('.add-to-cart-btn').forEach(button => {
button.addEventListener('click', function() {
    updateCartIcon();
});
});



