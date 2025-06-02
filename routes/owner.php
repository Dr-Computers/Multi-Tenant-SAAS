<?php

use App\Http\Controllers\TapPaymentController;
use App\Models\Utility;

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'owner',
        'as' => 'owner.',
        'namespace' => 'App\Http\Controllers\Owner',
        'middleware' => ['auth', 'XSS', 'revalidate', 'owner_panel'],
    ],
    function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
    }
);
