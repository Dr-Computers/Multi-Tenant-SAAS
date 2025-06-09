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
        Route::get('profile', 'DashboardController@profile')->name('profile');
        Route::post('profile', 'DashboardController@editprofile')->name('profile.update');
        Route::post('password', 'DashboardController@updatePassword')->name('profile.update.password');

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


        Route::resource('settings', 'SystemController');
        Route::post('reset-permissions', 'SystemController@resetPermissions')->name('settings.reset-permissions');

        Route::group([
            'prefix' => 'finance',
            'as' => 'finance.',
        ], function () {

            

                Route::resource('invoices', 'InvoiceController')->names('invoices');
          
        });



        Route::get('reports/invoice', [ReportController::class, 'invoiceIndex'])->name('report.invoices.index');

        // Invoice Report
        Route::get('reports/payment', [ReportController::class, 'paymentIndex'])->name('report.payments.index');

        // Expense Report
        Route::get('reports/cheques', [ReportController::class, 'chequesIndex'])->name('report.cheques.index');

        // Cheques Report
        Route::get('reports/expense', [ReportController::class, 'expenseIndex'])->name('report.expenses.index');

        // Tenants Report
        Route::get('reports/tenants', [ReportController::class, 'tenantsIndex'])->name('report.tenants.index');
        Route::get('/reports/lease-expiry', [ReportController::class, 'leaseExpiryReport'])->name('report.lease-expiry.index');
        // Properties Report
        Route::get('reports/properties', [ReportController::class, 'propertiesIndex'])->name('report.properties.index');
        Route::get('reports/download', [ReportController::class, 'downloadPropertyReport'])->name('report.properties.download');


        // Units Report
        Route::get('reports/units', [ReportController::class, 'unitsIndex'])->name('report.units.index');
        Route::get('/units/{id}/view', [ReportController::class, 'view'])->name('units.view');
        Route::get('/units/{id}/download', [ReportController::class, 'downloadUnits'])->name('units.download');

        Route::get('reports/rent-collection', [ReportController::class, 'rentCollectionSummaryReport'])->name('report.rent_collection.index');


        // Payments Report
        Route::get('reports/maintainers', [ReportController::class, 'maintainersIndex'])->name('report.maintainers.index');
        Route::get('reports/maintenances', [ReportController::class, 'maintenancesIndex'])->name('report.maintenances.index');


        Route::get('reports/bank-transactions', [ReportController::class, 'transactionsIndex'])->name('report.bank_transactions.index');
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLossIndex'])->name('report.profit_loss.index');
        Route::get('/reports/balance-sheet', [ReportController::class, 'balanceSheetIndex'])->name('report.balance_sheet.index');
        Route::get('report/profit_loss/download_pdf', [ReportController::class, 'downloadPdf'])->name('report.profit_loss.download_pdf');

        //Fire And Safety Expiry Report
        Route::get('/reports/fireandsafety-expiry', [ReportController::class, 'fireandsafetyExpiryReport'])->name('report.fireandsafety-expiry.index');

        //Insurance Expiry Report
        Route::get('/reports/insurance-expiry', [ReportController::class, 'insuranceExpiryReport'])->name('report.insurance-expiry.index');

        //Building wise outstanding report
        Route::get('/reports/invoice-outstanding', [ReportController::class, 'invoiceOutstandingReport'])->name('report.invoice-outstanding.index');

        // Route for downloading the Profit and Loss Report as an Excel file
        Route::get('report/profit_loss/download_excel', [ReportController::class, 'downloadExcel'])->name('report.profit_loss.download_excel');
    }
);
