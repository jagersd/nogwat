<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendForgetRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $emailAddress;
    /**
     * Create a new message instance.
     */
    public function __construct($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Data removal request')
        ->view('mails.forgetme')
        ->with([
            'invitorName' =>  $this->emailAddress,
        ]);
    }

}
