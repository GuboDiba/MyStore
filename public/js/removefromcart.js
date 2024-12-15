
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
        button.addEventListener('click', function () {
            fetch('/cart/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cart cleared successfully!');
                    updateCartIcon(); 
                } else {
                    alert(`Failed to clear cart: ${data.message || 'Unknown error occurred'}`);
                }
            })
            .catch(error => {
                console.error('Error clearing the cart:', error);
                alert(`An unexpected error occurred: ${error.message}. Please try again later.`);
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

    // Initial cart count update
    updateCartIcon();
});


/////////////////
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-increase').forEach(button => {
        button.addEventListener('click', function () {
            console.log('Increase button clicked!');
            const productId = this.getAttribute('data-id');
            console.log('Product ID:', productId);

            updateCart(productId, 'increase');
        });
    });

    document.querySelectorAll('.btn-decrease').forEach(button => {
        button.addEventListener('click', function () {
            console.log('Decrease button clicked!');
            const productId = this.getAttribute('data-id');

            updateCart(productId, 'decrease');
            
        });
    });
});

const updateCart = (productId, action) => {
    console.log(`Updating cart for Product ID: ${productId}, Action: ${action}`);
    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Server Response:', data);
        if (data.success) {
            updateQuantityInput(productId, data.quantity);
            updateTotalPrice(productId, data.total);
            updateCartTotal(data.cartTotal);
        } else {
            console.error('Error updating cart:', data.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });
};

function updateQuantityInput(productId, quantity) {
    const quantityInput = document.querySelector(`input[data-id="${productId}"]`);
    if (quantityInput) {
        console.log('Updating quantity input:', quantityInput);
        quantityInput.value = quantity;
    } else {
        console.error(`Quantity input for Product ID "${productId}" not found.`);
    }
}


function updateTotalPrice(productId, totalPrice) {
    const quantityInput = document.querySelector(`input[data-id="${productId}"]`);
    if (!quantityInput) {
        console.error(`Quantity input for Product ID "${productId}" not found.`);
        return;
    }

    const totalPriceElement = quantityInput.closest('.card-body')?.querySelector('.card-text:last-child');
    if (totalPriceElement) {
        totalPriceElement.textContent = `Total: Ksh ${totalPrice.toLocaleString()}`;
    } else {
        console.error('Total price element not found.');
    }
}

function updateCartTotal(cartTotal) {
    const cartTotalElement = document.querySelector('h3');
    if (cartTotalElement) {
        cartTotalElement.textContent = `Total: Ksh ${cartTotal.toLocaleString()}`;
    } else {
        console.error('Cart total element not found.');
    }
}
