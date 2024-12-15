<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $amount_paid
 * @property string $payment_reference
 * @property int $payer_phone
 * @property string $status
 * @property Carbon $paid_at
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payment';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'amount_paid' => 'int',
		'payer_phone' => 'int',
		'paid_at' => 'datetime'
	];

	protected $fillable = [
		'amount_paid',
		'payment_reference',
		'payer_phone',
		'status',
		'paid_at'
	];
}
