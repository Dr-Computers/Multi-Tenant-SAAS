<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Mail\DefaultMail;
use Illuminate\Support\Facades\Mail;

class Email implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    protected $mailClass;
    protected $to;
    protected $bcc;
    protected $cc;
    protected $bccStatus;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
        $this->mailClass = 'App\\Mail\\' . ($details['emailClass'] ?? 'DefaultMail');
        $this->to = $this->details['to'];
        $this->bcc = explode(',', env("BCC_EMAIL"));
        $this->bccStatus = $this->details['bccStatus'] ?? false;
    }

    /**
     * Execute the job.
     *
     * @return void
     */


     public function handle()
     {
         \App\Emails::configureMailFromSettings();
     
         // Ensure $this->details is an array
         $details = is_string($this->details) ? json_decode($this->details, true) : $this->details;
     
         if (!is_array($details)) {
             throw new \Exception('Mail details must be an array.');
         }
     
         $email = new $this->mailClass($details);
     
      
         $mailer = Mail::to($this->to);
     
         if (!empty($this->bccStatus) && !empty($this->bcc)) {
             $mailer->bcc($this->bcc);
         }


     
         
         $mailer->send($email);
     }
     



    public static function dispatchWithDelay(array $details, int $delayInMinutes = 1)
    {
        self::dispatch($details)->delay(now()->addMinutes($delayInMinutes));
    }
}
