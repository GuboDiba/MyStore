<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    /**
     * Add an item to the cart
     */
    public function addToCart(Request $request)
    {
        // Validate the request parameters
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the logged-in user's ID
        $userId = auth()->id();
        $cartKey = "cart:$userId"; // Redis key for the user's cart

        // Retrieve existing cart from Redis or initialize an empty array
        $cart = Redis::get($cartKey);
        $cart = $cart ? json_decode($cart, true) : [];

        // Check if the product already exists in the cart, if so update the quantity
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $request->product_id) {
                $item['quantity'] += $request->quantity;
                $found = true;
                break;
            }
        }

        // If the product was not found, add it to the cart
        if (!$found) {
            $cart[] = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ];
        }

        // Save the updated cart back to Redis
        Redis::set($cartKey, json_encode($cart));

        return response()->json(['message' => 'Item added to cart']);
    }

    /**
     * Get all items in the cart
     */
    public function getCart()
    {
        // Get the logged-in user's ID
        $userId = auth()->id();
        $cartKey = "cart:$userId"; // Redis key for the user's cart

        // Get the cart from Redis
        $cart = Redis::get($cartKey);

        // Return an empty array if no cart found
        if (!$cart) {
            return response()->json([]);
        }

        // Decode the cart and return it
        return response()->json(json_decode($cart, true));
    }

    /**
     * Remove an item from the cart
     */
    public function removeFromCart(Request $request)
    {
        // Validate the request parameters
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        // Get the logged-in user's ID
        $userId = auth()->id();
        $cartKey = "cart:$userId"; // Redis key for the user's cart

        // Get the cart from Redis
        $cart = Redis::get($cartKey);

        // Return a message if the cart is empty
        if (!$cart) {
            return response()->json(['message' => 'Cart is empty']);
        }

        // Decode the cart
        $cart = json_decode($cart, true);

        // Remove the item with the matching product ID
        foreach ($cart as $index => $item) {
            if ($item['product_id'] == $request->product_id) {
                unset($cart[$index]);
                break;
            }
        }

        // Re-index the array to avoid gaps in the keys
        $cart = array_values($cart);

        // Save the updated cart back to Redis
        Redis::set($cartKey, json_encode($cart));

        return response()->json(['message' => 'Item removed from cart']);
    }

    /**
     * Update the quantity of an item in the cart
     */
    public function updateCartItem(Request $request)
    {
        // Validate the request parameters
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the logged-in user's ID
        $userId = auth()->id();
        $cartKey = "cart:$userId"; // Redis key for the user's cart

        // Get the cart from Redis
        $cart = Redis::get($cartKey);

        // Return a message if the cart is empty
        if (!$cart) {
            return response()->json(['message' => 'Cart is empty']);
        }

        // Decode the cart
        $cart = json_decode($cart, true);

        // Find the product in the cart and update the quantity
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $request->product_id) {
                $item['quantity'] = $request->quantity;
                $found = true;
                break;
            }
        }

        // Return a message if the product is not found
        if (!$found) {
            return response()->json(['message' => 'Product not found in cart']);
        }

        // Save the updated cart back to Redis
        Redis::set($cartKey, json_encode($cart));

        return response()->json(['message' => 'Item quantity updated']);
    }

    /**
     * Clear the entire cart
     */
    public function clearCart()
    {
        // Get the logged-in user's ID
        $userId = auth()->id();
        $cartKey = "cart:$userId"; // Redis key for the user's cart

        // Delete the cart from Redis
        Redis::del($cartKey);

        return response()->json(['message' => 'Cart cleared']);
    }
}
