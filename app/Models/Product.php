<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $price
 * @property string|null $image_url
 * @property int $stock
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Cart[] $carts
 * @property Collection|OrderItem[] $order_items
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';

	protected $casts = [
		'price' => 'int',
		'stock' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'price',
		'image_url',
		'stock'
	];

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function orders()
	{
		return $this->belongsToMany(Order::class)
					->withPivot('id', 'quantity', 'price')
					->withTimestamps();
	}
}
