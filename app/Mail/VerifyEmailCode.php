<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmailCode extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build(): VerifyEmailCode
    {
        return $this->markdown('emails.verify_code')
            ->subject('کد تایید ثبت‌نام شما');
    }
}
