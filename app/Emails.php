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

        $adminTemplate = InvoiceSetting::where('user_id', Auth::user()->creatorId())->first();

        // 1. Generate PDF and store it
        $pdf = PDF::loadView('pdf.invoices.basic', compact('order', 'adminTemplate'));

        // Use Laravel's storage path
        $relativePath = 'public/uploads/invoices/invoice-' . $order->order_id . '.pdf';
        $absolutePath = storage_path('app/' . $relativePath);

        // Ensure directory exists
        File::ensureDirectoryExists(dirname($absolutePath));
        $pdf->save($absolutePath);

        self::email(new \App\Jobs\Email([
            'emailClass'  => 'DefaultMail',
            'to'          => $email ?? $order->company->email,
            'bccStatus'   => false,
            'subject'     => 'Order Confirmation - Order No : '. $order->order_id,
            'contents'    => view('email.invoice', [
                'template' => $adminTemplate,
                'order'    => $order
            ])->render(),
            'files' => [asset('storage/uploads/invoices/invoice-' . $order->order_id . '.pdf')],
        ]));

        // To admin
        self::email(new Email([
            'emailClass' => 'DefaultMail',
            'to' => env('ADMIN_EMAIL'),
            'bccStatus' => false,
            'subject' => 'New Order Created - Order No : '. $order->order_id,
            'contents'    => view('email.invoice', [
                'template' => $adminTemplate,
                'order'    => $order
            ])->render(),
            // 'files' => [$absolutePath],
            'files' => [asset('storage/uploads/invoices/invoice-' . $order->order_id . '.pdf')],
        ]));

        unlink($absolutePath);
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
