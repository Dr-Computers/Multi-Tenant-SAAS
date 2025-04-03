<?php

use App\Http\Controllers\TapPaymentController;
use App\Models\Utility;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Comapny\DashboardController;
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


Route::group(
    [
        'prefix' => 'company',  // URL prefix
        'as' => 'company.',     // Name prefix
        'middleware' => [
            'auth',       // Ensures user is logged in
            'XSS',        // Custom middleware for XSS prevention
            'revalidate', // Prevents back button after logout
        ],
    ],
    function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['XSS', 'revalidate']);

        Route::get('login/{lang}', [AuthenticatedSessionController::class, 'showCustomerLoginLang'])->name('login.lang')->middleware(['XSS']);
        Route::get('login', [AuthenticatedSessionController::class, 'showCustomerLoginForm'])->name('login')->middleware(['XSS']);
        Route::post('login', [AuthenticatedSessionController::class, 'customerLogin'])->name('login.store')->middleware(['XSS']);


        Route::get('/password/resets/{lang?}', [AuthenticatedSessionController::class, 'showCustomerLinkRequestForm'])->name('change.langPass');
        Route::post('/password/email', [AuthenticatedSessionController::class, 'postCustomerEmail'])->name('password.email');

        Route::get('reset-password/{token}', [AuthenticatedSessionController::class, 'getCustomerPassword'])->name('reset.password')->middleware(['XSS']);
        Route::get('reset-password', [AuthenticatedSessionController::class, 'updateCustomerPassword'])->name('password.reset');


        //================================= Retainer  ====================================//
        Route::get('retainer', [RetainerController::class, 'customerRetainer'])->name('retainer')->middleware(['auth:customer', 'XSS']);
        Route::get('retainer/{id}/show', [RetainerController::class, 'customerRetainerShow'])->name('retainer.show')->middleware(['auth:customer', 'XSS']);
        Route::get('retainer/{id}/send', [RetainerController::class, 'customerRetainerSend'])->name('retainer.send')->middleware(['auth:customer', 'XSS']);

        Route::post('retainer/{id}/send/mail', [RetainerController::class, 'customerRetainerSendMail'])->name('retainer.send.mail')->middleware(['auth:customer', 'XSS']);
        // Route::get('dashboard', [CustomerController::class, 'dashboard'])->name('dashboard')->middleware(['auth:customer', 'XSS']);


        Route::get('invoice', [InvoiceController::class, 'customerInvoice'])->name('invoice')->middleware(['auth:customer', 'XSS']);
        Route::get('/invoice/pay/{invoice}', [InvoiceController::class, 'payinvoice'])->name('pay.invoice')->middleware(['XSS']);

        // Route::get('/retainer/pay/{retainer}', [RetainerController::class, 'payretainer'])->name('pay.retainerpay')->middleware(['XSS']);
        Route::get('proposal', [ProposalController::class, 'customerProposal'])->name('proposal')->middleware(['auth:customer', 'XSS']);

        Route::get('proposal/{id}/show', [ProposalController::class, 'customerProposalShow'])->name('proposal.show')->middleware(['auth:customer', 'XSS']);
        Route::get('invoicesend//{id}', [InvoiceController::class, 'customerInvoiceSend'])->name('invoice.send')->middleware(['auth:customer', 'XSS']);
        Route::post('invoice/{id}/send/mail', [InvoiceController::class, 'customerInvoiceSendMail'])->name('invoice.send.mail')->middleware(['auth:customer', 'XSS']);


        Route::get('invoice/{id}/show', [InvoiceController::class, 'customerInvoiceShow'])->name('invoice.show')->middleware(['auth:customer', 'XSS']);

        // Route::post('invoice/{id}/payment', [StripePaymentController::class, 'addpayment'])->name('invoice.payment')->middleware(['auth:customer', 'XSS']);
        // Route::post('retainer/{id}/payment', [StripePaymentController::class, 'addretainerpayment'])->name('retainer.payment')->middleware(['auth:customer', 'XSS']);


        Route::get('payment', [CustomerController::class, 'payment'])->name('payment')->middleware(['auth:customer', 'XSS']);
        Route::get('transaction', [CustomerController::class, 'transaction'])->name('transaction')->middleware(['auth:customer', 'XSS']);
        Route::post('logout', [CustomerController::class, 'customerLogout'])->name('logout')->middleware(['auth:customer', 'XSS']);


        Route::get('profile', [CustomerController::class, 'profile'])->name('profile')->middleware(['auth:customer', 'XSS']);
        Route::post('update-profile', [CustomerController::class, 'editprofile'])->name('update.profile')->middleware(['auth:customer', 'XSS']);
        Route::post('billing-info', [CustomerController::class, 'editBilling'])->name('update.billing.info')->middleware(['auth:customer', 'XSS']);


        Route::post('shipping-info', [CustomerController::class, 'editShipping'])->name('update.shipping.info')->middleware(['auth:customer', 'XSS']);
        Route::post('change.password', [CustomerController::class, 'updatePassword'])->name('update.password')->middleware(['auth:customer', 'XSS']);
        Route::get('change-language/{lang}', [CustomerController::class, 'changeLanquage'])->name('change.language')->middleware(['auth:customer', 'XSS']);


        //================================= contract ====================================//

        Route::resource('contract', ContractController::class)->middleware(['auth:customer', 'revalidate']);

        Route::post('contract/{id}/description', [ContractController::class, 'descriptionStore'])->name('contract.description.store')->middleware(['auth:customer', 'XSS']);
        Route::post('contract/{id}/file', [ContractController::class, 'fileUpload'])->name('contract.file.upload')->middleware(['auth:customer', 'XSS']);
        Route::post('/contract/{id}/comment', [ContractController::class, 'commentStore'])->name('comment.store')->middleware(['auth:customer', 'XSS']);


        Route::post('/contract/{id}/note', [ContractController::class, 'noteStore'])->name('contract.note.store')->middleware(['auth:customer', 'XSS']);
        Route::get('contract/pdf/{id}', [ContractController::class, 'pdffromcontract'])->name('contract.download.pdf')->middleware(['auth:customer', 'XSS']);
        Route::get('contract/{id}/get_contract', [ContractController::class, 'printContract'])->name('get.contract')->middleware(['auth:customer', 'XSS']);


        Route::get('/signature/{id}', [ContractController::class, 'signature'])->name('signature')->middleware(['auth:customer', 'XSS']);
        Route::post('/signaturestore', [ContractController::class, 'signatureStore'])->name('signaturestore')->middleware(['auth:customer', 'XSS']);
        Route::get('contract/pdf/{id}', [ContractController::class, 'pdffromcontract'])->name('contract.download.pdf')->middleware(['auth:customer', 'XSS']);


        Route::delete('/contract/{id}/file/delete/{fid}', [ContractController::class, 'fileDelete'])->name('contract.file.delete')->middleware(['auth:customer', 'XSS']);
        Route::get('/contract/{id}/comment', [ContractController::class, 'commentDestroy'])->name('comment.destroy')->middleware(['auth:customer', 'XSS']);
        Route::get('/contract/{id}/note', [ContractController::class, 'noteDestroy'])->name('contract.note.destroy')->middleware(['auth:customer', 'XSS']);
        Route::post('/contract_status_edit/{id}', [ContractController::class, 'contract_status_edit'])->name('contract.status')->middleware(['auth:customer', 'XSS']);



        //================================= Invoice Payment Gateways  ====================================//
        Route::post('/paymentwall', [PaymentWallPaymentController::class, 'invoicepaymentwall'])->name('invoice.paymentwallpayment')->middleware(['XSS']);

        Route::post('{id}/invoice-with-paypal', [PaypalController::class, 'customerPayWithPaypal'])->name('invoice.with.paypal')->middleware(['XSS', 'revalidate']);
        Route::get('{id}/get-payment-status/{amount}', [PaypalController::class, 'customerGetPaymentStatus'])->name('get.payment.status')->middleware(['XSS', 'revalidate']);


        Route::post('{id}/pay-with-paypal', [PaypalController::class, 'customerretainerPayWithPaypal'])->name('pay.with.paypal')->middleware(['XSS', 'revalidate']);
        Route::get('{id}/{amount}/get-retainer-payment-status', [PaypalController::class, 'customerGetRetainerPaymentStatus'])->name('get.retainer.payment.status')->middleware(['XSS', 'revalidate']);

        Route::post('invoice/{id}/payment', [StripePaymentController::class, 'addpayment'])->name('invoice.payment')->middleware(['XSS', 'revalidate']);


        Route::post('/retainer-pay-with-paystack', [PaystackPaymentController::class, 'RetainerPayWithPaystack'])->name('retainer.pay.with.paystack')->middleware(['XSS:customer']);
        Route::get('/retainer/paystack/{retainer_id}/{amount}/{pay_id}', [App\Http\Controllers\PaystackPaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.paystack')->middleware(['XSS:customer']);

        Route::post('/invoice-pay-with-paystack', [PaystackPaymentController::class, 'invoicePayWithPaystack'])->name('invoice.pay.with.paystack')->middleware(['XSS', 'revalidate']);
        Route::get('/invoice/paystack/{invoice_id}/{amount}/{pay_id}', [App\Http\Controllers\PaystackPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.paystack')->middleware(['XSS', 'revalidate']);



        Route::post('/retainer-pay-with-flaterwave', [FlutterwavePaymentController::class, 'retainerPayWithFlutterwave'])->name('retainer.pay.with.flaterwave')->middleware(['XSS', 'revalidate']);
        Route::get('/retainer/flaterwave/{txref}/{retainer_id}', [App\Http\Controllers\FlutterwavePaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.flaterwave')->middleware(['XSS', 'revalidate',]);

        Route::post('/invoice-pay-with-flaterwave', [FlutterwavePaymentController::class, 'invoicePayWithFlutterwave'])->name('invoice.pay.with.flaterwave')->middleware(['XSS', 'revalidate']);
        Route::get('/invoice/flaterwave/{txref}/{invoice_id}', [App\Http\Controllers\FlutterwavePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.flaterwave')->middleware(['XSS', 'revalidate',]);


        Route::post('/retainer-pay-with-razorpay', [RazorpayPaymentController::class, 'retainerPayWithRazorpay'])->name('retainer.pay.with.razorpay')->middleware(['XSS', 'revalidate']);
        Route::get('/retainer/razorpay/{amount}/{retainer_id}', [App\Http\Controllers\RazorpayPaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.razorpay')->middleware(['XSS', 'revalidate',]);

        Route::post('/invoice-pay-with-razorpay', [RazorpayPaymentController::class, 'invoicePayWithRazorpay'])->name('invoice.pay.with.razorpay')->middleware(['XSS', 'revalidate']);
        Route::get('/invoice/razorpay/{amount}/{invoice_id}', [App\Http\Controllers\RazorpayPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.razorpay')->middleware(['XSS', 'revalidate',]);

        Route::post('/retainer-pay-with-paytm', [PaytmPaymentController::class, 'retainerPayWithPaytm'])->name('retainer.pay.with.paytm')->middleware(['XSS:customer']);



        Route::post('/invoice-pay-with-paytm', [PaytmPaymentController::class, 'invoicePayWithPaytm'])->name('invoice.pay.with.paytm')->middleware(['XSS:customer']);
        Route::post('/invoice/paytm/{invoice}/{amount}', [App\Http\Controllers\PaytmPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.paytm')->middleware(['XSS:customer']);



        Route::post('/retainer-pay-with-mercado', [MercadoPaymentController::class, 'retainerPayWithMercado'])->name('retainer.pay.with.mercado')->middleware(['XSS:customer']);
        Route::any('/retainer/mercado/{retainer}', [MercadoPaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.mercado')->middleware(['XSS', 'revalidate']);

        Route::post('/invoice-pay-with-mercado', [MercadoPaymentController::class, 'invoicePayWithMercado'])->name('invoice.pay.with.mercado')->middleware(['XSS', 'revalidate']);
        Route::any('/invoice/mercado/{invoice}', [MercadoPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.mercado')->middleware(['XSS', 'revalidate']);


        Route::post('/retainer-pay-with-mollie', [MolliePaymentController::class, 'retainerPayWithMollie'])->name('retainer.pay.with.mollie')->middleware(['XSS', 'revalidate']);

        Route::post('/invoice-pay-with-mollie', [MolliePaymentController::class, 'invoicePayWithMollie'])->name('invoice.pay.with.mollie')->middleware(['XSS', 'revalidate']);
        Route::get('/invoice/mollie/{invoice}/{amount}', [MolliePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.mollie')->middleware(['XSS', 'revalidate']);


        Route::post('/retainer-pay-with-skrill', [SkrillPaymentController::class, 'retainerPayWithSkrill'])->name('retainer.pay.with.skrill')->middleware(['XSS', 'revalidate']);
        Route::get('/retainer/skrill/{retainer}/{amount}', [SkrillPaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.skrill')->middleware(['XSS', 'revalidate']);


        Route::post('/invoice-pay-with-skrill', [SkrillPaymentController::class, 'invoicePayWithSkrill'])->name('invoice.pay.with.skrill')->middleware(['XSS', 'revalidate']);
        Route::get('/invoice/skrill/{invoice}/{amount}', [SkrillPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.skrill')->middleware(['XSS', 'revalidate']);

        Route::post('/retainer-pay-with-coingate', [CoingatePaymentController::class, 'retainerPayWithCoingate'])->name('retainer.pay.with.coingate')->middleware(['XSS', 'revalidate']);
        Route::get('/retainer/coingate/{retainer}/{amount}', [CoingatePaymentController::class, 'getRetainerPaymentStatus'])->name('retainer.coingate')->middleware(['XSS', 'revalidate']);

        Route::post('/invoice-pay-with-coingate', [CoingatePaymentController::class, 'invoicePayWithCoingate'])->name('invoice.pay.with.coingate')->middleware(['XSS', 'revalidate']);
        Route::get('/invoice/coingate/{invoice}/{amount}', [CoingatePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.coingate')->middleware(['XSS', 'revalidate']);
    }
);
