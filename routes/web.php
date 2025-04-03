<?php

use App\Http\Controllers\TapPaymentController;
use App\Models\Utility;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductServiceController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\VenderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RetainerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DebitNoteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PaystackPaymentController;
use App\Http\Controllers\FlutterwavePaymentController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\MercadoPaymentController;
use App\Http\Controllers\MolliePaymentController;
use App\Http\Controllers\SkrillPaymentController;
use App\Http\Controllers\CoingatePaymentController;
use App\Http\Controllers\PaymentWallPaymentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\PaytmPaymentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ToyyibpayController;
use App\Http\Controllers\PayFastController;
use App\Http\Controllers\UsersLogController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\NotificationTemplatesController;
use App\Http\Controllers\BankTransferController;
use App\Http\Controllers\ProductServiceCategoryController;
use App\Http\Controllers\AiTemplateController;
use App\Http\Controllers\IyzipayController;
use App\Http\Controllers\SspayController;
use App\Http\Controllers\PaytabController;
use App\Http\Controllers\BenefitPaymentController;
use App\Http\Controllers\CashfreeController;
use App\Http\Controllers\AamarpayController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthorizeNetController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ChartOfAccountTypeController;
use App\Http\Controllers\PaytrController;
use App\Http\Controllers\YooKassaController;
use App\Http\Controllers\XenditPaymentController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PayHereController;
use App\Http\Controllers\NepalstePaymnetController;
use App\Http\Controllers\FedapayController;
use App\Http\Controllers\CinetPayController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\KhaltiPaymentController;
use App\Http\Controllers\OzowPaymentController;
use App\Http\Controllers\PaiementProController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductServiceUnitController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\ReferralProgramController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransferController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


require __DIR__ . '/auth.php';


Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice')->middleware('auth');

Route::get('/verify-email/{lang?}', [EmailVerificationPromptController::class, 'showVerifyForm'])->name('verification.notice.lang');

