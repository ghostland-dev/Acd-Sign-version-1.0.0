<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public  $title;
    public $sender;
    public $receiver;
    public $urlConfirm;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $sender, $receiver, $urlConfirm)
    {
        $this->title = $title;
        $this->sender= $sender;
        $this->receiver = $receiver;
        $this->urlConfirm = $urlConfirm;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)
        ->view('test');
    }
}
