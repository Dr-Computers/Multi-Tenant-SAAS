<?php

namespace App;

use App\Jobs\Email;
use App\Models\EmailTemplate;
use App\Models\InvoiceSetting;
use App\Models\Utility;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

trait Emails
{

    public static function configureMailFromSettings()
    {
        $settings = Utility::settings();

        config([
            'mail.default' => $settings['mail_driver'] ?? 'smtp',
            'mail.mailers.smtp.host' => $settings['mail_host'] ?? '',
            'mail.mailers.smtp.port' => $settings['mail_port'] ?? 587,
            'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? 'tls',
            'mail.mailers.smtp.username' => $settings['mail_username'] ?? '',
            'mail.mailers.smtp.password' => $settings['mail_password'] ?? '',
            'mail.from.address' => $settings['mail_from_address'] ?? '',
            'mail.from.name' => $settings['mail_from_name'] ?? '',
        ]);
    }


    public static function sendError(array $content)
    {
        self::email(new Email([
            'emailClass' => 'DefaultMail',
            'to' => env('DEV_EMAIL'),
            'bccStatus' => false,
            'subject' => __("Error occured"),
            'contents' => view('email.exception')->withContent($content)->render(),
        ]));
    }



    public static function sendOrderInvoice($order, $email = null)
    {
        self::configureMailFromSettings();

        // $adminTemplate = InvoiceSetting::where('user_id', Auth::user()->creatorId())->first();

        // // 1. Generate PDF and store it
        // $pdf = PDF::loadView('pdf.invoices.basic', compact('order', 'adminTemplate'));

        // // Use Laravel's storage path
        // $relativePath = 'public/uploads/invoices/invoice-' . $order->order_id . '.pdf';
        // $absolutePath = storage_path('app/' . $relativePath);

        // // Ensure directory exists
        // File::ensureDirectoryExists(dirname($absolutePath));
        // $pdf->save($absolutePath);


        $adminTemplate = InvoiceSetting::where('user_id', Auth::user()->creatorId())->first();

        $pdf = PDF::loadView('pdf.invoices.partial.admin-invoice', compact('order', 'adminTemplate'))->setPaper('a4', 'portrait');

        // Save PDF to temporary location
        $relativePath = 'public/uploads/invoices/invoice-' . $order->order_id . '.pdf';
        $absolutePath = storage_path('app/' . $relativePath);

        File::ensureDirectoryExists(dirname($absolutePath));
        $pdf->save($absolutePath);



        self::email(new \App\Jobs\Email([
            'emailClass'  => 'DefaultMail',
            'to'          => $email ?? $order->company->email,
            'bccStatus'   => false,
            'subject'     => 'Order Confirmation - Order No : ' . $order->order_id,
            'contents'    => view('pdf.invoices.partial.admin-invoice', [
                'adminTemplate' => $adminTemplate,
                'order'    => $order
            ])->render(),
            'files' => [asset('storage/uploads/invoices/invoice-' . $order->order_id . '.pdf')],
        ]));

        // To admin
        // self::email(new Email([
        //     'emailClass' => 'DefaultMail',
        //     'to' => env('ADMIN_EMAIL'),
        //     'bccStatus' => false,
        //     'subject' => 'New Order Created - Order No : ' . $order->order_id,
        //     'contents'    => view('pdf.invoices.partial.admin-invoice', [
        //         'adminTemplate' => $adminTemplate,
        //         'order'    => $order
        //     ])->render(),
        //     // 'files' => [$absolutePath],
        //     'files' => [asset('storage/uploads/invoices/invoice-' . $order->order_id . '.pdf')],
        // ]));

        unlink($absolutePath);
    }


