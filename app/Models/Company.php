<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{


    public static function planOrderStore($plan, $company_id)
    {
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $order = Order::create(
            [
                'order_id' => $orderID,
                'name' => null,
                'card_number' => null,
                'card_exp_month' => null,
                'card_exp_year' => null,
                'plan_name' => $plan->name,
                'plan_id' => $plan->id,
                'price' => $plan->price,
                'price_currency' => 'AED',
                'txn_id' => '',
                'payment_status' => 'succeeded',
                'receipt' => null,
                'user_id' => $company_id,
            ]
        );

        foreach ($plan->module_section ?? [] as $section) {
            $sections  = new CompanySubscription();
            $sections->order_id         = $order->id;
            $sections->secrion_id       = $section->section_id;
            $sections->section_validity = $plan->duration;
            $sections->save();
        }

    }
}
