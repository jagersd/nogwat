<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $invitor)
    {
        $this->invitor = $invitor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nog Wat Groepsuitnodiging')
        ->view('mails.invites')
        ->with([
            'invitorName' =>  $this->invitor->namespace,
            'invitorEmail' => $this->invitor->email
        ]);
    }
}
