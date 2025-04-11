<?php

use App\Http\Controllers\TapPaymentController;
use App\Models\Utility;

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\CompanyController;
// use App\Http\Controllers\Admin\DashboardController;
// use App\Http\Controllers\Admin\EmailTemplateController;
// use App\Http\Controllers\Admin\NotificationTemplatesController;
// use App\Http\Controllers\ExpenseController;
// use App\Http\Controllers\Admin\PlanController;
// use App\Http\Controllers\Admin\PlanRequestController;
// use App\Http\Controllers\Admin\SectionRequestController;
// use App\Http\Controllers\Admin\RoleController;
// use App\Http\Controllers\SystemController;
// use App\Http\Controllers\WebhookController;
// use App\Http\Controllers\StripePaymentController;
// use App\Http\Controllers\Admin\UserController;

// Route::group(['prefix'=>'admin','as'=>'admin.'],
//     [
//         'middleware' => [
//             'auth', // Ensures the user is logged in
//             'XSS', // Custom middleware for preventing XSS attacks
//             'revalidate', // Middleware to prevent back button access after logout
//         ],
//     ],
//     function () {

Route::group(
    [
        'prefix' => 'admin',  // URL prefix
        'as' => 'admin.',     // Name prefix
        'middleware' => [
            'auth',       // Ensures user is logged in
            'XSS',        // Custom middleware for XSS prevention
            'revalidate', // Prevents back button after logout
        ],
        'namespace' => 'App\Http\Controllers\Admin',
    ],
    function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['XSS', 'revalidate']);
        Route::resource('company', CompanyController::class)->names('company');
        Route::resource('users', UserController::class)->names('users');
        Route::resource('roles', RoleController::class)->names('roles');
        Route::any('company-reset-password/{id}', [CompanyController::class, 'userPassword'])->name('company.reset');
        Route::post('company-reset-password/{id}', [CompanyController::class, 'userPasswordReset'])->name('company.password.update');
        Route::post('company-unable', [CompanyController::class, 'UserUnable'])->name('company.unable');

        Route::get('company-login/{id}', [CompanyController::class, 'LoginManage'])->name('company.login');


        Route::resource('settings', SystemController::class);

        Route::post('email-settings', [SystemController::class, 'saveEmailSettings'])->name('email.settings');
        Route::post('company-settings', [SystemController::class, 'saveCompanySettings'])->name('company.settings');

        Route::post('stripe-settings', [SystemController::class, 'savePaymentSettings'])->name('payment.settings');
        Route::post('system-settings', [SystemController::class, 'saveSystemSettings'])->name('system.settings');
        Route::post('recaptcha-settings', [SystemController::class, 'recaptchaSettingStore'])->name('recaptcha.settings.store');
        Route::post('storage-settings', [SystemController::class, 'storageSettingStore'])->name('storage.setting.store');

        Route::get('company-setting', [SystemController::class, 'companyIndex'])->name('company.setting');
        Route::post('business-setting', [SystemController::class, 'saveBusinessSettings'])->name('business.setting');
        Route::any('twilio-settings', [SystemController::class, 'saveTwilioSettings'])->name('twilio.settings');
        Route::post('company-payment-setting', [SystemController::class, 'saveCompanyPaymentSettings'])->name('company.payment.settings');


        Route::get('company-info/{id}', [UserController::class, 'CompnayInfo'])->name('company.info');


        Route::post('cookie-setting', [SystemController::class, 'saveCookieSettings'])->name('cookie.setting');
        Route::post('chatgptkey', [SystemController::class, 'chatgptkey'])->name('settings.chatgptkey');
        Route::post('reset-permissions', [SystemController::class, 'resetPermissions'])->name('settings.reset-permissions');


        Route::post('test', [SystemController::class, 'testMail'])->name('test.mail');
        Route::post('test-mail', [SystemController::class, 'testSendMail'])->name('test.send.mail');

        Route::post('setting/seo', [SystemController::class, 'SeoSettings'])->name('seo.settings');

        Route::resource('webhook', WebhookController::class);

        Route::post('company-email-settings', [SystemController::class, 'saveCompanyEmailSetting'])->name('company.email.settings');



        Route::get('plan/sections/{id}', [PlanController::class, 'SectionEdit'])->name('plans.section-edit');
        Route::put('plan/sections/{id}', [PlanController::class, 'sectionUpdate'])->name('plans.section.update');
        Route::get('plan/sections', [PlanController::class, 'Sections'])->name('plans.sections');


        Route::get('plan/section-request', [SectionRequestController::class, 'index'])->name('plans.section_request');

        Route::get('plan/plan-trial/{id}', [PlanController::class, 'PlanTrial'])->name('plan.trial');
        Route::resource('plans', PlanController::class);
        Route::post('plan-disable', [PlanController::class, 'planDisable'])->name('plan.disable');
        Route::get('plan_request', [PlanRequestController::class, 'index'])->name('plan_request.index');

        Route::get('order', [StripePaymentController::class, 'index'])->name('order.index');
        Route::get('/refund/{id}/{user_id}', [StripePaymentController::class, 'refund'])->name('order.refund');
        Route::get('/stripe/{code}', [StripePaymentController::class, 'stripe'])->name('stripe');
        Route::post('/stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');

        // Plan Request Module
        Route::get('request_frequency/{id}', [PlanRequestController::class, 'requestView'])->name('request.view');
        Route::get('request_send/{id}', [PlanRequestController::class, 'userRequest'])->name('send.request');
        Route::get('request_response/{id}/{response}', [PlanRequestController::class, 'acceptRequest'])->name('response.request');
        Route::get('request_cancel/{id}', [PlanRequestController::class, 'cancelRequest'])->name('request.cancel');


        Route::resource('notification-templates', NotificationTemplatesController::class)->except('index');
        Route::get('notification-templates/{id?}/{lang?}', [NotificationTemplatesController::class, 'index'])->name('notification-templates.index');

        Route::get('notification_template_lang/{id}/{lang?}', [NotificationTemplatesController::class, 'manageNotificationLang'])->name('manage.notification.language');

        Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language');
        Route::put('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language');
        Route::post('email_template_status', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language');

        Route::resource('email_template', EmailTemplateController::class);


        Route::group([
            'prefix' => 'realestate',
            'as' => 'realestate.',
            'namespace' => 'Realestate',
        ], function () {
            Route::resource('categories', 'CategoryController')->names('categories');
            Route::resource('amenities', 'AnimetyController')->names('amenities');
            Route::resource('furnishings', 'FurnishingController')->names('furnishings');
            Route::resource('landmarks', 'LandmarkController')->names('landmarks');

        });
    }
);
