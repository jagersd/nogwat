<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDevMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$type,$content)
    {   
        $this->email = $email;
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('feedback from '. $this->email)
        ->view('mails.feedback')
        ->with([
            'userMail' => $this->email,
            'type' =>  $this->type,
            'content' =>  $this->content,
        ]);
    }
}
