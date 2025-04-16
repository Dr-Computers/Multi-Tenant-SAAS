<?php

use App\Http\Controllers\TapPaymentController;
use App\Models\Utility;

use Illuminate\Support\Facades\Route;

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

        Route::get('/', 'DashboardController@index')->name('dashboard')->middleware(['XSS', 'revalidate']);

        Route::resource('users', 'UserController')->names('users');
        Route::resource('roles', 'RoleController')->names('roles');
        Route::any('company-reset-password/{id}', 'CompanyController@userPassword')->name('company.reset');
        Route::post('company-reset-password/{id}', 'CompanyController@userPasswordReset')->name('company.password.update');
        Route::post('company-unable', 'CompanyController@UserUnable')->name('company.unable');

        Route::get('company-login/{id}', 'CompanyController@LoginWithCompany')->name('company.login');
        Route::get('company-login-activty/{id}', 'CompanyController@LoginManage')->name('company.login_fn');

        Route::get('login-with-company/exit', 'CompanyController@ExitCompany')->name('exit.company');
        Route::get('company-info/{id}', 'CompanyController@CompnayInfo')->name('company.info');
        Route::get('company/{id}/plan', 'CompanyController@upgradePlan')->name('plan.upgrade');
        Route::get('company/{id}/plan/{pid}', 'CompanyController@activePlan')->name('plan.active');
        Route::resource('company', 'CompanyController')->names('company');

        Route::resource('settings', 'SystemController');

        Route::post('email-settings', 'SystemController@saveEmailSettings')->name('email.settings');
        Route::post('company-settings', 'SystemController@saveCompanySettings')->name('company.settings');

        Route::post('stripe-settings', 'SystemController@savePaymentSettings')->name('payment.settings');
        Route::post('system-settings', 'SystemController@saveSystemSettings')->name('system.settings');
        Route::post('recaptcha-settings', 'SystemController@recaptchaSettingStore')->name('recaptcha.settings.store');
        Route::post('storage-settings', 'SystemController@storageSettingStore')->name('storage.setting.store');

        Route::get('company-setting', 'SystemController@companyIndex')->name('company.setting');
        Route::post('business-setting', 'SystemController@saveBusinessSettings')->name('business.setting');
        Route::any('twilio-settings', 'SystemController@saveTwilioSettings')->name('twilio.settings');
        Route::post('company-payment-setting', 'SystemController@saveCompanyPaymentSettings')->name('company.payment.settings');

   



        Route::post('cookie-setting', 'SystemController@saveCookieSettings')->name('cookie.setting');
        Route::post('chatgptkey', 'SystemController@chatgptkey')->name('settings.chatgptkey');
        Route::post('reset-permissions', 'SystemController@resetPermissions')->name('settings.reset-permissions');


        Route::post('test', 'SystemController@testMail')->name('test.mail');
        Route::post('test-mail', 'SystemController@testSendMail')->name('test.send.mail');

        Route::post('setting/seo', 'SystemController@SeoSettings')->name('seo.settings');

        Route::resource('webhook', 'WebhookController'::class);

        Route::post('company-email-settings', 'SystemController@saveCompanyEmailSetting')->name('company.email.settings');



        Route::get('plan/sections/{id}', 'PlanController@SectionEdit')->name('plans.section-edit');
        Route::put('plan/sections/{id}', 'PlanController@sectionUpdate')->name('plans.section.update');
        Route::get('plan/sections', 'PlanController@Sections')->name('plans.sections');


        Route::get('plan/section-request', 'SectionRequestController@index')->name('plans.section_request');

        Route::get('plan/plan-trial/{id}', 'PlanController@PlanTrial')->name('plan.trial');
        Route::resource('plans', 'PlanController');
        Route::post('plan-disable', 'PlanController@planDisable')->name('plan.disable');
        Route::get('plan_request', 'PlanRequestController@index')->name('plan_request.index');

        Route::get('order', 'StripePaymentController@index')->name('order.index');
        Route::get('/refund/{id}/{user_id}', 'StripePaymentController@refund')->name('order.refund');
        Route::get('/stripe/{code}', 'StripePaymentController@stripe')->name('stripe');
        Route::post('/stripe', 'StripePaymentController@stripePost')->name('stripe.post');

        // Plan Request Module
        Route::get('request_frequency/{id}', 'PlanRequestController@requestView')->name('request.view');
        Route::get('request_send/{id}', 'PlanRequestController@userRequest')->name('send.request');
        Route::get('request_response/{id}/{response}', 'PlanRequestController@acceptRequest')->name('response.request');
        Route::get('request_cancel/{id}', 'PlanRequestController@cancelRequest')->name('request.cancel');


        Route::resource('notification-templates', 'NotificationTemplatesController')->except('index');
        Route::get('notification-templates/{id?}/{lang?}', 'NotificationTemplatesController@index')->name('notification-templates.index');

        Route::get('notification_template_lang/{id}/{lang?}', 'NotificationTemplatesController@manageNotificationLang')->name('manage.notification.language');

        Route::get('email_template_lang/{id}/{lang?}', 'EmailTemplateController@manageEmailLang')->name('manage.email.language');
        Route::put('email_template_store/{pid}', 'EmailTemplateController@storeEmailLang')->name('store.email.language');
        Route::post('email_template_status', 'EmailTemplateController@updateStatus')->name('status.email.language');

        Route::resource('email_template', 'EmailTemplateController');


        Route::group([
            'prefix' => 'realestate',
            'as' => 'realestate.',
            'namespace' => 'Realestate',
        ], function () {
            Route::resource('categories', 'CategoryController')->names('categories');
            Route::resource('amenities', 'AmenityController')->names('amenities');
            Route::resource('furnishings', 'FurnishingController')->names('furnishings');
            Route::resource('landmarks', 'LandmarkController')->names('landmarks');
        });
    }
);
