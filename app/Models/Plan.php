<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration',
        'max_users',
        'max_customers',
        'max_venders',
        'storage_limit',
        'description',
        'image',
        'enable_chatgpt',
        'trial',
        'trial_days',
        'is_disable',
        'business_type',

    ];

    public static $arrDuration = [
        'lifetime' => 'Lifetime',
        'month' => 'Per Month',
        'year' => 'Per Year'
    ];

    public function status()
    {
        return [
            __('Lifetime'),
            __('Per Month'),
            __('Per Year')
        ];
    }

    public static function total_plan()
    {
        return Plan::count();
    }

    public static function most_purchese_plan()
    {
        $free_plan = Plan::where('price', '<=', 0)->first()->id ?? 0;

        return User:: select(DB::raw('count(*) as total'))->where('type', '=', 'company')->where('plan', '!=', $free_plan)->groupBy('plan')->first();
    }

    public function module_section(){
        return $this->hasMany('App\Models\PlanModuleSection','plan_id','id');
    }

    public function businessType(){
        return $this->hasOne('App\Models\BusinessType','id','business_type');
    }

}
