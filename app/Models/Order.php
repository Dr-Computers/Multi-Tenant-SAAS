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
        return $this->hasOne(User::class, 'id', 'company_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'user_id', 'company_id');
    }

    public function companySubscriptions(){
        return $this->hasMany(CompanySubscription::class, 'order_id', 'id');
    }

    public function plan()
    {
        return $this->hasMany(Plan::class, 'id', 'plan_id');
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

     public static function pendingInvoices()
     {
         return Order::where('payment_status', 'pending')->count();
     }
 
     public static function dueInvoices()
     {
         return Order::where('payment_status', 'pending')
             ->whereRaw('DATE_ADD(created_at, INTERVAL 7 DAY) < NOW()')
             ->count();
     }


    public static function totalAmount()
    {
        return Order::sum('price');
    }

    public static function pendingAmount()
    {
        return Order::where('payment_status', 'pending')->sum('price');
    }

    public static function dueAmount()
    {
        return Order::where('payment_status', 'pending')
            ->whereRaw('DATE_ADD(created_at, INTERVAL 7 DAY) < NOW()')
            ->sum('price');
    }

    
}
