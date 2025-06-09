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
        Route::get('profile', 'DashboardController@profile')->name('profile');
        Route::post('profile', 'DashboardController@editprofile')->name('profile.update');
        Route::post('password', 'DashboardController@updatePassword')->name('profile.update.password');


        Route::resource('users', 'UserController')->names('users');
        Route::resource('roles', 'RoleController')->names('roles');

        Route::get('/permissions', 'PermissionController@index')->name('permissions.index');
        Route::get('/permissions/create', 'PermissionController@create')->name('permissions.create');
        Route::put('/permissions/{id}', 'PermissionController@update')->name('permissions.update');

        Route::post('/permissions-upload', 'PermissionController@upload')->name('permissions.upload');

        Route::any('company-reset-permissions/{id}', 'CompanyController@resetPermissions')->name('company.reset-permissions');
        Route::any('company-reset-password/{id}', 'CompanyController@userPassword')->name('company.reset');
        Route::post('company-reset-password/{id}', 'CompanyController@userPasswordReset')->name('company.password.update');
        Route::post('company-unable', 'CompanyController@UserUnable')->name('company.unable');

        Route::get('company/addon-features/{id}', 'CompanyController@addonFeatures')->name('company.addon-features');
        Route::post('company/addon-features/{id}', 'CompanyController@addonFeaturesStore')->name('company.addon-features.store');

        Route::post('company/existing-features/{id}', 'CompanyController@existingFeaturesStore')->name('company.existing-features.update');

        Route::delete('company/existing-feature-remove/{company_id}/{id}', 'CompanyController@existingFeaturesRemove')->name('company.existing-features.update');


        Route::post('company/coupon-validate', 'CompanyController@validateCoupon')->name('company.coupon.validate');

        Route::get('company-login/{id}', 'CompanyController@LoginWithCompany')->name('company.login');
        Route::get('company-login-activty/{id}', 'CompanyController@LoginManage')->name('company.login_fn');

        Route::get('login-with-company/exit', 'CompanyController@ExitCompany')->name('exit.company');
        Route::get('company-info/{id}', 'CompanyController@CompnayInfo')->name('company.info');
        Route::get('company/{id}/plan', 'CompanyController@upgradePlan')->name('company.plan.upgrade');
        Route::post('company/{company_id}/plan/{id}', 'CompanyController@upgradePlanStore')->name('company.plan.upgrade.store');
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

        Route::post('invoice/template/settings/store', 'SystemController@invoiceTemplateStore')->name('invoice.template.settings.store');
        Route::post('letter-pad/template/settings/store', 'SystemController@letterPadTemplateStore')->name('letter-pad.template.settings.store');


        Route::post('test', 'SystemController@testMail')->name('test.mail');
        Route::post('test-mail', 'SystemController@testSendMail')->name('test.send.mail');

        Route::post('setting/seo', 'SystemController@SeoSettings')->name('seo.settings');

        // Route::resource('webhook', 'WebhookController'::class);

        Route::post('company-email-settings', 'SystemController@saveCompanyEmailSetting')->name('company.email.settings');


        Route::get('plan/sections/import', 'PlanController@SectionForm')->name('plans.sections.create');
        Route::post('plan/sections/import', 'PlanController@sectionUpload')->name('plans.sections.import');

        Route::get('plan/sections/{id}', 'PlanController@SectionEdit')->name('plans.section-edit');
        Route::put('plan/sections/{id}', 'PlanController@sectionUpdate')->name('plans.section.update');
        Route::get('plan/sections', 'PlanController@Sections')->name('plans.sections');


        Route::get('plan/section-request', 'SectionRequestController@index')->name('plans.section_request.index');
        Route::get('section/request-response/{id}/{response}', 'SectionRequestController@acceptRequest')->name('section.response.request');


        Route::get('plan/plan-trial/{id}', 'PlanController@PlanTrial')->name('plan.trial');
        Route::resource('plans', 'PlanController');
        Route::post('plan-disable', 'PlanController@planDisable')->name('plan.disable');
        Route::get('plan_request', 'PlanRequestController@index')->name('plans.plan_request.index');

        Route::get('order', 'OrderController@index')->name('order.index');
        Route::get('order/send-email/{order}', 'OrderController@sendEmail')->name('order.send.email');
        Route::post('order/send-email/{order}', 'OrderController@sendEmailProcess')->name('order.send-email.process');
        Route::get('order/download/invoice/{order}', 'OrderController@downloadInvoice')->name('order.download.invoice');
        Route::get('order/make/payment/{order}', 'OrderController@makePayment')->name('order.make.payment');
        Route::post('order/mark-as-payment/{order}', 'OrderController@markAsPayment')->name('order.mark.as.payment');
        Route::delete('order/{id}/destroy', 'OrderController@destroy')->name('order.destroy');

        Route::get('/refund/{id}/{user_id}', 'OrderController@refund')->name('order.refund');

        Route::get('/stripe/{code}', 'OrderController@stripe')->name('stripe');
        Route::post('/stripe', 'OrderController@stripePost')->name('stripe.post');

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
        Route::resource('templates/invoice', 'InvoiceTemplateController')->names('templates.invoices');
        Route::resource('templates/letter-pad', 'letterPadTemplateController')->names('templates.letter-pads');
        Route::resource('templates/estimate', 'EstimateTemplateController')->names('templates.estimates');


        Route::group([
            'prefix' => 'realestate',
            'as' => 'realestate.',
            'namespace' => 'Realestate',
        ], function () {

            Route::get('categories/requests', 'CategoryController@requestsList')->name('categories.requests');
            Route::get('categories/requests-single/{id}', 'CategoryController@requestsSingle')->name('categories.request-single');
            Route::post('categories/requests-accept/{id}', 'CategoryController@requestsAccept')->name('categories.request-accept');
            Route::post('categories/requests-reject/{id}', 'CategoryController@requestsReject')->name('categories.request-reject');
            Route::resource('categories', 'CategoryController')->names('categories');

            Route::get('amenities/requests', 'AmenityController@requestsList')->name('amenities.requests');
            Route::get('amenities/requests-single/{id}', 'AmenityController@requestsSingle')->name('amenities.request-single');
            Route::post('amenities/requests-accept/{id}', 'AmenityController@requestsAccept')->name('amenities.request-accept');
            Route::post('amenities/requests-reject/{id}', 'AmenityController@requestsReject')->name('amenities.request-reject');
            Route::resource('amenities', 'AmenityController')->names('amenities');

            Route::get('furnishings/requests', 'FurnishingController@requestsList')->name('furnishings.requests');
            Route::get('furnishings/requests-single/{id}', 'FurnishingController@requestsSingle')->name('furnishings.request-single');
            Route::post('furnishings/requests-accept/{id}', 'FurnishingController@requestsAccept')->name('furnishings.request-accept');
            Route::post('furnishings/requests-reject/{id}', 'FurnishingController@requestsReject')->name('furnishings.request-reject');
            Route::resource('furnishings', 'FurnishingController')->names('furnishings');

            Route::get('landmarks/requests', 'LandmarkController@requestsList')->name('landmarks.requests');
            Route::get('landmarks/requests-single/{id}', 'LandmarkController@requestsSingle')->name('landmarks.request-single');
            Route::post('landmarks/requests-accept/{id}', 'LandmarkController@requestsAccept')->name('landmarks.request-accept');
            Route::post('landmarks/requests-reject/{id}', 'LandmarkController@requestsReject')->name('landmarks.request-reject');
            Route::resource('landmarks', 'LandmarkController')->names('landmarks');
        });


        /*Support Ticket*/
        Route::group([
            'prefix' => 'tickets',
            'as' => 'tickets.',
        ], function () {
            Route::resource('/', 'SupportTicketController');
            Route::get('view/{company_id}/{ticket_no}', 'SupportTicketController@view')->name('view');
            Route::post('reply/{ticket_no}', 'SupportTicketController@sendreply')->name('reply');
            Route::post('tickets/closed/{ticket_no}', 'SupportTicketController@closedTicket')->name('closed_ticket');
            Route::get('ticket/assigned-staff', 'SupportTicketController@assigned_staff')->name('assigned_staff');
        });
    }
);
