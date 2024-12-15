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
        $request->validate([
            'product_id' => 'required|integer',
            'name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|string',         
            'description' => 'required|string',  
            'price' => 'required|numeric',        
        ]);

        $userId = auth()->id();
        $cartKey = "cart:$userId"; 

        $cart = Redis::get($cartKey);
        $cart = $cart ? json_decode($cart, true) : [];

        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $request->product_id) {
                $item['quantity'] += $request->quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'name'=> $request->name,
                'image' => $request->image,               
                'description' => $request->description,  
                'price' => $request->price,       
            ];
        }

        Redis::set($cartKey, json_encode($cart));

        return response()->json([
            'success' => true,  
            'message' => 'Item added to cart',
        ]);   
    }

    /**
     * Get all items in the cart
     */
    public function getCartCount()
{
    // Get the logged-in user's ID
    $userId = auth()->id();
    $cartKey = "cart:$userId";

    $cart = Redis::get($cartKey);
    $cart = $cart ? json_decode($cart, true) : [];

    $cartCount = array_sum(array_column($cart, 'quantity'));  

    return response()->json([
        'cart_count' => $cartCount,
    ]);
}

    public function getCart()
    {
        $userId = auth()->id();
        $cartKey = "cart:$userId"; 

        $cart = Redis::get($cartKey);

        if (!$cart) {
            return response()->json([]);
        }
        $cart = json_decode($cart, true);

        // Decode the cart and return it
        // return response()->json(json_decode($cart, true));
        return view('cart', compact('cart'));

    }

  

    /**
     * Remove an item from the cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $userId = auth()->id();
        $cartKey = "cart:$userId"; 

        $cart = Redis::get($cartKey);

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty']);
        }

        // Decode the cart
        $cart = json_decode($cart, true);

        foreach ($cart as $index => $item) {
            if ($item['product_id'] == $request->product_id) {
                unset($cart[$index]);
                break;
            }
        }

        $cart = array_values($cart);

        // Save the updated cart back to Redis
        Redis::set($cartKey, json_encode($cart));

        return response()->json(['message' => 'Item removed from cart']);
    }


    // CartController.php
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'action' => 'required|in:increase,decrease',
        ]);
    
        $productId = (int)$request->product_id; 
        $action = $request->action;
    
        $cartKey = "cart:" . auth()->id();
        $cart = Redis::get($cartKey);
        $cart = $cart ? json_decode($cart, true) : [];
    
        \Log::info('Cart contents before update:', $cart);
    
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $found = true;
                
                if ($action === 'increase') {
                    $item['quantity'] += 1;
                } elseif ($action === 'decrease' && $item['quantity'] > 1) {
                    $item['quantity'] -= 1;
                } else {
                    return response()->json(['success' => false, 'message' => 'Quantity cannot be less than 1']);
                }
    
                break;
            }
        }
    
        if ($found) {
            Redis::set($cartKey, json_encode($cart));
    
            $itemTotal = $item['price'] * $item['quantity'];
            $cartTotal = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));
    
            \Log::info('Cart contents after update:', $cart);
    
            return response()->json([
                'success' => true,
                'quantity' => $item['quantity'],
                'total' => $itemTotal,
                'cartTotal' => $cartTotal,
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }
    
    
    
    

    /**
     * Update the quantity of an item in the cart
     */
    public function updateCartItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = auth()->id();
        $cartKey = "cart:$userId"; 
        $cart = Redis::get($cartKey);

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty']);
        }

        $cart = json_decode($cart, true);

        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $request->product_id) {
                $item['quantity'] = $request->quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json(['message' => 'Product not found in cart']);
        }

        Redis::set($cartKey, json_encode($cart));

        return response()->json(['message' => 'Item quantity updated']);
    }

    /**
     * Clear the entire cart
     */
    public function clearCart()
    {
        $userId = auth()->id();
        $cartKey = "cart:$userId";
        Redis::del($cartKey);

        return response()->json(['message' => 'Cart cleared']);
    }


    public function cart()
{
    $cart = session('cart', []);
    
    $order = Order::latest()->first(); 
    return view('cart', compact('cart', 'order'));
}


public function checkout()
{
    $cart = Redis::get('cart:' . auth()->id());
    $cart = $cart ? json_decode($cart, true) : [];
    
    $totalPrice = 0;
    foreach ($cart as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
    
    return view('checkout', compact('cart', 'totalPrice'));
}

}