    public static function createCompany($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'bccStatus'  => false,
            'subject'    => 'Welcome to Our Platform',
            'contents'   => view('email.company.created', compact('user'))->render(),
        ]));
    }


    public static function companyPasswordReseted($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'bccStatus'  => false,
            'subject'    => 'Your Password Has Been Reset',
            'contents'   => view('email.company.password_reset', compact('user'))->render(),
        ]));
    }


    public static function companyPlanUpgraded($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'bccStatus'  => false,
            'subject'    => 'Your Company Plan Has Been Upgraded',
            'contents'   => view('email.company.plan_upgraded', compact('user'))->render(),
        ]));
    }


    public static function companyPlanNeedRenew($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'bccStatus'  => false,
            'subject'    => 'Your Company Plan Needs Renewal',
            'contents'   => view('email.company.plan_renewal_notice', compact('user'))->render(),
        ]));
    }


    public static function companyPlanExpired($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'subject'    => 'Your Company Plan Has Expired',
            'contents'   => view('email.company.plan_expired', compact('user'))->render(),
        ]));
    }

    public static function companyFeatureNeedRenew($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'subject'    => 'Your Company Features Need Renewal',
            'contents'   => view('email.company.feature_renew_needed', compact('user'))->render(),
        ]));
    }


    public static function companyFeatureExpired($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'subject'    => 'Some Company Features Have Expired',
            'contents'   => view('email.company.feature_expired', compact('user'))->render(),
        ]));
    }


    public static function companyAccountDisabled($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'subject'    => 'Your Company Account Has Been Disabled',
            'contents'   => view('email.company.account_disabled', compact('user'))->render(),
        ]));
    }


    public static function companyAccountActivated($user)
    {
        self::configureMailFromSettings();
    
        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'subject'    => 'Your Company Account Has Been Activated',
            'contents'   => view('email.company.account_activated', compact('user'))->render(),
        ]));
    }
    

    public static function newPlanRequest($planRequest)
    {
        self::configureMailFromSettings();
    
        $adminEmail = setting('admin_email') ?? 'admin@example.com';
    
        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $adminEmail,
            'subject'    => 'New Plan Request Received',
            'contents'   => view('email.company.new_plan_request', compact('planRequest'))->render(),
        ]));
    }
    

    public static function newSectionRequest($sectionRequest)
    {
        self::configureMailFromSettings();
    
        $adminEmail = setting('admin_email') ?? 'admin@example.com';
    
        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $adminEmail,
            'subject'    => 'New Section Request Received',
            'contents'   => view('email.company.new_section_request', compact('sectionRequest'))->render(),
        ]));
    }
    

    public static function companyAllowedFeatureExceed($user, $email = null)
    {
        self::configureMailFromSettings();
    
        $to = $email ?? $user->email;
    
        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $to,
            'subject'    => 'Company Feature Limit Exceeded',
            'contents'   => view('email.company.feature_limit_exceeded', compact('user'))->render(),
        ]));
    }
    


    public static function availableNewFeatures($features, $coupon = null)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => env('ADMIN_EMAIL'),
            'bccStatus'  => false,
            'subject'    => 'New Features Available',
            'contents'   => view('email.features.available', compact('features', 'coupon'))->render(),
        ]));
    }


    public static function realestateSetupNewDataRequested($newRequest)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => env('ADMIN_EMAIL'),
            'bccStatus'  => false,
            'subject'    => 'New Real Estate Setup Data Requested',
            'contents'   => view('email.realestate.new_data', compact('newRequest'))->render(),
        ]));
    }


    public static function newInvoiceTemplateCreated($invoiceTemplate)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => env('ADMIN_EMAIL'),
            'bccStatus'  => false,
            'subject'    => 'New Invoice Template Created',
            'contents'   => view('email.templates.invoice_created', compact('invoiceTemplate'))->render(),
        ]));
    }


    public static function newEstimateTemplateCreated($estimateTemplate)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => env('ADMIN_EMAIL'),
            'bccStatus'  => false,
            'subject'    => 'New Estimate Template Created',
            'contents'   => view('email.templates.estimate_created', compact('estimateTemplate'))->render(),
        ]));
    }


    public static function newLetterPadTemplateCreated($letterPad)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => env('ADMIN_EMAIL'),
            'bccStatus'  => false,
            'subject'    => 'New Letter Pad Template Created',
            'contents'   => view('email.templates.letterpad_created', compact('letterPad'))->render(),
        ]));
    }


    ///////////////////////////////////////////////////////////////////////////////////////

    public static function createStaffUser($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'bccStatus'  => false,
            'subject'    => 'Staff Account Created',
            'contents'   => view('email.staff.created', compact('user'))->render(),
        ]));
    }

    public static function deletedStaffUser($user)
    {
        self::configureMailFromSettings();

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $user->email,
            'bccStatus'  => false,
            'subject'    => 'Staff Account Deleted',
            'contents'   => view('email.staff.deleted', compact('user'))->render(),
        ]));
    }


    ///////////////////////////////////////////////////////////////////////////////////////



    public static function supportTicketCreated($ticket)
    {
        self::configureMailFromSettings();

        $chats = $ticket->chats; // adjust this if it's a different relation

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $ticket->user->email,
            'bccStatus'  => false,
            'subject'    => 'Support Ticket Created',
            'contents'   => view('email.support.created', compact('ticket', 'chats'))->render(),
        ]));
    }


    public static function supportTicketReplied($ticket)
    {
        self::configureMailFromSettings();

        $chats = $ticket->chats;

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $ticket->user->email,
            'bccStatus'  => false,
            'subject'    => 'Support Ticket Updated',
            'contents'   => view('email.support.replied', compact('ticket', 'chats'))->render(),
        ]));
    }


    public static function supportTicketClosed($ticket)
    {
        self::configureMailFromSettings();

        $chats = $ticket->chats;

        self::email(new \App\Jobs\Email([
            'emailClass' => 'DefaultMail',
            'to'         => $ticket->user->email,
            'bccStatus'  => false,
            'subject'    => 'Support Ticket Closed',
            'contents'   => view('email.support.closed', compact('ticket', 'chats'))->render(),
        ]));
    }


    // /**
    //  * Dispatch email job
    //  * @param Email $mail
    //  */
    public static function email(Email $mail)
    {
        !ENV('EMAIL', false) or dispatch($mail);
    }
}
