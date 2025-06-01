<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Company extends Model
{

    protected $with = ['activeSubscription'];


    public function planOrders()
    {
        return $this->hasMany(SubscriptionOrder::class, 'company_id', 'user_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(SubscriptionOrder::class, 'company_id', 'user_id')
            ->where('status', 1);
    }


    public static function generateOrderId($orderID)
    {
        $orderIdExist = Order::where('order_id', $orderID)->exists();

        if ($orderIdExist) {
            // Try again recursively with a new ID
            $newID = strtoupper(uniqid());
            return self::generateOrderId($newID);
        }

        return $orderID;
    }



    public static function planOrderStore($plan, $company_id)
    {

        // $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $orderID = strtoupper(uniqid());

        $orderID = self::generateOrderId($orderID);


        $order = Order::create(
            [
                'order_id' => $orderID,
                'plan_name' => $plan->name,
                'plan_id' => $plan->id,
                'price' => $plan->price,
                'subtotal' => $plan->price,
                'tax'      => 0,
                'discount'  => 0,
                'coupon_code' => null,
                'price_currency' => 'AED',
                'txn_id' => '',
                'payment_status' => 'pending',
                'receipt' => null,
                'company_id' => $company_id,
            ]
        );

        foreach ($plan->module_section ?? [] as $section) {
            $subSection                 = CompanySubscription::where('section_id', $section->section_id)->where('company_id', $company_id)->first();
            if (!$subSection) {
                $subSection                   = new CompanySubscription();
                $subSection->section_id       = $section->section_id;
                $subSection->status           = 1;
            }

            // Set end date based on plan type
            if ($plan->duration == 'lifetime') {
                $subSection->section_validity = Carbon::now()->addYears(30);
            } elseif ($plan->duration == 'yearly') {
                $subSection->section_validity = Carbon::now()->addYear();
            } elseif ($plan->duration == 'monthly') {
                $subSection->section_validity = Carbon::now()->addMonth();
            } elseif ($plan->trial_days > 0) {
                $subSection->section_validity = Carbon::now()->addDays($plan->trial_days);
            } else {
                $subSection->section_validity = Carbon::now();
            }
            $subSection->plan_id           = $plan->id;
            $subSection->order_id          = $order->id;
            $subSection->company_id        = $company_id;
            $subSection->save();
        }


        // First, deactivate old subscriptions
        SubscriptionOrder::where('company_id', $company_id)
            ->update(['status' => 0]);

        // Now, create a new subscription
        $subscriptionOrder = new SubscriptionOrder();
        $subscriptionOrder->company_id = $company_id;
        $subscriptionOrder->order_id   = $order->id;
        $subscriptionOrder->plan_id = $plan->id;
        $subscriptionOrder->start_of_date = now();

        // Set end date based on plan type
        if ($plan->duration == 'lifetime') {
            $subscriptionOrder->end_of_date = Carbon::now()->addYears(30);
        } elseif ($plan->duration == 'year') {
            $subscriptionOrder->end_of_date = Carbon::now()->addYear();
        } elseif ($plan->duration == 'month') {
            $subscriptionOrder->end_of_date = Carbon::now()->addMonth();
        } elseif ($plan->trial_days > 0) {
            $subscriptionOrder->end_of_date = Carbon::now()->addDays($plan->trial_days);
        } else {
            $subscriptionOrder->end_of_date = Carbon::now();
        }

        // Copy limits from the plan
        $subscriptionOrder->max_users = $plan->max_users ?? 0;
        $subscriptionOrder->max_tenants = $plan->max_venders ?? 0;
        $subscriptionOrder->max_owners = $plan->max_customers ?? 0;
        $subscriptionOrder->max_storage_capacity = $plan->storage_limit ?? 0;
        $subscriptionOrder->status = 1; // Active
        $subscriptionOrder->save();

        if ($plan) {
            foreach ($plan->module_section ?? [] as $section) {
                foreach ($section->section->permissions ?? [] as $permission) {
                    $companyPermission = CompanyPermission::where('permission_id', $permission->id)->where('company_id', $company_id)->first();
                    if (!$companyPermission) {
                        $companyPermission                  = new CompanyPermission();
                        $companyPermission->permission_id   = $permission->id;
                        $companyPermission->company_id      = $company_id;
                        $companyPermission->save();
                    }
                }
            }
        }
    }


    public static function sectionOrderStore($features = [], $company_id, $tax = 0, $subtotal = 0, $discount = 0, $coupon_code = 0, $grandtotal = 0)
    {


        $orderID = strtoupper(uniqid());
        $orderID = self::generateOrderId($orderID);

        $order = Order::create(
            [
                'order_id'      => $orderID,
                'company_id'    => $company_id,
                'plan_name'     => 'Addon Features',
                'plan_id'       => 0,
                'subtotal'      => $subtotal,
                'tax'           => $tax,
                'discount'      => $discount,
                'coupon_code'   => $coupon_code,
                'price'         => $grandtotal,
                'price_currency' => 'AED',
                'txn_id'        => '',
                'payment_status' => 'pending',
                'receipt'       => null,
                'user_id'       => $company_id,
            ]
        );

        foreach ($features ?? [] as $section) {
            $subSection = CompanySubscription::where('section_id', $section)
                ->where('company_id', $company_id)
                // ->whereNull('plan_id')
                ->first();

            if (!$subSection) {
                $subSection = new CompanySubscription();
                $subSection->order_id         = $order->id;
                $subSection->plan_id          = null;
                $subSection->company_id       = $company_id;
                $subSection->section_id       = $section;
                $subSection->status           = 1;
                $subSection->section_validity = Carbon::now()->addMonth();
            } else {
                // Ensure section_validity is parsed as Carbon
                $validity = Carbon::parse($subSection->section_validity);
                if ($subSection->status == 1 && $validity->isFuture()) {
                    // If active and still valid, extend validity
                    $subSection->section_validity = $validity->addMonth();
                } else {
                    // Expired or inactive, set new validity
                    $subSection->section_validity = Carbon::now()->addMonth();
                }
            }
            $subSection->save();

            if ($subSection) {
                foreach ($subSection->section->permissions ?? [] as $permission) {
                    $companyPermission = CompanyPermission::where('permission_id', $permission->id)->where('company_id', $company_id)->first();
                    if (!$companyPermission) {
                        $companyPermission                  = new CompanyPermission();
                        $companyPermission->permission_id   = $permission->id;
                        $companyPermission->company_id      = $company_id;
                        $companyPermission->save();
                    }
                }
            }
        }



        // Set end date based on plan type
        // if ($section->duration == 'lifetime') {
        //     $sections->section_validity = Carbon::now()->addYears(30);
        // } elseif ($section->duration == 'year') {
        //     $sections->section_validity = Carbon::now()->addYear();
        // } elseif ($section->duration == 'month') {

        // } else {
        //     $sections->section_validity = Carbon::now();
        // }

    }

    public static function createCompanyRoles($company_id)
    {
        // Create company role
        $role_c             = new Role();
        $role_c->name       = 'company-' . $company_id;
        $role_c->guard_name = 'web';
        $role_c->created_by = $company_id;
        $role_c->is_editable = 1;
        $role_c->is_deletable = 0;
        $role_c->save();

        $companyPermissions = Permission::where('is_company', '1')->get();
        $role_c->givePermissionTo($companyPermissions);

        // Create tenant role
        $role_t       = new Role();
        $role_t->name = 'tenant-' . $company_id;
        $role_t->guard_name = 'web';
        $role_c->created_by = $company_id;
        $role_c->is_editable = 1;
        $role_c->is_deletable = 0;
        $role_t->save();

        $tenantPermissions = Permission::where('is_tenant', '1')->get();
        $role_t->givePermissionTo($tenantPermissions);

        // Create owner role
        $role_o       = new Role();
        $role_o->name = 'owner-' . $company_id;
        $role_o->guard_name = 'web';
        $role_c->created_by = $company_id;
        $role_c->is_editable = 1;
        $role_c->is_deletable = 0;
        $role_o->save();

        $ownerPermissions = Permission::where('is_owner', '1')->get();
        $role_o->givePermissionTo($ownerPermissions);


        // Create maintainer role
        $role_o       = new Role();
        $role_o->name = 'maintainer-' . $company_id;
        $role_o->guard_name = 'web';
        $role_c->created_by = $company_id;
        $role_c->is_editable = 1;
        $role_c->is_deletable = 0;
        $role_o->save();

        $maintainerPermissions = Permission::where('is_maintainer', '1')->get();
        $role_o->givePermissionTo($maintainerPermissions);
    }
}
