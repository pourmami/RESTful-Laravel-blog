<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct() {}

    public function build(): ResetPasswordEmail
    {
        return $this->markdown('emails.reset_password')
            ->subject('بازنشانی رمز عبور');
    }
}
