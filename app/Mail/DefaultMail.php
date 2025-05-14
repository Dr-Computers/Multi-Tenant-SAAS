<?php

// namespace App\Mail;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Mail\Mailable;
// use Illuminate\Queue\SerializesModels;

// class DefaultMail extends Mailable
// {
//     use Queueable, SerializesModels;

//     public $subject;
//     public $name;
//     public $reply_name;
//     public $reply_to;
//     public $content;

//     public $attachments = []; // <--- add this

//     public function __construct(array $params)
//     {
//         $this->subject = ($params['subject'] ?? null).' - '.env('APP_NAME');
//         $this->name = $params['name'] ?? null;
//         $this->reply_name = isset($params['reply_name']) ? $params['reply_name'].' - '.env('APP_NAME') : env('MAIL_FROM_NAME');
//         $this->reply_to = $params['reply_to'] ?? null;
//         $this->content = $params['contents'] ?? null;
//         $this->attachments = $params['attachments'] ?? []; // <--- capture attachments
//     }

//     public function build()
//     {
//         $mail = $this->replyTo($this->reply_to, $this->reply_name)
//                      ->subject($this->subject)
//                      ->view('email.base');

//         // Attach each file, if any
//         foreach ($this->attachments as $filePath) {
//             $mail->attach($filePath);
//         }

//         return $mail;
//     }
// }

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Env;

class DefaultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $name;
    public $reply_name;
    public $reply_to;
    public $content;
    public $files;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->subject = $params['subject'] ?? null;
        $this->name = $params['name'] ?? null;
        $this->reply_name = isset($params['reply_name']) ? $params['reply_name'] : env('MAIL_FROM_NAME');
        $this->reply_to = $params['reply_to'] ?? null;
        $this->content = $params['contents'] ?? null;
        $this->files = isset($params['files']) ? (array) $params['files'] : [];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->reply_to, $this->reply_name)
            ->subject($this->subject)
            ->view('email.base');
    }


    public function attachments(): array
    {

        $files = [];

        if (!empty($this->files) && is_array($this->files)) {
            foreach ($this->files as $filePath) {
                if (env('APP_ENV') != 'local') {
                    $files[] = Attachment::fromPath($filePath);
                }
            }
        }

        return $files;
    }
}
