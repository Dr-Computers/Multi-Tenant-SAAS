<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'plan_name',
        'plan_id',
        'price',
        'subtotal',
        'tax',
        'discount',
        'coupon_code',
        'price_currency',
        'txn_id',
        'payment_status',
        'payment_type',
        'receipt',
        'company_id',
        'is_refund',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','company_id');
    }

    public static function total_orders()
    {
        return Order::count();
    }

    public static function total_orders_price()
    {
        return Order::sum('price');
    }

    public function total_coupon_used()
    {
        return $this->hasOne('App\Models\UserCoupon', 'order', 'order_id');
    }
}
