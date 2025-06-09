<?php

use App\Http\Controllers\TapPaymentController;
use App\Models\Utility;

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'maintainer',
        'as' => 'maintainer.',
        'namespace' => 'App\Http\Controllers\Maintainer',
        'middleware' => ['auth', 'XSS', 'revalidate', 'maintainer_panel'],
    ],
    function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::get('profile', 'DashboardController@profile')->name('profile');
        Route::post('profile', 'DashboardController@editprofile')->name('profile.update');
        Route::post('password', 'DashboardController@updatePassword')->name('profile.update.password');


        Route::resource('maintenance-works', 'SystemController')->names('maintenance-works');
        Route::resource('settings', 'SystemController');
        Route::post('reset-permissions', 'SystemController@resetPermissions')->name('settings.reset-permissions');
    }
);
