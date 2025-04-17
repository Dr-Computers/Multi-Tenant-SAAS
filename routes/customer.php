<?php

// use App\Http\Controllers\TapPaymentController;
// use App\Models\Utility;

// use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Redirect;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\ProductServiceController;
// use App\Http\Controllers\ProposalController;
// use App\Http\Controllers\VenderController;
// use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\RetainerController;
// use App\Http\Controllers\InvoiceController;
// use App\Http\Controllers\CreditNoteController;
// use App\Http\Controllers\BillController;
// use App\Http\Controllers\DebitNoteController;
// use App\Http\Controllers\ReportController;
// use App\Http\Controllers\TransactionController;
// use App\Http\Controllers\SystemController;
// use App\Http\Controllers\PlanRequestController;
// use App\Http\Controllers\EmailTemplateController;
// use App\Http\Controllers\PaypalController;
// use App\Http\Controllers\StripePaymentController;
// use App\Http\Controllers\PaystackPaymentController;
// use App\Http\Controllers\FlutterwavePaymentController;
// use App\Http\Controllers\RazorpayPaymentController;
// use App\Http\Controllers\MercadoPaymentController;
// use App\Http\Controllers\MolliePaymentController;
// use App\Http\Controllers\SkrillPaymentController;
// use App\Http\Controllers\CoingatePaymentController;
// use App\Http\Controllers\PaymentWallPaymentController;
// use App\Http\Controllers\Auth\AuthenticatedSessionController;
// use App\Http\Controllers\Auth\RegisteredUserController;
// use App\Http\Controllers\LanguageController;
// use App\Http\Controllers\ContractController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\ChartOfAccountController;
// use App\Http\Controllers\RevenueController;
// use App\Http\Controllers\PaytmPaymentController;
// use App\Http\Controllers\PaymentController;
// use App\Http\Controllers\Auth\VerifyEmailController;
// use App\Http\Controllers\Auth\EmailVerificationPromptController;
// use App\Http\Controllers\Auth\EmailVerificationNotificationController;
// use App\Http\Controllers\JournalEntryController;
// use App\Http\Controllers\CouponController;
// use App\Http\Controllers\ToyyibpayController;
// use App\Http\Controllers\PayFastController;
// use App\Http\Controllers\UsersLogController;
// use App\Http\Controllers\WebhookController;
// use App\Http\Controllers\NotificationTemplatesController;
// use App\Http\Controllers\BankTransferController;
// use App\Http\Controllers\ProductServiceCategoryController;
// use App\Http\Controllers\AiTemplateController;
// use App\Http\Controllers\IyzipayController;
// use App\Http\Controllers\SspayController;
// use App\Http\Controllers\PaytabController;
// use App\Http\Controllers\BenefitPaymentController;
// use App\Http\Controllers\CashfreeController;
// use App\Http\Controllers\AamarpayController;
// use App\Http\Controllers\AssetController;
// use App\Http\Controllers\AuthorizeNetController;
// use App\Http\Controllers\BankAccountController;
// use App\Http\Controllers\BudgetController;
// use App\Http\Controllers\ChartOfAccountTypeController;
// use App\Http\Controllers\PaytrController;
// use App\Http\Controllers\YooKassaController;
// use App\Http\Controllers\XenditPaymentController;
// use App\Http\Controllers\MidtransController;
// use App\Http\Controllers\CustomFieldController;
// use App\Http\Controllers\GoalController;
// use App\Http\Controllers\PlanController;
// use App\Http\Controllers\PayHereController;
// use App\Http\Controllers\NepalstePaymnetController;
// use App\Http\Controllers\FedapayController;
// use App\Http\Controllers\CinetPayController;
// use App\Http\Controllers\ContractTypeController;
// use App\Http\Controllers\ExpenseController;
// use App\Http\Controllers\KhaltiPaymentController;
// use App\Http\Controllers\OzowPaymentController;
// use App\Http\Controllers\PaiementProController;
// use App\Http\Controllers\PermissionController;
// use App\Http\Controllers\ProductServiceUnitController;
// use App\Http\Controllers\ProductStockController;
// use App\Http\Controllers\ReferralProgramController;
// use App\Http\Controllers\RoleController;
// use App\Http\Controllers\TaxController;
// use App\Http\Controllers\TransferController;

// Route::prefix('vender')->as('vender.')->group(
//     function () {

//         Route::get('login/{lang}', [AuthenticatedSessionController::class, 'showVenderLoginLang'])->name('login.lang')->middleware(['XSS']);
//         Route::get('login', [AuthenticatedSessionController::class, 'showVenderLoginForm'])->name('login')->middleware(['XSS']);
//         Route::post('login', [AuthenticatedSessionController::class, 'VenderLogin'])->name('login.store')->middleware(['XSS']);


//         Route::get('/password/resets/{lang?}', [AuthenticatedSessionController::class, 'showVendorLinkRequestForm'])->name('change.langPass')->middleware(['XSS']);
//         Route::post('/password/email', [AuthenticatedSessionController::class, 'postVendorEmail'])->name('password.email')->middleware(['XSS']);
//         Route::get('reset-password/{token}', [AuthenticatedSessionController::class, 'getVendorPassword'])->name('reset.password')->middleware(['XSS']);
//         Route::post('reset-password', [AuthenticatedSessionController::class, 'updateVendorPassword'])->name('password.reset')->middleware(['XSS']);



//         Route::get('dashboard', [VenderController::class, 'dashboard'])->name('dashboard')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::get('bill', [BillController::class, 'VenderBill'])->name('bill')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::get('bill/{id}/show', [BillController::class, 'venderBillShow'])->name('bill.show')->middleware(['auth:vender', 'XSS', 'revalidate']);


//         Route::get('bill/{id}/send', [BillController::class, 'venderBillSend'])->name('bill.send')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::post('bill/{id}/send/mail', [BillController::class, 'venderBillSendMail'])->name('bill.send.mail')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::get('payment', [VenderController::class, 'payment'])->name('payment')->middleware(['auth:vender', 'XSS', 'revalidate']);


//         Route::get('transaction', [VenderController::class, 'transaction'])->name('transaction')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::post('logout', [VenderController::class, 'venderLogout'])->name('logout')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::get('profile', [VenderController::class, 'profile'])->name('profile')->middleware(['auth:vender', 'XSS', 'revalidate']);


//         Route::post('update-profile', [VenderController::class, 'editprofile'])->name('update.profile')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::post('billing-info', [VenderController::class, 'editBilling'])->name('update.billing.info')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::post('shipping-info', [VenderController::class, 'editShipping'])->name('update.shipping.info')->middleware(['auth:vender', 'XSS', 'revalidate']);


//         Route::post('change.password', [VenderController::class, 'updatePassword'])->name('update.password')->middleware(['auth:vender', 'XSS', 'revalidate']);
//         Route::get('change-language/{lang}', [VenderController::class, 'changeLanquage'])->name('change.language')->middleware(['auth:vender', 'XSS', 'revalidate']);
//     }
// );
