<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'stock'
    ];

    /**
     * Get the carts that contain the product.
     */
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity');
    }

    /**
     * Get the orders that include the product.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }

    // get items as orderd
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
