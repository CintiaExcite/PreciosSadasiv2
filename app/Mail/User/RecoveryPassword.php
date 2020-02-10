<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecoveryPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $pass_temp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $pass_temp)
    {
        $this->pass_temp = $pass_temp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@precios.sadasi.com', 'Sistema de Precios Sadasi')
                    ->subject('Recuperación de contraseña')
                    ->with(['pass_temp' => $this->pass_temp])
                    ->view('emails.user.recoverypassword');
    }
}
