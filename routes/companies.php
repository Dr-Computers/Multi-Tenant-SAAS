<?php


use App\Http\Controllers\Company\Realestate\InvoiceController;
use App\Http\Controllers\Company\Realestate\OtherInvoiceController;
use App\Http\Controllers\Company\Realestate\PropertyController;
use App\Http\Controllers\Company\BankAccountController;
use App\Http\Controllers\Company\Realestate\PaymentController;
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
        Route::get('profile', 'DashboardController@profile')->name('profile');
        Route::post('profile', 'DashboardController@editprofile')->name('profile.update');
        Route::post('password', 'DashboardController@updatePassword')->name('profile.update.password');




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

            Route::resource('maintainers', 'MaintenanceController')->names('maintainers');
            Route::get('maintainers/{user}/reset-password', 'MaintenanceController@resetPasswordForm')->name('maintainers.reset.form');
            Route::post('maintainers/{user}/reset-password', 'MaintenanceController@resetPassword')->name('maintainers.reset.update');
            Route::get('maintainers/create/documents/{user_id}', 'MaintenanceController@createDocuments')->name('maintainers.create-documents');
            Route::post('maintainers/create/documents/{user_id}', 'MaintenanceController@uploadDocuments')->name('maintainers.upload-documents');
            Route::delete('maintainers/documents/delete/{document}', 'MaintenanceController@deleteDocument')->name('maintainers.documents.destroy');

            Route::get('maintenance-requests/units/{id}', 'MaintenanceRequestController@getUnits')->name('maintenance-requests.units');

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
                Route::get('/invoice-payments', [PaymentController::class, 'index'])->name('invoice.payments.index');
                Route::get('/payments/choose', [PaymentController::class, 'choosePayment'])->name('payments.choose');
                Route::get('/invoice-payments/create', [PaymentController::class, 'create'])->name('invoice.payments.create');
                Route::get('unit/{pid}/invoice', [InvoiceController::class, 'getUnitinvoice'])->name('unit.invoice');
                Route::get('/payment/{id}/cheque', [PaymentController::class, 'getChequeDetails'])->name('payments.cheque');
                Route::post('invoice/due-amount', [PaymentController::class, 'getDueAmount'])->name('invoice.due.amount');

                // Route::get('payment/tenant/{pid}/invoice', [InvoicePaymentController::class, 'getInvoices'])->name('tenant.invoices');
                // Route::get('/payable', [PayableController::class, 'index'])->name('payable.index');
                // Route::get('/payable/create', [PayableController::class, 'create'])->name('payable.create');
                // Route::get('/payable/edit/{id}', [PayableController::class, 'edit'])->name('payable.edit');
                // Route::post('/payable/store', [PayableController::class, 'store'])->name('payable.store');
                // Route::put('/payable/{payable}', [PayableController::class, 'update'])->name('payable.update');
                // Route::delete('payable/{payable}/destroy', [PayableController::class, 'destroy'])->name('payable.destroy');
                // Route::get('payable/{payable}/download', [PayableController::class, 'download'])->name('payable.download');
            });

            // Bank Accounts
            Route::get('/bank-account/details', [BankAccountController::class, 'getAccountDetails'])->name('bank-account.fetchdetails');
            Route::resource('bank-accounts', BankAccountController::class);
        });

        Route::group([
            'prefix' => 'tickets',
            'as' => 'tickets.',
        ], function () {
            Route::resource('/', 'SupportTicketController');
            Route::get('view/{company_id}/{ticket_no}', 'SupportTicketController@view')->name('view');
            Route::post('reply/{ticket_no}', 'SupportTicketController@sendreply')->name('reply');
        });
        
       
        

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
    }
);
