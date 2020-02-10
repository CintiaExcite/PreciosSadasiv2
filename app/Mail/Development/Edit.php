<?php

namespace App\Mail\Development;

use App\Models\Development;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Edit extends Mailable
{
    use Queueable, SerializesModels;

    protected $development;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Development $development)
    {
        $this->development = $development;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@precios.sadasi.com', 'Sistema de Precios Sadasi')
                    ->subject('Desarrollo editado')
                    ->with(['development' => $this->development])
                    ->view('emails.development.edit');
    }
}