Route::get('/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->name('verification.verify')->middleware('auth');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('verification.send');

Route::get('/register/{ref?}/{lang?}', [RegisteredUserController::class, 'showRegistrationForm'])->name('register');

Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

Route::get('/login/{lang?}', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');

Route::get('/password/resets/{lang?}', [AuthenticatedSessionController::class, 'showLinkRequestForm'])->name('langPass');

Route::get('/', [Controller::class, 'dashboard'])->name('dashboard')->middleware(['XSS', 'revalidate']);


Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard')->middleware(['auth', 'XSS', 'revalidate']);


Route::get('/bill/pay/{bill}', [BillController::class, 'paybill'])->name('pay.billpay');
Route::get('/proposal/pay/{proposal}', [ProposalController::class, 'payproposal'])->name('pay.proposalpay');
Route::get('/retainer/pay/{retainer}', [RetainerController::class, 'payretainer'])->name('pay.retainerpay');
Route::get('/invoice/pay/{invoice}', [InvoiceController::class, 'payinvoice'])->name('pay.invoice');
Route::get('bill/pdf/{id}', [BillController::class, 'bill'])->name('bill.pdf')->middleware(['XSS', 'revalidate']);
Route::get('proposal/pdf/{id}', [ProposalController::class, 'proposal'])->name('proposal.pdf')->middleware(['XSS', 'revalidate']);
Route::get('retainer/pdf/{id}', [RetainerController::class, 'retainer'])->name('retainer.pdf')->middleware(['XSS', 'revalidate',]);
Route::get('invoice/pdf/{id}', [InvoiceController::class, 'invoice'])->name('invoice.pdf')->middleware(['XSS', 'revalidate']);

Route::get('export/Proposal', [ProposalController::class, 'export'])->name('proposal.export');
Route::get('export/invoice', [InvoiceController::class, 'export'])->name('invoice.export');
Route::get('export/Bill', [BillController::class, 'export'])->name('Bill.export');
Route::get('export/retainer', [RetainerController::class, 'export'])->name('retainer.export');





//================================= Notification  ====================================//

Route::any('/cookie-consent', [SystemController::class, 'CookieConsent'])->name('cookie-consent');
Route::get('generate/{template_name}', [AiTemplateController::class, 'create'])->name('generate');
Route::post('generate/keywords/{id}', [AiTemplateController::class, 'getKeywords'])->name('generate.keywords');
Route::post('generate/response', [AiTemplateController::class, 'AiGenerate'])->name('generate.response');

Route::get('grammar/{template}', [AiTemplateController::class, 'grammar'])->name('grammar');
Route::post('grammar/response', [AiTemplateController::class, 'grammarProcess'])->name('grammar.response');


// cache settings :-

Route::get('/config-cache', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('optimize:clear');
    return redirect()->back()->with('success', 'Clear Cache successfully.');
});

Route::group(['middleware' => ['verified']], function () {

    Route::get('invoice/{id}/show', [InvoiceController::class, 'customerInvoiceShow'])->name('customer.invoice.show')->middleware(['auth:customer', 'XSS', 'revalidate',]);

    Route::get('users/{id}/login-with-company', [UserController::class, 'LoginWithCompany'])->name('login.with.company');
    Route::get('login-with-company/exit', [UserController::class, 'ExitCompany'])->name('exit.company');


    //================================= Contract Type  ====================================//


    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS',
                'revalidate',
            ],
        ],
        function () {
            Route::resource('contractType', ContractTypeController::class)->middleware(['auth', 'XSS']);
        }
    );

    //================================= Contract  ====================================//
    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS',
                'revalidate',
            ],
        ],
        function () {
            // Route::get('contract/{id}/description', 'ContractController@description')->name('contract.description');
            // Route::get('contract/grid', 'ContractController@grid')->name('contract.grid');
            Route::resource('contract', ContractController::class)->middleware(['auth', 'XSS']);

            Route::get('contract/duplicate/{id}', [ContractController::class, 'duplicate'])->name('contract.duplicate')->middleware(['auth', 'XSS']);
            Route::put('contract/duplicatecontract/{id}', [ContractController::class, 'duplicatecontract'])->name('contract.duplicatecontract')->middleware(['auth', 'XSS']);
            Route::post('contract/{id}/description', [ContractController::class, 'descriptionStore'])->name('contract.description.store')->middleware(['auth', 'XSS']);
            Route::post('contract/{id}/file', [ContractController::class, 'fileUpload'])->name('contract.file.upload')->middleware(['auth', 'XSS']);
            Route::get('/contract/{id}/file/{fid}', [ContractController::class, 'fileDownload'])->name('contract.file.download')->middleware(['auth', 'XSS']);
            Route::delete('/contract/{id}/file/delete/{fid}', [ContractController::class, 'fileDelete'])->name('contract.file.delete')->middleware(['auth', 'XSS']);
            Route::post('/contract/{id}/comment', [ContractController::class, 'commentStore'])->name('comment.store')->middleware(['auth', 'XSS']);
            Route::get('/contract/{id}/comment', [ContractController::class, 'commentDestroy'])->name('comment.destroy')->middleware(['auth', 'XSS']);
            Route::post('/contract/{id}/note', [ContractController::class, 'noteStore'])->name('contract.note.store')->middleware(['auth', 'XSS']);
            Route::get('contract/{id}/note', [ContractController::class, 'noteDestroy'])->name('contract.note.destroy')->middleware(['auth', 'XSS']);
            Route::get('contract/pdf/{id}', [ContractController::class, 'pdffromcontract'])->name('contract.download.pdf')->middleware(['auth', 'XSS']);
            Route::get('contract/{id}/get_contract', [ContractController::class, 'printContract'])->name('get.contract')->middleware(['auth', 'XSS']);
            Route::get('/signature/{id}', [ContractController::class, 'signature'])->name('signature')->middleware(['auth', 'XSS']);
            Route::post('/signaturestore', [ContractController::class, 'signatureStore'])->name('signaturestore')->middleware(['auth', 'XSS']);
            Route::get('/contract/{id}/mail', [ContractController::class, 'sendmailContract'])->name('send.mail.contract')->middleware(['auth', 'XSS']);
            // Route::post('/contract_status_edit/{id}', [ContractController::class,'contract_status_edit'])->name('contract.status')->middleware(['auth','XSS']);




        }
    );

    //================================= Retainers  ====================================//



    Route::post('retainer/product', [RetainerController::class, 'product'])->name('retainer.product')->middleware(['auth', 'XSS']);



    Route::get('retainer/{id}/sent', [RetainerController::class, 'sent'])->name('retainer.sent');
    Route::get('retainer/{id}/status/change', [RetainerController::class, 'statusChange'])->name('retainer.status.change');
    Route::get('retainer/{id}/resent', [RetainerController::class, 'resent'])->name('retainer.resent');
    Route::get('retainer/{id}/duplicate', [RetainerController::class, 'duplicate'])->name('retainer.duplicate');
    Route::get('retainer/{id}/payment', [RetainerController::class, 'payment'])->name('retainer.payment');
    Route::post('retainer/{id}/payment/create', [RetainerController::class, 'createPayment'])->name('retainer.payment.create');
    Route::get('retainer/{id}/payment/reminder', [RetainerController::class, 'paymentReminder'])->name('retainer.payment.reminder');
    Route::post('retainer/{id}/payment/{pid}/destroy', [RetainerController::class, 'paymentDestroy'])->name('retainer.payment.destroy');
    Route::get('retainer/{id}/convert', [RetainerController::class, 'convert'])->name('retainer.convert');
    Route::post('retainer/product/destroy', [RetainerController::class, 'productDestroy'])->name('retainer.product.destroy');
    Route::get('retainer/items/', [RetainerController::class, 'items'])->name('retainer.items');



    Route::resource('retainer', RetainerController::class)->except('create')->middleware(['auth', 'XSS']);

    Route::get('retainer/create/{cid}', [RetainerController::class, 'create'])->name('retainer.create')->middleware(['auth', 'XSS']);

    // Route::get('/retainer/pay/{retainer}', [RetainerController::class,'payretainer'])->name('pay.retainerpay')->middleware(['auth:customer','XSS']);


    Route::post('/retainer/template/setting', [RetainerController::class, 'saveRetainerTemplateSettings'])->name('retainer.template.setting')->middleware(['auth', 'XSS']);



    Route::get('/retainer/preview/{template}/{color}', [RetainerController::class, 'previewRetainer'])->name('retainer.preview')->middleware(['auth', 'XSS']);


    //================================= Email Templates  ====================================//
   
    Route::get('user/{id}/plan', [UserController::class, 'upgradePlan'])->name('plan.upgrade')->middleware(['XSS', 'revalidate']);

    Route::get('user/{id}/plan/{pid}', [UserController::class, 'activePlan'])->name('plan.active')->middleware(['XSS', 'revalidate']);
    Route::get('profile', [UserController::class, 'profile'])->name('profile')->middleware(['XSS', 'revalidate']);
    Route::post('edit-profile', [UserController::class, 'editprofile'])->name('update.account')->middleware(['XSS', 'revalidate']);



    Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');
    Route::get('change-language/{lang}', [UserController::class, 'changeMode'])->name('change.mode');


   
    Route::resource('permissions', PermissionController::class)->middleware(['auth', 'XSS', 'revalidate']);


    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {
            Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
            Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
            Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
            Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
            Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
            Route::delete('/lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');

            Route::post('disable-language', [LanguageController::class, 'disableLang'])->name('disablelanguage')->middleware(['auth', 'XSS']);
        }
    );


    Route::get('productservice/index', [ProductServiceController::class, 'index'])->name('productservice.index')->middleware(['auth', 'XSS']);
    Route::get('export/productservice', [ProductServiceController::class, 'export'])->name('productservice.export');
    Route::get('import/productservice/file', [ProductServiceController::class, 'importFile'])->name('productservice.file.import');
    Route::resource('productservice', ProductServiceController::class)->except('index')->middleware(['auth', 'XSS', 'revalidate']);




    //Product Stock
    Route::resource('productstock', ProductStockController::class)->middleware(['auth', 'XSS', 'revalidate',]);



    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::get('customer/{id}/show', [CustomerController::class, 'show'])->name('customer.show');
            Route::ANY('customer/{id}/statement', [CustomerController::class, 'statement'])->name('customer.statement');



            Route::any('customer-reset-password/{id}', [CustomerController::class, 'customerPassword'])->name('customer.reset');
            Route::post('customer-reset-password/{id}', [CustomerController::class, 'customerPasswordReset'])->name('customer.password.update');



            Route::resource('customer', CustomerController::class)->except('show');
        }
    );
    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::get('vender/{id}/show', [VenderController::class, 'show'])->name('vender.show');
            Route::ANY('vender/{id}/statement', [VenderController::class, 'statement'])->name('vender.statement');

            Route::any('vender-reset-password/{id}', [VenderController::class, 'venderPassword'])->name('vender.reset');
            Route::post('vender-reset-password/{id}', [VenderController::class, 'vendorPasswordReset'])->name('vender.password.update');

            Route::resource('vender', VenderController::class)->except('show');
        }
    );

    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::resource('bank-account', BankAccountController::class);
        }
    );
    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::get('transfer/index', [TransferController::class, 'index'])->name('transfer.index');

            Route::resource('transfer', TransferController::class)->except('index');
        }
    );





    Route::resource('product-category', ProductServiceCategoryController::class)->middleware(['auth', 'XSS', 'revalidate']);

    Route::post('product-category/getaccount', [ProductServiceCategoryController::class, 'getAccount'])->name('productServiceCategory.getaccount')->middleware(['auth', 'XSS', 'revalidate']);


    Route::resource('taxes', TaxController::class)->middleware(['auth', 'XSS', 'revalidate']);

    Route::resource('product-unit', ProductServiceUnitController::class)->middleware(['auth', 'XSS', 'revalidate']);


    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {
            Route::get('invoice/{id}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoice.duplicate');
            Route::get('invoice/{id}/shipping/print', [InvoiceController::class, 'shippingDisplay'])->name('invoice.shipping.print');
            Route::get('invoice/{id}/payment/reminder', [InvoiceController::class, 'paymentReminder'])->name('invoice.payment.reminder');

            Route::get('invoice/index', [InvoiceController::class, 'index'])->name('invoice.index');
            Route::post('invoice/product/destroy', [InvoiceController::class, 'productDestroy'])->name('invoice.product.destroy');
            Route::post('invoice/product', [InvoiceController::class, 'product'])->name('invoice.product');

            Route::post('invoice/customer', [InvoiceController::class, 'customer'])->name('invoice.customer');
            Route::get('invoice/{id}/sent', [InvoiceController::class, 'sent'])->name('invoice.sent');
            Route::get('invoice/{id}/resent', [InvoiceController::class, 'resent'])->name('invoice.resent');

            Route::get('invoice/{id}/payment', [InvoiceController::class, 'payment'])->name('invoice.payment');
            Route::post('invoice/{id}/payment', [InvoiceController::class, 'createPayment'])->name('invoice.payment.store');
            Route::post('invoice/{id}/payment/{pid}/destroy', [InvoiceController::class, 'paymentDestroy'])->name('invoice.payment.destroy');
            Route::get('invoice/items', [InvoiceController::class, 'items'])->name('invoice.items');


            Route::resource('invoice', InvoiceController::class)->except('index','create');
            Route::get('invoice/create/{cid}', [InvoiceController::class, 'create'])->name('invoice.create');
        }
    );
    Route::get('/invoices/preview/{template}/{color}', [InvoiceController::class, 'previewInvoice'])->name('invoice.preview');
    Route::post('/invoices/template/setting', [InvoiceController::class, 'saveTemplateSettings'])->name('invoice.template.setting');

    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::get('credit-note', [CreditNoteController::class, 'index'])->name('credit.note');
            Route::get('custom-credit-note', [CreditNoteController::class, 'customCreate'])->name('invoice.custom.credit.note');
            Route::post('custom-credit-note', [CreditNoteController::class, 'customStore'])->name('invoice.custom.credit.note.store');

            Route::get('credit-note/bill', [CreditNoteController::class, 'getinvoice'])->name('invoice.get');
            Route::get('invoice/{id}/credit-note', [CreditNoteController::class, 'create'])->name('invoice.credit.note');
            Route::post('invoice/{id}/credit-note', [CreditNoteController::class, 'store'])->name('invoice.credit.note.store');

            Route::get('invoice/{id}/credit-note/edit/{cn_id}', [CreditNoteController::class, 'edit'])->name('invoice.edit.credit.note');
            Route::post('invoice/{id}/credit-note/edit/{cn_id}', [CreditNoteController::class, 'update'])->name('invoice.update.credit.note');
            Route::delete('invoice/{id}/credit-note/delete/{cn_id}', [CreditNoteController::class, 'destroy'])->name('invoice.delete.credit.note');
        }
    );

    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {
            Route::get('debit-note', [DebitNoteController::class, 'index'])->name('debit.note');
            Route::get('custom-debit-note', [DebitNoteController::class, 'customCreate'])->name('bill.custom.debit.note');
            Route::post('custom-debit-note', [DebitNoteController::class, 'customStore'])->name('bill.custom.debit.note.store');

            Route::get('debit-note/bill', [DebitNoteController::class, 'getbill'])->name('bill.get');
            Route::get('bill/{id}/debit-note', [DebitNoteController::class, 'create'])->name('bill.debit.note');
            Route::post('bill/{id}/debit-note', [DebitNoteController::class, 'store'])->name('bill.debit.note.store');

            Route::get('bill/{id}/debit-note/edit/{cn_id}', [DebitNoteController::class, 'edit'])->name('bill.edit.debit.note');
            Route::post('bill/{id}/debit-note/edit/{cn_id}', [DebitNoteController::class, 'update'])->name('bill.update.debit.note');
            Route::delete('bill/{id}/debit-note/delete/{cn_id}', [DebitNoteController::class, 'destroy'])->name('bill.delete.debit.note');
        }
    );

    Route::get('/bill/preview/{template}/{color}', [BillController::class, 'previewBill'])->name('bill.preview');
    Route::post('/bill/template/setting', [BillController::class, 'saveBillTemplateSettings'])->name('bill.template.setting');


    Route::resource('taxes', TaxController::class)->middleware(['auth', 'XSS', 'revalidate']);

    Route::get('revenue/index', [RevenueController::class, 'index'])->name('revenue.index')->middleware(['XSS', 'revalidate',]);

    Route::resource('revenue', RevenueController::class)->middleware(['auth', 'XSS', 'revalidate'])->except('index');

    // Route::get('bill/pdf/{id}', 'BillController@bill')->name('bill.pdf')->middleware(['XSS','revalidate',]);

    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::get('bill/{id}/duplicate', [BillController::class, 'duplicate'])->name('bill.duplicate');
            Route::get('bill/{id}/shipping/print', [BillController::class, 'shippingDisplay'])->name('bill.shipping.print');
            Route::get('bill/index', [BillController::class, 'index'])->name('bill.index');
            Route::post('bill/product/destroy', [BillController::class, 'productDestroy'])->name('bill.product.destroy');

            Route::post('bill/product', [BillController::class, 'product'])->name('bill.product');
            Route::post('bill/vender', [BillController::class, 'vender'])->name('bill.vender');
            Route::get('bill/{id}/sent', [BillController::class, 'sent'])->name('bill.sent');
            Route::get('bill/{id}/resent', [BillController::class, 'resent'])->name('bill.resent');

            Route::get('bill/{id}/payment', [BillController::class, 'payment'])->name('bill.payment');
            Route::post('bill/{id}/payment', [BillController::class, 'createPayment'])->name('add.bill.payment');
            Route::post('bill/{id}/payment/{pid}/destroy', [BillController::class, 'paymentDestroy'])->name('bill.payment.destroy');
            Route::get('bill/items', [BillController::class, 'items'])->name('bill.items');

            Route::resource('bill', BillController::class)->except('index','create');
            Route::get('bill/create/{cid}', [BillController::class, 'create'])->name('bill.create');
        }
    );

    Route::get('payment/index', [PaymentController::class, 'index'])->name('payment.index')->middleware(['auth', 'XSS', 'revalidate']);

    Route::resource('banktransfer', BankTransferController::class)->middleware(['auth', 'XSS', 'revalidate']);
    Route::post('plan-pay-with-bank', [BankTransferController::class, 'planPayWithbank'])->middleware('XSS', 'auth')->name('plan.pay.with.bank');
    Route::get('/change_status/{id}/{response}', [BankTransferController::class, 'ChangeStatus'])->name('change.status')->middleware(['auth', 'XSS', 'revalidate']);

    // Route::get('/action-status/{id}/{response}', [BankTransferController::class,'invoicechangestatus'])->name('action.status')->middleware(['XSS','revalidate']);
    Route::get('action-status/{id}/{response}', [BankTransferController::class, 'invoicechangestatus'])->name('action.status')->middleware(['XSS', 'revalidate']);

    Route::get('invoice-payment-show/{id}', [BankTransferController::class, 'invoicpaymenteshow'])->name('invoice.payment.show')->middleware(['XSS']);
    Route::delete('invoice-delete/{id}', [BankTransferController::class, 'invoicedestroy'])->name('invoice.delete');

    // Route::delete('order/{id}', [BankTransferController::class,'destroy'])->name('order.destroy');

    Route::get('retainer-payment-show/{id}', [BankTransferController::class, 'retainerpaymenteshow'])->name('retainer.payment.show')->middleware(['XSS']);
    Route::get('retainer-change-status/{id}/{response}', [BankTransferController::class, 'retainerchangestatus'])->name('retainer.change.status')->middleware(['XSS', 'revalidate']);
    Route::delete('retainer-delete/{id}', [BankTransferController::class, 'retainerdestroy'])->name('retainer.delete');

    // Route::delete('order/{id}', [BankTransferController::class,'destroy'])->name('order.destroy');

    Route::resource('payment', PaymentController::class)->except('index')->middleware(['auth', 'XSS', 'revalidate']);

    Route::resource('expenses', ExpenseController::class)->middleware(['auth', 'XSS', 'revalidate']);

    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {
            Route::get('report/transaction', [TransactionController::class, 'index'])->name('transaction.index');
        }
    );

    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {
            Route::get('report/income-summary', [ReportController::class, 'incomeSummary'])->name('report.income.summary');
            Route::get('report/expense-summary', [ReportController::class, 'expenseSummary'])->name('report.expense.summary');
            Route::get('report/income-vs-expense-summary', [ReportController::class, 'incomeVsExpenseSummary'])->name('report.income.vs.expense.summary');


            Route::get('report/tax-summary', [ReportController::class, 'taxSummary'])->name('report.tax.summary');
            // Route::get('report/profit-loss-summary', [ReportController::class, 'profitLossSummary'])->name('report.profit.loss.summary');
            Route::get('report/profit-loss/{view?}/{collapseView?}', [ReportController::class, 'profitLoss'])->name('report.profit.loss');
            Route::get('report/invoice-summary', [ReportController::class, 'invoiceSummary'])->name('report.invoice.summary');


            Route::get('reports-monthly-cashflow', [ReportController::class, 'monthlyCashflow'])->name('report.monthly.cashflow')->middleware(['auth', 'XSS']);
            Route::get('reports-quarterly-cashflow', [ReportController::class, 'quarterlyCashflow'])->name('report.quarterly.cashflow')->middleware(['auth', 'XSS']);


            Route::get('report/bill-summary', [ReportController::class, 'billSummary'])->name('report.bill.summary');
            Route::get('report/product-stock-report', [ReportController::class, 'productStock'])->name('report.product.stock.report');
            Route::get('report/invoice-report', [ReportController::class, 'invoiceReport'])->name('report.invoice');


            Route::get('report/account-statement-report', [ReportController::class, 'accountStatement'])->name('report.account.statement');
            // Route::get('report/balance-sheet', [ReportController::class, 'balanceSheet'])->name('report.balance.sheet');
            Route::get('report/balance-sheet/{view?}/{collapseview?}', [ReportController::class, 'balanceSheet'])->name('report.balance.sheet');
            Route::get('report/ledger', [ReportController::class, 'ledgerSummary'])->name('report.ledger');
            Route::get('report/trial-balance/{view?}', [ReportController::class, 'trialBalanceSummary'])->name('trial.balance');
            Route::post('export/trial-balance', [ReportController::class, 'trialBalanceExport'])->name('trial.balance.export');

            Route::get('report/filter-chart', [ReportController::class, 'getFilteredChartData'])->name('filter.chart.data');
            Route::post('export/profit-loss', [ReportController::class, 'profitLossExport'])->name('profit.loss.export');

            Route::post('export/balance-sheet', [ReportController::class, 'balanceSheetExport'])->name('balance.sheet.export');
        }
    );
    Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon')->middleware(['auth', 'XSS']);

    // Route::get(
    //     '/apply-coupon',
    //     [CouponController::class, 'applyCoupon'],
    // )->name('apply.coupon')->middleware(
    //     [
    //         'auth',
    //         'XSS',
    //     ]
    // );

    Route::resource('coupons', CouponController::class)->middleware(['auth', 'XSS', 'revalidate']);

    Route::group(
        ['middleware' => ['auth', 'XSS', 'revalidate'],],
        function () {

            Route::get('proposal/{id}/status/change', [ProposalController::class, 'statusChange'])->name('proposal.status.change');
            Route::get('proposal/{id}/convert', [ProposalController::class, 'convert'])->name('proposal.convert');
            Route::get('proposal/{id}/duplicate', [ProposalController::class, 'duplicate'])->name('proposal.duplicate');

            Route::post('proposal/product/destroy', [ProposalController::class, 'productDestroy'])->name('proposal.product.destroy');
            Route::post('proposal/customer', [ProposalController::class, 'customer'])->name('proposal.customer');
            Route::post('proposal/product', [ProposalController::class, 'product'])->name('proposal.product');

            Route::get('proposal/items', [ProposalController::class, 'items'])->name('proposal.items');
            Route::get('proposal/{id}/sent', [ProposalController::class, 'sent'])->name('proposal.sent');
            Route::get('proposal/{id}/resent', [ProposalController::class, 'resent'])->name('proposal.resent');
            Route::get('proposal/{id}/convertinvoice', [ProposalController::class, 'convertInvoice'])->name('proposal.convertinvoice');

            Route::resource('proposal', ProposalController::class)->except('create');
            Route::get('proposal/create/{cid}', [ProposalController::class, 'create'])->name('proposal.create');
        }
    );

    Route::get('/proposal/preview/{template}/{color}', [ProposalController::class, 'previewProposal'])->name('proposal.preview');
    Route::post('/proposal/template/setting', [ProposalController::class, 'saveProposalTemplateSettings'])->name('proposal.template.setting');


    //Budget Planner //
    Route::resource('budget', BudgetController::class)->middleware(['auth', 'XSS', 'revalidate']);


    Route::resource('goal', GoalController::class)->middleware(['auth', 'XSS', 'revalidate']);
    Route::resource('account-assets', AssetController::class)->middleware(['auth', 'XSS', 'revalidate']);
    Route::resource('custom-field', CustomFieldController::class)->middleware(['auth', 'XSS', 'revalidate']);


    // Route::post('plan-pay-with-paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal');
    // Route::get('{id}/plan-get-payment-status', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status');
    Route::post('chart-of-account/subtype', [ChartOfAccountController::class, 'getSubType'])->name('charofAccount.subType');



    // UserLogs
    Route::resource('userlogs', UsersLogController::class)->middleware(['auth', 'XSS', 'revalidate']);




    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::resource('chart-of-account', ChartOfAccountController::class);
        }
    );
    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::resource('chart-of-account-type', ChartOfAccountTypeController::class);
        }
    );

    Route::group(
        [
            'middleware' => [
                'auth',
                'XSS', 'revalidate',
            ],
        ],
        function () {

            Route::post('journal-entry/account/destroy', [JournalEntryController::class, 'accountDestroy'])->name('journal.account.destroy');

            Route::delete('journal-entry/journal/destroy/{item_id}', [JournalEntryController::class, 'journalDestroy'])->name('journal.destroy');

            Route::resource('journal-entry', JournalEntryController::class);
        }
    );

    //================================= Plan Payment Gateways  ====================================//
    Route::post('/plan-pay-with-paystack', [PaystackPaymentController::class, 'planPayWithPaystack'])->name('plan.pay.with.paystack')->middleware(['auth', 'XSS',]);
    Route::get('/plan/paystack/{pay_id}/{plan_id}', [PaystackPaymentController::class, 'getPaymentStatus'])->name('plan.paystack')->middleware(['auth', 'XSS',]);

    Route::post('/plan-pay-with-flaterwave', [FlutterwavePaymentController::class, 'planPayWithFlutterwave'])->name('plan.pay.with.flaterwave')->middleware(['auth', 'XSS',]);
    Route::get('/plan/flaterwave/{txref}/{plan_id}', [FlutterwavePaymentController::class, 'getPaymentStatus'])->name('plan.flaterwave')->middleware(['auth', 'XSS',]);

    Route::post('/plan-pay-with-razorpay', [RazorpayPaymentController::class, 'planPayWithRazorpay'])->name('plan.pay.with.razorpay')->middleware(['auth', 'XSS',]);
    Route::get('/plan/razorpay/{txref}/{plan_id}', [RazorpayPaymentController::class, 'getPaymentStatus'])->name('plan.razorpay')->middleware(['auth', 'XSS',]);


    Route::post('/plan-pay-with-paytm', [PaytmPaymentController::class, 'planPayWithPaytm'])->name('plan.pay.with.paytm')->middleware(['auth', 'XSS',]);
    Route::post('/plan/paytm/{plan}/{coupon?}', [PaytmPaymentController::class, 'getPaymentStatus'])->name('plan.paytm')->middleware(['auth', 'XSS',]);


    Route::post('/plan-pay-with-mercado', [MercadoPaymentController::class, 'planPayWithMercado'])->name('plan.pay.with.mercado')->middleware(['auth', 'XSS',]);
    Route::get('/plan/mercado/{plan}', [MercadoPaymentController::class, 'getPaymentStatus'])->name('plan.mercado')->middleware(['auth', 'XSS',]);


    Route::post('/plan-pay-with-mollie', [MolliePaymentController::class, 'planPayWithMollie'])->name('plan.pay.with.mollie')->middleware(['auth', 'XSS',]);
    Route::get('/plan/mollie/{plan}', [MolliePaymentController::class, 'getPaymentStatus'])->name('plan.mollie')->middleware(['auth', 'XSS',]);


    Route::post('/plan-pay-with-skrill', [SkrillPaymentController::class, 'planPayWithSkrill'])->name('plan.pay.with.skrill')->middleware(['auth', 'XSS',]);
    Route::get('/plan/skrill/{plan}', [SkrillPaymentController::class, 'getPaymentStatus'])->name('plan.skrill')->middleware(['auth', 'XSS',]);


    Route::post('/plan-pay-with-coingate', [CoingatePaymentController::class, 'planPayWithCoingate'])->name('plan.pay.with.coingate')->middleware(['auth', 'XSS',]);
    Route::get('/plan/coingate/{plan}/{coupons_id}', [CoingatePaymentController::class, 'getPaymentStatus'])->name('plan.coingate')->middleware(['auth', 'XSS',]);

    Route::post('iyzipay/prepare', [IyziPayController::class, 'initiatePayment'])->name('iyzipay.payment.init');
    Route::post('iyzipay/callback/plan/{id}/{amount}/{coupan_code?}', [IyzipayController::class, 'iyzipayCallback'])->name('iyzipay.payment.callback');

    Route::post('/sspay', [SspayController::class, 'SspayPaymentPrepare'])->name('plan.sspaypayment');
    Route::get('sspay-payment-plan/{plan_id}/{amount}/{couponCode}', [SspayController::class, 'SspayPlanGetPayment'])->middleware(['auth'])->name('plan.sspay.callback');

    Route::post('plan-pay-with-paytab', [PaytabController::class, 'planPayWithpaytab'])->middleware(['auth'])->name('plan.pay.with.paytab');
    Route::any('paytab-success/plan', [PaytabController::class, 'PaytabGetPayment'])->middleware(['auth'])->name('plan.paytab.success');

    Route::any('/payment/benefit', [BenefitPaymentController::class, 'planPayWithbenefit'])->name('plan.pay.with.benefit');
    Route::any('call_back', [BenefitPaymentController::class, 'benefitPlanGetPayment'])->name('plan.benefit.call_back');

    Route::post('plan/cashfree/payments/', [CashfreeController::class, 'plancashfreePayment'])->name('plan.pay.with.cashfree');
    Route::any('cashfree/payments/success', [CashfreeController::class, 'cashfreePaymentSuccess'])->name('cashfreePayment.success');

    Route::post('/aamarpay/payment', [AamarpayController::class, 'aamarpaywithplan'])->name('pay.aamarpay.payment');
    Route::any('/aamarpay/success/{data}', [AamarpayController::class, 'aamarpaysuccess'])->name('pay.aamarpay.success');

    Route::post('/paytr/payment', [PaytrController::class, 'PlanpayWithPaytr'])->name('pay.paytr.payment');
    Route::any('/paytr/success', [PaytrController::class, 'paytrsuccess'])->name('pay.paytr.success');

    Route::post('/plan/yookassa/payment', [YooKassaController::class,'planPayWithYooKassa'])->name('plan.pay.with.yookassa');
    Route::get('/plan/yookassa/{plan}', [YooKassaController::class,'planGetYooKassaStatus'])->name('plan.get.yookassa.status');

    Route::any('/xendit/payment', [XenditPaymentController::class, 'planPayWithXendit'])->name('plan.xendit.payment');
    Route::any('/xendit/payment/status', [XenditPaymentController::class, 'planGetXenditStatus'])->name('plan.xendit.status');

    Route::any('/midtrans', [MidtransController::class, 'planPayWithMidtrans'])->name('plan.get.midtrans');
    Route::any('/midtrans/callback', [MidtransController::class, 'planGetMidtransStatus'])->name('plan.get.midtrans.status');

 
    Route::post('plan-pay-with-paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal')->middleware(['auth', 'XSS', 'revalidate']);
    Route::get('{id}/plan-get-payment-status/{amount}', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status')->middleware(['auth', 'XSS', 'revalidate']);


    Route::any('plan-paiementpro-payment', [PaiementProController::class, 'planPayWithPaiementpro'])->name('plan.pay.with.paiementpro')->middleware(['auth','XSS']);
    Route::any('/plan-paiementpro-status/{plan_id}',  [PaiementProController::class, 'planGetPaiementproStatus'])->name('plan.paiementpro.status')->middleware(['auth','XSS']);

	Route::post('plan-nepalste-payment/', [NepalstePaymnetController::class, 'planPayWithNepalste'])->name('plan.pay.with.nepalste')->middleware(['auth','XSS']);
    Route::get('plan-nepalste-status/',[NepalstePaymnetController::class,'planGetNepalsteStatus'])->name('plan.nepalste.status')->middleware(['auth','XSS']);
    Route::get('plan-nepalste-cancel/',[NepalstePaymnetController::class,'planGetNepalsteCancel'])->name('plan.nepalste.cancel')->middleware(['auth','XSS']);

	Route::any('plan-cinetpay-payment', [CinetPayController::class, 'planPayWithCinetpay'])->name('plan.pay.with.cinetpay')->middleware(['auth','XSS']);
    Route::any('plan-cinetpay-return',  [CinetPayController::class, 'planCinetPayReturn'])->name('plan.cinetpay.return')->middleware(['auth']);
    Route::any('plan-cinetpay-notify',  [CinetPayController::class, 'planCinetPayNotify'])->name('plan.cinetpay.notify')->middleware(['auth','XSS']);

	Route::any('plan-fedapay-payment', [FedapayController::class, 'planPayWithFedapay'])->name('plan.pay.with.fedapay')->middleware(['auth','XSS']);
    Route::any('plan-fedapay-status',  [FedapayController::class, 'planGetFedapayStatus'])->name('plan.fedapay.status')->middleware(['auth','XSS']);

    Route::any('plan-payhere-payment', [PayHereController::class, 'planPayWithPayHere'])->name('plan.pay.with.payhere')->middleware(['auth','XSS']);
    Route::any('plan-payhere-status',  [PayHereController::class, 'planGetPayHereStatus'])->name('plan.payhere.status')->middleware(['auth','XSS']);

    // Tap Payment
    Route::post('plan-pay-with-tap', [TapPaymentController::class, 'planPayWithTap'])->name('plan.pay.with.tap');
    Route::any('plan-get-tap-status/{plan_id}', [TapPaymentController::class, 'planGetTapStatus'])->name('plan.get.tap.status');

    // AuthorizeNet Payment
    Route::post('plan-pay-with-authorizenet', [AuthorizeNetController::class, 'planPayWithAuthorizeNet'])->name('plan.pay.with.authorizenet');
    Route::any('plan-get-authorizenet-status', [AuthorizeNetController::class, 'planGetAuthorizeNetStatus'])->name('plan.get.authorizenet.status');

    // Khalti Payment
    Route::post('plan-pay-with-khalti', [KhaltiPaymentController::class, 'planPayWithKhalti'])->name('plan.pay.with.khalti');
    Route::any('plan-get-khalti-status', [KhaltiPaymentController::class, 'planGetKhaltiStatus'])->name('plan.get.khalti.status');

    //ozow payment
    Route::post('plan-pay-with-ozow', [OzowPaymentController::class, 'planPayWithozow'])->name('plan.pay.with.ozow');
    Route::any('plan-get-ozow-status', [OzowPaymentController::class, 'planGetozowStatus'])->name('plan.get.ozow.status');


   
    // Referral program
    Route::get('referral-program/company', [ReferralProgramController::class, 'companyIndex'])->name('referral-program.company')->middleware(['auth','XSS']);
	Route::resource('referral-program', ReferralProgramController::class)->middleware(['auth','XSS']);
	Route::get('request-amount-sent/{id}', [ReferralProgramController::class, 'requestedAmountSent'])->name('request.amount.sent');
	Route::get('request-amount-cancel/{id}', [ReferralProgramController::class, 'requestCancel'])->name('request.amount.cancel');
	Route::post('request-amount-store/{id}', [ReferralProgramController::class, 'requestedAmountStore'])->name('request.amount.store');
	Route::get('request-amount/{id}/{status}', [ReferralProgramController::class, 'requestedAmount'])->name('amount.request');

    // --------------------------- invoice payments  ---------------------//////


    Route::post('/invoice-pay-with-stripe', [StripePaymentController::class, 'invoicePayWithStripe'])->name('invoice.pay.with.stripe');
    Route::post('{id}/pay-with-paypal', [PaypalController::class, 'clientPayWithPaypal'])->name('client.pay.with.paypal')->middleware(['auth', 'XSS',]);
    Route::get('invoice/pay/pdf/{id}', [InvoiceController::class, 'pdffrominvoice'])->name('invoice.download.pdf');


    Route::get('/retainer/coingate/{retainer}/{amount}', [CoingatePaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.coingate')->middleware(['XSS', 'revalidate']);

    Route::post('import/productservice', [ProductServiceController::class, 'import'])->name('productservice.import');


    Route::get('export/customer', [CustomerController::class, 'export'])->name('customer.export');
    Route::get('import/customer/file', [CustomerController::class, 'importFile'])->name('customer.file.import');
    Route::post('import/customer', [CustomerController::class, 'import'])->name('customer.import');


    Route::get('export/vender', [VenderController::class, 'export'])->name('vender.export');
    Route::get('import/vender/file', [VenderController::class, 'importFile'])->name('vender.file.import');
    Route::post('import/vender', [VenderController::class, 'import'])->name('vender.import');

    Route::get('export/transaction', [TransactionController::class, 'export'])->name('transaction.export');
    Route::get('export/accountstatement', [ReportController::class, 'export'])->name('accountstatement.export');
    Route::get('export/productstock', [ReportController::class, 'stock_export'])->name('productstock.export');
    Route::get('export/revenue/{date}', [RevenueController::class, 'export'])->name('revenue.export');
    Route::get('export/payment/{date}', [PaymentController::class, 'export'])->name('payment.export');


    // //Clear Config cache:
    // Route::get('/config-cache', function() {
    //     $exitCode = Artisan::call('config:cache');
    //     return '<h1>Clear Config cleared</h1>';
    // });
    // Route::get('/config-clear', function() {
    //     $exitCode = Artisan::call('config:clear');
    //     return '<h1>Clear Config cleared</h1>';
    // });

    // ------------------------------------- PaymentWall ------------------------------
    Route::post('/paymentwalls', [PaymentWallPaymentController::class, 'paymentwall'])->name('plan.paymentwallpayment')->middleware(['XSS']);
    Route::post('/plan-pay-with-paymentwall/{plan}', [PaymentWallPaymentController::class, 'planPayWithPaymentWall'])->name('plan.pay.with.paymentwall')->middleware(['XSS']);
    Route::get('/plan/{flag}', [PaymentWallPaymentController::class, 'planeerror'])->name('error.plan.show');

    Route::post('/paymentwall', [PaymentWallPaymentController::class, 'invoicepaymentwall'])->name('invoice.paymentwallpayment')->middleware(['XSS']);
    Route::post('/invoice-pay-with-paymentwall/{plan}', [PaymentWallPaymentController::class, 'planeerror'])->name('invoice.pay.with.paymentwall')->middleware(['XSS']);
    Route::get('/invoices/{flag}/{invoice}', [PaymentWallPaymentController::class, 'invoiceerror'])->name('error.invoice.show');



    Route::get('/retainer/{flag}/{retainer}', [PaymentWallPaymentController::class, 'retainererror'])->name('error.retainer.show')->middleware(['XSS']);

    // Route::get('{id}/{amount}/get-payment-status{slug?}', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status')->middleware(['XSS']);
});

Route::post('{id}/invoice-with-banktransfer', [BankTransferController::class, 'invoicePayWithbank'])->name('invoice.with.banktransfer')->middleware(['XSS', 'revalidate']);
Route::post('{id}/retainer-with-banktransfer', [BankTransferController::class, 'retainerPayWithbank'])->name('retainer.with.banktransfer')->middleware(['XSS', 'revalidate']);

Route::post('retainer/{id}/payment', [StripePaymentController::class, 'addretainerpayment'])->name('retainer.payment')->middleware(['XSS']);
// Route::post('{id}/pay-with-paypal', [PaypalController::class,'customerretainerPayWithPaypal'])->name('pay.with.paypal')->middleware(['XSS', 'revalidate']);

Route::post('/retainer/paytm/{retainer}/{amount}', [PaytmPaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.paytm')->middleware(['XSS']);
Route::get('/retainer/mollie/{invoice}/{amount}', [MolliePaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.mollie')->middleware(['XSS', 'revalidate']);
Route::get('/retainer/skrill/{retainer}/{amount}', [SkrillPaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.skrill')->middleware(['XSS', 'revalidate']);
Route::get('/retainer/coingate/{retainer}/{amount}', [CoingatePaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.coingate')->middleware(['XSS', 'revalidate']);
Route::post('/paymentwall', [PaymentWallPaymentController::class, 'retainerpaymentwall'])->name('retainer.paymentwallpayment')->middleware(['XSS']);
Route::post('/retainer-pay-with-paymentwall/{plan}', [PaymentWallPaymentController::class, 'retainerPayWithPaymentwall'])->name('retainer.pay.with.paymentwall')->middleware(['XSS']);


// ------------------------------------- Toyyibpay ------------------------------
Route::post('/toyyibpay', [ToyyibpayController::class, 'charge'])->name('plan.toyyibpaypayment');
Route::get('/plan-pay-with-toyyibpay/{id}/{status}/{coupon}', [ToyyibpayController::class, 'status'])->name('plan.status');

Route::post('{id}/invoice-with-toyyibpay', [ToyyibpayController::class, 'invoicepaywithtoyyibpay'])->name('invoice.with.toyyibpay');
Route::get('{id}/invoice-toyyibpay-status/{amount}', [ToyyibpayController::class, 'invoicetoyyibpaystatus'])->name('invoice.toyyibpay.status');

Route::post('{id}/pay-with-toyyibpay', [ToyyibpayController::class, 'retainerpaywithtoyyibpay'])->name('pay.with.toyyibpay')->middleware(['XSS', 'revalidate']);
Route::get('{id}/{amount}/get-retainer-payment-status', [ToyyibpayController::class, 'retaineroyyibpaystatus'])->name('retainer.toyyibpay')->middleware(['XSS', 'revalidate']);

// ------------------------------------- PayFast ------------------------------
Route::post('payfast-plan', [PayFastController::class, 'index'])->name('payfast.payment');
Route::get('payfast-plan/{success}', [PayFastController::class, 'success'])->name('payfast.payment.success');

Route::post('invoice-with-payfast', [PayFastController::class, 'invoicePayWithPayFast'])->name('invoice.with.payfast');
Route::get('invoice-payfast-status/{success}', [PayFastController::class, 'invoicepayfaststatus'])->name('invoice.payfast.status');

Route::post('retainer-with-payfast', [PayFastController::class, 'retainerPayWithPayFast'])->name('retainer.with.payfast');
Route::get('retainer-payfast-status/{success}', [PayFastController::class, 'retainerpayfaststatus'])->name('retainer.payfast.status');

Route::post('{id}/invoice-with-iyzipay', [IyzipayController::class, 'invoicePayWithIyziPay'])->name('invoice.with.iyzipay')->middleware(['XSS', 'revalidate']);
Route::post('invoice/iyzipay/callback/{id}/{amount}', [IyzipayController::class, 'iyzipaypaymentCallback'])->name('iyzipay.callback')->middleware(['XSS', 'revalidate']);

Route::post('{id}/retainer-with-iyzipay', [IyzipayController::class, 'retainerPayWithIyziPay'])->name('retainer.with.iyzipay')->middleware(['XSS', 'revalidate']);
Route::post('retainer/iyzipay/callback/{id}/{amount}', [IyzipayController::class, 'retaineriyzipaypaymentCallback'])->name('retainer.iyzipay.callback')->middleware(['XSS', 'revalidate']);

Route::post('/invoice-pay-with-sspay', [SspayController::class, 'invoicepaywithsspaypay'])->name('invoice.pay.with.sspay');
Route::get('/invoice/sspay/{invoice}/{amount}', [SspayController::class, 'getInvoicePaymentStatus'])->name('invoice.sspay');

Route::post('/retainer-pay-with-sspay', [SspayController::class, 'retainerpaywithsspaypay'])->name('retainer.pay.with.sspay');
Route::get('/retainer/sspay/{retainer}/{amount}', [SspayController::class, 'getRetainerPaymentStatus'])->name('retainer.sspay');

Route::post('pay-with-paytab/{id}', [PaytabController::class, 'invoicePayWithpaytab'])->name('invoice.pay.with.paytab');
Route::any('paytab-success/{invoice}/{amount}', [PaytabController::class, 'PaytabGetPaymentStatus'])->name('invoice.paytab');

Route::post('/retainer-pay-with-paytab/{id}', [PaytabController::class, 'retainerpaywithpaytab'])->name('retainer.pay.with.paytab');
Route::get('retainer-paytab-success/{retainer}/{amount}', [PaytabController::class, 'getRetainerPaymentStatus'])->name('retainer.paytab');

Route::post('pay-with-benefit/{id}', [BenefitPaymentController::class, 'invoicePayWithbenefit'])->name('invoice.pay.with.benefit');
Route::any('benefit-success/{invoice}/{amount}', [BenefitPaymentController::class, 'benefitGetPaymentStatus'])->name('invoice.benefit');

Route::post('/retainer-pay-with-benefit/{id}', [BenefitPaymentController::class, 'retainerpaywithbenefit'])->name('retainer.pay.with.benefit');
Route::get('retainer-benefit-success/{retainer}/{amount}', [BenefitPaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.benefit');

Route::post('{id}/cashfree/payments/invoice', [CashfreeController::class, 'invoicepayWithCashfree'])->name('invoice.pay.with.cashfree');
Route::any('invoice-cashfree-success', [CashfreeController::class, 'invoiceCashfreePaymentSuccess'])->name('invoice.cashfreePayment.success');

Route::post('{id}/cashfree/payments/retainer', [CashfreeController::class, 'retainerpayWithCashfree'])->name('retainer.pay.with.cashfree');
Route::any('retainer-cashfree-success', [CashfreeController::class, 'retainerCashfreePaymentSuccess'])->name('retainer.cashfreePayment.success');

Route::post('{id}/aamarpay/payment', [AamarpayController::class, 'invoicepayWithAamarpay'])->name('invoice.pay.aamarpay.payment');
Route::any('aamarpay/success/invoice/{data}', [AamarpayController::class, 'invoiceAamarpaysuccess'])->name('invoice.pay.aamarpay.success');

Route::post('{id}/aamarpay/payment/retainer', [AamarpayController::class, 'retainerpayWithAamarpay'])->name('retainer.pay.aamarpay.payment');
Route::any('aamarpay/success/retainer/{data}', [AamarpayController::class, 'retainerAamarpaysuccess'])->name('retainer.pay.aamarpay.success');

Route::post('{id}/paytr/payment', [PaytrController::class, 'invoicepayWithPaytr'])->name('invoice.pay.paytr.payment');
Route::any('paytr/success/invoice', [PaytrController::class, 'invoicePaytrsuccess'])->name('invoice.pay.paytr.success');

Route::post('{id}/paytr/payment/retainer', [PaytrController::class, 'retainerpayWithPaytr'])->name('retainer.pay.paytr.payment');
Route::any('paytr/success/retainer', [PaytrController::class, 'retainerPaytrsuccess'])->name('retainer.pay.paytr.success');

Route::post('invoice-with-yookassa/{id}', [YooKassaController::class, 'invoicePayWithYookassa'])->name('invoice.with.yookassa');
Route::any('invoice-yookassa-status/', [YooKassaController::class, 'getInvociePaymentStatus'])->name('invoice.yookassa.status');

Route::post('retainer-with-yookassa/{id}', [YooKassaController::class, 'retainerPayWithYookassa'])->name('retainer.with.yookassa');
Route::any('retainer-yookassa-status/', [YooKassaController::class, 'getRetainerPaymentStatus'])->name('retainer.yookassa.status');

Route::any('/invoice-with-xendit', [XenditPaymentController::class, 'invoicePayWithXendit'])->name('invoice.with.xendit');
Route::any('/invoice-xendit-status', [XenditPaymentController::class, 'getInvociePaymentStatus'])->name('invoice.xendit.status');

Route::any('/retainer-with-xendit', [XenditPaymentController::class, 'retainerPayWithXendit'])->name('retainer.with.xendit');
Route::any('/retainer-xendit-status', [XenditPaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.xendit.status');

Route::any('invoice-with-midtrans/', [MidtransController::class, 'invoicePayWithMidtrans'])->name('invoice.with.midtrans');
Route::any('invoice-midtrans-status/', [MidtransController::class, 'getInvociePaymentStatus'])->name('invoice.midtrans.status');

Route::any('retainer-with-midtrans/', [MidtransController::class, 'retainerPayWithMidtrans'])->name('retainer.with.midtrans');
Route::any('retainer-midtrans-status/', [MidtransController::class, 'getRetainerPaymentStatus'])->name('retainer.midtrans.status');


//--------------------------------------------Invoice---------------------------//

// Paiementpro
Route::any('invoice-paiementpro-payment/{id}', [PaiementProController::class, 'invoicePayWithPaiementpro'])->name('invoice.with.paiementpro')->middleware(['XSS']);
Route::any('/invoice-paiementpro-status/{invoice_id}',  [PaiementProController::class, 'invoiceGetPaiementproStatus'])->name('invoice.paiementpro.status')->middleware(['XSS']);

// Nepalste
Route::post('invoice-nepalste-payment/{id}', [NepalstePaymnetController::class, 'invoicePayWithNepalste'])->name('invoice.with.nepalste')->middleware(['XSS']);
Route::get('invoice-nepalste-status/{id}/{amt?}',[NepalstePaymnetController::class,'invoiceGetNepalsteStatus'])->name('invoice.nepalste.status')->middleware(['XSS']);
Route::get('invoice-nepalste-cancel/',[NepalstePaymnetController::class,'invoiceGetNepalsteCancel'])->name('invoice.nepalste.cancel')->middleware(['XSS']);

// Cinetpay
Route::any('invoice-cinetpay-payment/{id}', [CinetPayController::class, 'invoicePayWithCinetPay'])->name('invoice.with.cinetpay')->middleware(['XSS']);
Route::any('invoice-cinetpay-return/{id}/{amt?}',  [CinetPayController::class, 'invoiceCinetPayReturn'])->name('invoice.cinetpay.return')->middleware(['XSS']);
Route::any('invoice-cinetpay-notify/{id?}',  [CinetPayController::class, 'invoiceCinetPayNotify'])->name('invoice.cinetpay.notify')->middleware(['XSS']);

//Fedapay
Route::any('invoice-fedapay-payment/{id}', [FedapayController::class, 'invoicePayWithFedapay'])->name('invoice.with.fedapay')->middleware(['XSS']);
Route::any('invoice-fedapay-status/{id}/{amt?}',  [FedapayController::class, 'invoiceGetFedapayStatus'])->name('invoice.fedapay.status')->middleware(['XSS']);

//Payhere
Route::any('invoice-payhere-payment/{id}', [PayHereController::class, 'invoicePayWithPayHere'])->name('invoice.with.payhere')->middleware(['XSS']);
Route::any('invoice-payhere-status/{id}/{amt?}',  [PayHereController::class, 'invoiceGetPayHereStatus'])->name('invoice.payhere.status')->middleware(['XSS']);

//Tap
Route::any('invoice-tap-payment', [TapPaymentController::class, 'invoicePayWithTap'])->name('invoice.with.tap')->middleware(['XSS']);
Route::any('invoice-tap-status',  [TapPaymentController::class, 'invoiceGetTapStatus'])->name('invoice.tap.status')->middleware(['XSS']);

//AuhorizeNet
Route::any('/invoice-authorizenet-payment', [AuthorizeNetController::class, 'invoicePayWithAuthorizeNet'])->name('invoice.with.authorizenet');
Route::any('/invoice-get-authorizenet-status',[AuthorizeNetController::class,'getInvoicePaymentStatus'])->name('invoice.get.authorizenet.status');

//Khalti
Route::any('/invoice-khalti-payment', [KhaltiPaymentController::class, 'invoicePayWithKhalti'])->name('invoice.with.khalti');
Route::any('/invoice-get-khalti-status',[KhaltiPaymentController::class,'getInvoicePaymentStatus'])->name('invoice.get.khalti.status');

//ozow
Route::any('/invoice-ozow-payment', [OzowPaymentController::class, 'invoicePayWithozow'])->name('invoice.with.ozow');
Route::any('/invoice-get-ozow-status/{id}',[OzowPaymentController::class,'getInvoicePaymentStatus'])->name('invoice.get.ozow.status');

//----------------------------------Retainer--------------------------//

// Paiementpro
Route::any('retainer-paiementpro-payment/{id}', [PaiementProController::class, 'retainerPayWithPaiementpro'])->name('retainer.with.paiementpro')->middleware(['XSS']);
Route::any('/retainer-paiementpro-status/{retainer_id}',  [PaiementProController::class, 'retainerGetPaiementproStatus'])->name('retainer.paiementpro.status')->middleware(['XSS']);

//Nepalste
Route::post('retainer-nepalste-payment/{id}', [NepalstePaymnetController::class, 'retainerPayWithNepalste'])->name('retainer.with.nepalste')->middleware(['XSS']);
Route::get('retainer-nepalste-status/{id}/{amt?}',[NepalstePaymnetController::class,'retainerGetNepalsteStatus'])->name('retainer.nepalste.status')->middleware(['XSS']);
Route::get('retainer-nepalste-cancel/',[NepalstePaymnetController::class,'retainerGetNepalsteCancel'])->name('retainer.nepalste.cancel')->middleware(['XSS']);

//Cinetpay
Route::any('retainer-cinetpay-payment/{id}', [CinetPayController::class, 'retainerPayWithCinetpay'])->name('retainer.with.cinetpay')->middleware(['XSS']);
Route::any('retainer-cinetpay-return/{id}/{amt?}',  [CinetPayController::class, 'retainerCinetPayReturn'])->name('retainer.cinetpay.return')->middleware(['XSS']);
Route::any('retainer-cinetpay-notify/{id?}',  [CinetPayController::class, 'retainerCinetPayNotify'])->name('retainer.cinetpay.notify')->middleware(['XSS']);

//Fedapay
Route::any('retainer-fedapay-payment/{id}', [FedapayController::class, 'retainerPayWithFedapay'])->name('retainer.with.fedapay')->middleware(['XSS']);
Route::any('retainer-fedapay-status/{id}/{amt?}',  [FedapayController::class, 'retainerGetFedapayStatus'])->name('retainer.fedapay.status')->middleware(['XSS']);

//Payhere
Route::any('retainer-payhere-payment/{id}', [PayHereController::class, 'retainerPayWithPayHere'])->name('retainer.with.payhere')->middleware(['XSS']);
Route::any('retainer-payhere-status/{id}/{amt?}',  [PayHereController::class, 'retainerGetPayHereStatus'])->name('retainer.payhere.status')->middleware(['XSS']);


//Tap
Route::any('retainer-tap-payment/', [TapPaymentController::class, 'retainerPayWithTap'])->name('retainer.with.tap')->middleware(['XSS']);
Route::any('retainer-tap-status/',  [TapPaymentController::class, 'retainerGetTapStatus'])->name('retainer.tap.status')->middleware(['XSS']);

//AuthorizeNet
Route::any('/retainer-authorizenet-payment', [AuthorizeNetController::class, 'retainerPayWithAuthorizeNet'])->name('retainer.with.authorizenet');
Route::any('/retainer-get-authorizenet-status',[AuthorizeNetController::class,'getRetainerPaymentStatus'])->name('retainer.get.authorizenet.status');

//Khalti
Route::any('/retainer-khalti-payment', [KhaltiPaymentController::class, 'retainerPayWithKhalti'])->name('retainer.with.khalti');
Route::any('/retainer-get-khalti-status',[KhaltiPaymentController::class,'getRetainerPaymentStatus'])->name('retainer.get.khalti.status');

//ozow
Route::any('/retainer-ozow-payment', [OzowPaymentController::class, 'retainerPayWithozow'])->name('retainer.with.ozow');
Route::any('/retainer-get-ozow-status/{id}',[OzowPaymentController::class,'getRetainerPaymentStatus'])->name('retainer.get.ozow.status');



Route::get('{id}/{amount}/get-retainer-payment-status', [PaypalController::class,'customerGetRetainerPaymentStatus'])->name('get.retainer.payment.status')->middleware(['XSS', 'revalidate']);


require __DIR__ . '/admin.php';
require __DIR__ . '/companies.php';
require __DIR__ . '/customer.php';