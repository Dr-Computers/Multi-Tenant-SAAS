<?php

use App\Http\Controllers\Company\AssetController;
use App\Http\Controllers\Company\Finance\InvoiceController;
use App\Http\Controllers\Company\Finance\OtherInvoiceController;
use App\Http\Controllers\Company\Realestate\PropertyController;
use App\Http\Controllers\Company\Finance\BankAccountController;
use App\Http\Controllers\Company\Finance\ExpenseController;
use App\Http\Controllers\Company\LiabilityController;
use App\Http\Controllers\Company\Finance\PaymentController;
use App\Http\Controllers\Company\Finance\PaymentPayableController;
use App\Http\Controllers\Company\Realestate\ReportController;
use Illuminate\Support\Facades\Route;


Route::get('company/plan-expired', 'App\Http\Controllers\Company\DashboardController@planExpired')->name('company.plan-expired');
Route::group(
    [
        'prefix' => 'company',
        'as' => 'company.',
        'namespace' => 'App\Http\Controllers\Company',
        'middleware' => ['auth', 'XSS', 'revalidate', 'company_panel', 'check.plan.expiry'],
    ],
    function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::get('profile', 'DashboardController@profile')->name('profile');
        Route::post('profile', 'DashboardController@editprofile')->name('profile.update');
        Route::post('password', 'DashboardController@updatePassword')->name('profile.update.password');

        Route::get('plan/upgrade', 'DashboardController@planUpgrade')->name('plan.upgrade');
        Route::post('plan/upgrade/store', 'DashboardController@planUpgradeStore')->name('plan.upgrade.store');
        Route::get('addon/features', 'DashboardController@addonFeatures')->name('addon.features');
        Route::post('addon/features/store', 'DashboardController@addonFeaturesStore')->name('addon.features.store');
        Route::post('coupon/validate', 'DashboardController@couponValidate')->name('coupon.validate');

        Route::get('ajax/owner-properties/{owner_id}', 'Finance\InvoiceController@ownerProperties')->name('ajax.owner-properties');
        Route::get('ajax/tenant-properties/{tenant_id}', 'Finance\InvoiceController@tenantProperties')->name('ajax.tenant-properties');
        Route::get('ajax/property-units/{tenant_id}', 'Finance\InvoiceController@propertyUnits')->name('ajax.property-units');

        Route::get('login-with-company/exit', 'DashboardController@ExitCompany')->name('exit.company');


        // HRMS
        Route::group([
            'prefix' => 'hrms',
            'as' => 'hrms.',
            'namespace' => 'HRMS',
        ], function () {
            Route::resource('users', 'UserController')->names('users');
            Route::get('users/{user}/reset-password', 'UserController@resetPasswordForm')->name('users.reset.form');
            Route::post('users/{user}/reset-password', 'UserController@resetPassword')->name('users.reset.update');
            Route::get('users/create/documents/{user_id}', 'UserController@createDocuments')->name('users.create-documents');
            Route::post('users/create/documents/{user_id}', 'UserController@uploadDocuments')->name('users.upload-documents');
            Route::delete('users/documents/delete/{document}', 'UserController@deleteDocument')->name('users.documents.destroy');
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
            Route::get('owners/create/documents/{user_id}', 'OwnerController@createDocuments')->name('owners.create-documents');
            Route::post('owners/create/documents/{user_id}', 'OwnerController@uploadDocuments')->name('owners.upload-documents');
            Route::delete('owners/documents/delete/{document}', 'OwnerController@deleteDocument')->name('owners.documents.destroy');

            Route::resource('properties', 'PropertyController')->names('properties');

            Route::get('lease-properties', 'PropertyLeaseController@index')->name('properties.lease.index');
            Route::get('lease-properties/create/{unit}', 'PropertyLeaseController@create')->name('properties.lease.create');
            Route::post('lease-properties/{unit}/store', 'PropertyLeaseController@store')->name('properties.lease.store');
            Route::get('lease-properties/{unit}/show', 'PropertyLeaseController@create')->name('properties.lease.show');
            Route::post('lease-properties/{unit}', 'PropertyLeaseController@update')->name('properties.lease.update');
            Route::delete('lease-properties/{unit}/destroy', 'PropertyLeaseController@destroy')->name('properties.lease.destroy');

            Route::post('lease-properties/{unit}/cancel', 'PropertyLeaseController@cancel')->name('properties.lease.cancel');
            Route::post('lease-properties/{unit}/in-hold', 'PropertyLeaseController@inHold')->name('properties.lease.in-hold');
            Route::post('lease-properties/{unit}/approve', 'PropertyLeaseController@approve')->name('properties.lease.approve');



            // Route::resource('property-units/{id}', 'PropertyUnitController')->names('property.units');
            Route::get('property/{pid}/unit', [PropertyController::class, 'getPropertyUnit'])->name('property.unit');
            Route::resource('property/{property_id}/units', 'PropertyUnitController')->names('property.units');

            Route::get('unit/{uid}/rent-type', [PropertyController::class, 'getUnitRentType'])->name('unit.rent_type');
            Route::resource('tenants', 'TenantController')->names('tenants');
            Route::get('tenants/{user}/reset-password', 'TenantController@resetPasswordForm')->name('tenants.reset.form');
            Route::post('tenants/{user}/reset-password', 'TenantController@resetPassword')->name('tenants.reset.update');
            Route::get('tenants/create/documents/{user_id}', 'TenantController@createDocuments')->name('tenants.create-documents');
            Route::post('tenants/create/documents/{user_id}', 'TenantController@uploadDocuments')->name('tenants.upload-documents');
            Route::delete('tenants/documents/delete/{document}', 'TenantController@deleteDocument')->name('tenants.documents.destroy');

            Route::resource('maintainers', 'MaintainerController')->names('maintainers');
            Route::get('maintainers/{user}/reset-password', 'MaintainerController@resetPasswordForm')->name('maintainers.reset.form');
            Route::post('maintainers/{user}/reset-password', 'MaintainerController@resetPassword')->name('maintainers.reset.update');
            Route::get('maintainers/create/documents/{user_id}', 'MaintainerController@createDocuments')->name('maintainers.create-documents');
            Route::post('maintainers/create/documents/{user_id}', 'MaintainerController@uploadDocuments')->name('maintainers.upload-documents');
            Route::delete('maintainers/documents/delete/{document}', 'MaintainerController@deleteDocument')->name('maintainers.documents.destroy');

            Route::get('maintenance-requests/units/{id}', 'MaintenanceRequestController@getUnits')->name('maintenance-requests.units');

            Route::get('maintenance-requests/invoice/{id}/create', 'MaintenanceRequestController@invoiceCreate')->name('maintenance-requests.create-invoice');
            Route::post('maintenance-requests/invoice/{id}/store', 'MaintenanceRequestController@invoiceStore')->name('maintenance-requests.store-invoice');
            Route::post('maintenance-requests/invoice/{id}/download', 'MaintenanceRequestController@invoiceDownload')->name('maintenance-requests.download-invoice');
            Route::get('maintenance-requests/invoice/{id}/edit', 'MaintenanceRequestController@invoiceEdit')->name('maintenance-requests.edit-invoice');
            Route::post('maintenance-requests/invoice/{id}/update', 'MaintenanceRequestController@invoiceUpdate')->name('maintenance-requests.update-invoice');
            Route::resource('maintenance-requests', 'MaintenanceRequestController')->names('maintenance-requests');

            Route::resource('categories', 'CategoryController')->names('categories');
            Route::resource('furnishing', 'FurnishingController')->names('furnishing');
            Route::resource('amenities', 'AmenitiesController')->names('amenities');
            Route::resource('landmarks', 'LandmarkController')->names('landmarks');
        });


        // Finance Group

        Route::group([
            'prefix' => 'finance',
            'as' => 'finance.',
        ], function () {

            // Realestate Finance
            Route::group([
                'prefix' => 'realestate',
                'as' => 'realestate.',
            ], function () {
                Route::get('/invoice/choose', [InvoiceController::class, 'chooseInvoice'])->name('invoice.choose');
                Route::resource('invoices', InvoiceController::class)->names('invoices');
                Route::delete('invoice/type/destroy', [InvoiceController::class, 'invoiceTypeDestroy'])->name('invoice.type.destroy');
                Route::resource('invoice-other', OtherInvoiceController::class);
                Route::get('unit/{pid}/invoice', [InvoiceController::class, 'getUnitinvoice'])->name('unit.invoice');
                //payment
                Route::get('/payments/choose', [PaymentController::class, 'choosePayment'])->name('payments.choose');

                //invoice Payments
                Route::get('/invoice-payments', [PaymentController::class, 'index'])->name('invoice.payments.index');
                Route::get('/invoice-payments/create/{invoice_id}', [PaymentController::class, 'create'])->name('invoice.payments.create');
                Route::post('/invoice-payments/{invoice_id}', [PaymentController::class, 'store'])->name('invoice.payments.store');
                Route::get('/invoice-payments/{payment}/edit', [PaymentController::class, 'edit'])->name('invoice.payments.edit');
                Route::put('/invoice-payments/{payment}', [PaymentController::class, 'update'])->name('invoice.payments.update');
                Route::delete('/invoice-payments/{payment}', [PaymentController::class, 'destroy'])->name('invoice.payments.destroy');
                Route::get('/payment/{id}/cheque', [PaymentController::class, 'getChequeDetails'])->name('payments.cheque');
                Route::post('invoice/due-amount', [PaymentController::class, 'getDueAmount'])->name('invoice.due.amount');

                //Other payments
                Route::get('/other-payments', [PaymentController::class, 'otherIndex'])->name('other.payments.index');
                Route::get('/other-payments/create', [PaymentController::class, 'otherCreate'])->name('other.payments.create');
                Route::get('/other-payments/{payment}/edit', [PaymentController::class, 'otherEdit'])->name('other.payments.edit');
                Route::delete('/other-payments/{payment}', [PaymentController::class, 'otherDestroy'])->name('other.payments.destroy');
                Route::get('other-payments/tenant/{pid}/invoice', [PaymentController::class, 'getInvoices'])->name('tenant.invoices');

                //Payments Payable

                Route::resource('/payments/payable', PaymentPayableController::class)->names('payments.payables');
                Route::get('user/{tid}/type', [PaymentPayableController::class, 'fetchUsersByType'])->name('user.type');
            });

            // Bank Accounts
            Route::get('/bank-account/details', [BankAccountController::class, 'getAccountDetails'])->name('bank-account.fetchdetails');
            Route::resource('bank-accounts', BankAccountController::class)->names('bank-accounts');
            Route::resource('expense', ExpenseController::class);
        });

        Route::get('asset-list', [AssetController::class, 'index'])->name('assets.index');
        Route::get('assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('assets/store', [AssetController::class, 'store'])->name('assets.store');
        Route::get('assets/{id}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('assets/{id}', [AssetController::class, 'update'])->name('assets.update');
        Route::delete('assets/{id}', [AssetController::class, 'destroy'])->name('assets.destroy');

        Route::get('liabilities', [LiabilityController::class, 'index'])->name('liabilities.index');
        Route::get('liabilities/create', [LiabilityController::class, 'create'])->name('liabilities.create');
        Route::post('liabilities/store', [LiabilityController::class, 'store'])->name('liabilities.store');
        Route::get('liabilities/{id}/edit', [liabilityController::class, 'edit'])->name('liabilities.edit');
        Route::put('liabilities/{id}', [LiabilityController::class, 'update'])->name('liabilities.update');
        Route::delete('liabilities/{id}', [LiabilityController::class, 'destroy'])->name('liabilities.destroy');

        //Support Ticket 
        Route::group([
            'prefix' => 'tickets',
            'as' => 'tickets.',
        ], function () {
            Route::resource('/', 'SupportTicketController');
            Route::get('view/{company_id}/{ticket_no}', 'SupportTicketController@view')->name('view');
            Route::post('reply/{ticket_no}', 'SupportTicketController@sendreply')->name('reply');
        });



        //Media Storage
        Route::group([
            'prefix' => 'media',
            'as' => 'media.',
        ], function () {
            Route::get('/', 'MediaController@index')->name('index');

            Route::post('/folder', 'MediaController@storeFolder')->name('folder.store');
            Route::get('/folder/create', 'MediaController@createFolder')->name('folder.create');
            Route::get('/folder/rename/{folder_id}', 'MediaController@renameFolder')->name('folder.rename');
            Route::put('/folder/rename/{folder_id}', 'MediaController@updateFolder')->name('folder.rename.update');
            Route::delete('/folder/delete/{folder_id}', 'MediaController@deleteFolder')->name('folder.delete');
            Route::get('folder/{folder_id}', 'MediaController@subFolder')->name('folder.sub');
            Route::get('folder/{folder_id}/files/select/', 'MediaController@showFileUploadForm')->name('files.select');
            Route::delete('files/delete/{file_id}', 'MediaController@deleteFile')->name('files.delete');

            Route::post('/upload', 'MediaController@uploadFiles')->name('file.upload');
        });


        Route::group([
            'prefix' => 'subcriptions',
            'as' => 'subcriptions.',
        ], function () {
            Route::get('plans/features', 'DashboardController@addonFeatures')->name('plans.sections');
            Route::get('plans', 'DashboardController@planUpgrade')->name('plans.index');
            Route::get('orders', 'DashboardController@ordersListing')->name('orders.index');
            Route::get('plan-requests', 'DashboardController@planRequets')->name('plan_request.index');
            Route::get('plans/features?tab=existing-requests', 'DashboardController@addonFeatures')->name('section_request.index');
            Route::get('order/download/invoice/{order}', 'DashboardController@downloadInvoice')->name('order.download.invoice');

        });

        //Settingd
        Route::resource('settings', 'SystemController');
        Route::post('company-settings', 'SystemController@saveCompanySettings')->name('company-settings');
        Route::get('company-setting', 'SystemController@companyIndex')->name('company.setting');
        Route::post('business-setting', 'SystemController@saveBusinessSettings')->name('business.setting');
        Route::post('system-settings', 'SystemController@saveSystemSettings')->name('system.settings');
        Route::post('reset-permissions', 'SystemController@resetPermissions')->name('settings.reset-permissions');
        Route::post('invoice/template/settings/store', 'SystemController@invoiceTemplateStore')->name('invoice.template.settings.store');
        Route::post('letter-pad/template/settings/store', 'SystemController@letterPadTemplateStore')->name('letter-pad.template.settings.store');
        Route::post('estimate/template/settings/store', 'SystemController@estimateTemplateStore')->name('estimate.template.settings.store');
        Route::post('chatgptkey', 'SystemController@chatgptkey')->name('settings.chatgptkey');

        //End Settings

        Route::group(
            [
                'middleware' => [
                    'auth',
                    'XSS',
                ],
            ],
            function () {
                Route::get('/expenses/import', [ImportController::class, 'createImport'])->name('expense.createImport');
                Route::post('/expenses/import', [ImportController::class, 'store'])->name('expense.import');
                Route::get('/tenants/import', [ImportController::class, 'createTenantsImport'])->name('tenants.createImport');
                Route::post('/tenants/import', [ImportController::class, 'storeTenants'])->name('tenants.import');
                Route::get('/properties/import', [ImportController::class, 'createPropertiesImport'])->name('properties.createImport');
                Route::post('/properties/import', [ImportController::class, 'storeProperties'])->name('properties.import');
                Route::get('/units/import', [ImportController::class, 'createUnitsImport'])->name('units.createImport');
                Route::post('/units/import', [ImportController::class, 'storeUnits'])->name('units.import');
                Route::post('/tenant/{tenantId}/cancel', [TenantController::class, 'cancelTenant'])->name('tenant.cancel');

                Route::get('tenant/{tenant}/renew', [TenantController::class, 'showRenewForm'])->name('tenant.renew');
                Route::put('tenant/{tenant}/renew', [TenantController::class, 'renew'])->name('tenant.renew.submit');

                Route::post('/tenant/{tenantId}/active', [TenantController::class, 'activateTenant'])->name('tenant.activate');
                Route::post('/tenant/{tenantId}/case', [TenantController::class, 'caseTenant'])->name('tenant.case');


                Route::get('tenant/{tenant}/renew', [TenantController::class, 'showRenewForm'])->name('tenant.renew');
                Route::put('tenant/{tenant}/renew', [TenantController::class, 'renew'])->name('tenant.renew.submit');

                Route::get('/renew-report/{id}/download', [TenantController::class, 'downloadRenewReport'])->name('renew-report.download');

                Route::get('/lease-report/{id}/download', [TenantController::class, 'downloadLeaseReport'])->name('lease-report.download');
            }
        );


        //report
        Route::group(
            [
                'middleware' => [
                    'auth',
                    'XSS',
                ],
            ],
            function () {
                Route::get('reports/invoice', [ReportController::class, 'invoiceIndex'])->name('report.invoices.index');
                Route::get('reports/deposit/payment', [ReportController::class, 'depositPaymentIndex'])->name('report.deposit.payments.index');

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
    }

);
