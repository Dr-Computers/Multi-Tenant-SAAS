<?php

use App\Http\Controllers\Company\Realestate\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'company',
        'as' => 'company.',
        'namespace' => 'App\Http\Controllers\Company',
        'middleware' => ['auth', 'XSS', 'revalidate'],
    ],
    function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');

        // HRMS
        Route::group([
            'prefix' => 'hrms',
            'as' => 'hrms.',
            'namespace' => 'HRMS',
        ], function () {
            Route::resource('users', 'UserController')->names('users');
            Route::get('users/{user}/reset-password', 'UserController@resetPasswordForm')->name('users.reset.form');
            Route::post('users/{user}/reset-password', 'UserController@resetPassword')->name('users.reset.update');
            Route::resource('roles', 'RoleController')->names('roles');
        });

        // Realestate
        Route::group([
            'prefix' => 'realestate',
            'as' => 'realestate.',
            'namespace' => 'Realestate',
        ], function () {
            Route::resource('owners', 'OwnerController')->names('owners');
            Route::get('owners/{user}/reset-password', 'OwnerController@resetPasswordForm')->name('owners.reset.form');
            Route::post('owners/{user}/reset-password', 'OwnerController@resetPassword')->name('owners.reset.update');
            Route::resource('properties', 'PropertyController')->names('properties');
            Route::resource('property-units/{id}', 'PropertyUnitController')->names('property.units');
          
            Route::resource('tenants', 'TenantController')->names('tenants');
            Route::get('tenants/{user}/reset-password', 'TenantController@resetPasswordForm')->name('tenants.reset.form');
            Route::post('tenants/{user}/reset-password', 'TenantController@resetPassword')->name('tenants.reset.update');
            Route::resource('maintainers', 'MaintenanceController')->names('maintainers');
            Route::get('maintainers/{user}/reset-password', 'MaintenanceController@resetPasswordForm')->name('maintainers.reset.form');
            Route::post('maintainers/{user}/reset-password', 'MaintenanceController@resetPassword')->name('maintainers.reset.update');
            Route::resource('maintaince-request', 'MaintainceRequestController')->names('maintaince-request');
            Route::resource('categories', 'CategoryController')->names('categories');
            Route::resource('furnishing', 'FurnishingController')->names('furnishing');
            Route::resource('amenities', 'AmenitiesController')->names('amenities');
            Route::resource('landmarks', 'LandmarkController')->names('landmarks');

            Route::resource('invoices',InvoiceController::class)->names('invoices');
            

            

     

        });
    }
);
