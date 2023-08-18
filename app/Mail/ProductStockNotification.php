<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductStockNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Alerta de Stock de Productos";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.productstocknotification')
                    ->subject($this->subject)
                    ->with('data', $this->data);
    }
}
