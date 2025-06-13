<?php

use App\Http\Controllers\TapPaymentController;
use App\Models\Utility;

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'tenant',
        'as' => 'tenant.',
        'namespace' => 'App\Http\Controllers\Tenant',
        'middleware' => ['auth', 'XSS', 'revalidate', 'tenant_panel'],
    ],
    function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::get('profile', 'DashboardController@profile')->name('profile');
        Route::post('profile', 'DashboardController@editprofile')->name('profile.update');
        Route::post('password', 'DashboardController@updatePassword')->name('profile.update.password');


        Route::resource('settings', 'SystemController');
        Route::post('reset-permissions', 'SystemController@resetPermissions')->name('settings.reset-permissions');

        Route::group([
            'prefix' => 'realestate',
            'as' => 'realestate.',
            'namespace' => 'Realestate',
        ], function () {
            Route::resource('properties', 'PropertyController')->names('properties');
            Route::resource('property/{property_id}/units', 'PropertyUnitController')->names('property.units');

            Route::get('lease-properties', 'PropertyLeaseController@index')->name('properties.lease.index');

            Route::resource('maintenance-requests', 'MaintenanceRequestController')->names('maintenance-requests');
        });

           Route::group([
            'prefix' => 'finance',
            'as' => 'finance.',
        ], function () {
                Route::resource('invoices', 'InvoiceController')->names('invoices');
                Route::resource('payable', 'PayableController')->names('payable');
        });
    }
);
