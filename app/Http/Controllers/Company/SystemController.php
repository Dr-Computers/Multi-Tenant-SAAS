<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyPermission;
use App\Models\CompanySubscription;
use App\Models\EstimateSettings;
use App\Models\EstimateTemplate;
use App\Models\InvoiceSetting;
use App\Models\InvoiceTemplate;
use App\Models\LetterPadSettings;
use App\Models\LetterPadTemplate;
use App\Models\Mail\EmailTest;
use App\Models\Mail\testMail;
use App\Models\Permission as ModelsPermission;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Artisan;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Traits\ActivityLogger;
use Exception;

class SystemController extends Controller
{

    use ActivityLogger;
    public function index()
    {
        // if (\Auth::user()->can('manage system settings')) {
        $settings              = Utility::settings();
        $company_payment_setting = Utility::getAdminPaymentSetting();
        $invoiceTemplates      = InvoiceTemplate::where('type', 'company')->get();
        $InvoiceSettings = InvoiceSetting::where('user_id', Auth::user()->creatorId())->first();

        $letterPadTemplates      = LetterPadTemplate::get();
        $letterPadSettings       = LetterPadSettings::where('user_id', Auth::user()->creatorId())->first();


        $estimateTemplates      = EstimateTemplate::get();
        $estimateSettings       = EstimateSettings::where('user_id', Auth::user()->creatorId())->first();

        return view('company.settings.index', compact('settings', 'company_payment_setting', 'invoiceTemplates', 'InvoiceSettings', 'letterPadTemplates', 'letterPadSettings', 'estimateTemplates', 'estimateSettings'));
        // } else {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('brand settings')) {
            if ($request->logo_dark) {
                $request->validate(
                    [
                        'logo_dark' => 'image',
                    ]
                );

                $logoName = 'logo-dark.png';
                $dir = 'uploads/logo/';

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];

                $path = Utility::upload_file($request, 'logo_dark', $logoName, $dir, $validation);

                if ($path['flag'] == 1) {
                    $logo_dark = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->logo_light) {
                $request->validate(
                    [
                        'logo_light' => 'image',
                    ]
                );
                $lightlogoName = 'logo-light.png';


                $dir = 'uploads/logo/';

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];

                $path = Utility::upload_file($request, 'logo_light', $lightlogoName, $dir, $validation);

                if ($path['flag'] == 1) {
                    $logo_light = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->favicon) {
                $request->validate(
                    [
                        'favicon' => 'image',
                    ]
                );
                $favicon = 'favicon.png';

                $dir = 'uploads/logo/';

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];

                $path = Utility::upload_file($request, 'favicon', $favicon, $dir, $validation);

                if ($path['flag'] == 1) {
                    $favicon = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->landing_logo) {
                $request->validate(
                    [
                        'landing_logo' => 'image',
                    ]
                );
                $landingLogoName = 'landing_logo.png';
                $path            = $request->file('landing_logo')->storeAs('uploads/logo/', $landingLogoName);
            }



            $arrEnv = [
                'SITE_RTL' => !isset($request->SITE_RTL) ? 'off' : 'on',
            ];
            Utility::setEnvironmentValue($arrEnv);


            $settings = Utility::settings();
            if (!empty($request->title_text) || !empty($request->email_verification) || !empty($request->footer_text) || !empty($request->default_language) || isset($request->display_landing_page) || isset($request->enable_signup) || isset($request->color) || isset($request->cust_theme_bg) || isset($request->cust_darklayout)) {
                $post = $request->all();
                if (!isset($request->display_landing_page)) {
                    $post['display_landing_page'] = 'off';
                }

                if (!isset($request->enable_signup)) {
                    $post['enable_signup'] = 'off';
                }
                if (!isset($request->email_verification)) {
                    $post['email_verification'] = 'off';
                }

                if (!isset($request->cust_theme_bg)) {
                    $cust_theme_bg         = (isset($request->cust_theme_bg)) ? 'on' : 'off';
                    $post['cust_theme_bg'] = $cust_theme_bg;
                }
                if (!isset($request->cust_darklayout)) {

                    $cust_darklayout         = isset($request->cust_darklayout) ? 'on' : 'off';
                    $post['cust_darklayout'] = $cust_darklayout;
                }

                if (!isset($request->SITE_RTL)) {
                    $SITE_RTL         = isset($request->SITE_RTL) ? 'on' : 'off';
                    $post['SITE_RTL'] = $SITE_RTL;
                }

                if (isset($request->color) && $request->color_flag == 'false') {
                    $post['color'] = $request->color;
                } else {
                    $post['color'] = $request->custom_color;
                }

                unset($post['_token'], $post['logo_dark'], $post['logo_light'], $post['favicon']);
                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings))) {
                        DB::insert(
                            'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                Auth::user()->creatorId(),
                            ]
                        );
                    }
                }
            }

            $this->logActivity(
                'Brand Settings Updated',
                'Brand Settings Updated',
                route('company.settings.index'),
                'Brand Settings Updated successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );

            return redirect()->back()->with('success', 'Setting successfully updated.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function saveCompanySettings(Request $request)
    {
        if (\Auth::user()->can('brand settings')) {
            DB::beginTransaction();
            try {

                $user = Auth::user();
                $request->validate(
                    [
                        'company_name' => 'required|string|max:255',
                    ]
                );
                $post = $request->all();
                unset($post['_token']);

                if (!isset($post['tax_number'])) {
                    $post['tax_number'] = 'off';
                }

                // $settings = Utility::settings();
                // foreach ($post as $key => $data) {
                //     if (in_array($key, array_keys($settings))) {
                //         DB::insert(
                //             'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                //             [
                //                 $data,
                //                 $key,
                //                 Auth::user()->creatorId(),
                //             ]
                //         );
                //     }
                // }

                $company_id              = Auth::user()->creatorId();
                $company                 = Company::where('user_id', $company_id)->first();
                $company->bussiness_name = $request->company_name;
                $company->address        = $request->company_address;
                $company->city           = $request->company_city;
                $company->country        = $request->company_country;
                $company->postalcode     = $request->company_zipcode;
                $company->reg_no         = $request->registration_number;
                if ($request->has('tax_number')) {
                    $company->vat            = $request->vat_number;
                } else {
                    $company->vat            = NULL;
                }
                if ($request->logo_1) {

                    $request->validate(
                        [
                            'logo_1' => 'image',
                        ]
                    );


                    $logoName     = $user->id . '-logo-1.png';

                    $dir = 'uploads/logo/';

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $file_path = $request->logo_1;
                    $image_size = $request->file('logo_1')->getSize();

                    // $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                    // if ($result == 1) {
                    // Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                    $path = Utility::upload_file($request, 'logo_1', $logoName, $dir, $validation);

                    if ($path['flag'] == 1) {
                        $logo_1 = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    // } else {
                    //     return redirect()->back()->with('error', $result);
                    // }

                    // $path         = $request->file('logo_1')->storeAs('uploads/logo/', $logoName);
                    $logoName = !empty($request->logo_1) ? $logoName : 'logo-1.png';
                    $company->logo_1  = $logoName;
                }
                if ($request->logo_2) {

                    $request->validate(
                        [
                            'logo_2' => 'image',
                        ]
                    );


                    $logoName2     = $user->id . '-logo-2.png';

                    $dir = 'uploads/logo/';

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $file_path = $request->logo_2;
                    $image_size = $request->file('logo_2')->getSize();

                    // $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                    // if ($result == 1) {
                    // Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                    $path = Utility::upload_file($request, 'logo_2', $logoName2, $dir, $validation);

                    if ($path['flag'] == 1) {
                        $logo_2 = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    // } else {
                    //     return redirect()->back()->with('error', $result);
                    // }

                    // $path         = $request->file('logo_2')->storeAs('uploads/logo/', $logoName);
                    $logoName2 = !empty($request->logo_2) ? $logoName2 : 'logo-2.png';
                    $company->logo_2  = $logoName2;
                }
                if ($request->company_seal) {

                    $request->validate(
                        [
                            'company_seal' => 'image',
                        ]
                    );

                    $sealName2     = $user->id . '-seal-2.png';

                    $dir = 'uploads/logo/';

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $file_path = $request->company_seal;
                    $image_size = $request->file('company_seal')->getSize();

                    // $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                    // if ($result == 1) {
                    // Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                    $path = Utility::upload_file($request, 'company_seal', $sealName2, $dir, $validation);

                    if ($path['flag'] == 1) {
                        $company_seal = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    // } else {
                    //     return redirect()->back()->with('error', $result);
                    // }

                    // $path         = $request->file('company_seal')->storeAs('uploads/logo/', $logoName);
                    $sealName2 = !empty($request->company_seal) ? $sealName2 : 'logo-2.png';
                    $company->seal  = $sealName2;
                }
                if ($request->company_signature) {

                    $request->validate(
                        [
                            'company_signature' => 'image',
                        ]
                    );

                    $signature     = $user->id . '-signature-2.png';

                    $dir = 'uploads/logo/';

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $file_path = $request->company_signature;
                    $image_size = $request->file('company_signature')->getSize();

                    // $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                    // if ($result == 1) {
                    // Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                    $path = Utility::upload_file($request, 'company_signature', $signature, $dir, $validation);

                    if ($path['flag'] == 1) {
                        $sealName2 = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    // } else {
                    //     return redirect()->back()->with('error', $result);
                    // }

                    // $path         = $request->file('company_signature')->storeAs('uploads/logo/', $logoName);
                    $signature = !empty($request->company_signature) ? $signature : 'logo-2.png';
                    $company->signature  = $signature;
                }
                $company->save();

                $this->logActivity(
                    'Company Settings Updated',
                    'Company Settings Updated',
                    route('company.settings.index'),
                    'Company Settings Updated successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                DB::commit();
                return redirect()->back()->with('success', __('Setting successfully updated.'));
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function saveSystemSettings(Request $request)
    {
        if (\Auth::user()->can('system settings')) {

            DB::beginTransaction();
            try {
                $user = Auth::user();
                $request->validate(
                    [
                        'site_currency' => 'required',
                    ]
                );
                $post = $request->all();
                unset($post['_token']);

                if (!isset($post['shipping_display'])) {
                    $post['shipping_display'] = 'off';
                }

                $settings = Utility::settings();
                $settings['footer_notes'] = $request->input('footer_notes');

                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings))) {
                        DB::insert(
                            'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                Auth::user()->creatorId(),
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                            ]
                        );
                    }
                }
                DB::commit();

                $this->logActivity(
                    'Company Settings Updated',
                    'Company Settings Updated',
                    route('company.settings.index'),
                    'Company Settings Updated successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                return redirect()->back()->with('success', __('Setting successfully updated.'));
            } catch (Exception $e) {
                DB::rollBack();
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function saveBusinessSettings(Request $request)
    {

        if (\Auth::user()->can('brand settings')) {
            DB::beginTransaction();
            try {
                $user = Auth::user();

                if ($request->company_logo_dark) {
                    $request->validate(
                        [
                            'company_logo_dark' => 'image',
                        ]
                    );
                    $logoName     = $user->id . '-logo-dark.png';
                    $dir = 'uploads/logo/';
                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $file_path = $request->company_logo_dark;
                    $image_size = $request->file('company_logo_dark')->getSize();
                    // $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);
                    // if ($result == 1) {
                    // Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                    $path = Utility::upload_file($request, 'company_logo_dark', $logoName, $dir, $validation);
                    if ($path['flag'] == 1) {
                        $company_logo_dark = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    // } else {
                    //     return redirect()->back()->with('error', $result);
                    // }

                    // $path         = $request->file('company_logo_dark')->storeAs('uploads/logo/', $logoName);
                    $company_logo = !empty($request->company_logo_dark) ? $logoName : 'logo-dark.png';
                    // dd($company_logo);
                    DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $logoName,
                            'company_logo_dark',
                            Auth::user()->creatorId(),
                        ]
                    );
                }

                if ($request->company_logo_light) {

                    $request->validate(
                        [
                            'company_logo_light' => 'image',
                        ]
                    );


                    $logoName     = $user->id . '-logo-light.png';

                    $dir = 'uploads/logo/';

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];

                    $file_path = $request->company_logo_light;
                    $image_size = $request->file('company_logo_light')->getSize();

                    // $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                    // if ($result == 1) {
                    // Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                    $path = Utility::upload_file($request, 'company_logo_light', $logoName, $dir, $validation);

                    if ($path['flag'] == 1) {
                        $company_logo_light = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    // } else {
                    //     return redirect()->back()->with('error', $result);
                    // }

                    // $path         = $request->file('company_logo_light')->storeAs('uploads/logo/', $logoName);
                    $company_logo = !empty($request->company_logo_light) ? $logoName : 'logo-light.png';

                    DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $logoName,
                            'company_logo_light',
                            Auth::user()->creatorId(),
                        ]
                    );
                }

                if ($request->company_favicon) {
                    $request->validate(
                        [
                            'company_favicon' => 'image',
                        ]
                    );
                    $favicon = $user->id . '_favicon.png';


                    $dir = 'uploads/logo/';

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];

                    $file_path = $request->company_favicon;
                    $image_size = $request->file('company_favicon')->getSize();

                    // $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                    // if ($result == 1) {
                    // Utility::changeStorageLimit(Auth::user()->creatorId(), $file_path);
                    $path = Utility::upload_file($request, 'company_favicon', $favicon, $dir, $validation);

                    if ($path['flag'] == 1) {
                        $company_favicon = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    // } else {
                    //     return redirect()->back()->with('error', $result);
                    // }


                    // $path    = $request->file('company_favicon')->storeAs('uploads/logo/', $favicon);

                    $company_favicon = !empty($request->favicon) ? $favicon : 'favicon.png';

                    DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $favicon,
                            'company_favicon',
                            Auth::user()->creatorId(),
                        ]
                    );
                }

                $settings = Utility::settings();

                // if (!empty($request->title_text) || !empty($request->SITE_RTL) || !empty($request->cust_theme_bg) || !empty($request->cust_darklayout)) {
                $post = $request->all();

                unset($post['_token'], $post['company_logo_dark'], $post['company_logo_light'], $post['company_favicon']);


                if (!isset($request->SITE_RTL)) {
                    $post['SITE_RTL'] = 'off';
                }

                if (!isset($request->cust_theme_bg)) {
                    $post['cust_theme_bg'] = 'off';
                }

                if (!isset($request->cust_darklayout)) {
                    $post['cust_darklayout'] = 'off';
                }
                if (isset($request->color) && $request->color_flag == 'false') {
                    $post['color'] = $request->color;
                } else {
                    $post['color'] = $request->custom_color;
                }
                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings))) {
                        DB::insert(
                            'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                Auth::user()->creatorId(),
                            ]
                        );
                    }
                }
                // }


                $this->logActivity(
                    'Business Settings Updated',
                    'Business Settings Updated',
                    route('company.settings.index'),
                    'Business Settings Updated successfully',
                    Auth::user()->creatorId(),
                    Auth::user()->id
                );

                DB::commit();

                return redirect()->back()->with('success', 'Brand Setting successfully updated.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function companyIndex()
    {
        $usr = Auth::user();

        if ($usr->type == 'company') {
            if (Auth::user()->can('brand settings')) {
                $settings                = Utility::settings();
                $company_payment_setting = Utility::getCompanyPaymentSetting(\Auth::user()->id);

                return view('admin.settings.company', compact('settings', 'company_payment_setting'));
            } else {
                return redirect()->back()->with('error', 'Permission denied.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function invoiceTemplateStore(Request $request)
    {
        $invSettings = InvoiceSetting::where('user_id', Auth::user()->creatorId())->first();
        if (!$invSettings) {
            $invSettings = new InvoiceSetting();
        }
        $invSettings->user_id          = Auth::user()->creatorId();
        $invSettings->template         = $request->invoice_template;
        $invSettings->prefix           = $request->prefix;
        $invSettings->due_after        = $request->due_after;
        $invSettings->terms            = $request->terms;
        $invSettings->save();

        $this->logActivity(
            'Invoice Template Choosed',
            'Invoice Template Choosed',
            route('company.settings.index'),
            'Invoice Template Choosed Successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        return redirect()->back()->with('success', __('Invoice template successfully updated.'));
    }


    public function letterPadTemplateStore(Request $request)
    {
        $letterSettings = LetterPadSettings::where('user_id', Auth::user()->creatorId())->first();
        if (!$letterSettings) {
            $letterSettings = new LetterPadSettings();
        }
        $letterSettings->user_id          = Auth::user()->creatorId();
        $letterSettings->template         = $request->template;
        $letterSettings->terms            = $request->terms;
        $letterSettings->save();


        $this->logActivity(
            'Letterpad Template Choosed',
            'Letterpad Template Choosed',
            route('company.settings.index'),
            'Letterpad Template Choosed Successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        return redirect()->back()->with('success', __('Letter pad template successfully updated.'));
    }


    public function estimateTemplateStore(Request $request)
    {
        $estimateSettings = EstimateSettings::where('user_id', Auth::user()->creatorId())->first();
        if (!$estimateSettings) {
            $estimateSettings = new EstimateSettings();
        }
        $estimateSettings->user_id          = Auth::user()->creatorId();
        $estimateSettings->template         = $request->estimateTemplate;
        $estimateSettings->terms            = $request->terms;
        $estimateSettings->save();


        $this->logActivity(
            'Estimate Template Choosed',
            'Estimate Template Choosed',
            route('company.settings.index'),
            'Estimate Template Choosed Successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        return redirect()->back()->with('success', __('Letter pad template successfully updated.'));
    }




    public function resetPermissions()
    {
        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 403);
        }

        // Remove all existing permissions
        $user->permissions()->detach();

        $company_id = Auth::user()->creatorId();

        // $companySubscriptions = CompanySubscription::where('company_id',$company_id)->where('status',1)->get();

        $permissionIds = CompanyPermission::where('company_id', $company_id)->pluck('permission_id');
        if (!empty($user->roles)) {
            foreach ($user->roles as $role) {
                // Optionally, detach all current permissions
                $role->syncPermissions([]); // This clears existing role permissions

                // Now assign the allowed permissions
                foreach ($permissionIds as $permissionId) {
                    $permission = Permission::find($permissionId);
                    if ($permission) {
                        $role->givePermissionTo($permission);
                    }
                }
            }
        }

        // If you need permission names (Spatie expects names), retrieve them:
        $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        // Assign permissions back to the user
        $user->givePermissionTo($permissionNames);

        // Clear cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // $roles = $user->roles;


        // foreach ($roles as $role) {
        //     // Detach all permissions from the role
        //     $role->syncPermissions([]);
        // }



        // $defaultPermissions = ModelsPermission::where('is_company', '1')->get();

        // // Assign new permissions
        // foreach ($defaultPermissions as $permission) {
        //     $perm = Permission::firstOrCreate(['name' => $permission->name]); // Ensure permission exists
        //     $user->givePermissionTo($perm);
        // }


        $this->logActivity(
            'Permission Reseted',
            'Permission Reseted',
            route('company.settings.index'),
            'Permission Reseted',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        return redirect()->back()->with('success', __('Permissions reset successfully'));
    }


     public function chatgptkey(Request $request)
    {
        // if (\Auth::user()->type == 'super admin') {
            $user = \Auth::user();
            if (!empty($request->chatgpt_key)) {
                $post = $request->all();
                $post['chatgpt_key'] = $request->chatgpt_key;
                $post['chatgpt_model_name'] = $request->chatgpt_model_name;

                unset($post['_token']);
                foreach ($post as $key => $data) {

                    $settings = Utility::settings();

                    if (in_array($key, array_keys($settings))) {

                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                \Auth::user()->creatorId(),
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                            ]
                        );
                    }
                }
            }
            return redirect()->back()->with('success', __('Chatgpykey successfully saved.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }
}
